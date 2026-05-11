<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\WompiPayment;
use App\Services\WompiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WompiController extends Controller
{
    public function __construct(
        private WompiService $wompiService
    ) {}

    /**
     * Crear transacción de pago con Wompi
     * 
     * POST /wompi/create-transaction
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTransaction(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            $order = Order::findOrFail($validated['order_id']);

            // Verificar que la orden pertenece al usuario autenticado (si está autenticado)
            if (auth()->check() && $order->user_id && $order->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para pagar esta orden',
                ], 403);
            }

            // Verificar que la orden no tenga ya un pago aprobado
            $existingPayment = WompiPayment::where('order_id', $order->id)
                ->where('status', 'APPROVED')
                ->first();

            if ($existingPayment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta orden ya tiene un pago aprobado',
                ], 400);
            }

            // Crear transacción
            $payment = $this->wompiService->createTransaction($order);

            // Si se creó un link de pago, devolverlo
            if ($payment->payment_link_url) {
                return response()->json([
                    'success' => true,
                    'payment_id' => $payment->id,
                    'payment_link' => $payment->payment_link_url,
                ]);
            }

            // Si no hay link, devolver datos para checkout manual
            $checkoutData = $this->wompiService->getCheckoutData($payment);

            return response()->json([
                'success' => true,
                'payment_id' => $payment->id,
                'checkout_data' => $checkoutData,
                'checkout_url' => $this->wompiService->getCheckoutUrl(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error creando transacción Wompi', [
                'error' => $e->getMessage(),
                'order_id' => $validated['order_id'] ?? null,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la transacción de pago',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Callback después del pago (redirección desde Wompi)
     * 
     * GET /wompi/callback
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        $transactionId = $request->get('id');
        $reference = $request->get('reference');

        if (!$reference) {
            return redirect('/')->with('error', 'Referencia de pago no encontrada');
        }

        // Buscar el pago
        $payment = WompiPayment::where('reference', $reference)->first();

        if (!$payment) {
            return redirect('/')->with('error', 'Pago no encontrado');
        }

        // Si tenemos transaction_id, consultar estado en Wompi
        if ($transactionId) {
            $transactionData = $this->wompiService->getTransactionStatus($transactionId);

            if ($transactionData) {
                $status = strtoupper($transactionData['data']['status'] ?? 'ERROR');
                
                $payment->update([
                    'transaction_id' => $transactionId,
                    'status' => $status,
                    'status_message' => $transactionData['data']['status_message'] ?? null,
                    'payment_method' => $transactionData['data']['payment_method_type'] ?? null,
                    'wompi_response' => $transactionData['data'] ?? null,
                ]);

                // Actualizar orden
                $this->updateOrderStatus($payment);
            }
        }

        // Redirigir según el estado
        if ($payment->isApproved()) {
            return redirect('/mi-cuenta')->with('success', '¡Pago aprobado! Tu pedido ha sido confirmado.');
        } elseif ($payment->isDeclined()) {
            return redirect('/mi-cuenta')->with('error', 'El pago fue rechazado. Por favor intenta nuevamente.');
        } else {
            return redirect('/mi-cuenta')->with('info', 'Tu pago está siendo procesado.');
        }
    }

    /**
     * Webhook de Wompi
     * 
     * POST /wompi/webhook
     * 
     * IMPORTANTE: Este endpoint debe estar excluido de CSRF
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function webhook(Request $request)
    {
        // Obtener firma del header
        $signature = $request->header('X-Event-Checksum');
        
        if (!$signature) {
            Log::warning('Webhook Wompi sin firma');
            return response()->json(['error' => 'Missing signature'], 400);
        }

        $payload = $request->all();

        // Validar firma
        if (!$this->wompiService->validateWebhookSignature($payload, $signature)) {
            Log::warning('Webhook Wompi con firma inválida', [
                'signature' => $signature,
                'ip' => $request->ip(),
            ]);
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Procesar webhook
        $processed = $this->wompiService->processWebhook($payload);

        if ($processed) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Processing failed'], 500);
    }

    /**
     * Consultar estado de un pago
     * 
     * GET /wompi/payment/{payment}/status
     * 
     * @param WompiPayment $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentStatus(WompiPayment $payment)
    {
        // Verificar permisos (solo si está autenticado)
        if (auth()->check() && $payment->order->user_id && $payment->order->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado',
            ], 403);
        }

        // Si hay transaction_id, consultar estado actualizado
        if ($payment->transaction_id) {
            $transactionData = $this->wompiService->getTransactionStatus($payment->transaction_id);
            
            if ($transactionData) {
                $status = strtoupper($transactionData['data']['status'] ?? 'ERROR');
                
                $payment->update([
                    'status' => $status,
                    'status_message' => $transactionData['data']['status_message'] ?? null,
                    'wompi_response' => $transactionData['data'] ?? null,
                ]);

                $this->updateOrderStatus($payment);
            }
        }

        return response()->json([
            'success' => true,
            'payment' => [
                'id' => $payment->id,
                'reference' => $payment->reference,
                'transaction_id' => $payment->transaction_id,
                'status' => $payment->status,
                'status_label' => $payment->status_label,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
            ],
            'order' => [
                'id' => $payment->order->id,
                'status' => $payment->order->status,
                'status_label' => $payment->order->status_label,
            ],
        ]);
    }

    /**
     * Actualizar estado de la orden según el pago
     */
    private function updateOrderStatus(WompiPayment $payment): void
    {
        $order = $payment->order;

        if (!$order) {
            return;
        }

        $orderStatus = match ($payment->status) {
            'APPROVED' => 'confirmed',
            'DECLINED', 'VOIDED', 'ERROR' => 'cancelled',
            default => 'pending',
        };

        $paymentStatus = match ($payment->status) {
            'APPROVED' => 'approved',
            'DECLINED', 'VOIDED' => 'rejected',
            default => 'pending',
        };

        $order->update([
            'status' => $orderStatus,
            'payment_status' => $paymentStatus,
            'payment_method' => 'wompi',
            'payment_details' => [
                'transaction_id' => $payment->transaction_id,
                'payment_method' => $payment->payment_method,
                'status' => $payment->status,
            ],
        ]);
    }
}

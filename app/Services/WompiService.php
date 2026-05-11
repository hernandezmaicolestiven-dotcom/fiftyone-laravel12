<?php

namespace App\Services;

use App\Models\Order;
use App\Models\WompiPayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Servicio para integración con Wompi
 * 
 * Documentación oficial: https://docs.wompi.co/docs/colombia/
 * 
 * IMPORTANTE:
 * - Este servicio maneja SOLO operaciones de backend
 * - Las llaves privadas NUNCA deben exponerse al frontend
 * - Todas las firmas se generan en el servidor
 */
class WompiService
{
    private string $publicKey;
    private string $privateKey;
    private string $integritySecret;
    private string $eventsSecret;
    private bool $isSandbox;
    private string $apiUrl;

    public function __construct()
    {
        $this->publicKey = config('services.wompi.public_key', '');
        $this->privateKey = config('services.wompi.private_key', '');
        $this->integritySecret = config('services.wompi.integrity_secret', '');
        $this->eventsSecret = config('services.wompi.events_secret', '');
        $this->isSandbox = config('services.wompi.sandbox', true);
        
        // URL base según ambiente
        $this->apiUrl = $this->isSandbox 
            ? 'https://sandbox.wompi.co/v1'
            : 'https://production.wompi.co/v1';

        $this->validateConfiguration();
    }

    /**
     * Validar que las variables de entorno estén configuradas
     */
    private function validateConfiguration(): void
    {
        if (empty($this->publicKey)) {
            throw new \Exception('WOMPI_PUBLIC_KEY no está configurada');
        }

        if (empty($this->integritySecret)) {
            throw new \Exception('WOMPI_INTEGRITY_SECRET no está configurada');
        }

        // Validar prefijo de la llave pública
        $expectedPrefix = $this->isSandbox ? 'pub_test_' : 'pub_prod_';
        if (!str_starts_with($this->publicKey, $expectedPrefix)) {
            Log::warning("La llave pública no tiene el prefijo esperado para el ambiente actual: {$expectedPrefix}");
        }
    }

    /**
     * Crear una transacción de pago
     * 
     * @param Order $order
     * @return WompiPayment
     */
    public function createTransaction(Order $order): WompiPayment
    {
        // Generar referencia única
        $reference = $this->generateReference($order->id);
        
        // Convertir monto a centavos (Wompi requiere centavos)
        $amountInCents = (int) ($order->total * 100);
        
        // Generar firma de integridad
        $integritySignature = $this->generateIntegritySignature(
            $reference,
            $amountInCents,
            'COP'
        );

        // Crear registro de pago
        $payment = WompiPayment::create([
            'order_id' => $order->id,
            'reference' => $reference,
            'amount' => $order->total,
            'amount_in_cents' => $amountInCents,
            'currency' => 'COP',
            'status' => 'PENDING',
            'customer_email' => $order->customer_email ?? $order->user->email ?? 'noemail@example.com',
            'integrity_signature' => $integritySignature,
            'customer_data' => [
                'name' => $order->customer_name,
                'phone' => $order->customer_phone,
                'address' => $order->shipping_address,
                'city' => $order->city,
            ],
        ]);

        // Crear link de pago usando la API de Wompi
        $paymentLink = $this->createPaymentLink($payment);
        
        if ($paymentLink) {
            $payment->update([
                'payment_link_id' => $paymentLink['id'] ?? null,
                'payment_link_url' => $paymentLink['url'] ?? null,
            ]);
        }

        Log::info('Transacción Wompi creada', [
            'payment_id' => $payment->id,
            'order_id' => $order->id,
            'reference' => $reference,
            'amount' => $order->total,
            'payment_link' => $paymentLink['url'] ?? null,
        ]);

        return $payment;
    }

    /**
     * Crear un link de pago usando la API de Wompi
     * 
     * @param WompiPayment $payment
     * @return array|null
     */
    private function createPaymentLink(WompiPayment $payment): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->privateKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/payment_links", [
                'name' => 'Orden #' . $payment->order_id,
                'description' => 'Pago de orden en FiftyOne',
                'single_use' => true,
                'collect_shipping' => false,
                'currency' => $payment->currency,
                'amount_in_cents' => $payment->amount_in_cents,
                'redirect_url' => route('wompi.callback'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'id' => $data['data']['id'] ?? null,
                    'url' => $data['data']['url'] ?? null,
                ];
            }

            Log::error('Error al crear link de pago Wompi', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Excepción al crear link de pago Wompi', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Generar referencia única para la transacción
     * Formato: ORDER-{order_id}-{timestamp}-{random}
     */
    private function generateReference(int $orderId): string
    {
        return sprintf(
            'ORDER-%d-%s-%s',
            $orderId,
            now()->format('YmdHis'),
            Str::random(6)
        );
    }

    /**
     * Generar firma de integridad según documentación de Wompi
     * 
     * La firma se calcula como:
     * SHA256(reference + amount_in_cents + currency + integrity_secret)
     * 
     * CRÍTICO: Esta firma DEBE generarse en el backend
     * 
     * @param string $reference
     * @param int $amountInCents
     * @param string $currency
     * @return string
     */
    public function generateIntegritySignature(
        string $reference,
        int $amountInCents,
        string $currency = 'COP'
    ): string {
        $concatenated = $reference . $amountInCents . $currency . $this->integritySecret;
        
        return hash('sha256', $concatenated);
    }

    /**
     * Obtener datos para el checkout (frontend)
     * 
     * @param WompiPayment $payment
     * @return array
     */
    public function getCheckoutData(WompiPayment $payment): array
    {
        return [
            'public_key' => $this->publicKey,
            'reference' => $payment->reference,
            'amount_in_cents' => $payment->amount_in_cents,
            'currency' => $payment->currency,
            'signature' => $payment->integrity_signature,
            'redirect_url' => route('wompi.callback'),
            'customer_email' => $payment->customer_email,
            'customer_data' => $payment->customer_data,
        ];
    }

    /**
     * Consultar estado de una transacción en Wompi
     * 
     * @param string $transactionId
     * @return array|null
     */
    public function getTransactionStatus(string $transactionId): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->privateKey,
                'Accept' => 'application/json',
            ])->get("{$this->apiUrl}/transactions/{$transactionId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Error al consultar transacción Wompi', [
                'transaction_id' => $transactionId,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Excepción al consultar transacción Wompi', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Validar firma de webhook
     * 
     * @param array $payload
     * @param string $signature
     * @return bool
     */
    public function validateWebhookSignature(array $payload, string $signature): bool
    {
        // Wompi envía la firma en el header X-Event-Checksum
        // Se calcula como: SHA256(event_data + events_secret)
        
        $eventData = json_encode($payload);
        $expectedSignature = hash('sha256', $eventData . $this->eventsSecret);

        $isValid = hash_equals($expectedSignature, $signature);

        if (!$isValid) {
            Log::warning('Firma de webhook inválida', [
                'expected' => $expectedSignature,
                'received' => $signature,
            ]);
        }

        return $isValid;
    }

    /**
     * Procesar webhook de Wompi
     * 
     * @param array $payload
     * @return bool
     */
    public function processWebhook(array $payload): bool
    {
        try {
            $event = $payload['event'] ?? null;
            $data = $payload['data'] ?? [];
            $transaction = $data['transaction'] ?? [];

            if (!$event || !$transaction) {
                Log::warning('Webhook Wompi con datos incompletos', ['payload' => $payload]);
                return false;
            }

            // Buscar el pago por referencia
            $reference = $transaction['reference'] ?? null;
            if (!$reference) {
                Log::warning('Webhook sin referencia', ['payload' => $payload]);
                return false;
            }

            $payment = WompiPayment::where('reference', $reference)->first();
            if (!$payment) {
                Log::warning('Pago no encontrado para referencia', ['reference' => $reference]);
                return false;
            }

            // Actualizar estado del pago
            $status = strtoupper($transaction['status'] ?? 'ERROR');
            $payment->update([
                'transaction_id' => $transaction['id'] ?? null,
                'status' => $status,
                'status_message' => $transaction['status_message'] ?? null,
                'payment_method' => $transaction['payment_method_type'] ?? null,
                'payment_method_type' => $transaction['payment_method'] ?? null,
                'wompi_response' => $transaction,
                'webhook_received_at' => now(),
                'webhook_data' => $payload,
            ]);

            // Actualizar orden según el estado del pago
            $this->updateOrderFromPayment($payment);

            Log::info('Webhook Wompi procesado', [
                'payment_id' => $payment->id,
                'status' => $status,
                'transaction_id' => $transaction['id'] ?? null,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error procesando webhook Wompi', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return false;
        }
    }

    /**
     * Actualizar orden según estado del pago
     * 
     * @param WompiPayment $payment
     */
    private function updateOrderFromPayment(WompiPayment $payment): void
    {
        $order = $payment->order;

        if (!$order) {
            return;
        }

        // Mapear estados de Wompi a estados de orden
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

        Log::info('Orden actualizada desde pago Wompi', [
            'order_id' => $order->id,
            'order_status' => $orderStatus,
            'payment_status' => $paymentStatus,
        ]);
    }

    /**
     * Obtener URL del checkout de Wompi
     */
    public function getCheckoutUrl(): string
    {
        // La URL correcta es sin la /p/ al final
        return $this->isSandbox
            ? 'https://checkout.wompi.co/l'
            : 'https://checkout.wompi.co/l';
    }

    /**
     * Verificar si está en modo sandbox
     */
    public function isSandbox(): bool
    {
        return $this->isSandbox;
    }
}

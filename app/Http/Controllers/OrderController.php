<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'nullable|email|max:255',
            'customer_phone'   => 'nullable|string|max:30',
            'shipping_address' => 'nullable|string|max:500',
            'city'             => 'nullable|string|max:100',
            'notes'            => 'nullable|string|max:1000',
            'items'            => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'coupon_code'        => 'nullable|string|max:20',
            'payment_method'     => 'required|string|in:nequi,daviplata,pse,bancolombia,efecty,tarjeta',
            'payment_details'    => 'nullable|array',
        ]);

        try {
            $order = $this->orderService->createOrder($data);

            // Crear factura automáticamente
            $invoiceService = app(\App\Services\InvoiceService::class);
            $invoiceService->createInvoiceForOrder($order);

            // Guardar dirección como predeterminada del usuario
            if (auth()->check() && !empty($data['shipping_address'])) {
                auth()->user()->update([
                    'default_address' => $data['shipping_address'],
                    'default_city'    => $data['city'] ?? null,
                ]);
            }

            return response()->json(['success' => true, 'order_id' => $order->id], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear pedido', ['error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'Error al procesar el pedido.'], 500);
        }
    }
}

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
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'coupon_code'        => 'nullable|string|max:20',
        ]);

        try {
            $order = $this->orderService->createOrder($data);

            return response()->json(['success' => true, 'order_id' => $order->id], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear pedido', ['error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'Error al procesar el pedido.'], 500);
        }
    }
}

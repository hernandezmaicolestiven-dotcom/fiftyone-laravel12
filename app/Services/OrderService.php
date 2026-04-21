<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * OrderService
 *
 * Centraliza la lógica de negocio relacionada con pedidos:
 * creación, actualización de estado y notificaciones.
 * Los controladores solo delegan aquí — no contienen lógica de negocio.
 */
class OrderService
{
    /**
     * Crea un nuevo pedido con sus items y dispara notificaciones.
     *
     * @param  array  $data  Datos validados del request
     *
     * @throws \Throwable Si falla la transacción de base de datos
     */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $total = 0;
            $itemsData = [];

            // Calcular total y preparar items
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
            }

            // Aplicar cupón si viene
            if (!empty($data['coupon_code'])) {
                $coupon = \App\Models\Coupon::where('code', strtoupper($data['coupon_code']))->first();
                if ($coupon && $coupon->isValid()) {
                    $total = max(0, $total - $coupon->discount($total));
                    if ($coupon->uses_left !== null) {
                        $coupon->decrement('uses_left');
                    }
                }
            }

            // Crear pedido (vincular al usuario si está autenticado)
            $order = Order::create([
                'user_id'          => auth()->id(),
                'customer_name'    => $data['customer_name'],
                'customer_email'   => $data['customer_email'] ?? null,
                'customer_phone'   => $data['customer_phone'] ?? null,
                'shipping_address' => $data['shipping_address'] ?? null,
                'city'             => $data['city'] ?? null,
                'notes'            => $data['notes'] ?? null,
                'total'            => $total,
                'status'           => 'pending',
            ]);

            $order->items()->createMany($itemsData);
            $order->load('items');

            // Notificar al cliente si tiene email
            $this->notifyNewOrder($order);

            Log::info('Pedido creado', ['order_id' => $order->id, 'total' => $total]);

            return $order;
        });
    }

    /**
     * Actualiza el estado de un pedido y notifica al cliente si cambió.
     */
    public function updateStatus(Order $order, string $newStatus): Order
    {
        $oldStatus = $order->status;

        $order->update(['status' => $newStatus]);

        // Solo notificar si el estado realmente cambió y el cliente tiene email
        if ($oldStatus !== $newStatus && $order->customer_email) {
            try {
                Notification::route('mail', $order->customer_email)
                    ->notify(new OrderStatusChangedNotification($order, $oldStatus));
            } catch (\Exception $e) {
                // No interrumpir el flujo si el email falla — solo loguear
                Log::warning('Error enviando email de cambio de estado', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Estado de pedido actualizado', [
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);

        return $order;
    }

    /**
     * Envía notificaciones de nuevo pedido al cliente y a los admins.
     */
    private function notifyNewOrder(Order $order): void
    {
        try {
            if ($order->customer_email) {
                Notification::route('mail', $order->customer_email)
                    ->notify(new NewOrderNotification($order));
            }

            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new NewOrderNotification($order));

            // Notificación WhatsApp al admin via wa.me link (log)
            $waMsg = urlencode("🛍️ Nuevo pedido #{$order->id} de {$order->customer_name} por $" . number_format($order->total, 0, ',', '.') . " COP. Estado: Pendiente.");
            Log::info("WhatsApp admin: https://wa.me/573118422192?text={$waMsg}");

        } catch (\Exception $e) {
            Log::warning('Error enviando notificación de nuevo pedido', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;

class InvoiceService
{
    /**
     * Crea una factura para un pedido
     */
    public function createInvoiceForOrder(Order $order): Invoice
    {
        // Cargar items con productos
        $order->load('items.product');
        
        // Preparar items del pedido
        $items = $order->items->map(function ($item) {
            return [
                'name' => $item->product->name ?? 'Producto',
                'size' => $item->size ?? '-',
                'color' => $item->color ?? null,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'discount' => 0, // Puedes agregar lógica de descuentos aquí
            ];
        })->toArray();

        // Calcular totales
        $totals = Invoice::calculateTotals($items);

        // Generar número de factura
        $invoiceNumber = Invoice::generateInvoiceNumber();

        // Crear factura
        return Invoice::create([
            'invoice_number' => $invoiceNumber,
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_address' => $order->shipping_address,
            'customer_document' => null, // Agregar si tienes este campo
            'subtotal' => $totals['subtotal'],
            'total_discounts' => $totals['total_discounts'],
            'total_iva' => $totals['total_iva'],
            'total' => $totals['total'],
            'items' => $totals['items'],
        ]);
    }

    /**
     * Obtiene o crea una factura para un pedido
     */
    public function getOrCreateInvoice(Order $order): Invoice
    {
        // Cargar items con productos si no están cargados
        if (!$order->relationLoaded('items')) {
            $order->load('items.product');
        }
        
        $invoice = Invoice::where('order_id', $order->id)->first();

        if (!$invoice) {
            $invoice = $this->createInvoiceForOrder($order);
        }

        return $invoice;
    }
}

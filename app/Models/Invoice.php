<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'order_id', 'user_id',
        'customer_name', 'customer_email', 'customer_address', 'customer_document',
        'subtotal', 'total_discounts', 'total_iva', 'total', 'items',
        'status', 'cancellation_reason', 'cancelled_at', 'iva_percentage', 'iva_exempt'
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'total_discounts' => 'decimal:2',
        'total_iva' => 'decimal:2',
        'total' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'iva_exempt' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generar número de factura consecutivo
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = InvoiceSetting::get('invoice_prefix', 'FV');
        $year = date('Y');
        $number = InvoiceSetting::getNextInvoiceNumber();
        
        return sprintf('%s-%s-%04d', $prefix, $year, $number);
    }

    /**
     * Calcular totales con IVA configurable
     */
    public static function calculateTotals(array $items, float $ivaPercentage = null): array
    {
        if ($ivaPercentage === null) {
            $ivaPercentage = InvoiceSetting::get('iva_percentage', 19.00);
        }

        $subtotal = 0;
        $totalDiscounts = 0;
        $totalIva = 0;
        $processedItems = [];

        foreach ($items as $item) {
            $price = $item['price'];
            $quantity = $item['quantity'];
            $discount = $item['discount'] ?? 0;

            $baseGravable = $price * $quantity;
            $discountAmount = ($baseGravable * $discount) / 100;
            $baseAfterDiscount = $baseGravable - $discountAmount;
            $iva = ($baseAfterDiscount * $ivaPercentage) / 100;
            $itemTotal = $baseAfterDiscount + $iva;

            $subtotal += $baseGravable;
            $totalDiscounts += $discountAmount;
            $totalIva += $iva;

            $processedItems[] = array_merge($item, [
                'base_gravable' => round($baseGravable, 2),
                'discount_amount' => round($discountAmount, 2),
                'iva' => round($iva, 2),
                'total' => round($itemTotal, 2),
            ]);
        }

        return [
            'items' => $processedItems,
            'subtotal' => round($subtotal, 2),
            'total_discounts' => round($totalDiscounts, 2),
            'total_iva' => round($totalIva, 2),
            'total' => round($subtotal - $totalDiscounts + $totalIva, 2),
            'iva_percentage' => $ivaPercentage,
        ];
    }

    /**
     * Anular factura
     */
    public function cancel(string $reason): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);
    }

    /**
     * Verificar si está activa
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}

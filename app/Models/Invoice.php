<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'order_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_address',
        'customer_document',
        'subtotal',
        'total_discounts',
        'total_iva',
        'total',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'total_discounts' => 'decimal:2',
        'total_iva' => 'decimal:2',
        'total' => 'decimal:2',
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
     * Genera el número de factura con formato FACT-YYYYMMDD-XXXX
     */
    public static function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "FACT-{$date}-";
        
        // Obtener el último consecutivo del día
        $lastInvoice = self::where('invoice_number', 'like', "{$prefix}%")
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calcula los totales de la factura según productos
     */
    public static function calculateTotals(array $items): array
    {
        $subtotal = 0;
        $totalDiscounts = 0;
        $totalIva = 0;
        $calculatedItems = [];

        foreach ($items as $item) {
            $precioUnitario = $item['price'];
            $cantidad = $item['quantity'];
            $descuento = $item['discount'] ?? 0;

            // Cálculos por producto
            $subtotalProducto = $precioUnitario * $cantidad;
            $descuentoAplicado = $subtotalProducto * ($descuento / 100);
            $baseGravable = $subtotalProducto - $descuentoAplicado;
            $ivaProducto = $baseGravable * 0.19;
            $totalProducto = $baseGravable + $ivaProducto;

            $calculatedItems[] = [
                'name' => $item['name'],
                'size' => $item['size'] ?? null,
                'color' => $item['color'] ?? null,
                'price' => $precioUnitario,
                'quantity' => $cantidad,
                'discount' => $descuento,
                'subtotal_producto' => round($subtotalProducto, 2),
                'descuento_aplicado' => round($descuentoAplicado, 2),
                'base_gravable' => round($baseGravable, 2),
                'iva' => round($ivaProducto, 2),
                'total' => round($totalProducto, 2),
            ];

            $subtotal += $baseGravable;
            $totalDiscounts += $descuentoAplicado;
            $totalIva += $ivaProducto;
        }

        return [
            'items' => $calculatedItems,
            'subtotal' => round($subtotal, 2),
            'total_discounts' => round($totalDiscounts, 2),
            'total_iva' => round($totalIva, 2),
            'total' => round($subtotal + $totalIva, 2),
        ];
    }
}

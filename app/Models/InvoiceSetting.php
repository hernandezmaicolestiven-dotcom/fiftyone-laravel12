<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Obtener valor de configuración
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'number' => (float) $setting->value,
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            default => $setting->value,
        };
    }

    /**
     * Establecer valor de configuración
     */
    public static function set(string $key, $value, string $type = 'string'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }

    /**
     * Obtener siguiente número de factura y incrementar
     */
    public static function getNextInvoiceNumber(): int
    {
        $current = (int) self::get('next_invoice_number', 1);
        self::set('next_invoice_number', $current + 1, 'number');
        return $current;
    }
}

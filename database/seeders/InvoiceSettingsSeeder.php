<?php

namespace Database\Seeders;

use App\Models\InvoiceSetting;
use Illuminate\Database\Seeder;

class InvoiceSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Configuración inicial de facturación
        $settings = [
            ['key' => 'iva_percentage', 'value' => '19.00', 'type' => 'number'],
            ['key' => 'invoice_prefix', 'value' => 'FV', 'type' => 'string'],
            ['key' => 'next_invoice_number', 'value' => '1', 'type' => 'number'],
            ['key' => 'company_name', 'value' => 'FiftyOne', 'type' => 'string'],
            ['key' => 'company_nit', 'value' => '', 'type' => 'string'],
            ['key' => 'company_address', 'value' => '', 'type' => 'string'],
            ['key' => 'company_phone', 'value' => '', 'type' => 'string'],
            ['key' => 'company_email', 'value' => '', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            InvoiceSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }

        $this->command->info('✅ Configuración de facturación inicializada');
    }
}

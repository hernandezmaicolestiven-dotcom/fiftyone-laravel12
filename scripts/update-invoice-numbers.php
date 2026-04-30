<?php

/**
 * Script para actualizar numeración de facturas existentes
 * Convierte números aleatorios a numeración consecutiva legal
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Invoice;
use App\Models\InvoiceSetting;

echo "🔄 Actualizando numeración de facturas...\n\n";

// Obtener todas las facturas ordenadas por fecha de creación
$invoices = Invoice::orderBy('created_at')->get();

if ($invoices->isEmpty()) {
    echo "ℹ️  No hay facturas para actualizar.\n";
    exit(0);
}

$prefix = InvoiceSetting::get('invoice_prefix', 'FV');
$counter = 1;

foreach ($invoices as $invoice) {
    $year = $invoice->created_at->format('Y');
    $newNumber = sprintf('%s-%s-%04d', $prefix, $year, $counter);
    
    $oldNumber = $invoice->invoice_number;
    $invoice->invoice_number = $newNumber;
    $invoice->save();
    
    echo "✅ {$oldNumber} → {$newNumber}\n";
    $counter++;
}

// Actualizar el próximo número
InvoiceSetting::set('next_invoice_number', $counter, 'number');

echo "\n✨ Actualización completada!\n";
echo "📊 Total de facturas actualizadas: " . $invoices->count() . "\n";
echo "🔢 Próximo número: {$prefix}-" . date('Y') . "-" . str_pad($counter, 4, '0', STR_PAD_LEFT) . "\n";

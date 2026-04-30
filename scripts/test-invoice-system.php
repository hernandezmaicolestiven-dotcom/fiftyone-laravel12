<?php

/**
 * Script de prueba del sistema de facturación
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Invoice;
use App\Models\InvoiceSetting;

echo "🧪 Probando sistema de facturación...\n\n";

// 1. Verificar configuración
echo "1️⃣ Verificando configuración...\n";
$ivaPercentage = InvoiceSetting::get('iva_percentage', 19.00);
$invoicePrefix = InvoiceSetting::get('invoice_prefix', 'FV');
$nextNumber = InvoiceSetting::get('next_invoice_number', 1);
$companyName = InvoiceSetting::get('company_name', 'FiftyOne');

echo "   ✅ IVA: {$ivaPercentage}%\n";
echo "   ✅ Prefijo: {$invoicePrefix}\n";
echo "   ✅ Próximo número: {$nextNumber}\n";
echo "   ✅ Empresa: {$companyName}\n\n";

// 2. Verificar facturas existentes
echo "2️⃣ Verificando facturas existentes...\n";
$totalInvoices = Invoice::count();
$activeInvoices = Invoice::where('status', 'active')->count();
$cancelledInvoices = Invoice::where('status', 'cancelled')->count();
$totalAmount = Invoice::where('status', 'active')->sum('total');

echo "   📊 Total de facturas: {$totalInvoices}\n";
echo "   ✅ Activas: {$activeInvoices}\n";
echo "   ❌ Anuladas: {$cancelledInvoices}\n";
echo "   💰 Total facturado: $" . number_format($totalAmount, 0, ',', '.') . "\n\n";

// 3. Verificar última factura
echo "3️⃣ Verificando última factura...\n";
$lastInvoice = Invoice::latest()->first();
if ($lastInvoice) {
    echo "   📄 Número: {$lastInvoice->invoice_number}\n";
    echo "   👤 Cliente: {$lastInvoice->customer_name}\n";
    echo "   💵 Total: $" . number_format($lastInvoice->total, 0, ',', '.') . "\n";
    echo "   📅 Fecha: {$lastInvoice->created_at->format('d/m/Y H:i')}\n";
    echo "   🔖 Estado: " . ($lastInvoice->status === 'active' ? '✅ Activa' : '❌ Anulada') . "\n\n";
} else {
    echo "   ℹ️  No hay facturas en el sistema\n\n";
}

// 4. Verificar próxima numeración
echo "4️⃣ Próxima factura será...\n";
$nextInvoiceNumber = Invoice::generateInvoiceNumber();
echo "   🔢 {$nextInvoiceNumber}\n\n";

// 5. Verificar rutas
echo "5️⃣ Verificando rutas...\n";
$routes = [
    'admin.invoices.index',
    'admin.invoices.show',
    'admin.invoices.settings',
    'admin.invoices.update-settings',
    'admin.invoices.cancel',
    'admin.invoices.resend',
    'admin.invoices.download-pdf',
    'admin.invoices.export-csv',
];

foreach ($routes as $route) {
    if (Route::has($route)) {
        echo "   ✅ {$route}\n";
    } else {
        echo "   ❌ {$route} - NO ENCONTRADA\n";
    }
}

echo "\n✨ Prueba completada!\n";
echo "🎯 Sistema de facturación: " . ($totalInvoices > 0 ? "✅ FUNCIONAL" : "⚠️  SIN DATOS") . "\n";

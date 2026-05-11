<?php

/**
 * Script para probar el endpoint de Wompi directamente
 * 
 * Ejecutar: php scripts/test-wompi-endpoint.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║         PRUEBA DE ENDPOINT WOMPI                               ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// 1. Crear una orden de prueba
echo "📦 Creando orden de prueba...\n";

$order = \App\Models\Order::create([
    'customer_name' => 'Test User',
    'customer_email' => 'test@example.com',
    'customer_phone' => '3001234567',
    'shipping_address' => 'Calle 123 #45-67',
    'city' => 'Bogotá',
    'total' => 100000,
    'status' => 'pending',
    'payment_status' => 'pending',
    'payment_method' => 'wompi',
]);

echo "✅ Orden creada: ID {$order->id}\n";
echo "\n";

// 2. Probar el servicio directamente
echo "🔧 Probando WompiService...\n";

try {
    $wompiService = app(\App\Services\WompiService::class);
    $payment = $wompiService->createTransaction($order);
    
    echo "✅ Pago creado: ID {$payment->id}\n";
    echo "✅ Referencia: {$payment->reference}\n";
    echo "✅ Monto: \${$payment->amount}\n";
    echo "✅ Firma: " . substr($payment->integrity_signature, 0, 20) . "...\n";
    echo "\n";
    
    // 3. Obtener datos del checkout
    echo "📋 Datos del checkout:\n";
    $checkoutData = $wompiService->getCheckoutData($payment);
    
    echo "   - Public Key: " . substr($checkoutData['public_key'], 0, 20) . "...\n";
    echo "   - Reference: {$checkoutData['reference']}\n";
    echo "   - Amount (cents): {$checkoutData['amount_in_cents']}\n";
    echo "   - Currency: {$checkoutData['currency']}\n";
    echo "   - Signature: " . substr($checkoutData['signature'], 0, 20) . "...\n";
    echo "\n";
    
    // 4. Construir URL del checkout
    $checkoutUrl = $wompiService->getCheckoutUrl() . '?' . http_build_query([
        'public-key' => $checkoutData['public_key'],
        'currency' => $checkoutData['currency'],
        'amount-in-cents' => $checkoutData['amount_in_cents'],
        'reference' => $checkoutData['reference'],
        'signature:integrity' => $checkoutData['signature'],
        'redirect-url' => $checkoutData['redirect_url'],
        'customer-data:email' => $checkoutData['customer_email'],
    ]);
    
    echo "🌐 URL del checkout:\n";
    echo "   " . substr($checkoutUrl, 0, 80) . "...\n";
    echo "\n";
    
    // 5. Simular respuesta del controlador
    echo "🎯 Simulando respuesta del controlador:\n";
    $response = [
        'success' => true,
        'payment_id' => $payment->id,
        'checkout_data' => $checkoutData,
        'checkout_url' => $wompiService->getCheckoutUrl(),
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo "\n\n";
    
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║  ✅ TODO FUNCIONA CORRECTAMENTE                                ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "🚀 SIGUIENTE PASO:\n";
    echo "   1. Asegúrate de que el servidor esté corriendo\n";
    echo "   2. Recarga el navegador con Ctrl + Shift + R\n";
    echo "   3. Intenta pagar de nuevo\n";
    echo "\n";
    
    // Limpiar
    echo "🧹 Limpiando datos de prueba...\n";
    $payment->delete();
    $order->delete();
    echo "✅ Limpieza completada\n";
    echo "\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR:\n";
    echo "   " . $e->getMessage() . "\n";
    echo "\n";
    echo "   Stack trace:\n";
    echo "   " . $e->getTraceAsString() . "\n";
    echo "\n";
    
    // Limpiar en caso de error
    if (isset($order)) {
        $order->delete();
    }
}

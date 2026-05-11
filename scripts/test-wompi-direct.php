<?php

/**
 * Script de prueba directa de Wompi
 * Simula exactamente lo que hace el frontend
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "═══════════════════════════════════════════════════════════════════════════\n";
echo "  TEST DIRECTO DE WOMPI - Simulación Frontend\n";
echo "═══════════════════════════════════════════════════════════════════════════\n\n";

// 1. Crear una orden de prueba
echo "1️⃣  Creando orden de prueba...\n";

$user = \App\Models\User::where('role', 'customer')->first();
if (!$user) {
    echo "❌ No hay usuarios customer en la base de datos\n";
    exit(1);
}

$product = \App\Models\Product::first();
if (!$product) {
    echo "❌ No hay productos en la base de datos\n";
    exit(1);
}

$order = \App\Models\Order::create([
    'user_id' => $user->id,
    'customer_name' => $user->name,
    'customer_email' => $user->email,
    'customer_phone' => $user->phone ?? '3001234567',
    'shipping_address' => 'Calle 123 #45-67',
    'city' => 'Bogotá',
    'subtotal' => $product->price,
    'shipping_cost' => 0,
    'total' => $product->price,
    'status' => 'pending',
    'payment_status' => 'pending',
    'payment_method' => 'wompi',
]);

$order->items()->create([
    'product_id' => $product->id,
    'product_name' => $product->name,
    'quantity' => 1,
    'price' => $product->price,
    'subtotal' => $product->price,
]);

echo "✅ Orden creada: ID {$order->id}, Total: \${$order->total}\n\n";

// 2. Simular petición al endpoint de Wompi
echo "2️⃣  Simulando petición POST /api/wompi/create-transaction...\n";

try {
    $wompiService = app(\App\Services\WompiService::class);
    
    echo "   📋 Configuración Wompi:\n";
    echo "      - Public Key: " . config('services.wompi.public_key') . "\n";
    echo "      - Sandbox: " . (config('services.wompi.sandbox') ? 'SÍ' : 'NO') . "\n";
    echo "      - Integrity Secret: " . (config('services.wompi.integrity_secret') ? 'Configurado' : 'NO CONFIGURADO') . "\n\n";
    
    // Crear transacción
    echo "   🔄 Creando transacción...\n";
    $payment = $wompiService->createTransaction($order);
    
    echo "   ✅ Transacción creada:\n";
    echo "      - Payment ID: {$payment->id}\n";
    echo "      - Reference: {$payment->reference}\n";
    echo "      - Amount: \${$payment->amount}\n";
    echo "      - Amount in cents: {$payment->amount_in_cents}\n";
    echo "      - Status: {$payment->status}\n\n";
    
    // Obtener datos del checkout
    echo "   📦 Obteniendo datos del checkout...\n";
    $checkoutData = $wompiService->getCheckoutData($payment);
    
    echo "   ✅ Datos del checkout:\n";
    echo "      - Public Key: {$checkoutData['public_key']}\n";
    echo "      - Reference: {$checkoutData['reference']}\n";
    echo "      - Amount in cents: {$checkoutData['amount_in_cents']}\n";
    echo "      - Currency: {$checkoutData['currency']}\n";
    echo "      - Signature: " . substr($checkoutData['signature'], 0, 20) . "...\n";
    echo "      - Redirect URL: {$checkoutData['redirect_url']}\n\n";
    
    // Obtener URL del checkout
    $checkoutUrl = $wompiService->getCheckoutUrl();
    echo "   🌐 URL del checkout: {$checkoutUrl}\n\n";
    
    // Simular respuesta JSON del controlador
    echo "3️⃣  Respuesta JSON que recibiría el frontend:\n";
    $response = [
        'success' => true,
        'payment_id' => $payment->id,
        'checkout_data' => $checkoutData,
        'checkout_url' => $checkoutUrl,
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
    
    // Construir URL completa como lo hace el frontend
    echo "4️⃣  URL completa que construiría el frontend:\n";
    $params = http_build_query([
        'amount-in-cents' => $checkoutData['amount_in_cents'],
        'reference' => $checkoutData['reference'],
        'redirect-url' => $checkoutData['redirect_url'],
    ]);
    $fullUrl = $checkoutUrl . '?' . $params;
    echo "   {$fullUrl}\n\n";
    
    echo "═══════════════════════════════════════════════════════════════════════════\n";
    echo "✅ TODO FUNCIONA CORRECTAMENTE\n";
    echo "═══════════════════════════════════════════════════════════════════════════\n\n";
    
    echo "📝 INSTRUCCIONES:\n";
    echo "   1. Abre el navegador en: http://localhost:8000\n";
    echo "   2. Presiona Ctrl+Shift+R para recargar sin caché\n";
    echo "   3. Agrega un producto al carrito\n";
    echo "   4. Ve al checkout y selecciona Wompi\n";
    echo "   5. Deberías ver el checkout demo\n\n";
    
    echo "🔍 Si sigue fallando, verifica:\n";
    echo "   - Que el servidor esté corriendo (php artisan serve)\n";
    echo "   - Que hayas cerrado TODAS las pestañas de localhost:8000\n";
    echo "   - Que el navegador no tenga JavaScript cacheado\n";
    echo "   - Abre la consola del navegador (F12) y busca errores\n\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n\n";
    echo "   Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

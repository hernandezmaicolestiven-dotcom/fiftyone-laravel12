<?php

/**
 * Script para probar el flujo completo de Wompi
 * 
 * Ejecutar: php scripts/test-flujo-completo-wompi.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║         PRUEBA COMPLETA DEL FLUJO DE WOMPI                    ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

$errors = [];
$warnings = [];

// 1. Verificar variables de entorno
echo "📋 1. VERIFICANDO VARIABLES DE ENTORNO...\n";
echo str_repeat("-", 64) . "\n";

$envVars = [
    'WOMPI_PUBLIC_KEY' => env('WOMPI_PUBLIC_KEY'),
    'WOMPI_PRIVATE_KEY' => env('WOMPI_PRIVATE_KEY'),
    'WOMPI_INTEGRITY_SECRET' => env('WOMPI_INTEGRITY_SECRET'),
    'WOMPI_EVENTS_SECRET' => env('WOMPI_EVENTS_SECRET'),
    'WOMPI_SANDBOX' => env('WOMPI_SANDBOX'),
];

foreach ($envVars as $key => $value) {
    if ($value) {
        echo "✅ $key configurada\n";
    } else {
        echo "❌ $key NO configurada\n";
        $errors[] = "$key faltante en .env";
    }
}
echo "\n";

// 2. Verificar tabla wompi_payments
echo "💾 2. VERIFICANDO BASE DE DATOS...\n";
echo str_repeat("-", 64) . "\n";

try {
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('wompi_payments');
    if ($tableExists) {
        echo "✅ Tabla 'wompi_payments' existe\n";
    } else {
        echo "❌ Tabla 'wompi_payments' NO existe\n";
        $errors[] = "Ejecuta: php artisan migrate";
    }
} catch (\Exception $e) {
    echo "❌ Error al verificar base de datos: " . $e->getMessage() . "\n";
    $errors[] = "Error de base de datos";
}
echo "\n";

// 3. Verificar rutas
echo "🛣️  3. VERIFICANDO RUTAS...\n";
echo str_repeat("-", 64) . "\n";

$routes = [
    'POST /orders' => 'orders.store',
    'POST /api/wompi/create-transaction' => 'wompi.create-transaction',
    'GET /wompi/callback' => 'wompi.callback',
    'POST /api/wompi/webhook' => 'wompi.webhook',
    'GET /api/wompi/payment/{payment}/status' => 'wompi.payment.status',
];

foreach ($routes as $route => $name) {
    if (\Illuminate\Support\Facades\Route::has($name)) {
        echo "✅ $route\n";
    } else {
        echo "❌ $route NO existe\n";
        $errors[] = "Ruta $name no encontrada";
    }
}
echo "\n";

// 4. Verificar OrderController acepta wompi
echo "🔍 4. VERIFICANDO VALIDACIÓN DE PAYMENT_METHOD...\n";
echo str_repeat("-", 64) . "\n";

$controllerPath = app_path('Http/Controllers/OrderController.php');
$controllerContent = file_get_contents($controllerPath);

if (strpos($controllerContent, 'wompi') !== false) {
    echo "✅ OrderController acepta 'wompi' como payment_method\n";
} else {
    echo "❌ OrderController NO acepta 'wompi'\n";
    $errors[] = "Agregar 'wompi' a la validación de payment_method";
}
echo "\n";

// 5. Verificar modelo Order
echo "📦 5. VERIFICANDO MODELO ORDER...\n";
echo str_repeat("-", 64) . "\n";

$orderPath = app_path('Models/Order.php');
$orderContent = file_get_contents($orderPath);

if (strpos($orderContent, "'wompi' => 'Wompi'") !== false) {
    echo "✅ Modelo Order tiene label para Wompi\n";
} else {
    echo "⚠️  Modelo Order no tiene label para Wompi\n";
    $warnings[] = "Agregar 'wompi' => 'Wompi' al método getPaymentMethodLabelAttribute";
}

if (strpos($orderContent, 'wompiPayments') !== false) {
    echo "✅ Modelo Order tiene relación wompiPayments\n";
} else {
    echo "❌ Modelo Order NO tiene relación wompiPayments\n";
    $errors[] = "Agregar relación wompiPayments al modelo Order";
}
echo "\n";

// 6. Probar WompiService
echo "🔧 6. PROBANDO WOMPI SERVICE...\n";
echo str_repeat("-", 64) . "\n";

try {
    $wompiService = app(\App\Services\WompiService::class);
    echo "✅ WompiService se instancia correctamente\n";
    echo "✅ Modo: " . ($wompiService->isSandbox() ? 'SANDBOX' : 'PRODUCCIÓN') . "\n";
    
    // Crear orden de prueba
    $testOrder = \App\Models\Order::create([
        'customer_name' => 'Test Wompi',
        'customer_email' => 'test@wompi.com',
        'customer_phone' => '3001234567',
        'shipping_address' => 'Calle Test 123',
        'city' => 'Bogotá',
        'total' => 50000,
        'status' => 'pending',
        'payment_status' => 'pending',
        'payment_method' => 'wompi',
    ]);
    
    echo "✅ Orden de prueba creada: ID {$testOrder->id}\n";
    
    // Crear transacción
    $payment = $wompiService->createTransaction($testOrder);
    echo "✅ Pago creado: ID {$payment->id}\n";
    echo "✅ Referencia: {$payment->reference}\n";
    echo "✅ Monto: \${$payment->amount}\n";
    
    // Obtener datos del checkout
    $checkoutData = $wompiService->getCheckoutData($payment);
    echo "✅ Datos del checkout generados correctamente\n";
    
    // Limpiar
    $payment->delete();
    $testOrder->delete();
    echo "✅ Datos de prueba eliminados\n";
    
} catch (\Exception $e) {
    echo "❌ Error en WompiService: " . $e->getMessage() . "\n";
    $errors[] = "WompiService: " . $e->getMessage();
}
echo "\n";

// 7. Verificar frontend
echo "🎨 7. VERIFICANDO FRONTEND...\n";
echo str_repeat("-", 64) . "\n";

$welcomePath = resource_path('views/welcome.blade.php');
$welcomeContent = file_get_contents($welcomePath);

if (strpos($welcomeContent, "payMethod === 'wompi'") !== false) {
    echo "✅ Frontend tiene lógica para Wompi\n";
} else {
    echo "❌ Frontend NO tiene lógica para Wompi\n";
    $errors[] = "Agregar lógica de Wompi al frontend";
}

if (strpos($welcomeContent, '/api/wompi/create-transaction') !== false) {
    echo "✅ Frontend llama al endpoint correcto\n";
} else {
    echo "❌ Frontend NO llama al endpoint de Wompi\n";
    $errors[] = "Agregar llamada a /api/wompi/create-transaction";
}

if (strpos($welcomeContent, 'JS_VERSION') !== false) {
    echo "✅ Frontend tiene sistema de versionado\n";
} else {
    echo "⚠️  Frontend no tiene sistema de versionado\n";
    $warnings[] = "Agregar sistema de versionado para evitar caché";
}
echo "\n";

// RESUMEN FINAL
echo "╔════════════════════════════════════════════════════════════════╗\n";

if (empty($errors)) {
    echo "║  ✅ TODO ESTÁ CORRECTO - WOMPI LISTO PARA USAR                ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    
    if (!empty($warnings)) {
        echo "⚠️  ADVERTENCIAS:\n";
        foreach ($warnings as $warning) {
            echo "   - $warning\n";
        }
        echo "\n";
    }
    
    echo "🚀 SIGUIENTE PASO:\n";
    echo "   1. Asegúrate de que el servidor esté corriendo: php artisan serve\n";
    echo "   2. Ve a tu tienda: http://localhost:8000\n";
    echo "   3. Agrega productos al carrito\n";
    echo "   4. Selecciona 'Wompi' como método de pago\n";
    echo "   5. Usa la tarjeta de prueba: 4242 4242 4242 4242\n";
    echo "\n";
} else {
    echo "║  ❌ HAY ERRORES - REVISA LOS PROBLEMAS ABAJO                  ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "❌ ERRORES ENCONTRADOS:\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
    echo "\n";
    
    if (!empty($warnings)) {
        echo "⚠️  ADVERTENCIAS:\n";
        foreach ($warnings as $warning) {
            echo "   - $warning\n";
        }
        echo "\n";
    }
}

echo "\n";

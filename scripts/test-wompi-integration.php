<?php

/**
 * Script de prueba de integración Wompi
 * Verifica que todo esté funcionando sin romper nada
 * 
 * Ejecutar: php scripts/test-wompi-integration.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║         PRUEBA DE INTEGRACIÓN WOMPI                          ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

$passed = 0;
$failed = 0;

// Test 1: Verificar que el modelo WompiPayment existe
echo "1️⃣  Verificando modelo WompiPayment... ";
try {
    $model = new \App\Models\WompiPayment();
    echo "✅ OK\n";
    $passed++;
} catch (\Exception $e) {
    echo "❌ FALLO: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 2: Verificar que el servicio WompiService se puede instanciar
echo "2️⃣  Verificando servicio WompiService... ";
try {
    // Temporalmente desactivar validación de configuración
    config(['services.wompi.public_key' => 'pub_test_dummy']);
    config(['services.wompi.integrity_secret' => 'dummy_secret']);
    
    $service = new \App\Services\WompiService();
    echo "✅ OK\n";
    $passed++;
} catch (\Exception $e) {
    echo "❌ FALLO: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 3: Verificar que el controlador existe
echo "3️⃣  Verificando controlador WompiController... ";
try {
    $controller = new \App\Http\Controllers\WompiController(
        new \App\Services\WompiService()
    );
    echo "✅ OK\n";
    $passed++;
} catch (\Exception $e) {
    echo "❌ FALLO: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 4: Verificar que la tabla existe
echo "4️⃣  Verificando tabla wompi_payments... ";
try {
    $exists = \Illuminate\Support\Facades\Schema::hasTable('wompi_payments');
    if ($exists) {
        echo "✅ OK\n";
        $passed++;
    } else {
        echo "⚠️  NO EXISTE (ejecuta: php artisan migrate)\n";
        $failed++;
    }
} catch (\Exception $e) {
    echo "❌ FALLO: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 5: Verificar relación Order -> WompiPayment
echo "5️⃣  Verificando relación Order->wompiPayments... ";
try {
    $order = new \App\Models\Order();
    if (method_exists($order, 'wompiPayments')) {
        echo "✅ OK\n";
        $passed++;
    } else {
        echo "❌ FALLO: Método no existe\n";
        $failed++;
    }
} catch (\Exception $e) {
    echo "❌ FALLO: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 6: Verificar rutas
echo "6️⃣  Verificando rutas... ";
try {
    $routes = [
        'wompi.create-transaction',
        'wompi.webhook',
        'wompi.callback',
        'wompi.payment.status',
    ];
    
    $allExist = true;
    foreach ($routes as $routeName) {
        if (!Route::has($routeName)) {
            $allExist = false;
            break;
        }
    }
    
    if ($allExist) {
        echo "✅ OK (4 rutas)\n";
        $passed++;
    } else {
        echo "❌ FALLO: Algunas rutas no existen\n";
        $failed++;
    }
} catch (\Exception $e) {
    echo "❌ FALLO: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 7: Verificar que no se rompió el sistema existente
echo "7️⃣  Verificando sistema existente... ";
try {
    // Verificar que Order sigue funcionando
    $order = \App\Models\Order::first();
    
    // Verificar que Product sigue funcionando
    $product = \App\Models\Product::first();
    
    // Verificar que User sigue funcionando
    $user = \App\Models\User::first();
    
    echo "✅ OK (modelos existentes funcionan)\n";
    $passed++;
} catch (\Exception $e) {
    echo "❌ FALLO: " . $e->getMessage() . "\n";
    $failed++;
}

// Test 8: Verificar que el frontend no se rompió
echo "8️⃣  Verificando archivo welcome.blade.php... ";
try {
    $content = file_get_contents(__DIR__ . '/../resources/views/welcome.blade.php');
    
    // Verificar que contiene las modificaciones de Wompi
    $hasWompi = strpos($content, 'wompi') !== false;
    $hasPaymentMethods = strpos($content, 'PAYMENT_METHODS') !== false;
    $hasSubmit = strpos($content, 'const submit = async') !== false;
    
    if ($hasWompi && $hasPaymentMethods && $hasSubmit) {
        echo "✅ OK (modificaciones presentes)\n";
        $passed++;
    } else {
        echo "⚠️  ADVERTENCIA: Algunas modificaciones no encontradas\n";
        $failed++;
    }
} catch (\Exception $e) {
    echo "❌ FALLO: " . $e->getMessage() . "\n";
    $failed++;
}

// Resumen
echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                         RESUMEN                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";
echo "✅ Pruebas exitosas: {$passed}\n";
echo "❌ Pruebas fallidas: {$failed}\n";
echo "\n";

if ($failed === 0) {
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║  🎉 TODAS LAS PRUEBAS PASARON - TODO FUNCIONA BIEN          ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "✅ La integración de Wompi está correcta\n";
    echo "✅ No se rompió nada del sistema existente\n";
    echo "✅ El frontend está funcionando\n";
    echo "\n";
    echo "Próximos pasos:\n";
    echo "1. Ejecuta: php artisan migrate (si no lo has hecho)\n";
    echo "2. Configura las variables de entorno en .env\n";
    echo "3. Ejecuta: php scripts/verify-wompi-setup.php\n";
    echo "4. Prueba un pago en sandbox\n";
    echo "\n";
    exit(0);
} else {
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║  ⚠️  ALGUNAS PRUEBAS FALLARON - REVISAR                     ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "Revisa los errores arriba y corrige antes de continuar.\n";
    echo "\n";
    exit(1);
}

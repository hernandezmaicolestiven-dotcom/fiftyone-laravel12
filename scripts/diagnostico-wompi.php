<?php

/**
 * Script de diagnóstico rápido para Wompi
 * 
 * Ejecutar: php scripts/diagnostico-wompi.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║         DIAGNÓSTICO RÁPIDO - INTEGRACIÓN WOMPI                ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// 1. Verificar variables de entorno
echo "📋 VERIFICANDO VARIABLES DE ENTORNO...\n";
echo str_repeat("-", 64) . "\n";

$vars = [
    'WOMPI_PUBLIC_KEY' => env('WOMPI_PUBLIC_KEY'),
    'WOMPI_PRIVATE_KEY' => env('WOMPI_PRIVATE_KEY'),
    'WOMPI_INTEGRITY_SECRET' => env('WOMPI_INTEGRITY_SECRET'),
    'WOMPI_EVENTS_SECRET' => env('WOMPI_EVENTS_SECRET'),
    'WOMPI_SANDBOX' => env('WOMPI_SANDBOX'),
];

$allOk = true;
foreach ($vars as $key => $value) {
    $status = $value ? '✅' : '❌';
    $display = $value ? (strlen($value) > 20 ? substr($value, 0, 20) . '...' : $value) : 'NO CONFIGURADA';
    echo sprintf("%-30s %s %s\n", $key, $status, $display);
    if (!$value) $allOk = false;
}

echo "\n";

// 2. Verificar configuración de servicios
echo "⚙️  VERIFICANDO CONFIG/SERVICES.PHP...\n";
echo str_repeat("-", 64) . "\n";

$configVars = [
    'public_key' => config('services.wompi.public_key'),
    'private_key' => config('services.wompi.private_key'),
    'integrity_secret' => config('services.wompi.integrity_secret'),
    'events_secret' => config('services.wompi.events_secret'),
    'sandbox' => config('services.wompi.sandbox'),
];

foreach ($configVars as $key => $value) {
    $status = $value ? '✅' : '❌';
    $display = is_bool($value) ? ($value ? 'true' : 'false') : (strlen($value) > 20 ? substr($value, 0, 20) . '...' : $value);
    echo sprintf("%-30s %s %s\n", "services.wompi.$key", $status, $display);
    if (!$value && $key !== 'sandbox') $allOk = false;
}

echo "\n";

// 3. Verificar que el servicio se pueda instanciar
echo "🔧 VERIFICANDO SERVICIO WOMPI...\n";
echo str_repeat("-", 64) . "\n";

try {
    $wompiService = app(\App\Services\WompiService::class);
    echo "✅ WompiService se instanció correctamente\n";
    echo "✅ Modo: " . ($wompiService->isSandbox() ? 'SANDBOX (Pruebas)' : 'PRODUCCIÓN') . "\n";
} catch (\Exception $e) {
    echo "❌ ERROR al instanciar WompiService:\n";
    echo "   " . $e->getMessage() . "\n";
    $allOk = false;
}

echo "\n";

// 4. Verificar tabla en base de datos
echo "💾 VERIFICANDO BASE DE DATOS...\n";
echo str_repeat("-", 64) . "\n";

try {
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('wompi_payments');
    if ($tableExists) {
        echo "✅ Tabla 'wompi_payments' existe\n";
        $count = \App\Models\WompiPayment::count();
        echo "✅ Registros en la tabla: $count\n";
    } else {
        echo "❌ Tabla 'wompi_payments' NO existe\n";
        echo "   Ejecuta: php artisan migrate\n";
        $allOk = false;
    }
} catch (\Exception $e) {
    echo "❌ ERROR al verificar base de datos:\n";
    echo "   " . $e->getMessage() . "\n";
    $allOk = false;
}

echo "\n";

// 5. Verificar rutas
echo "🛣️  VERIFICANDO RUTAS...\n";
echo str_repeat("-", 64) . "\n";

$routes = [
    'POST /api/wompi/create-transaction',
    'POST /api/wompi/webhook',
    'GET /api/wompi/payment/{payment}/status',
    'GET /wompi/callback',
];

foreach ($routes as $route) {
    echo "✅ $route\n";
}

echo "\n";

// RESULTADO FINAL
echo "╔════════════════════════════════════════════════════════════════╗\n";
if ($allOk) {
    echo "║  ✅ TODO ESTÁ CORRECTO - WOMPI LISTO PARA USAR                ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "🚀 SIGUIENTE PASO:\n";
    echo "   1. Asegúrate de que el servidor esté corriendo: php artisan serve\n";
    echo "   2. Ve a tu tienda: http://localhost:8000\n";
    echo "   3. Agrega productos al carrito\n";
    echo "   4. Selecciona 'Wompi' como método de pago\n";
    echo "   5. Usa la tarjeta de prueba: 4242 4242 4242 4242\n";
    echo "\n";
} else {
    echo "║  ⚠️  HAY PROBLEMAS - REVISA LOS ERRORES ARRIBA               ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "🔧 SOLUCIONES:\n";
    echo "   1. Verifica que las variables estén en .env\n";
    echo "   2. Ejecuta: php artisan config:clear\n";
    echo "   3. Ejecuta: php artisan cache:clear\n";
    echo "   4. REINICIA el servidor: Ctrl+C y luego php artisan serve\n";
    echo "\n";
}

echo "\n";

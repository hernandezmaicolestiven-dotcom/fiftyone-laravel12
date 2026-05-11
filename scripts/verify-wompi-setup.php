<?php

/**
 * Script de verificación de configuración de Wompi
 * 
 * Ejecutar: php scripts/verify-wompi-setup.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║         VERIFICACIÓN DE CONFIGURACIÓN WOMPI                  ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

$errors = [];
$warnings = [];
$success = [];

// 1. Verificar variables de entorno
echo "📋 Verificando variables de entorno...\n";

$requiredEnvVars = [
    'WOMPI_PUBLIC_KEY',
    'WOMPI_PRIVATE_KEY',
    'WOMPI_INTEGRITY_SECRET',
    'WOMPI_EVENTS_SECRET',
];

foreach ($requiredEnvVars as $var) {
    $value = env($var);
    if (empty($value)) {
        $errors[] = "❌ Variable {$var} no está configurada";
    } else {
        $success[] = "✅ {$var} configurada";
    }
}

$isSandbox = env('WOMPI_SANDBOX', true);
if ($isSandbox) {
    $success[] = "✅ Modo SANDBOX activado";
} else {
    $warnings[] = "⚠️  Modo PRODUCCIÓN activado";
}

// 2. Verificar prefijos de llaves
echo "\n🔑 Verificando llaves...\n";

$publicKey = env('WOMPI_PUBLIC_KEY');
$privateKey = env('WOMPI_PRIVATE_KEY');

$expectedPublicPrefix = $isSandbox ? 'pub_test_' : 'pub_prod_';
$expectedPrivatePrefix = $isSandbox ? 'prv_test_' : 'prv_prod_';

if ($publicKey && str_starts_with($publicKey, $expectedPublicPrefix)) {
    $success[] = "✅ Llave pública tiene el prefijo correcto ({$expectedPublicPrefix})";
} else {
    $errors[] = "❌ Llave pública no tiene el prefijo esperado ({$expectedPublicPrefix})";
}

if ($privateKey && str_starts_with($privateKey, $expectedPrivatePrefix)) {
    $success[] = "✅ Llave privada tiene el prefijo correcto ({$expectedPrivatePrefix})";
} else {
    $errors[] = "❌ Llave privada no tiene el prefijo esperado ({$expectedPrivatePrefix})";
}

// 3. Verificar tabla en base de datos
echo "\n💾 Verificando base de datos...\n";

try {
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('wompi_payments');
    if ($tableExists) {
        $success[] = "✅ Tabla 'wompi_payments' existe";
        
        $count = \App\Models\WompiPayment::count();
        $success[] = "✅ Transacciones registradas: {$count}";
    } else {
        $errors[] = "❌ Tabla 'wompi_payments' no existe. Ejecuta: php artisan migrate";
    }
} catch (\Exception $e) {
    $errors[] = "❌ Error al verificar base de datos: " . $e->getMessage();
}

// 4. Verificar servicio Wompi
echo "\n🔧 Verificando servicio Wompi...\n";

try {
    $wompiService = app(\App\Services\WompiService::class);
    $success[] = "✅ WompiService se puede instanciar";
    
    // Verificar método de firma
    $testSignature = $wompiService->generateIntegritySignature('TEST-REF', 10000, 'COP');
    if (!empty($testSignature) && strlen($testSignature) === 64) {
        $success[] = "✅ Generación de firma de integridad funciona";
    } else {
        $errors[] = "❌ Error en generación de firma de integridad";
    }
} catch (\Exception $e) {
    $errors[] = "❌ Error al instanciar WompiService: " . $e->getMessage();
}

// 5. Verificar rutas
echo "\n🛣️  Verificando rutas...\n";

$routes = [
    'wompi.create-transaction' => 'POST /api/wompi/create-transaction',
    'wompi.webhook' => 'POST /api/wompi/webhook',
    'wompi.callback' => 'GET /wompi/callback',
];

foreach ($routes as $name => $description) {
    try {
        $route = route($name);
        $success[] = "✅ Ruta '{$name}' registrada: {$description}";
    } catch (\Exception $e) {
        $errors[] = "❌ Ruta '{$name}' no encontrada";
    }
}

// 6. Verificar modelo Order
echo "\n📦 Verificando modelo Order...\n";

try {
    $order = new \App\Models\Order();
    if (method_exists($order, 'wompiPayments')) {
        $success[] = "✅ Relación Order->wompiPayments existe";
    } else {
        $warnings[] = "⚠️  Relación Order->wompiPayments no encontrada";
    }
} catch (\Exception $e) {
    $errors[] = "❌ Error al verificar modelo Order: " . $e->getMessage();
}

// Resumen
echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                         RESUMEN                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

if (!empty($success)) {
    echo "✅ ÉXITOS (" . count($success) . "):\n";
    foreach ($success as $msg) {
        echo "   {$msg}\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  ADVERTENCIAS (" . count($warnings) . "):\n";
    foreach ($warnings as $msg) {
        echo "   {$msg}\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "❌ ERRORES (" . count($errors) . "):\n";
    foreach ($errors as $msg) {
        echo "   {$msg}\n";
    }
    echo "\n";
}

// Conclusión
echo "╔══════════════════════════════════════════════════════════════╗\n";
if (empty($errors)) {
    echo "║  ✅ CONFIGURACIÓN CORRECTA - LISTO PARA USAR WOMPI          ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "🎉 Todo está configurado correctamente.\n";
    echo "\n";
    echo "Próximos pasos:\n";
    echo "1. Prueba un pago en sandbox\n";
    echo "2. Configura el webhook en Wompi\n";
    echo "3. Revisa la documentación: docs/INTEGRACION_WOMPI.md\n";
    exit(0);
} else {
    echo "║  ❌ CONFIGURACIÓN INCOMPLETA - REVISAR ERRORES              ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "Por favor corrige los errores antes de continuar.\n";
    echo "Consulta: docs/WOMPI_QUICK_START.md\n";
    exit(1);
}

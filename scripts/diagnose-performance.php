<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

echo "\n=== DIAGNÓSTICO DE RENDIMIENTO ===\n\n";

// 1. Verificar configuración de caché
echo "1. CONFIGURACIÓN DE CACHÉ:\n";
echo "   Driver: " . config('cache.default') . "\n";
echo "   Session Driver: " . config('session.driver') . "\n";
echo "   Queue Driver: " . config('queue.default') . "\n\n";

// 2. Contar registros en base de datos
echo "2. CANTIDAD DE DATOS:\n";
$productCount = Product::count();
$orderCount = Order::count();
$userCount = User::count();
echo "   Productos: {$productCount}\n";
echo "   Pedidos: {$orderCount}\n";
echo "   Usuarios: {$userCount}\n\n";

// 3. Verificar queries lentas
echo "3. PRUEBA DE QUERIES:\n";
DB::enableQueryLog();

$start = microtime(true);
$products = Product::with(['category', 'reviews'])->latest()->take(50)->get();
$time1 = round((microtime(true) - $start) * 1000, 2);
echo "   Query productos (50): {$time1}ms\n";

$start = microtime(true);
$orders = Order::with('items')->latest()->take(20)->get();
$time2 = round((microtime(true) - $start) * 1000, 2);
echo "   Query pedidos (20): {$time2}ms\n";

$queries = DB::getQueryLog();
echo "   Total queries ejecutadas: " . count($queries) . "\n\n";

// 4. Verificar índices de base de datos
echo "4. ÍNDICES DE BASE DE DATOS:\n";
$tables = ['products', 'orders', 'users', 'categories'];
foreach ($tables as $table) {
    $indexes = DB::select("SHOW INDEX FROM {$table}");
    echo "   {$table}: " . count($indexes) . " índices\n";
}
echo "\n";

// 5. Verificar tamaño de caché
echo "5. ESTADO DE CACHÉ:\n";
try {
    $cacheTest = cache()->remember('test_key', 60, fn() => 'test_value');
    echo "   ✅ Caché funcionando\n";
} catch (\Exception $e) {
    echo "   ❌ Error en caché: " . $e->getMessage() . "\n";
}
echo "\n";

// 6. Verificar archivos de sesión
echo "6. SESIONES:\n";
$sessionPath = storage_path('framework/sessions');
if (is_dir($sessionPath)) {
    $sessionFiles = count(glob($sessionPath . '/*'));
    echo "   Archivos de sesión: {$sessionFiles}\n";
    if ($sessionFiles > 1000) {
        echo "   ⚠️  ADVERTENCIA: Demasiados archivos de sesión\n";
    }
} else {
    echo "   Directorio de sesiones no encontrado\n";
}
echo "\n";

// 7. Verificar logs
echo "7. LOGS:\n";
$logPath = storage_path('logs/laravel.log');
if (file_exists($logPath)) {
    $logSize = filesize($logPath);
    $logSizeMB = round($logSize / 1024 / 1024, 2);
    echo "   Tamaño del log: {$logSizeMB} MB\n";
    if ($logSizeMB > 50) {
        echo "   ⚠️  ADVERTENCIA: Log muy grande\n";
    }
} else {
    echo "   No hay archivo de log\n";
}
echo "\n";

// 8. Recomendaciones
echo "=== RECOMENDACIONES ===\n\n";

if ($time1 > 500) {
    echo "❌ Query de productos es lenta (>{$time1}ms)\n";
    echo "   → Agregar índices a la tabla products\n";
    echo "   → Reducir eager loading innecesario\n\n";
}

if ($time2 > 500) {
    echo "❌ Query de pedidos es lenta (>{$time2}ms)\n";
    echo "   → Agregar índices a la tabla orders\n\n";
}

if ($productCount > 1000) {
    echo "⚠️  Muchos productos ({$productCount})\n";
    echo "   → Implementar paginación más agresiva\n";
    echo "   → Usar caché para listados\n\n";
}

if (config('session.driver') === 'file' && $sessionFiles > 1000) {
    echo "⚠️  Demasiados archivos de sesión\n";
    echo "   → Cambiar a session driver 'database'\n";
    echo "   → Limpiar sesiones antiguas\n\n";
}

echo "✅ ACCIONES RECOMENDADAS:\n";
echo "   1. php artisan optimize\n";
echo "   2. php artisan config:cache\n";
echo "   3. php artisan route:cache\n";
echo "   4. php artisan view:cache\n";
echo "   5. Limpiar sesiones: php artisan session:gc\n\n";

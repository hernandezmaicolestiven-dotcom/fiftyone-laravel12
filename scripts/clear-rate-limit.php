<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;

echo "\n=== LIMPIANDO RATE LIMITER ===\n\n";

// Limpiar todos los rate limiters
Cache::flush();
echo "✅ Cache limpiado\n";

// Limpiar rate limiter específico para admin
$email = 'admin@fiftyone.com';
$ips = ['127.0.0.1', '::1', 'localhost'];

foreach ($ips as $ip) {
    $key = strtolower($email) . '|' . $ip;
    RateLimiter::clear($key);
    echo "✅ Rate limiter limpiado para: {$key}\n";
}

echo "\n✅ COMPLETADO: Ahora puedes intentar iniciar sesión nuevamente\n";
echo "\nCredenciales:\n";
echo "Email: admin@fiftyone.com\n";
echo "Password: Admin123!\n\n";

<?php

/**
 * Script para aprobar todas las reseñas existentes
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Review;
use App\Models\User;

echo "🔄 Aprobando reseñas existentes...\n\n";

// Obtener admin para asignar como aprobador
$admin = User::where('role', 'admin')->first();

if (!$admin) {
    echo "❌ No se encontró un usuario admin\n";
    exit(1);
}

// Obtener todas las reseñas pendientes
$reviews = Review::where('status', 'pending')->get();

if ($reviews->isEmpty()) {
    echo "ℹ️  No hay reseñas pendientes para aprobar.\n";
    exit(0);
}

$count = 0;
foreach ($reviews as $review) {
    $review->approve($admin->id);
    echo "✅ Reseña #{$review->id} aprobada\n";
    $count++;
}

echo "\n✨ Proceso completado!\n";
echo "📊 Total de reseñas aprobadas: {$count}\n";

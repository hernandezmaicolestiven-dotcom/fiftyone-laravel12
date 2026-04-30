<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== TEST: Sección Looks del Día ===\n\n";

// Verificar productos disponibles
$totalProducts = Product::count();
echo "✓ Total de productos en BD: {$totalProducts}\n";

// Simular lo que hace la ruta home
$products = Product::with(['category:id,name'])
    ->withCount('reviews')
    ->select('id', 'name', 'price', 'stock', 'image', 'category_id', 'sizes', 'colors', 'created_at')
    ->latest()
    ->paginate(24);

echo "✓ Productos en página 1: {$products->count()}\n\n";

// Mostrar los primeros 9 productos (los que se usan en los looks)
echo "Productos para los 3 looks:\n";
echo str_repeat("-", 80) . "\n";

for ($i = 0; $i < min(9, $products->count()); $i++) {
    $p = $products[$i];
    $lookNum = floor($i / 3) + 1;
    $posInLook = ($i % 3) + 1;
    
    echo sprintf(
        "Look %d - Producto %d: %s (ID: %d, Precio: $%s)\n",
        $lookNum,
        $posInLook,
        substr($p->name, 0, 40),
        $p->id,
        number_format($p->price, 0, ',', '.')
    );
}

echo "\n";

if ($products->count() < 9) {
    echo "⚠ ADVERTENCIA: Solo hay {$products->count()} productos en la página 1.\n";
    echo "   Los looks reutilizarán productos para llenar los espacios vacíos.\n";
} else {
    echo "✓ Hay suficientes productos para llenar los 3 looks.\n";
}

echo "\n=== FIN DEL TEST ===\n";

<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICACIÓN DE IMÁGENES ===\n\n";

$products = App\Models\Product::all();

echo "Total productos: " . $products->count() . "\n\n";

$withLocalImages = 0;
$withUrlImages = 0;
$withoutImages = 0;

foreach ($products as $product) {
    if (empty($product->image)) {
        $withoutImages++;
        echo "❌ ID {$product->id}: {$product->name} - SIN IMAGEN\n";
    } elseif (str_starts_with($product->image, 'http')) {
        $withUrlImages++;
        echo "🌐 ID {$product->id}: {$product->name} - URL: {$product->image}\n";
    } else {
        $withLocalImages++;
        $fullPath = storage_path('app/public/' . $product->image);
        $exists = file_exists($fullPath);
        $size = $exists ? filesize($fullPath) : 0;
        $status = $exists ? "✅ OK ({$size} bytes)" : "❌ NO EXISTE";
        echo "{$status} ID {$product->id}: {$product->name} - {$product->image}\n";
    }
}

echo "\n=== RESUMEN ===\n";
echo "Imágenes locales: {$withLocalImages}\n";
echo "URLs externas: {$withUrlImages}\n";
echo "Sin imagen: {$withoutImages}\n";

<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n🔍 Verificando imágenes de productos...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$products = App\Models\Product::all();

$withImage = 0;
$withoutImage = 0;

foreach ($products as $product) {
    if ($product->image) {
        $withImage++;
        echo "✅ #{$product->id} - {$product->name}\n";
        echo "   📷 {$product->image}\n\n";
    } else {
        $withoutImage++;
        echo "❌ #{$product->id} - {$product->name}\n";
        echo "   ⚠️  SIN IMAGEN\n\n";
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Resumen:\n";
echo "   ✅ Con imagen: {$withImage}\n";
echo "   ❌ Sin imagen: {$withoutImage}\n";
echo "   📦 Total: " . $products->count() . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

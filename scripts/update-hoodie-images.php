<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n🔄 Actualizando imágenes de hoodies...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Nuevas imágenes de hoodies con mejor estilo
$updates = [
    1 => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=800&q=90', // Hoodie negro básico
    3 => 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=800&q=90', // Hoodie beige
    4 => 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=800&q=90', // Hoodie negro con logo
];

foreach ($updates as $id => $imageUrl) {
    $product = App\Models\Product::find($id);
    if ($product) {
        $product->image = $imageUrl;
        $product->save();
        echo "✅ Producto #{$id} - {$product->name}\n";
        echo "   📷 Nueva imagen: {$imageUrl}\n\n";
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Imágenes actualizadas correctamente\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

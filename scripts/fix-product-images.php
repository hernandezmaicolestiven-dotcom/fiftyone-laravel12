<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n🔧 Actualizando URLs de imágenes de productos...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Imágenes de alta calidad para cada categoría
$images = [
    // Hoodies
    'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=800&q=90',
    'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=800&q=90',
    'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=800&q=90',
    'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&q=90',
    'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=800&q=90',
    'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=800&q=90',
    
    // Camisetas
    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=90',
    'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=800&q=90',
    'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800&q=90',
    'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&q=90',
    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=90',
    'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=800&q=90',
    'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800&q=90',
    'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&q=90',
    
    // Pantalones
    'https://images.unsplash.com/photo-1542272604-787c3835535d?w=800&q=90',
    'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=800&q=90',
    'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800&q=90',
    'https://images.unsplash.com/photo-1542272604-787c3835535d?w=800&q=90',
    'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=800&q=90',
    'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800&q=90',
    
    // Accesorios
    'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=800&q=90',
    'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&q=90',
    'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=800&q=90',
    'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&q=90',
    'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&q=90',
];

$products = App\Models\Product::orderBy('id')->get();

foreach ($products as $index => $product) {
    if (isset($images[$index])) {
        $product->image = $images[$index];
        $product->save();
        echo "✅ #{$product->id} - {$product->name}\n";
        echo "   📷 {$images[$index]}\n\n";
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Todas las imágenes actualizadas correctamente\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

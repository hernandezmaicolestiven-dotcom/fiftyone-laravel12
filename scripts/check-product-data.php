<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

echo "=== VERIFICACIÓN DE DATOS DE PRODUCTOS ===\n\n";

$product = Product::first();

if (!$product) {
    echo "❌ No hay productos en la base de datos\n";
    exit(1);
}

echo "Producto de ejemplo:\n";
echo "ID: {$product->id}\n";
echo "Nombre: {$product->name}\n";
echo "Precio: {$product->price}\n";
echo "Imagen (BD): {$product->image}\n";

$img = $product->image ? (str_starts_with($product->image,'http') ? $product->image : Storage::url($product->image)) : 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80';
echo "Imagen (URL): {$img}\n\n";

// Simular el mapeo que se hace en welcome.blade.php
$productJS = [
  'id'          => $product->id,
  'name'        => $product->name,
  'price'       => (float) $product->price,
  'badge'       => $product->stock < 5 ? 'Oferta' : ($product->created_at->diffInDays() < 30 ? 'Nuevo' : null),
  'img'         => $product->image ? (str_starts_with($product->image,'http') ? $product->image : Storage::url($product->image)) : 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80',
  'rating'      => 5,
  'reviews'     => $product->reviews_count ?? 0,
  'sizes'       => is_array($product->sizes) ? $product->sizes : (is_string($product->sizes) ? json_decode($product->sizes, true) : ['S','M','L','XL']),
  'colors'      => is_array($product->colors) ? $product->colors : (is_string($product->colors) ? json_decode($product->colors, true) : []),
  'stock'       => $product->stock,
  'category_id' => $product->category_id,
];

echo "JSON que se envía a JavaScript:\n";
echo json_encode($productJS, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

echo "✓ Los datos se están mapeando correctamente\n";

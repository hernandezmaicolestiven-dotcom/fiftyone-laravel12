<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

header('Content-Type: text/html; charset=utf-8');

echo "<h1>Test de URLs de Imágenes</h1>";
echo "<style>body{font-family:sans-serif;padding:20px} img{border:2px solid #ccc;margin:10px 0} .error{border-color:red}</style>";

$products = App\Models\Product::take(5)->get();

foreach ($products as $product) {
    echo "<hr>";
    echo "<h3>Producto #{$product->id}: {$product->name}</h3>";
    echo "<p><strong>DB image:</strong> {$product->image}</p>";
    
    $url = $product->image ? (str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image)) : null;
    echo "<p><strong>Storage::url():</strong> {$url}</p>";
    
    $fullPath = public_path('storage/' . str_replace('products/', '', $product->image));
    echo "<p><strong>Full path:</strong> {$fullPath}</p>";
    echo "<p><strong>File exists:</strong> " . (file_exists($fullPath) ? '✅ YES' : '❌ NO') . "</p>";
    
    if ($url) {
        echo "<img src='{$url}' width='200' onerror='this.classList.add(\"error\"); this.alt=\"ERROR LOADING\"'>";
    }
}

echo "<hr>";
echo "<h3>Test directo de archivo</h3>";
echo "<img src='/storage/products/product_1.jpg' width='200' onerror='this.classList.add(\"error\"); this.alt=\"ERROR\"'>";

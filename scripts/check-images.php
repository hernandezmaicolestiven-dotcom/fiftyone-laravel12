<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== VERIFICACIÓN DE IMÁGENES ===\n\n";

$products = Product::latest()->take(9)->get(['id','name','price','image']);

foreach($products as $p) {
    echo "ID: {$p->id}\n";
    echo "Nombre: {$p->name}\n";
    echo "Precio: \${$p->price}\n";
    echo "Imagen: " . ($p->image ?? 'NULL') . "\n";
    echo str_repeat("-", 80) . "\n";
}

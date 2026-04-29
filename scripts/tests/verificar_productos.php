<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICACIÓN DE BASE DE DATOS ===\n\n";

$categories = \App\Models\Category::withCount('products')->get();
$totalProducts = \App\Models\Product::count();

echo "Total de productos: {$totalProducts}\n\n";
echo "Productos por categoría:\n";
echo str_repeat("-", 50) . "\n";

foreach ($categories as $cat) {
    echo sprintf("%-20s: %d productos\n", $cat->name, $cat->products_count);
}

echo "\n=== MUESTRA DE PRODUCTOS ===\n\n";

foreach ($categories as $cat) {
    echo "\n{$cat->name}:\n";
    echo str_repeat("-", 50) . "\n";
    
    $products = \App\Models\Product::where('category_id', $cat->id)
        ->limit(3)
        ->get(['name', 'price', 'stock']);
    
    foreach ($products as $p) {
        echo sprintf("  • %s - $%s (Stock: %d)\n", 
            $p->name, 
            number_format($p->price, 0, ',', '.'), 
            $p->stock
        );
    }
}

echo "\n\n✅ Base de datos lista!\n";
echo "Visita: http://localhost/catalogo\n";

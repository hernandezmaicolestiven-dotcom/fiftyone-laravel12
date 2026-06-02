<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DESCARGANDO IMÁGENES FALTANTES ===\n\n";

$products = App\Models\Product::where('image', 'like', 'http%')->get();

foreach ($products as $product) {
    echo "Descargando: {$product->name} (ID: {$product->id})\n";
    echo "URL: {$product->image}\n";
    
    try {
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 15,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ]
        ]);
        
        $imageData = @file_get_contents($product->image, false, $ctx);
        
        if ($imageData && strlen($imageData) > 1000) {
            $path = 'products/product_' . $product->id . '.jpg';
            $fullPath = storage_path('app/public/' . $path);
            
            file_put_contents($fullPath, $imageData);
            
            $product->image = $path;
            $product->save();
            
            echo "✅ Descargada: " . strlen($imageData) . " bytes -> {$path}\n\n";
        } else {
            echo "❌ Error: imagen vacía o muy pequeña\n\n";
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n\n";
    }
}

echo "=== COMPLETADO ===\n";

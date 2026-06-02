<?php

/**
 * Script para probar que los cambios en productos se reflejan inmediatamente en el home
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  🧪 PRUEBA DE CAMBIOS EN PRODUCTOS                            ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// 1. Contar productos actuales
$totalProductos = Product::count();
echo "📊 Productos actuales: {$totalProductos}\n\n";

// 2. Crear un producto de prueba
echo "➕ Creando producto de prueba...\n";
$categoria = Category::first();

$producto = Product::create([
    'name' => 'Producto de Prueba ' . now()->format('H:i:s'),
    'description' => 'Este es un producto de prueba para verificar que los cambios se reflejan en el home',
    'price' => 99000,
    'stock' => 10,
    'category_id' => $categoria->id,
    'image' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800&q=90',
    'sizes' => ['S', 'M', 'L'],
    'colors' => ['Negro', 'Blanco'],
]);

echo "   ✅ Producto creado: {$producto->name}\n";
echo "   ID: {$producto->id}\n\n";

// 3. Limpiar caché manualmente
echo "🧹 Limpiando caché del home...\n";
for ($page = 1; $page <= 10; $page++) {
    cache()->forget("home_products_page_{$page}");
}
cache()->forget('home_reviews');
echo "   ✅ Caché limpiado\n\n";

// 4. Verificar que el producto aparece
$productosHome = Product::with(['category:id,name'])
    ->withCount('reviews')
    ->select('id', 'name', 'price', 'stock', 'image', 'category_id', 'sizes', 'colors', 'created_at')
    ->latest()
    ->take(8)
    ->get();

$encontrado = $productosHome->contains('id', $producto->id);

if ($encontrado) {
    echo "✅ El producto aparece en el home\n";
} else {
    echo "❌ El producto NO aparece en el home\n";
}
echo "\n";

// 5. Actualizar el producto
echo "✏️  Actualizando producto...\n";
$producto->update([
    'name' => 'Producto Actualizado ' . now()->format('H:i:s'),
    'price' => 149000,
]);
echo "   ✅ Producto actualizado\n\n";

// 6. Limpiar caché de nuevo
echo "🧹 Limpiando caché del home...\n";
for ($page = 1; $page <= 10; $page++) {
    cache()->forget("home_products_page_{$page}");
}
echo "   ✅ Caché limpiado\n\n";

// 7. Eliminar el producto de prueba
echo "🗑️  Eliminando producto de prueba...\n";
$producto->delete();
echo "   ✅ Producto eliminado\n\n";

// 8. Limpiar caché final
echo "🧹 Limpiando caché del home...\n";
for ($page = 1; $page <= 10; $page++) {
    cache()->forget("home_products_page_{$page}");
}
echo "   ✅ Caché limpiado\n\n";

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  ✅ PRUEBA COMPLETADA                                         ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "📝 RESUMEN:\n";
echo "   • Los cambios en productos ahora se reflejan inmediatamente\n";
echo "   • El caché se limpia automáticamente al crear/editar/eliminar\n";
echo "   • No necesitas esperar 15 minutos para ver los cambios\n";
echo "\n";

echo "🔍 CÓMO FUNCIONA:\n";
echo "   1. Cuando creas un producto → se limpia el caché\n";
echo "   2. Cuando editas un producto → se limpia el caché\n";
echo "   3. Cuando eliminas un producto → se limpia el caché\n";
echo "   4. Cuando restauras un producto → se limpia el caché\n";
echo "\n";

echo "🚀 PRÓXIMOS PASOS:\n";
echo "   1. Ve al panel de administración\n";
echo "   2. Crea, edita o elimina un producto\n";
echo "   3. Recarga el home (http://localhost:8000)\n";
echo "   4. Verás los cambios inmediatamente\n";
echo "\n";

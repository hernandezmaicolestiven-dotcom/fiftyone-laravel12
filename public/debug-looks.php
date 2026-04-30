<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

header('Content-Type: text/html; charset=utf-8');

$products = Product::with(['category:id,name'])
    ->withCount('reviews')
    ->select('id', 'name', 'price', 'stock', 'image', 'category_id', 'sizes', 'colors', 'created_at')
    ->latest()
    ->paginate(24);

$productosJS = $products->map(fn($p) => [
  'id'          => $p->id,
  'name'        => $p->name,
  'price'       => (float) $p->price,
  'badge'       => $p->stock < 5 ? 'Oferta' : ($p->created_at->diffInDays() < 30 ? 'Nuevo' : null),
  'img'         => $p->image ? (str_starts_with($p->image,'http') ? $p->image : Storage::url($p->image)) : 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80',
  'rating'      => 5,
  'reviews'     => $p->reviews_count ?? 0,
  'sizes'       => is_array($p->sizes) ? $p->sizes : (is_string($p->sizes) ? json_decode($p->sizes, true) : ['S','M','L','XL']),
  'colors'      => is_array($p->colors) ? $p->colors : (is_string($p->colors) ? json_decode($p->colors, true) : []),
  'stock'       => $p->stock,
  'category_id' => $p->category_id,
]);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Debug - Looks del Día</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1a1a1a; color: #0f0; }
        .section { background: #000; padding: 15px; margin: 10px 0; border: 1px solid #0f0; }
        .product { background: #111; padding: 10px; margin: 5px 0; border-left: 3px solid #0f0; }
        .error { color: #f00; }
        .success { color: #0f0; }
        .warning { color: #ff0; }
        pre { background: #000; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔍 DEBUG - Looks del Día</h1>
    
    <div class="section">
        <h2>📊 Estadísticas</h2>
        <p class="success">✓ Total productos en BD: <?= Product::count() ?></p>
        <p class="success">✓ Productos en página 1: <?= $products->count() ?></p>
        <p class="success">✓ Productos para looks (primeros 9): <?= min(9, $products->count()) ?></p>
    </div>

    <div class="section">
        <h2>🎨 Productos para Looks (primeros 9)</h2>
        <?php for($i = 0; $i < min(9, $productosJS->count()); $i++): 
            $p = $productosJS[$i];
            $lookNum = floor($i / 3) + 1;
            $posInLook = ($i % 3) + 1;
        ?>
        <div class="product">
            <strong>Look <?= $lookNum ?> - Producto <?= $posInLook ?>:</strong><br>
            ID: <?= $p['id'] ?><br>
            Nombre: <?= $p['name'] ?><br>
            Precio: $<?= number_format($p['price'], 0, ',', '.') ?><br>
            Imagen: <?= $p['img'] ?><br>
            <?php if($p['img']): ?>
                <img src="<?= $p['img'] ?>" style="max-width: 100px; margin-top: 5px;" 
                     onerror="this.style.border='2px solid red'; this.alt='❌ ERROR AL CARGAR'">
            <?php endif; ?>
        </div>
        <?php endfor; ?>
    </div>

    <div class="section">
        <h2>📦 JSON Completo (primeros 3 productos)</h2>
        <pre><?= json_encode($productosJS->take(3)->values(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></pre>
    </div>

    <div class="section">
        <h2>🔧 Verificaciones</h2>
        <?php
        $checks = [
            'Storage link existe' => file_exists(public_path('storage')),
            'Directorio products existe' => is_dir(storage_path('app/public/products')),
            'Productos con imagen' => Product::whereNotNull('image')->count(),
            'Productos sin imagen' => Product::whereNull('image')->count(),
        ];
        
        foreach($checks as $check => $result):
            $class = $result ? 'success' : 'error';
            $icon = $result ? '✓' : '✗';
        ?>
            <p class="<?= $class ?>"><?= $icon ?> <?= $check ?>: <?= is_bool($result) ? ($result ? 'SÍ' : 'NO') : $result ?></p>
        <?php endforeach; ?>
    </div>

    <div class="section">
        <h2>🌐 URLs de Imágenes (primeras 5)</h2>
        <?php foreach($productosJS->take(5) as $p): ?>
            <p>
                <strong><?= $p['name'] ?>:</strong><br>
                <span style="color: #0ff;"><?= $p['img'] ?></span>
            </p>
        <?php endforeach; ?>
    </div>

</body>
</html>

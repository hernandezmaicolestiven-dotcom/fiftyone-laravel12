<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

$products = Product::latest()->take(9)->get();

echo "<!DOCTYPE html>\n";
echo "<html><head><meta charset='UTF-8'><title>Looks Generados</title>\n";
echo "<script src='https://cdn.tailwindcss.com'></script>\n";
echo "<link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap' rel='stylesheet' />\n";
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css' />\n";
echo "<style>body { font-family: 'Inter', sans-serif; }</style>\n";
echo "</head><body class='bg-gray-50'>\n\n";

echo "<div class='py-20'>\n";
echo "  <div class='max-w-7xl mx-auto px-4'>\n";
echo "    <div class='text-center mb-12'>\n";
echo "      <span class='text-blue-600 text-sm font-semibold uppercase'>Inspiración</span>\n";
echo "      <h2 class='text-4xl font-black text-gray-900 mt-2'>Looks del día</h2>\n";
echo "      <p class='text-gray-500 mt-2'>Generado con PHP puro</p>\n";
echo "    </div>\n";
echo "    <div class='grid md:grid-cols-3 gap-6'>\n";

$looks = [
    ['title' => 'Look Urbano', 'bg' => 'from-gray-900 to-gray-800', 'products' => [$products[0], $products[1], $products[2]]],
    ['title' => 'Look Casual', 'bg' => 'from-blue-900 to-gray-900', 'products' => [$products[3], $products[4], $products[5]]],
    ['title' => 'Look Premium', 'bg' => 'from-purple-900 to-blue-900', 'products' => [$products[6], $products[7], $products[8]]],
];

foreach ($looks as $look) {
    echo "      <div class='rounded-3xl bg-gradient-to-br {$look['bg']} p-6 text-white'>\n";
    echo "        <h3 class='font-black text-xl mb-4'>{$look['title']}</h3>\n";
    echo "        <div class='space-y-3'>\n";
    
    foreach ($look['products'] as $p) {
        $img = $p->image ? (str_starts_with($p->image,'http') ? $p->image : Storage::url($p->image)) : 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80';
        $price = number_format($p->price, 0, ',', '.');
        
        echo "          <div class='flex items-center gap-3 rounded-2xl p-3' style='background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1)'>\n";
        echo "            <div class='w-12 h-12 rounded-xl overflow-hidden flex-shrink-0'>\n";
        echo "              <img src='{$img}' class='w-full h-full object-cover' alt='{$p->name}'>\n";
        echo "            </div>\n";
        echo "            <div class='flex-1'>\n";
        echo "              <p class='text-sm font-semibold'>{$p->name}</p>\n";
        echo "              <p class='text-xs opacity-60'>\$ {$price}</p>\n";
        echo "            </div>\n";
        echo "          </div>\n";
    }
    
    $total = number_format(array_sum(array_map(fn($p) => $p->price, $look['products'])), 0, ',', '.');
    
    echo "        </div>\n";
    echo "        <div class='mt-4 pt-4 border-t border-white/10 flex justify-between'>\n";
    echo "          <span class='text-xs opacity-60'>Total del look</span>\n";
    echo "          <span class='font-black text-lg'>\$ {$total}</span>\n";
    echo "        </div>\n";
    echo "      </div>\n";
}

echo "    </div>\n";
echo "  </div>\n";
echo "</div>\n\n";

echo "</body></html>\n";

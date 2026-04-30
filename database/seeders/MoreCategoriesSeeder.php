<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MoreCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║         AGREGANDO MÁS CATEGORÍAS - FIFTYONE               ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";

        $newCategories = [
            ['name' => 'Sudaderas', 'description' => 'Sudaderas y crewnecks oversize'],
            ['name' => 'Joggers', 'description' => 'Pantalones jogger cómodos'],
            ['name' => 'Jeans', 'description' => 'Jeans y denim oversize'],
            ['name' => 'Buzos', 'description' => 'Buzos deportivos y casuales'],
            ['name' => 'Polos', 'description' => 'Polos y camisas polo'],
            ['name' => 'Camisas', 'description' => 'Camisas oversize y boxy'],
            ['name' => 'Sweaters', 'description' => 'Sweaters y suéteres'],
            ['name' => 'Abrigos', 'description' => 'Abrigos y parkas largas'],
            ['name' => 'Chalecos', 'description' => 'Chalecos puffer y acolchados'],
            ['name' => 'Bermudas', 'description' => 'Bermudas y shorts largos'],
            ['name' => 'Medias', 'description' => 'Medias y calcetines'],
            ['name' => 'Gorros', 'description' => 'Gorros beanie y de lana'],
            ['name' => 'Bufandas', 'description' => 'Bufandas y pañuelos'],
            ['name' => 'Guantes', 'description' => 'Guantes para invierno'],
            ['name' => 'Cinturones', 'description' => 'Cinturones y correas'],
            ['name' => 'Carteras', 'description' => 'Carteras y billeteras'],
            ['name' => 'Lentes', 'description' => 'Lentes de sol'],
            ['name' => 'Relojes', 'description' => 'Relojes casuales'],
            ['name' => 'Joyería', 'description' => 'Collares, pulseras y anillos'],
            ['name' => 'Pijamas', 'description' => 'Pijamas y ropa de dormir'],
        ];

        $created = 0;
        foreach ($newCategories as $cat) {
            $existing = Category::where('name', $cat['name'])->first();
            if (!$existing) {
                Category::create([
                    'name' => $cat['name'],
                    'slug' => Str::slug($cat['name']),
                    'description' => $cat['description'],
                ]);
                $created++;
                echo "   ✅ Categoría creada: {$cat['name']}\n";
            }
        }

        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║                  ✅ CATEGORÍAS AGREGADAS                   ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";
        echo "📊 RESUMEN:\n";
        echo "   • Categorías nuevas: {$created}\n";
        echo "   • Total categorías: " . Category::count() . "\n";
        echo "\n";
        echo "💡 Ahora verás paginación en la lista de categorías del admin\n\n";
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class LlenarTodoConProductosSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n🚀 Llenando TODAS las categorías con productos...\n\n";

        $productos = [
            // HOODIES (1)
            1 => [
                ['Hoodie Oversized Negro', 'Hoodie oversize con capucha ajustable', 89900, 45],
                ['Hoodie Básico Gris', 'Hoodie clásico de algodón', 69900, 60],
                ['Hoodie Estampado Urban', 'Hoodie con estampado frontal', 79900, 35],
                ['Hoodie Zip Negro', 'Hoodie con cierre completo', 94900, 28],
                ['Hoodie Cropped Blanco', 'Hoodie corto estilo moderno', 74900, 40],
            ],
            // CAMISETAS (2)
            2 => [
                ['Camiseta Básica Blanca', 'Camiseta 100% algodón', 39900, 80],
                ['Camiseta Negra Premium', 'Camiseta de alta calidad', 44900, 75],
                ['Camiseta Estampada Urban', 'Camiseta con diseño exclusivo', 49900, 50],
                ['Camiseta Oversize Gris', 'Camiseta holgada moderna', 54900, 45],
                ['Camiseta Rayas Navy', 'Camiseta a rayas marineras', 47900, 38],
            ],
            // PANTALONES (3)
            3 => [
                ['Pantalón Cargo Beige', 'Pantalón con múltiples bolsillos', 119900, 35],
                ['Pantalón Chino Negro', 'Pantalón elegante casual', 99900, 42],
                ['Pantalón Cargo Verde', 'Pantalón militar style', 114900, 30],
                ['Pantalón Formal Gris', 'Pantalón de vestir', 129900, 25],
            ],
            // CHAQUETAS (4)
            4 => [
                ['Chaqueta Bomber Negra', 'Chaqueta bomber clásica', 179900, 20],
                ['Chaqueta Denim Azul', 'Chaqueta de mezclilla', 159900, 25],
                ['Chaqueta Cuero Sintético', 'Chaqueta estilo motero', 199900, 15],
                ['Chaqueta Windbreaker', 'Chaqueta cortavientos', 149900, 30],
            ],
            // ACCESORIOS (7)
            7 => [
                ['Gorra Snapback Negra', 'Gorra ajustable urbana', 49900, 50],
                ['Mochila Urban Gris', 'Mochila espaciosa', 129900, 20],
                ['Billetera Cuero Negro', 'Billetera minimalista', 59900, 35],
                ['Llavero Metal Premium', 'Llavero de acero', 19900, 60],
            ],
            // SHORTS (8)
            8 => [
                ['Short Deportivo Negro', 'Short para entrenamiento', 54900, 45],
                ['Short Jean Azul', 'Short denim clásico', 64900, 38],
                ['Short Cargo Beige', 'Short con bolsillos', 69900, 32],
                ['Short Playa Tropical', 'Short estampado verano', 59900, 40],
            ],
            // SUDADERAS (9)
            9 => [
                ['Sudadera Crewneck Gris', 'Sudadera cuello redondo', 84900, 35],
                ['Sudadera Oversized Negra', 'Sudadera holgada', 94900, 28],
                ['Sudadera Estampada Urban', 'Sudadera con diseño', 89900, 30],
            ],
            // JOGGERS (10)
            10 => [
                ['Jogger Negro Básico', 'Jogger cómodo algodón', 79900, 40],
                ['Jogger Gris Premium', 'Jogger alta calidad', 89900, 35],
                ['Jogger Cargo Verde', 'Jogger estilo militar', 94900, 28],
            ],
            // JEANS (11)
            11 => [
                ['Jean Skinny Negro', 'Jean ajustado moderno', 109900, 35],
                ['Jean Recto Azul', 'Jean corte clásico', 99900, 40],
                ['Jean Rasgado Gris', 'Jean con detalles', 119900, 28],
                ['Jean Mom Fit Azul', 'Jean tiro alto', 114900, 32],
            ],
            // BUZOS (12)
            12 => [
                ['Buzo Deportivo Negro', 'Buzo para entrenamiento', 149900, 25],
                ['Buzo Completo Gris', 'Conjunto buzo', 179900, 20],
                ['Buzo Tech Azul', 'Buzo tecnológico', 169900, 22],
            ],
            // POLOS (13)
            13 => [
                ['Polo Clásico Blanco', 'Polo algodón piqué', 64900, 40],
                ['Polo Negro Premium', 'Polo alta calidad', 69900, 35],
                ['Polo Rayas Navy', 'Polo marinero', 67900, 32],
            ],
            // CAMISAS (14)
            14 => [
                ['Camisa Blanca Formal', 'Camisa vestir', 89900, 30],
                ['Camisa Denim Azul', 'Camisa mezclilla', 79900, 35],
                ['Camisa Cuadros Roja', 'Camisa leñador', 74900, 28],
                ['Camisa Lino Beige', 'Camisa verano', 84900, 25],
            ],
            // SWEATERS (15)
            15 => [
                ['Sweater Cuello V Gris', 'Sweater elegante', 94900, 28],
                ['Sweater Cuello Alto Negro', 'Sweater abrigado', 99900, 25],
                ['Sweater Rayas Navy', 'Sweater marinero', 89900, 30],
            ],
            // ABRIGOS (16)
            16 => [
                ['Abrigo Largo Negro', 'Abrigo elegante', 249900, 12],
                ['Abrigo Parka Verde', 'Abrigo militar', 229900, 15],
                ['Abrigo Lana Gris', 'Abrigo invierno', 269900, 10],
            ],
            // CHALECOS (17)
            17 => [
                ['Chaleco Puffer Negro', 'Chaleco acolchado', 129900, 22],
                ['Chaleco Denim Azul', 'Chaleco mezclilla', 99900, 25],
                ['Chaleco Formal Gris', 'Chaleco vestir', 119900, 18],
            ],
            // BERMUDAS (18)
            18 => [
                ['Bermuda Cargo Beige', 'Bermuda con bolsillos', 74900, 30],
                ['Bermuda Jean Azul', 'Bermuda denim', 69900, 35],
                ['Bermuda Deportiva Negra', 'Bermuda sport', 64900, 32],
            ],
            // MEDIAS (19)
            19 => [
                ['Pack 3 Medias Negras', 'Medias algodón', 24900, 80],
                ['Pack 3 Medias Blancas', 'Medias deportivas', 24900, 75],
                ['Medias Largas Grises', 'Medias altas', 19900, 60],
            ],
            // GORROS (20)
            20 => [
                ['Gorro Beanie Negro', 'Gorro tejido', 34900, 45],
                ['Gorro Beanie Gris', 'Gorro invierno', 34900, 40],
                ['Gorro Bucket Beige', 'Gorro pescador', 39900, 35],
            ],
            // BUFANDAS (21)
            21 => [
                ['Bufanda Lana Negra', 'Bufanda abrigada', 49900, 30],
                ['Bufanda Cuadros Roja', 'Bufanda escocesa', 54900, 25],
                ['Bufanda Gris Básica', 'Bufanda clásica', 44900, 32],
            ],
            // GUANTES (22)
            22 => [
                ['Guantes Cuero Negro', 'Guantes elegantes', 59900, 25],
                ['Guantes Lana Gris', 'Guantes tejidos', 39900, 30],
                ['Guantes Táctiles Negro', 'Guantes touch', 49900, 28],
            ],
            // CINTURONES (23)
            23 => [
                ['Cinturón Cuero Negro', 'Cinturón clásico', 54900, 35],
                ['Cinturón Lona Beige', 'Cinturón casual', 39900, 40],
                ['Cinturón Reversible', 'Cinturón 2 en 1', 64900, 28],
            ],
            // CARTERAS (24)
            24 => [
                ['Cartera Cuero Negro', 'Cartera minimalista', 79900, 30],
                ['Cartera Bifold Marrón', 'Cartera clásica', 69900, 32],
                ['Cartera Slim Gris', 'Cartera delgada', 74900, 28],
            ],
            // LENTES (25)
            25 => [
                ['Lentes Sol Aviador', 'Lentes clásicos', 89900, 25],
                ['Lentes Sol Wayfarer', 'Lentes modernos', 94900, 22],
                ['Lentes Sol Redondos', 'Lentes vintage', 84900, 20],
            ],
            // RELOJES (26)
            26 => [
                ['Reloj Digital Negro', 'Reloj deportivo', 149900, 18],
                ['Reloj Análogo Plata', 'Reloj elegante', 179900, 15],
                ['Reloj Smartwatch Negro', 'Reloj inteligente', 299900, 10],
            ],
            // JOYERÍA (27)
            27 => [
                ['Cadena Acero Plata', 'Cadena minimalista', 69900, 25],
                ['Pulsera Cuero Negro', 'Pulsera casual', 39900, 30],
                ['Anillo Acero Negro', 'Anillo moderno', 44900, 28],
            ],
            // PIJAMAS (28)
            28 => [
                ['Pijama Algodón Gris', 'Pijama cómodo', 79900, 30],
                ['Pijama Franela Azul', 'Pijama abrigado', 89900, 25],
                ['Pijama Short Negro', 'Pijama verano', 64900, 32],
            ],
        ];

        $totalCreados = 0;

        foreach ($productos as $categoryId => $items) {
            $category = Category::find($categoryId);
            
            if (!$category) {
                echo "⚠️  Categoría ID {$categoryId} no encontrada\n";
                continue;
            }

            echo "📦 {$category->name}:\n";

            foreach ($items as $item) {
                [$nombre, $descripcion, $precio, $stock] = $item;

                // Verificar si ya existe
                $existe = Product::where('name', $nombre)->exists();
                
                if ($existe) {
                    echo "   ⏭️  {$nombre} (ya existe)\n";
                    continue;
                }

                Product::create([
                    'name' => $nombre,
                    'description' => $descripcion,
                    'price' => $precio,
                    'stock' => $stock,
                    'category_id' => $categoryId,
                    'image' => 'products/placeholder.jpg',
                    'sizes' => json_encode(['S', 'M', 'L', 'XL']),
                ]);

                $totalCreados++;
                echo "   ✅ {$nombre} - \${$precio} ({$stock} unidades)\n";
            }

            echo "\n";
        }

        echo "════════════════════════════════════════════════════════\n";
        echo "✅ COMPLETADO!\n";
        echo "📊 Total productos creados: {$totalCreados}\n";
        echo "🎯 Todas las categorías tienen productos\n";
        echo "════════════════════════════════════════════════════════\n\n";
    }
}

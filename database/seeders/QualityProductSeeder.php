<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class QualityProductSeeder extends Seeder
{
    public function run(): void
    {
        // Primero eliminar todos los productos existentes de forma segura
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = [
            // CAMISETAS (8 productos) - Basado en imágenes reales
            [
                'name' => 'Camiseta Gris con Estampado',
                'description' => 'Camiseta gris con estampado frontal "INNER PICK", diseño urbano moderno',
                'price' => 70000,
                'stock' => 28,
                'category_id' => 2,
                'image' => 'products/product_1.jpg',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Beige Minimalista',
                'description' => 'Camiseta beige con logo circular minimalista en el pecho',
                'price' => 68000,
                'stock' => 35,
                'category_id' => 2,
                'image' => 'products/product_2.jpg',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Verde Oliva',
                'description' => 'Camiseta oversize blanca básica, perfecta para cualquier look',
                'price' => 65000,
                'stock' => 30,
                'category_id' => 2,
                'image' => 'products/product_3.jpg',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Negra Logo Bordado',
                'description' => 'Camiseta negra con estampado de mano en el pecho, diseño exclusivo',
                'price' => 75000,
                'stock' => 25,
                'category_id' => 2,
                'image' => 'products/product_4.jpg',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Azul Marino',
                'description' => 'Camiseta negra con texto "INNER PICK", estilo streetwear',
                'price' => 63000,
                'stock' => 38,
                'category_id' => 2,
                'image' => 'products/product_5.jpg',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Blanca Estampado Espalda',
                'description' => 'Camiseta negra con logo circular en el pecho, diseño clean',
                'price' => 73000,
                'stock' => 22,
                'category_id' => 2,
                'image' => 'products/product_6.jpg',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Boxy Negra Básica',
                'description' => 'Camiseta boxy fit negra básica, 100% algodón premium',
                'price' => 65000,
                'stock' => 40,
                'category_id' => 2,
                'image' => 'products/product_7.jpg',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Oversize Blanca',
                'description' => 'Camiseta oversize blanca esencial, fit perfecto',
                'price' => 60000,
                'stock' => 45,
                'category_id' => 2,
                'image' => 'products/product_8.jpg',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],

            // PANTALONES (6 productos)
            [
                'name' => 'Pantalón Cargo Negro',
                'description' => 'Cargo negro con múltiples bolsillos, tela resistente y cómoda',
                'price' => 95000,
                'stock' => 20,
                'category_id' => 3,
                'image' => 'products/product_9.jpg',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],
            [
                'name' => 'Jogger Gris Melange',
                'description' => 'Jogger gris melange, fit relajado con puños ajustables',
                'price' => 88000,
                'stock' => 25,
                'category_id' => 3,
                'image' => 'products/product_10.jpg',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],
            [
                'name' => 'Pantalón Cargo Beige',
                'description' => 'Beige clásico, estilo militar moderno con múltiples bolsillos',
                'price' => 98000,
                'stock' => 18,
                'category_id' => 3,
                'image' => 'products/product_11.jpg',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],
            [
                'name' => 'Jogger Negro Premium',
                'description' => 'Tela premium negra, muy cómodo para uso diario',
                'price' => 92000,
                'stock' => 28,
                'category_id' => 3,
                'image' => 'products/product_12.jpg',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],
            [
                'name' => 'Pantalón Cargo Verde Militar',
                'description' => 'Verde militar, resistente y funcional con estilo urbano',
                'price' => 100000,
                'stock' => 15,
                'category_id' => 3,
                'image' => 'products/product_13.jpg',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],
            [
                'name' => 'Jogger Azul Marino',
                'description' => 'Azul marino deportivo, perfecto para cualquier ocasión',
                'price' => 85000,
                'stock' => 30,
                'category_id' => 3,
                'image' => 'products/product_14.jpg',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],

            // ACCESORIOS (11 productos adicionales para completar 25)
            [
                'name' => 'Gorra Negra Logo Bordado',
                'description' => 'Gorra negra con logo bordado, ajustable y cómoda',
                'price' => 45000,
                'stock' => 50,
                'category_id' => 4,
                'image' => 'products/product_15.jpg',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Bolso Crossbody Negro',
                'description' => 'Bolso cruzado negro, múltiples compartimentos',
                'price' => 75000,
                'stock' => 20,
                'category_id' => 4,
                'image' => 'products/product_16.jpg',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Gorra Beige Aesthetic',
                'description' => 'Diseño aesthetic minimalista, color beige neutro',
                'price' => 48000,
                'stock' => 45,
                'category_id' => 4,
                'image' => 'products/product_17.jpg',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Mochila Negra Urban',
                'description' => 'Mochila urbana negra, compartimento para laptop',
                'price' => 120000,
                'stock' => 15,
                'category_id' => 4,
                'image' => 'products/product_18.jpg',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Riñonera Negra Streetwear',
                'description' => 'Riñonera práctica negra, estilo streetwear moderno',
                'price' => 55000,
                'stock' => 35,
                'category_id' => 4,
                'image' => 'products/product_19.jpg',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Gorra Gris Minimalista',
                'description' => 'Gorra gris con diseño minimalista, perfecta para cualquier look',
                'price' => 47000,
                'stock' => 40,
                'category_id' => 4,
                'image' => 'products/product_20.jpg',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Bolso Bandolera Beige',
                'description' => 'Bolso bandolera beige, estilo casual y funcional',
                'price' => 78000,
                'stock' => 18,
                'category_id' => 4,
                'image' => 'products/product_21.jpg',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Gorra Negra Básica',
                'description' => 'Gorra negra básica esencial, ajustable',
                'price' => 42000,
                'stock' => 55,
                'category_id' => 4,
                'image' => 'products/product_22.jpg',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Mochila Gris Urban',
                'description' => 'Mochila gris urbana con múltiples compartimentos',
                'price' => 125000,
                'stock' => 12,
                'category_id' => 4,
                'image' => 'products/product_23.jpg',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Riñonera Beige Aesthetic',
                'description' => 'Riñonera beige con diseño aesthetic moderno',
                'price' => 58000,
                'stock' => 30,
                'category_id' => 4,
                'image' => 'products/product_24.jpg',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Bolso Crossbody Gris',
                'description' => 'Bolso cruzado gris, perfecto para el día a día',
                'price' => 72000,
                'stock' => 22,
                'category_id' => 4,
                'image' => 'products/product_25.jpg',
                'sizes' => json_encode(['Única']),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        echo "\n✅ 25 productos de calidad creados correctamente\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "👕 8 Camisetas\n";
        echo "👖 6 Pantalones\n";
        echo "🎒 11 Accesorios\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class QualityProductSeeder extends Seeder
{
    public function run(): void
    {
        // Primero eliminar todos los productos existentes
        Product::truncate();

        $products = [
            // HOODIES (6 productos)
            [
                'name' => 'Hoodie Oversize Negro Básico',
                'description' => 'Hoodie oversize 100% algodón, diseño minimalista con capucha ajustable',
                'price' => 120000,
                'stock' => 25,
                'category_id' => 1,
                'image' => 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Hoodie Gris Melange Premium',
                'description' => 'Sudadera con capucha, fit relajado y bolsillo canguro',
                'price' => 125000,
                'stock' => 20,
                'category_id' => 1,
                'image' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Hoodie Beige Aesthetic',
                'description' => 'Diseño aesthetic con lavado vintage, tela suave al tacto',
                'price' => 130000,
                'stock' => 18,
                'category_id' => 1,
                'image' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Hoodie Negro con Logo Bordado',
                'description' => 'Hoodie premium con logo bordado en pecho, edición limitada',
                'price' => 145000,
                'stock' => 15,
                'category_id' => 1,
                'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Hoodie Azul Marino Clásico',
                'description' => 'Hoodie clásico azul marino, perfecto para cualquier ocasión',
                'price' => 118000,
                'stock' => 30,
                'category_id' => 1,
                'image' => 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Hoodie Crema Vintage Wash',
                'description' => 'Estilo vintage con lavado especial, color crema único',
                'price' => 135000,
                'stock' => 12,
                'category_id' => 1,
                'image' => 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],

            // CAMISETAS (8 productos)
            [
                'name' => 'Camiseta Boxy Negra Básica',
                'description' => 'Camiseta boxy fit, 100% algodón premium, corte oversize',
                'price' => 65000,
                'stock' => 40,
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Oversize Blanca',
                'description' => 'Básica blanca esencial, fit oversize perfecto',
                'price' => 60000,
                'stock' => 45,
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Gris con Estampado',
                'description' => 'Estampado gráfico frontal exclusivo, diseño urbano',
                'price' => 70000,
                'stock' => 28,
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Beige Minimalista',
                'description' => 'Diseño minimalista clean, color beige neutro',
                'price' => 68000,
                'stock' => 35,
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Verde Oliva',
                'description' => 'Color oliva militar, estilo streetwear',
                'price' => 65000,
                'stock' => 30,
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Negra Logo Bordado',
                'description' => 'Logo bordado en pecho, calidad premium',
                'price' => 75000,
                'stock' => 25,
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Azul Marino',
                'description' => 'Azul marino clásico, versátil y cómoda',
                'price' => 63000,
                'stock' => 38,
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],
            [
                'name' => 'Camiseta Blanca Estampado Espalda',
                'description' => 'Estampado grande en espalda, diseño exclusivo',
                'price' => 73000,
                'stock' => 22,
                'category_id' => 2,
                'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=600&q=80',
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            ],

            // PANTALONES (6 productos)
            [
                'name' => 'Pantalón Cargo Negro',
                'description' => 'Cargo con múltiples bolsillos, tela resistente',
                'price' => 95000,
                'stock' => 20,
                'category_id' => 3,
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600&q=80',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],
            [
                'name' => 'Jogger Gris Melange',
                'description' => 'Jogger cómodo, fit relajado con puños ajustables',
                'price' => 88000,
                'stock' => 25,
                'category_id' => 3,
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600&q=80',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],
            [
                'name' => 'Pantalón Cargo Beige',
                'description' => 'Beige clásico, estilo militar moderno',
                'price' => 98000,
                'stock' => 18,
                'category_id' => 3,
                'image' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=600&q=80',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],
            [
                'name' => 'Jogger Negro Premium',
                'description' => 'Tela premium, muy cómodo para uso diario',
                'price' => 92000,
                'stock' => 28,
                'category_id' => 3,
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600&q=80',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],
            [
                'name' => 'Pantalón Cargo Verde Militar',
                'description' => 'Verde militar, resistente y funcional',
                'price' => 100000,
                'stock' => 15,
                'category_id' => 3,
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600&q=80',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],
            [
                'name' => 'Jogger Azul Marino',
                'description' => 'Azul marino deportivo, perfecto para cualquier ocasión',
                'price' => 85000,
                'stock' => 30,
                'category_id' => 3,
                'image' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=600&q=80',
                'sizes' => json_encode(['28', '30', '32', '34', '36', '38']),
            ],

            // ACCESORIOS (5 productos)
            [
                'name' => 'Gorra Negra Logo Bordado',
                'description' => 'Gorra con logo bordado, ajustable',
                'price' => 45000,
                'stock' => 50,
                'category_id' => 4,
                'image' => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=600&q=80',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Bolso Crossbody Negro',
                'description' => 'Bolso cruzado, múltiples compartimentos',
                'price' => 75000,
                'stock' => 20,
                'category_id' => 4,
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Gorra Beige Aesthetic',
                'description' => 'Diseño aesthetic minimalista, color beige',
                'price' => 48000,
                'stock' => 45,
                'category_id' => 4,
                'image' => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=600&q=80',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Mochila Negra Urban',
                'description' => 'Mochila urbana, compartimento para laptop',
                'price' => 120000,
                'stock' => 15,
                'category_id' => 4,
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80',
                'sizes' => json_encode(['Única']),
            ],
            [
                'name' => 'Riñonera Negra Streetwear',
                'description' => 'Riñonera práctica, estilo streetwear moderno',
                'price' => 55000,
                'stock' => 35,
                'category_id' => 4,
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80',
                'sizes' => json_encode(['Única']),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        echo "\n✅ 25 productos de calidad creados correctamente\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📦 6 Hoodies\n";
        echo "👕 8 Camisetas\n";
        echo "👖 6 Pantalones\n";
        echo "🎒 5 Accesorios\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
}

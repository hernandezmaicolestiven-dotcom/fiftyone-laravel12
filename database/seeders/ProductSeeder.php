<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Eliminar productos existentes
        Product::query()->delete();

        $products = [
            // HOODIES (6 productos) - Cada uno con imagen única
            ['name' => 'Hoodie Oversize Negro Básico', 'description' => 'Hoodie oversize 100% algodón, diseño minimalista con capucha ajustable', 'price' => 120000, 'stock' => 25, 'category_id' => 1, 'image' => 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Hoodie Gris Melange Premium', 'description' => 'Sudadera con capucha, fit relajado y bolsillo canguro', 'price' => 125000, 'stock' => 20, 'category_id' => 1, 'image' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Hoodie Beige Aesthetic', 'description' => 'Diseño aesthetic con lavado vintage, tela suave al tacto', 'price' => 130000, 'stock' => 18, 'category_id' => 1, 'image' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Hoodie Negro con Logo Bordado', 'description' => 'Hoodie premium con logo bordado en pecho, edición limitada', 'price' => 145000, 'stock' => 15, 'category_id' => 1, 'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Hoodie Azul Marino Clásico', 'description' => 'Hoodie clásico azul marino, perfecto para cualquier ocasión', 'price' => 118000, 'stock' => 30, 'category_id' => 1, 'image' => 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Hoodie Crema Vintage Wash', 'description' => 'Estilo vintage con lavado especial, color crema único', 'price' => 135000, 'stock' => 12, 'category_id' => 1, 'image' => 'https://images.unsplash.com/photo-1614252369475-531eba835eb1?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],

            // CAMISETAS (8 productos) - Cada una con imagen única
            ['name' => 'Camiseta Boxy Negra Básica', 'description' => 'Camiseta boxy fit, 100% algodón premium, corte oversize', 'price' => 65000, 'stock' => 40, 'category_id' => 2, 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Camiseta Oversize Blanca', 'description' => 'Básica blanca esencial, fit oversize perfecto', 'price' => 60000, 'stock' => 45, 'category_id' => 2, 'image' => 'https://images.unsplash.com/photo-1562157873-818bc0726f68?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Camiseta Gris con Estampado', 'description' => 'Estampado gráfico frontal exclusivo, diseño urbano', 'price' => 70000, 'stock' => 28, 'category_id' => 2, 'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Camiseta Beige Minimalista', 'description' => 'Diseño minimalista clean, color beige neutro', 'price' => 68000, 'stock' => 35, 'category_id' => 2, 'image' => 'https://images.unsplash.com/photo-1622445275463-afa2ab738c34?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Camiseta Verde Oliva', 'description' => 'Color oliva militar, estilo streetwear', 'price' => 65000, 'stock' => 30, 'category_id' => 2, 'image' => 'https://images.unsplash.com/photo-1618354691792-d1d42acfd860?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Camiseta Negra Logo Bordado', 'description' => 'Logo bordado en pecho, calidad premium', 'price' => 75000, 'stock' => 25, 'category_id' => 2, 'image' => 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Camiseta Azul Marino', 'description' => 'Azul marino clásico, versátil y cómoda', 'price' => 63000, 'stock' => 38, 'category_id' => 2, 'image' => 'https://images.unsplash.com/photo-1622470953794-aa9c70b0fb9d?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Camiseta Blanca Estampado Espalda', 'description' => 'Estampado grande en espalda, diseño exclusivo', 'price' => 73000, 'stock' => 22, 'category_id' => 2, 'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],

            // PANTALONES (6 productos) - Cada uno con imagen única
            ['name' => 'Pantalón Cargo Negro', 'description' => 'Cargo con múltiples bolsillos, tela resistente', 'price' => 95000, 'stock' => 20, 'category_id' => 3, 'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['28', '30', '32', '34', '36', '38'])],
            ['name' => 'Jogger Gris Melange', 'description' => 'Jogger cómodo, fit relajado con puños ajustables', 'price' => 88000, 'stock' => 25, 'category_id' => 3, 'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['28', '30', '32', '34', '36', '38'])],
            ['name' => 'Pantalón Cargo Beige', 'description' => 'Beige clásico, estilo militar moderno', 'price' => 98000, 'stock' => 18, 'category_id' => 3, 'image' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['28', '30', '32', '34', '36', '38'])],
            ['name' => 'Jogger Negro Premium', 'description' => 'Tela premium, muy cómodo para uso diario', 'price' => 92000, 'stock' => 28, 'category_id' => 3, 'image' => 'https://images.unsplash.com/photo-1555689502-c4b22d76c56f?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['28', '30', '32', '34', '36', '38'])],
            ['name' => 'Pantalón Cargo Verde Militar', 'description' => 'Verde militar, resistente y funcional', 'price' => 100000, 'stock' => 15, 'category_id' => 3, 'image' => 'https://images.unsplash.com/photo-1603252109303-2751441dd157?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['28', '30', '32', '34', '36', '38'])],
            ['name' => 'Jogger Azul Marino', 'description' => 'Azul marino deportivo, perfecto para cualquier ocasión', 'price' => 85000, 'stock' => 30, 'category_id' => 3, 'image' => 'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['28', '30', '32', '34', '36', '38'])],

            // CHAQUETAS (5 productos) - Cada una con imagen única
            ['name' => 'Chaqueta Bomber Negra', 'description' => 'Bomber jacket clásica, estilo urbano moderno', 'price' => 150000, 'stock' => 18, 'category_id' => 4, 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Chaqueta Denim Oversize', 'description' => 'Chaqueta de mezclilla oversize, lavado vintage', 'price' => 135000, 'stock' => 22, 'category_id' => 4, 'image' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Chaqueta Puffer Negra', 'description' => 'Puffer jacket acolchada, perfecta para invierno', 'price' => 180000, 'stock' => 15, 'category_id' => 4, 'image' => 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Chaqueta Coach Beige', 'description' => 'Coach jacket estilo retro, color beige', 'price' => 145000, 'stock' => 20, 'category_id' => 4, 'image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
            ['name' => 'Chaqueta Windbreaker Negra', 'description' => 'Cortavientos ligero, ideal para cualquier ocasión', 'price' => 125000, 'stock' => 25, 'category_id' => 4, 'image' => 'https://images.unsplash.com/photo-1544022613-e87ca75a784a?auto=format&fit=crop&w=800&q=80', 'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL'])],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        echo "\n✅ 25 productos de ropa creados exitosamente\n";
    }
}

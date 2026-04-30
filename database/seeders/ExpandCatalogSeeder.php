<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpandCatalogSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║         EXPANDIENDO CATÁLOGO - MÁS PRODUCTOS              ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";

        $categories = Category::all();
        $customers = User::where('role', 'customer')->get();

        $imageUrls = [
            'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=800&q=80',
            'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=800&q=80',
            'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80',
            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=800&q=80',
            'https://images.unsplash.com/photo-1622445275463-afa2ab738c34?w=800&q=80',
            'https://images.unsplash.com/photo-1542272604-787c3835535d?w=800&q=80',
            'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=800&q=80',
        ];

        $colors = ['Negro', 'Blanco', 'Gris', 'Azul', 'Verde', 'Beige', 'Café', 'Rojo', 'Morado', 'Rosa'];
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        // Nombres adicionales por categoría
        $productNames = [
            'Hoodies' => [
                'Hoodie Essential', 'Hoodie Classic', 'Hoodie Premium Plus', 'Hoodie Urban Style',
                'Hoodie Street', 'Hoodie Comfort', 'Hoodie Deluxe', 'Hoodie Pro', 'Hoodie Elite',
                'Hoodie Supreme', 'Hoodie Signature', 'Hoodie Limited', 'Hoodie Special Edition',
                'Hoodie Exclusive', 'Hoodie Designer', 'Hoodie Luxury', 'Hoodie Collection',
                'Hoodie Modern', 'Hoodie Trendy', 'Hoodie Fashion'
            ],
            'Camisetas' => [
                'Camiseta Essential', 'Camiseta Classic', 'Camiseta Premium', 'Camiseta Urban',
                'Camiseta Street', 'Camiseta Comfort', 'Camiseta Deluxe', 'Camiseta Pro',
                'Camiseta Elite', 'Camiseta Supreme', 'Camiseta Signature', 'Camiseta Limited',
                'Camiseta Special', 'Camiseta Exclusive', 'Camiseta Designer', 'Camiseta Luxury',
                'Camiseta Collection', 'Camiseta Modern', 'Camiseta Trendy', 'Camiseta Fashion'
            ],
            'Pantalones' => [
                'Pantalón Essential', 'Pantalón Classic', 'Pantalón Premium', 'Pantalón Urban',
                'Pantalón Street', 'Pantalón Comfort', 'Pantalón Deluxe', 'Pantalón Pro',
                'Pantalón Elite', 'Pantalón Supreme', 'Pantalón Signature', 'Pantalón Limited',
                'Pantalón Special', 'Pantalón Exclusive', 'Pantalón Designer', 'Pantalón Luxury',
                'Pantalón Collection', 'Pantalón Modern', 'Pantalón Trendy', 'Pantalón Fashion'
            ],
            'Accesorios' => [
                'Gorra Essential', 'Bolso Classic', 'Gorra Premium', 'Mochila Urban',
                'Gorra Street', 'Bolso Comfort', 'Riñonera Deluxe', 'Gorra Pro',
                'Mochila Elite', 'Bolso Supreme', 'Gorra Signature', 'Mochila Limited',
                'Gorra Special', 'Bolso Exclusive', 'Gorra Designer', 'Mochila Luxury',
                'Bolso Collection', 'Gorra Modern', 'Riñonera Trendy', 'Mochila Fashion'
            ],
            'Chaquetas' => [
                'Chaqueta Essential', 'Chaqueta Classic', 'Chaqueta Premium', 'Chaqueta Urban',
                'Chaqueta Street', 'Chaqueta Comfort', 'Chaqueta Deluxe', 'Chaqueta Pro',
                'Chaqueta Elite', 'Chaqueta Supreme', 'Chaqueta Signature', 'Chaqueta Limited',
                'Chaqueta Special', 'Chaqueta Exclusive', 'Chaqueta Designer', 'Chaqueta Luxury',
                'Chaqueta Collection', 'Chaqueta Modern', 'Chaqueta Trendy', 'Chaqueta Fashion'
            ],
            'Shorts' => [
                'Short Essential', 'Short Classic', 'Short Premium', 'Short Urban',
                'Short Street', 'Short Comfort', 'Short Deluxe', 'Short Pro',
                'Short Elite', 'Short Supreme', 'Short Signature', 'Short Limited',
                'Short Special', 'Short Exclusive', 'Short Designer', 'Short Luxury',
                'Short Collection', 'Short Modern', 'Short Trendy', 'Short Fashion'
            ],
        ];

        $reviewComments = [
            'Excelente calidad, muy cómodo',
            'Me encantó, lo recomiendo',
            'Superó mis expectativas',
            'Muy buena relación calidad-precio',
            'Producto de calidad premium',
            'Perfecto para el día a día',
            'Material excelente',
            'Diseño moderno y cómodo',
            'Muy satisfecho con la compra',
            'Vale cada peso',
        ];

        $totalProducts = 0;
        $totalReviews = 0;

        foreach ($categories as $category) {
            $categoryNames = $productNames[$category->name] ?? [];
            
            echo "📦 Agregando productos a: {$category->name}\n";
            
            foreach ($categoryNames as $baseName) {
                foreach ($colors as $color) {
                    $name = "{$baseName} {$color}";
                    
                    // Verificar si ya existe
                    if (Product::where('name', $name)->exists()) {
                        continue;
                    }
                    
                    $product = Product::create([
                        'name' => $name,
                        'description' => "Producto premium de la colección FiftyOne. Diseño oversize moderno y cómodo. Material de alta calidad.",
                        'price' => rand(80000, 250000),
                        'stock' => rand(10, 50),
                        'category_id' => $category->id,
                        'image' => $imageUrls[array_rand($imageUrls)],
                        'sizes' => $sizes,
                        'colors' => [$color],
                    ]);
                    
                    $totalProducts++;
                    
                    // Agregar 2-4 reseñas por producto
                    $numReviews = rand(2, 4);
                    $usedCustomers = [];
                    
                    for ($i = 0; $i < $numReviews && $i < $customers->count(); $i++) {
                        $availableCustomers = $customers->filter(function($c) use ($usedCustomers) {
                            return !in_array($c->id, $usedCustomers);
                        });
                        
                        if ($availableCustomers->isEmpty()) break;
                        
                        $customer = $availableCustomers->random();
                        $usedCustomers[] = $customer->id;
                        
                        Review::create([
                            'product_id' => $product->id,
                            'user_id' => $customer->id,
                            'rating' => rand(4, 5),
                            'comment' => $reviewComments[array_rand($reviewComments)],
                        ]);
                        
                        $totalReviews++;
                    }
                }
            }
            
            echo "   ✅ Productos en {$category->name}: " . Product::where('category_id', $category->id)->count() . "\n";
        }

        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║                  ✅ EXPANSIÓN COMPLETADA                   ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";
        echo "📊 RESUMEN:\n";
        echo "   • Productos agregados: {$totalProducts}\n";
        echo "   • Reseñas agregadas: {$totalReviews}\n";
        echo "   • Total productos: " . Product::count() . "\n";
        echo "   • Total reseñas: " . Review::count() . "\n";
        echo "\n";
    }
}

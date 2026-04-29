<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FullCatalogSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║         LLENANDO CATÁLOGO COMPLETO - FIFTYONE             ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";

        // 1. Crear categorías
        echo "📁 Creando categorías...\n";
        $categories = [
            ['name' => 'Hoodies', 'slug' => 'hoodies', 'description' => 'Hoodies oversize premium'],
            ['name' => 'Camisetas', 'slug' => 'camisetas', 'description' => 'Camisetas boxy fit'],
            ['name' => 'Pantalones', 'slug' => 'pantalones', 'description' => 'Pantalones cargo y joggers'],
            ['name' => 'Accesorios', 'slug' => 'accesorios', 'description' => 'Gorras, bolsos y más'],
            ['name' => 'Chaquetas', 'slug' => 'chaquetas', 'description' => 'Chaquetas y abrigos'],
            ['name' => 'Shorts', 'slug' => 'shorts', 'description' => 'Shorts deportivos'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
        echo "   ✅ " . count($categories) . " categorías creadas\n\n";

        // 2. Crear usuarios clientes
        echo "👥 Creando clientes...\n";
        $customers = [];
        $customerNames = [
            'Carlos Rodríguez', 'María García', 'Juan Pérez', 'Ana Martínez',
            'Luis González', 'Laura Sánchez', 'Pedro López', 'Carmen Díaz',
            'Miguel Torres', 'Isabel Ramírez', 'Jorge Flores', 'Patricia Morales',
            'Roberto Castro', 'Elena Ortiz', 'Francisco Ruiz', 'Sofía Mendoza',
            'Diego Herrera', 'Valentina Silva', 'Andrés Vargas', 'Camila Rojas'
        ];

        foreach ($customerNames as $index => $name) {
            $email = 'cliente' . ($index + 1) . '@test.com';
            $customers[] = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('Cliente123!'),
                    'role' => 'customer',
                    'phone' => '300' . str_pad($index + 1, 7, '0', STR_PAD_LEFT),
                ]
            );
        }
        echo "   ✅ " . count($customers) . " clientes creados\n\n";

        // 3. Crear productos (100 productos variados)
        echo "👕 Creando productos...\n";
        $products = [];
        
        $productTemplates = [
            // Hoodies
            ['cat' => 'Hoodies', 'names' => ['Hoodie Oversize Negro', 'Hoodie Gris Melange', 'Hoodie Blanco Premium', 'Hoodie Azul Navy', 'Hoodie Verde Militar']],
            ['cat' => 'Hoodies', 'names' => ['Hoodie Beige Aesthetic', 'Hoodie Morado Urban', 'Hoodie Rojo Vintage', 'Hoodie Café Chocolate', 'Hoodie Naranja Sunset']],
            
            // Camisetas
            ['cat' => 'Camisetas', 'names' => ['Camiseta Boxy Negra', 'Camiseta Oversized Blanca', 'Camiseta Gris Básica', 'Camiseta Azul Cielo', 'Camiseta Verde Oliva']],
            ['cat' => 'Camisetas', 'names' => ['Camiseta Beige Sand', 'Camiseta Rosa Pastel', 'Camiseta Amarilla Neon', 'Camiseta Morada Dark', 'Camiseta Roja Fire']],
            
            // Pantalones
            ['cat' => 'Pantalones', 'names' => ['Pantalón Cargo Negro', 'Jogger Gris Premium', 'Cargo Beige Tactical', 'Jogger Negro Slim', 'Cargo Verde Militar']],
            ['cat' => 'Pantalones', 'names' => ['Pantalón Wide Leg Negro', 'Cargo Azul Navy', 'Jogger Café Brown', 'Pantalón Cargo Gris', 'Wide Leg Beige']],
            
            // Accesorios
            ['cat' => 'Accesorios', 'names' => ['Gorra Dad Hat Negra', 'Bolso Crossbody Negro', 'Gorra Trucker Blanca', 'Mochila Urban Gris', 'Gorra Snapback Azul']],
            ['cat' => 'Accesorios', 'names' => ['Riñonera Tactical Negra', 'Gorra Bucket Beige', 'Bolso Tote Canvas', 'Gorra 5 Panel Verde', 'Mochila Mini Negra']],
            
            // Chaquetas
            ['cat' => 'Chaquetas', 'names' => ['Chaqueta Bomber Negra', 'Parka Oversize Verde', 'Chaqueta Denim Azul', 'Bomber Beige Vintage', 'Chaqueta Coach Negra']],
            ['cat' => 'Chaquetas', 'names' => ['Parka Larga Gris', 'Chaqueta Puffer Negra', 'Bomber Reversible', 'Chaqueta Sherpa Café', 'Windbreaker Azul']],
            
            // Shorts
            ['cat' => 'Shorts', 'names' => ['Short Cargo Negro', 'Short Deportivo Gris', 'Cargo Short Beige', 'Short Jogger Negro', 'Cargo Short Verde']],
        ];

        $colors = [
            ['Negro', '#000000'], ['Blanco', '#FFFFFF'], ['Gris', '#808080'],
            ['Azul', '#0066CC'], ['Verde', '#2D5016'], ['Beige', '#D4C5B9'],
            ['Café', '#6F4E37'], ['Rojo', '#CC0000'], ['Morado', '#6A0DAD']
        ];

        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        
        $imageUrls = [
            'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=800&q=80',
            'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=800&q=80',
            'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80',
            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=800&q=80',
            'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=800&q=80',
            'https://images.unsplash.com/photo-1622445275463-afa2ab738c34?w=800&q=80',
            'https://images.unsplash.com/photo-1542272604-787c3835535d?w=800&q=80',
            'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=800&q=80',
        ];

        foreach ($productTemplates as $template) {
            $category = Category::where('name', $template['cat'])->first();
            
            foreach ($template['names'] as $name) {
                $price = rand(80000, 250000);
                $stock = rand(5, 50);
                $discount = rand(0, 30);
                
                $selectedColors = array_slice($colors, 0, rand(2, 4));
                $colorNames = array_column($selectedColors, 0);
                
                $product = Product::create([
                    'name' => $name,
                    'description' => "Producto premium de la colección FiftyOne. Diseño oversize moderno y cómodo. Material de alta calidad. Perfecto para el día a día.",
                    'price' => $price,
                    'stock' => $stock,
                    'category_id' => $category->id,
                    'image' => $imageUrls[array_rand($imageUrls)],
                    'sizes' => $sizes,
                    'colors' => $colorNames,
                ]);
                
                $products[] = $product;
            }
        }
        
        echo "   ✅ " . count($products) . " productos creados\n\n";

        // 4. Crear reseñas (300+ reseñas)
        echo "⭐ Creando reseñas...\n";
        $reviewComments = [
            'Excelente calidad, muy cómodo y el diseño es increíble',
            'Me encantó, la tela es suave y el fit perfecto',
            'Superó mis expectativas, definitivamente volveré a comprar',
            'Muy buena relación calidad-precio, lo recomiendo',
            'El producto llegó rápido y en perfectas condiciones',
            'Calidad premium, se nota la diferencia',
            'Perfecto para el día a día, muy versátil',
            'El mejor hoodie que he comprado, vale cada peso',
            'Talla perfecta, justo como esperaba',
            'Material de excelente calidad, muy satisfecho',
            'Diseño moderno y cómodo, me encanta',
            'Buena compra, cumple con lo prometido',
            'Producto original y de calidad',
            'Muy contento con la compra, llegó antes de lo esperado',
            'Excelente atención y producto de calidad',
        ];

        $reviewCount = 0;
        foreach ($products as $product) {
            $numReviews = rand(2, 6);
            $usedCustomers = [];
            
            for ($i = 0; $i < $numReviews; $i++) {
                // Seleccionar un cliente que no haya reseñado este producto
                $availableCustomers = array_filter($customers, function($c) use ($usedCustomers) {
                    return !in_array($c->id, $usedCustomers);
                });
                
                if (empty($availableCustomers)) break;
                
                $customer = $availableCustomers[array_rand($availableCustomers)];
                $usedCustomers[] = $customer->id;
                
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $customer->id,
                    'rating' => rand(4, 5),
                    'comment' => $reviewComments[array_rand($reviewComments)],
                ]);
                $reviewCount++;
            }
        }
        echo "   ✅ {$reviewCount} reseñas creadas\n\n";

        // 5. Crear pedidos (50 pedidos)
        echo "📦 Creando pedidos...\n";
        $statuses = ['pending', 'confirmed', 'shipped', 'delivered'];
        $cities = ['Bogotá', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena', 'Bucaramanga'];
        
        for ($i = 0; $i < 50; $i++) {
            $customer = $customers[array_rand($customers)];
            $status = $statuses[array_rand($statuses)];
            
            $order = Order::create([
                'user_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone,
                'shipping_address' => 'Calle ' . rand(1, 100) . ' # ' . rand(1, 50) . '-' . rand(1, 99),
                'city' => $cities[array_rand($cities)],
                'notes' => rand(0, 1) ? 'Entregar en horario de oficina' : null,
                'status' => $status,
                'payment_method' => ['nequi', 'daviplata', 'pse', 'tarjeta'][array_rand(['nequi', 'daviplata', 'pse', 'tarjeta'])],
                'payment_status' => $status === 'delivered' ? 'approved' : 'pending',
                'total' => 0, // Se calculará después
            ]);

            // Agregar items al pedido
            $numItems = rand(1, 4);
            $total = 0;
            
            for ($j = 0; $j < $numItems; $j++) {
                $product = $products[array_rand($products)];
                $quantity = rand(1, 2);
                $price = $product->price;
                $subtotal = $price * $quantity;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
                
                $total += $subtotal;
            }
            
            $order->update(['total' => $total]);
        }
        echo "   ✅ 50 pedidos creados\n\n";

        // 6. Crear cupones
        echo "🎟️  Creando cupones...\n";
        $coupons = [
            ['code' => 'BIENVENIDO', 'type' => 'percent', 'value' => 15, 'uses_left' => 100],
            ['code' => 'PRIMERACOMPRA', 'type' => 'percent', 'value' => 20, 'uses_left' => 50],
            ['code' => 'VERANO2026', 'type' => 'percent', 'value' => 25, 'uses_left' => 200],
            ['code' => 'FIFTYONE10', 'type' => 'percent', 'value' => 10, 'uses_left' => null],
            ['code' => 'DESCUENTO50K', 'type' => 'fixed', 'value' => 50000, 'uses_left' => 30],
        ];

        foreach ($coupons as $coupon) {
            Coupon::firstOrCreate(
                ['code' => $coupon['code']],
                array_merge($coupon, ['active' => true, 'expires_at' => now()->addMonths(3)])
            );
        }
        echo "   ✅ " . count($coupons) . " cupones creados\n\n";

        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║                  ✅ CATÁLOGO COMPLETO                      ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";
        echo "📊 RESUMEN:\n";
        echo "   • Categorías: " . Category::count() . "\n";
        echo "   • Productos: " . Product::count() . "\n";
        echo "   • Clientes: " . User::where('role', 'customer')->count() . "\n";
        echo "   • Reseñas: " . Review::count() . "\n";
        echo "   • Pedidos: " . Order::count() . "\n";
        echo "   • Cupones: " . Coupon::count() . "\n";
        echo "\n";
        echo "🚀 La tienda está lista con contenido completo!\n\n";
    }
}

<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $count = (int) ($this->command->ask('¿Cuántos registros quieres generar? (100 o 1000)', 100));

        $this->command->info("Generando {$count} registros de prueba...");

        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->error('No hay productos. Corre CategorySeeder y ProductSeeder primero.');
            return;
        }

        $statuses   = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
        $firstNames = ['Santiago', 'Valentina', 'Sebastián', 'Camila', 'Andrés', 'Laura', 'Felipe', 'María', 'Juan', 'Daniela', 'Carlos', 'Sofía', 'Miguel', 'Isabella', 'David'];
        $lastNames  = ['García', 'Rodríguez', 'Martínez', 'López', 'González', 'Hernández', 'Pérez', 'Sánchez', 'Ramírez', 'Torres', 'Flores', 'Rivera', 'Gómez', 'Díaz', 'Morales'];
        $comments   = [
            'Excelente calidad, muy cómodo.',
            'El fit oversize es perfecto, lo recomiendo.',
            'Llegó rápido y bien empacado.',
            'La tela es muy suave y duradera.',
            'Me encantó el color, igual a las fotos.',
            'Muy buena relación calidad-precio.',
            'El diseño es increíble, ya quiero más.',
            'Perfecto para el día a día.',
            'La costura es muy bien hecha.',
            'Lo compré para regalo y quedaron felices.',
        ];

        $bar = $this->command->getOutput()->createProgressBar($count);
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName  = $lastNames[array_rand($lastNames)];
            $name      = "{$firstName} {$lastName}";
            $email     = strtolower($firstName) . rand(100, 9999) . '@test.com';

            // Crear usuario cliente
            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make('password123'),
                'role'     => 'customer',
                'phone'    => '3' . rand(100000000, 999999999),
            ]);

            // Crear 1-3 pedidos por usuario
            $numOrders = rand(1, 3);
            for ($o = 0; $o < $numOrders; $o++) {
                $status    = $statuses[array_rand($statuses)];
                $orderProds = $products->random(rand(1, 3));
                $total     = 0;
                $items     = [];

                foreach ($orderProds as $prod) {
                    $qty      = rand(1, 3);
                    $subtotal = $prod->price * $qty;
                    $total   += $subtotal;
                    $items[]  = [
                        'product_id'   => $prod->id,
                        'product_name' => $prod->name,
                        'price'        => $prod->price,
                        'quantity'     => $qty,
                        'subtotal'     => $subtotal,
                    ];
                }

                $order = Order::create([
                    'user_id'        => $user->id,
                    'customer_name'  => $name,
                    'customer_email' => $email,
                    'customer_phone' => $user->phone,
                    'total'          => $total,
                    'status'         => $status,
                    'created_at'     => now()->subDays(rand(0, 180)),
                ]);

                foreach ($items as $item) {
                    OrderItem::create(array_merge($item, ['order_id' => $order->id]));
                }

                // Reseña si el pedido fue entregado
                if ($status === 'delivered' && rand(0, 1)) {
                    $prod = $orderProds->first();
                    Review::updateOrCreate(
                        ['product_id' => $prod->id, 'user_id' => $user->id],
                        [
                            'rating'  => rand(3, 5),
                            'comment' => $comments[array_rand($comments)],
                        ]
                    );
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info("✅ {$count} usuarios creados con pedidos y reseñas.");
        $this->command->info('Total usuarios: ' . User::where('role', 'customer')->count());
        $this->command->info('Total pedidos:  ' . Order::count());
        $this->command->info('Total reseñas:  ' . Review::count());
    }
}

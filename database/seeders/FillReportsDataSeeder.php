<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FillReportsDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║         LLENANDO DATOS PARA REPORTES - FIFTYONE           ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";

        $customers = User::where('role', 'customer')->get();
        $products = Product::all();
        
        if ($customers->isEmpty() || $products->isEmpty()) {
            echo "❌ No hay clientes o productos. Ejecuta FullCatalogSeeder primero.\n";
            return;
        }

        $statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
        $paymentMethods = ['nequi', 'daviplata', 'pse', 'tarjeta', 'bancolombia', 'efecty'];
        $cities = ['Bogotá', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena', 'Bucaramanga', 'Pereira', 'Manizales'];

        echo "📦 Creando pedidos históricos (últimos 6 meses)...\n";

        $ordersCreated = 0;
        $totalRevenue = 0;

        // Crear pedidos para los últimos 6 meses
        for ($month = 5; $month >= 0; $month--) {
            $ordersInMonth = rand(30, 60); // 30-60 pedidos por mes
            
            for ($i = 0; $i < $ordersInMonth; $i++) {
                $customer = $customers->random();
                $status = $statuses[array_rand($statuses)];
                
                // Fecha aleatoria dentro del mes
                $date = Carbon::now()->subMonths($month)->subDays(rand(0, 28));
                
                // Más pedidos entregados en meses pasados
                if ($month > 1 && rand(1, 100) > 30) {
                    $status = 'delivered';
                }
                
                $order = Order::create([
                    'user_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'customer_email' => $customer->email,
                    'customer_phone' => $customer->phone ?? '300' . rand(1000000, 9999999),
                    'shipping_address' => 'Calle ' . rand(1, 100) . ' # ' . rand(1, 50) . '-' . rand(1, 99),
                    'city' => $cities[array_rand($cities)],
                    'notes' => rand(0, 1) ? 'Entregar en horario de oficina' : null,
                    'status' => $status,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'payment_status' => $status === 'delivered' ? 'approved' : ($status === 'cancelled' ? 'cancelled' : 'pending'),
                    'total' => 0,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Agregar items al pedido (1-5 productos)
                $numItems = rand(1, 5);
                $orderTotal = 0;
                
                for ($j = 0; $j < $numItems; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    $price = $product->price;
                    $subtotal = $price * $quantity;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                    
                    $orderTotal += $subtotal;
                }
                
                $order->update(['total' => $orderTotal]);
                
                if ($status === 'delivered') {
                    $totalRevenue += $orderTotal;
                }
                
                $ordersCreated++;
            }
            
            $monthName = Carbon::now()->subMonths($month)->format('F Y');
            echo "   ✅ {$ordersInMonth} pedidos creados para {$monthName}\n";
        }

        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║                  ✅ DATOS DE REPORTES LISTOS               ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";
        echo "📊 RESUMEN:\n";
        echo "   • Pedidos históricos creados: {$ordersCreated}\n";
        echo "   • Total pedidos en sistema: " . Order::count() . "\n";
        echo "   • Ingresos totales (entregados): $" . number_format($totalRevenue, 0, ',', '.') . "\n";
        echo "   • Pedidos entregados: " . Order::where('status', 'delivered')->count() . "\n";
        echo "   • Pedidos pendientes: " . Order::where('status', 'pending')->count() . "\n";
        echo "\n";
        echo "📈 Los reportes ahora mostrarán datos completos de los últimos 6 meses\n\n";
    }
}

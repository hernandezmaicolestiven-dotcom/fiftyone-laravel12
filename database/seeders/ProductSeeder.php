<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(20)->create();

        // Asignar imágenes a los primeros 6 productos
        $images = [
            1 => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=600&q=80',
            2 => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80',
            3 => 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=600&q=80',
            4 => 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?w=600&q=80',
            5 => 'https://images.unsplash.com/photo-1598289431512-b97b0917affc?w=600&q=80',
            6 => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600&q=80',
        ];

        foreach ($images as $id => $url) {
            Product::where('id', $id)->update(['image' => $url]);
        }
    }
}

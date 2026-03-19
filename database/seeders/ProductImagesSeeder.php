<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            13 => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=600&q=80', // gorra
            14 => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80', // camiseta
            15 => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600&q=80',    // shorts/pantalon
            16 => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=600&q=80', // gorra
            17 => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80',    // zapatillas
            18 => 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=600&q=80', // camiseta oversize
        ];

        foreach ($images as $id => $url) {
            Product::where('id', $id)->update(['image' => $url]);
        }
    }
}

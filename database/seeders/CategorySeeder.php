<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hoodies', 'description' => 'Hoodies oversize y sudaderas con capucha'],
            ['name' => 'Camisetas', 'description' => 'Camisetas boxy y oversize'],
            ['name' => 'Pantalones', 'description' => 'Pantalones cargo y joggers'],
            ['name' => 'Chaquetas', 'description' => 'Chaquetas bomber, denim y puffer'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
            ]);
        }
    }
}

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
            ['name' => 'Ropa Deportiva', 'description' => 'Indumentaria para entrenar'],
            ['name' => 'Calzado', 'description' => 'Zapatillas y calzado deportivo'],
            ['name' => 'Accesorios', 'description' => 'Complementos para el deporte'],
            ['name' => 'Suplementos', 'description' => 'Nutrición deportiva'],
            ['name' => 'Equipamiento', 'description' => 'Equipos y máquinas de ejercicio'],
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

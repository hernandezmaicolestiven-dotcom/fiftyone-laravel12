<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Camiseta Dry-Fit', 'Shorts Running', 'Zapatillas Trail', 'Mochila Sport',
                'Guantes Gym', 'Proteína Whey', 'Banda Elástica', 'Botella Térmica',
                'Leggings Compresión', 'Gorra Deportiva',
            ]).' '.fake()->bothify('##??'),
            'description' => fake()->paragraph(),
            'stock' => rand(1, 100),
            'price' => rand(1000, 50000) / 100,
            'category_id' => Category::inRandomOrder()->first()?->id,
        ];
    }
}

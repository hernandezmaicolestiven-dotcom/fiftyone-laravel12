<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin principal
        User::updateOrCreate(
            ['email' => 'admin@fiftyone.co'],
            [
                'name' => 'Administrador Principal',
                'email' => 'admin@fiftyone.co',
                'password' => Hash::make('FiftyOne2026!'),
                'role' => 'admin',
            ]
        );

        // Colaborador de ejemplo
        User::updateOrCreate(
            ['email' => 'colaborador@fiftyone.co'],
            [
                'name' => 'Colaborador FiftyOne',
                'email' => 'colaborador@fiftyone.co',
                'password' => Hash::make('Colaborador2026!'),
                'role' => 'colaborador',
            ]
        );

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}

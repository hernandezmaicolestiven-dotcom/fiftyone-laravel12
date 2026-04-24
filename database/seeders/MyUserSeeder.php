<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MyUserSeeder extends Seeder
{
    public function run(): void
    {
        // Tu usuario personal
        User::updateOrCreate(
            ['email' => 'hernandezmaicolestiven@gmail.com'],
            [
                'name' => 'Hernandez Maicol',
                'email' => 'hernandezmaicolestiven@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'customer',
            ]
        );

        echo "✅ Usuario creado correctamente\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📧 Email: hernandezmaicolestiven@gmail.com\n";
        echo "🔑 Password: 12345678\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
}

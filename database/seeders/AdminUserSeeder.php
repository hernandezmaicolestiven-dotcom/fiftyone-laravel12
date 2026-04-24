<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin principal - mantener email original
        User::updateOrCreate(
            ['email' => 'admin@fiftyone.com'],
            [
                'name' => 'Admin FiftyOne',
                'email' => 'admin@fiftyone.com',
                'password' => Hash::make('FiftyOne2026!'),
                'role' => 'admin',
            ]
        );

        echo "✅ Credenciales actualizadas correctamente\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "📧 Admin: admin@fiftyone.com\n";
        echo "🔑 Nueva Password: FiftyOne2026!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
}

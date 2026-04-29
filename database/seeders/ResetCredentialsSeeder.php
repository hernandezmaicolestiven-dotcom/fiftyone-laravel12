<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ResetCredentialsSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════╗\n";
        echo "║         RESETEO DE CREDENCIALES - FIFTYONE                ║\n";
        echo "╚════════════════════════════════════════════════════════════╝\n";
        echo "\n";

        // 1. Admin Principal
        $admin = User::updateOrCreate(
            ['email' => 'admin@fiftyone.com'],
            [
                'name' => 'Administrador Principal',
                'email' => 'admin@fiftyone.com',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
                'phone' => '3001234567',
            ]
        );

        // 2. Cliente de Prueba
        $customer = User::updateOrCreate(
            ['email' => 'cliente@test.com'],
            [
                'name' => 'Cliente de Prueba',
                'email' => 'cliente@test.com',
                'password' => Hash::make('Cliente123!'),
                'role' => 'customer',
                'phone' => '3009876543',
            ]
        );

        // 3. Colaborador de Prueba
        $colaborador = User::updateOrCreate(
            ['email' => 'colaborador@fiftyone.com'],
            [
                'name' => 'Colaborador FiftyOne',
                'email' => 'colaborador@fiftyone.com',
                'password' => Hash::make('Colab123!'),
                'role' => 'colaborador',
                'phone' => '3005555555',
            ]
        );

        echo "✅ CREDENCIALES ACTUALIZADAS EXITOSAMENTE\n";
        echo "\n";
        echo "┌────────────────────────────────────────────────────────────┐\n";
        echo "│ 👨‍💼 PANEL DE ADMINISTRACIÓN                                │\n";
        echo "├────────────────────────────────────────────────────────────┤\n";
        echo "│ URL:      http://localhost:8000/admin/login               │\n";
        echo "│ Email:    admin@fiftyone.com                              │\n";
        echo "│ Password: Admin123!                                       │\n";
        echo "└────────────────────────────────────────────────────────────┘\n";
        echo "\n";
        echo "┌────────────────────────────────────────────────────────────┐\n";
        echo "│ 👤 CUENTA DE CLIENTE                                       │\n";
        echo "├────────────────────────────────────────────────────────────┤\n";
        echo "│ URL:      http://localhost:8000/login                     │\n";
        echo "│ Email:    cliente@test.com                                │\n";
        echo "│ Password: Cliente123!                                     │\n";
        echo "└────────────────────────────────────────────────────────────┘\n";
        echo "\n";
        echo "┌────────────────────────────────────────────────────────────┐\n";
        echo "│ 🤝 CUENTA DE COLABORADOR                                   │\n";
        echo "├────────────────────────────────────────────────────────────┤\n";
        echo "│ URL:      http://localhost:8000/admin/login               │\n";
        echo "│ Email:    colaborador@fiftyone.com                        │\n";
        echo "│ Password: Colab123!                                       │\n";
        echo "└────────────────────────────────────────────────────────────┘\n";
        echo "\n";
        echo "💡 IMPORTANTE:\n";
        echo "   - Las contraseñas son CASE SENSITIVE (mayúsculas importan)\n";
        echo "   - Incluye el signo de exclamación (!) al final\n";
        echo "   - Si olvidas la contraseña, ejecuta: php artisan db:seed --class=ResetCredentialsSeeder\n";
        echo "\n";
        echo "🔐 SEGURIDAD:\n";
        echo "   - Cambia estas contraseñas en producción\n";
        echo "   - Usa contraseñas fuertes y únicas\n";
        echo "   - Nunca compartas las credenciales de admin\n";
        echo "\n";
    }
}

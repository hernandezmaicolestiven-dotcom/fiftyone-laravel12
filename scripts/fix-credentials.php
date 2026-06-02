<?php

/**
 * Script para arreglar todas las credenciales de usuarios
 * Resetea las contraseñas a valores conocidos
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  🔧 ARREGLANDO CREDENCIALES DE USUARIOS                       ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// 1. Admin Principal
echo "👨‍💼 Actualizando Admin...\n";
$admin = User::updateOrCreate(
    ['email' => 'admin@fiftyone.com'],
    [
        'name' => 'Administrador FiftyOne',
        'email' => 'admin@fiftyone.com',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'phone' => '3001234567',
    ]
);
echo "   ✅ Admin actualizado\n\n";

// 2. Maicol Hernandez (el usuario que no funciona)
echo "👤 Actualizando cuenta de Maicol...\n";
$maicol = User::updateOrCreate(
    ['email' => 'hernandezmaicolestiven@gmail.com'],
    [
        'name' => 'Maicol Hernandez',
        'email' => 'hernandezmaicolestiven@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'customer',
        'phone' => '3009876543',
    ]
);
echo "   ✅ Cuenta de Maicol actualizada\n\n";

// 3. Cliente de Prueba
echo "👤 Actualizando Cliente de Prueba...\n";
$customer = User::updateOrCreate(
    ['email' => 'cliente@test.com'],
    [
        'name' => 'Cliente de Prueba',
        'email' => 'cliente@test.com',
        'password' => Hash::make('cliente123'),
        'role' => 'customer',
        'phone' => '3005555555',
    ]
);
echo "   ✅ Cliente de prueba actualizado\n\n";

// 4. Colaborador
echo "🤝 Actualizando Colaborador...\n";
$colaborador = User::updateOrCreate(
    ['email' => 'colaborador@fiftyone.com'],
    [
        'name' => 'Colaborador FiftyOne',
        'email' => 'colaborador@fiftyone.com',
        'password' => Hash::make('colab123'),
        'role' => 'colaborador',
        'phone' => '3007777777',
    ]
);
echo "   ✅ Colaborador actualizado\n\n";

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  ✅ CREDENCIALES ARREGLADAS EXITOSAMENTE                      ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "┌────────────────────────────────────────────────────────────────┐\n";
echo "│ 👨‍💼 PANEL DE ADMINISTRACIÓN                                    │\n";
echo "├────────────────────────────────────────────────────────────────┤\n";
echo "│ URL:      http://localhost:8000/admin/login                   │\n";
echo "│ Email:    admin@fiftyone.com                                  │\n";
echo "│ Password: admin123                                            │\n";
echo "└────────────────────────────────────────────────────────────────┘\n";
echo "\n";

echo "┌────────────────────────────────────────────────────────────────┐\n";
echo "│ 👤 CUENTA DE MAICOL HERNANDEZ                                  │\n";
echo "├────────────────────────────────────────────────────────────────┤\n";
echo "│ URL:      http://localhost:8000/login                         │\n";
echo "│ Email:    hernandezmaicolestiven@gmail.com                    │\n";
echo "│ Password: 12345678                                            │\n";
echo "└────────────────────────────────────────────────────────────────┘\n";
echo "\n";

echo "┌────────────────────────────────────────────────────────────────┐\n";
echo "│ 👤 CUENTA DE CLIENTE DE PRUEBA                                 │\n";
echo "├────────────────────────────────────────────────────────────────┤\n";
echo "│ URL:      http://localhost:8000/login                         │\n";
echo "│ Email:    cliente@test.com                                    │\n";
echo "│ Password: cliente123                                          │\n";
echo "└────────────────────────────────────────────────────────────────┘\n";
echo "\n";

echo "┌────────────────────────────────────────────────────────────────┐\n";
echo "│ 🤝 CUENTA DE COLABORADOR                                       │\n";
echo "├────────────────────────────────────────────────────────────────┤\n";
echo "│ URL:      http://localhost:8000/admin/login                   │\n";
echo "│ Email:    colaborador@fiftyone.com                            │\n";
echo "│ Password: colab123                                            │\n";
echo "└────────────────────────────────────────────────────────────────┘\n";
echo "\n";

// Verificar que las contraseñas funcionan
echo "🔍 Verificando contraseñas...\n";
$tests = [
    ['email' => 'admin@fiftyone.com', 'password' => 'admin123'],
    ['email' => 'hernandezmaicolestiven@gmail.com', 'password' => '12345678'],
    ['email' => 'cliente@test.com', 'password' => 'cliente123'],
    ['email' => 'colaborador@fiftyone.com', 'password' => 'colab123'],
];

$allOk = true;
foreach ($tests as $test) {
    $user = User::where('email', $test['email'])->first();
    if ($user && Hash::check($test['password'], $user->password)) {
        echo "   ✅ {$test['email']} - OK\n";
    } else {
        echo "   ❌ {$test['email']} - ERROR\n";
        $allOk = false;
    }
}

echo "\n";
if ($allOk) {
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║  🎉 TODAS LAS CREDENCIALES FUNCIONAN CORRECTAMENTE            ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
} else {
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║  ⚠️  ALGUNAS CREDENCIALES TIENEN PROBLEMAS                    ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
}

echo "\n";
echo "💡 Ahora puedes iniciar sesión con cualquiera de estas cuentas\n";
echo "\n";

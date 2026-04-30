<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "\n=== ACTUALIZANDO CREDENCIALES DE ADMIN ===\n\n";

// Buscar o crear admin
$admin = User::where('email', 'admin@fiftyone.com')->first();

if (!$admin) {
    echo "❌ Admin no encontrado, creando...\n";
    $admin = User::create([
        'name' => 'Administrador Principal',
        'email' => 'admin@fiftyone.com',
        'password' => Hash::make('Admin123!'),
        'role' => 'admin',
        'phone' => '3001234567',
    ]);
    echo "✅ Admin creado\n";
} else {
    echo "✅ Admin encontrado\n";
    echo "   ID: {$admin->id}\n";
    echo "   Email: {$admin->email}\n";
    echo "   Role: {$admin->role}\n";
    
    // Actualizar contraseña
    $admin->password = Hash::make('Admin123!');
    $admin->role = 'admin';
    $admin->save();
    echo "✅ Contraseña actualizada\n";
}

// Verificar que la contraseña funciona
$testPassword = 'Admin123!';
if (Hash::check($testPassword, $admin->password)) {
    echo "\n✅ VERIFICACIÓN EXITOSA: La contraseña funciona correctamente\n";
} else {
    echo "\n❌ ERROR: La contraseña no coincide\n";
}

echo "\n=== CREDENCIALES ===\n";
echo "Email: admin@fiftyone.com\n";
echo "Password: Admin123!\n";
echo "\n";

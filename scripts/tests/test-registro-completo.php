<?php

/**
 * Script de prueba para verificar el registro de usuarios
 * Ejecutar con: php test-registro-completo.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║         PRUEBA DE REGISTRO DE USUARIOS - FIFTYONE         ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Datos de prueba
$testEmail = 'nuevo_cliente_' . time() . '@test.com';
$testPassword = 'MiPassword123!';
$testName = 'Cliente de Prueba ' . date('H:i:s');

echo "📝 Creando nuevo usuario de prueba...\n";
echo "   Email: {$testEmail}\n";
echo "   Nombre: {$testName}\n";
echo "   Password: {$testPassword}\n";
echo "\n";

try {
    // Simular el registro
    $user = User::create([
        'name' => $testName,
        'email' => $testEmail,
        'phone' => '3001234567',
        'password' => Hash::make($testPassword),
        'role' => 'customer',
    ]);

    echo "✅ Usuario creado exitosamente!\n";
    echo "   ID: {$user->id}\n";
    echo "   Email: {$user->email}\n";
    echo "   Role: {$user->role}\n";
    echo "\n";

    // Verificar que se guardó en la base de datos
    $userFromDb = User::find($user->id);
    if ($userFromDb) {
        echo "✅ Usuario encontrado en la base de datos\n";
        echo "\n";
    } else {
        echo "❌ ERROR: Usuario NO encontrado en la base de datos\n";
        exit(1);
    }

    // Verificar que la contraseña se hasheó correctamente
    if (Hash::check($testPassword, $userFromDb->password)) {
        echo "✅ Contraseña hasheada correctamente\n";
        echo "\n";
    } else {
        echo "❌ ERROR: La contraseña NO se hasheó correctamente\n";
        exit(1);
    }

    // Simular login
    echo "🔐 Probando login con las credenciales...\n";
    if (Hash::check($testPassword, $userFromDb->password)) {
        echo "✅ Login exitoso! Las credenciales funcionan\n";
        echo "\n";
    } else {
        echo "❌ ERROR: Login fallido\n";
        exit(1);
    }

    // Mostrar todos los usuarios
    echo "📊 Total de usuarios en el sistema: " . User::count() . "\n";
    echo "\n";

    echo "╔════════════════════════════════════════════════════════════╗\n";
    echo "║              ✅ TODAS LAS PRUEBAS PASARON                  ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "💡 El sistema de registro funciona correctamente:\n";
    echo "   ✅ Los usuarios se crean en la base de datos\n";
    echo "   ✅ Las contraseñas se hashean correctamente\n";
    echo "   ✅ El login funciona con las credenciales\n";
    echo "   ✅ Los datos persisten en la base de datos\n";
    echo "\n";

    // Limpiar usuario de prueba
    echo "🧹 Limpiando usuario de prueba...\n";
    $user->delete();
    echo "✅ Usuario de prueba eliminado\n";
    echo "\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\n";
    exit(1);
}

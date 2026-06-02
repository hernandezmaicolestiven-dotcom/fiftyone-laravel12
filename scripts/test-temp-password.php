<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "\n🔐 Probando sistema de contraseña temporal...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$testEmail = 'hernandezmaicolestiven@gmail.com';
$user = App\Models\User::where('email', $testEmail)->first();

if (!$user) {
    echo "❌ Usuario no encontrado\n";
    exit(1);
}

echo "✅ Usuario: {$user->name} ({$user->email})\n\n";

// Generar contraseña temporal
$tempPassword = 'Temp' . rand(1000, 9999) . '!';

echo "🔑 Contraseña temporal generada: {$tempPassword}\n\n";

// Actualizar contraseña del usuario
$user->update([
    'password' => Hash::make($tempPassword)
]);

echo "✅ Contraseña actualizada en la base de datos\n\n";

// Intentar enviar el email
try {
    Mail::to($user->email)->send(new \App\Mail\TemporaryPasswordMail($user, $tempPassword));
    
    echo "✅ Email enviado correctamente\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📧 Revisa tu bandeja de entrada en:\n";
    echo "   {$user->email}\n\n";
    echo "🔐 Credenciales para iniciar sesión:\n";
    echo "   Email: {$user->email}\n";
    echo "   Contraseña temporal: {$tempPassword}\n\n";
    echo "📋 Pasos siguientes:\n";
    echo "1. Ve a: http://localhost:8000/login\n";
    echo "2. Ingresa tu email y la contraseña temporal\n";
    echo "3. Ve a 'Mi Cuenta' y cambia tu contraseña\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error al enviar el email: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n\n";
    
    echo "⚠️  Pero la contraseña fue actualizada en la BD\n";
    echo "   Puedes iniciar sesión con: {$tempPassword}\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

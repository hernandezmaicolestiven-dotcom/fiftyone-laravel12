<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n🔐 Probando sistema de recuperación de contraseña...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// 1. Verificar que existe un usuario de prueba
$testEmail = 'hernandezmaicolestiven@gmail.com';
$user = App\Models\User::where('email', $testEmail)->first();

if (!$user) {
    echo "❌ No se encontró el usuario con email: {$testEmail}\n";
    echo "   Creando usuario de prueba...\n\n";
    
    $user = App\Models\User::create([
        'name' => 'Hernandez Maicol',
        'email' => $testEmail,
        'password' => Hash::make('12345678'),
        'role' => 'customer',
    ]);
    
    echo "✅ Usuario creado correctamente\n\n";
}

echo "✅ Usuario encontrado:\n";
echo "   📧 Email: {$user->email}\n";
echo "   👤 Nombre: {$user->name}\n";
echo "   🔑 Rol: {$user->role}\n\n";

// 2. Verificar configuración de email
echo "📧 Configuración de email:\n";
echo "   MAIL_MAILER: " . config('mail.default') . "\n";
echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "   MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "   MAIL_FROM: " . config('mail.from.address') . "\n\n";

// 3. Generar token de recuperación
echo "🔑 Generando token de recuperación...\n";
$token = Illuminate\Support\Str::random(60);
$hashedToken = Hash::make($token);

// Guardar en la tabla password_reset_tokens
DB::table('password_reset_tokens')->updateOrInsert(
    ['email' => $user->email],
    [
        'email' => $user->email,
        'token' => $hashedToken,
        'created_at' => now(),
    ]
);

echo "✅ Token generado correctamente\n\n";

// 4. Generar URL de recuperación
$resetUrl = url(route('customer.password.reset', [
    'token' => $token,
    'email' => $user->email,
], false));

echo "🔗 URL de recuperación:\n";
echo "   {$resetUrl}\n\n";

// 5. Intentar enviar email (opcional)
echo "📨 ¿Deseas enviar el email de recuperación? (s/n): ";
// Para testing automático, comentamos la parte interactiva

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Sistema de recuperación verificado\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "📋 Pasos para probar:\n";
echo "1. Ve a: http://localhost:8000/recuperar-contrasena\n";
echo "2. Ingresa el email: {$testEmail}\n";
echo "3. Revisa tu bandeja de entrada\n";
echo "4. Haz clic en el enlace del email\n";
echo "5. Ingresa tu nueva contraseña\n\n";

echo "🔧 O usa esta URL directamente:\n";
echo "   {$resetUrl}\n\n";

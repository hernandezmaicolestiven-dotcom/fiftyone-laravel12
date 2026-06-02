<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "\n📧 Enviando email de recuperación de contraseña...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$testEmail = 'hernandezmaicolestiven@gmail.com';
$user = App\Models\User::where('email', $testEmail)->first();

if (!$user) {
    echo "❌ Usuario no encontrado\n";
    exit(1);
}

echo "✅ Usuario: {$user->name} ({$user->email})\n\n";

try {
    // Usar el sistema de Laravel para enviar el email
    $status = Illuminate\Support\Facades\Password::sendResetLink(
        ['email' => $user->email]
    );

    if ($status === Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
        echo "✅ Email enviado correctamente\n\n";
        echo "📬 Revisa tu bandeja de entrada en:\n";
        echo "   {$user->email}\n\n";
        echo "📋 Pasos siguientes:\n";
        echo "1. Abre tu email\n";
        echo "2. Busca el correo de 'FiftyOne'\n";
        echo "3. Haz clic en 'Restablecer Contraseña'\n";
        echo "4. Ingresa tu nueva contraseña\n\n";
    } else {
        echo "❌ Error al enviar el email\n";
        echo "   Status: {$status}\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n\n";
    
    // Mostrar más detalles del error
    if ($e->getPrevious()) {
        echo "   Error anterior: " . $e->getPrevious()->getMessage() . "\n";
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

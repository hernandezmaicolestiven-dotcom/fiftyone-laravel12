<?php

/**
 * Script de prueba para el sistema de recuperación de contraseña
 * Verifica que el envío de emails funcione correctamente
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  🔐 PRUEBA DE RECUPERACIÓN DE CONTRASEÑA                      ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// 1. Verificar configuración de email
echo "📧 Verificando configuración de email...\n";
echo "   MAIL_MAILER: " . config('mail.default') . "\n";
echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "   MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "   MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
echo "   MAIL_FROM: " . config('mail.from.address') . "\n";
echo "\n";

// 2. Buscar un usuario de prueba
echo "👤 Buscando usuario de prueba...\n";
$user = \App\Models\User::where('email', 'hernandezmaicolestiven@gmail.com')->first();

if (!$user) {
    echo "   ❌ Usuario no encontrado\n";
    echo "   Creando usuario de prueba...\n";
    
    $user = \App\Models\User::create([
        'name' => 'Maicol Hernandez',
        'email' => 'hernandezmaicolestiven@gmail.com',
        'password' => bcrypt('12345678'),
        'role' => 'customer',
    ]);
    
    echo "   ✅ Usuario creado\n";
} else {
    echo "   ✅ Usuario encontrado: {$user->name}\n";
}
echo "\n";

// 3. Generar contraseña temporal
echo "🔑 Generando contraseña temporal...\n";
$tempPassword = 'Temp' . rand(1000, 9999) . '!';
echo "   Contraseña temporal: {$tempPassword}\n";
echo "\n";

// 4. Actualizar contraseña en BD
echo "💾 Actualizando contraseña en base de datos...\n";
$user->update([
    'password' => \Hash::make($tempPassword)
]);
echo "   ✅ Contraseña actualizada en BD\n";
echo "\n";

// 5. Intentar enviar email
echo "📨 Intentando enviar email...\n";
try {
    \Mail::to($user->email)->send(new \App\Mail\TemporaryPasswordMail($user, $tempPassword));
    
    echo "   ✅ Email enviado exitosamente!\n";
    echo "\n";
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║  ✅ PRUEBA EXITOSA                                            ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "📋 Resumen:\n";
    echo "   • Email: {$user->email}\n";
    echo "   • Contraseña temporal: {$tempPassword}\n";
    echo "   • Estado: Email enviado correctamente\n";
    echo "\n";
    echo "🔍 Próximos pasos:\n";
    echo "   1. Revisa tu bandeja de entrada\n";
    echo "   2. Busca el email con asunto: '🔐 Tu contraseña temporal - FiftyOne'\n";
    echo "   3. Usa la contraseña temporal para iniciar sesión\n";
    echo "   4. Cambia tu contraseña desde 'Mi Cuenta'\n";
    echo "\n";
    
} catch (\Exception $e) {
    echo "   ❌ Error al enviar email\n";
    echo "\n";
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║  ❌ ERROR EN EL ENVÍO                                         ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "🔴 Mensaje de error:\n";
    echo "   " . $e->getMessage() . "\n";
    echo "\n";
    
    // Detectar tipo de error
    if (strpos($e->getMessage(), '535') !== false || strpos($e->getMessage(), 'Username and Password not accepted') !== false) {
        echo "🔍 Diagnóstico:\n";
        echo "   Este error indica que la contraseña de Gmail no es válida.\n";
        echo "\n";
        echo "✅ SOLUCIÓN:\n";
        echo "   1. Ve a: https://myaccount.google.com/apppasswords\n";
        echo "   2. Genera una nueva contraseña de aplicación\n";
        echo "   3. Actualiza MAIL_PASSWORD en el archivo .env\n";
        echo "   4. Ejecuta: php artisan config:clear\n";
        echo "   5. Vuelve a ejecutar este script\n";
        echo "\n";
        echo "📖 Guía completa: ACTUALIZAR_CONTRASEÑA_GMAIL.md\n";
        echo "\n";
        
    } elseif (strpos($e->getMessage(), 'Connection') !== false) {
        echo "🔍 Diagnóstico:\n";
        echo "   Error de conexión con el servidor SMTP.\n";
        echo "\n";
        echo "✅ SOLUCIÓN:\n";
        echo "   1. Verifica tu conexión a internet\n";
        echo "   2. Verifica que MAIL_HOST y MAIL_PORT sean correctos\n";
        echo "   3. Intenta cambiar MAIL_PORT de 587 a 465\n";
        echo "   4. Intenta cambiar MAIL_ENCRYPTION de tls a ssl\n";
        echo "\n";
        
    } else {
        echo "🔍 Diagnóstico:\n";
        echo "   Error desconocido. Revisa los logs para más detalles.\n";
        echo "\n";
        echo "✅ SOLUCIÓN:\n";
        echo "   1. Revisa storage/logs/laravel.log\n";
        echo "   2. Verifica la configuración en .env\n";
        echo "   3. Ejecuta: php artisan config:clear\n";
        echo "\n";
    }
    
    echo "💡 ALTERNATIVA: Usar Mailtrap para desarrollo\n";
    echo "   1. Crea cuenta en https://mailtrap.io\n";
    echo "   2. Copia las credenciales SMTP\n";
    echo "   3. Actualiza .env con las credenciales de Mailtrap\n";
    echo "   4. Ejecuta: php artisan config:clear\n";
    echo "\n";
}

// 6. Verificar que la contraseña se actualizó en BD
echo "🔍 Verificando contraseña en base de datos...\n";
$user->refresh();
$passwordWorks = \Hash::check($tempPassword, $user->password);

if ($passwordWorks) {
    echo "   ✅ La contraseña temporal funciona correctamente\n";
    echo "   ✅ Puedes iniciar sesión con: {$tempPassword}\n";
} else {
    echo "   ❌ Error: La contraseña no coincide\n";
}

echo "\n";
echo "════════════════════════════════════════════════════════════════\n";
echo "\n";

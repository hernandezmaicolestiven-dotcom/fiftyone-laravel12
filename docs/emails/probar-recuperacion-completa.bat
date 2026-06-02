@echo off
chcp 65001 >nul
color 0E
cls

echo.
echo ╔═══════════════════════════════════════════════════════════════╗
echo ║        PROBAR RECUPERACIÓN DE CONTRASEÑA COMPLETA             ║
echo ╚═══════════════════════════════════════════════════════════════╝
echo.
echo 🎯 Este script te guiará paso a paso para probar el sistema
echo.
echo ════════════════════════════════════════════════════════════════
echo.

:menu
echo.
echo 📋 PASOS DEL FLUJO:
echo.
echo    1. Enviar email de recuperación (cliente@test.com)
echo    2. Abrir Mailtrap para ver el email
echo    3. Abrir página de recuperación en el navegador
echo    4. Ver estado actual de la contraseña
echo    0. Salir
echo.
echo ════════════════════════════════════════════════════════════════
echo.

set /p "opcion=Selecciona una opción (0-4): "

if "%opcion%"=="1" goto send_email
if "%opcion%"=="2" goto open_mailtrap
if "%opcion%"=="3" goto open_recovery
if "%opcion%"=="4" goto check_password
if "%opcion%"=="0" goto end
goto menu

:send_email
cls
echo.
echo ════════════════════════════════════════════════════════════════
echo 📧 ENVIANDO EMAIL DE RECUPERACIÓN...
echo ════════════════════════════════════════════════════════════════
echo.
echo 🔄 Generando enlace para: cliente@test.com
echo.

php artisan tinker --execute="use Illuminate\Support\Facades\Password; $status = Password::sendResetLink(['email' => 'cliente@test.com']); echo 'Status: ' . $status;"

echo.
echo ✅ Email enviado a Mailtrap
echo.
echo 📧 SIGUIENTE PASO:
echo    1. Ve a: https://mailtrap.io
echo    2. Clic en "Sandboxes"
echo    3. Clic en tu sandbox
echo    4. Pestaña "Messages"
echo    5. Verás el email con el enlace
echo    6. Copia el enlace completo
echo    7. Pégalo en tu navegador
echo.
echo ════════════════════════════════════════════════════════════════
echo.
pause
goto menu

:open_mailtrap
cls
echo.
echo ════════════════════════════════════════════════════════════════
echo 🌐 ABRIENDO MAILTRAP...
echo ════════════════════════════════════════════════════════════════
echo.

start https://mailtrap.io/inboxes

echo ✅ Mailtrap abierto en tu navegador
echo.
echo 📧 PASOS EN MAILTRAP:
echo    1. Inicia sesión si no lo has hecho
echo    2. Clic en "Sandboxes" (menú lateral)
echo    3. Clic en tu sandbox
echo    4. Pestaña "Messages"
echo    5. Verás el email de recuperación
echo    6. Abre el email
echo    7. Copia el enlace "Restablecer contraseña"
echo    8. Pégalo en tu navegador
echo.
echo ════════════════════════════════════════════════════════════════
echo.
pause
goto menu

:open_recovery
cls
echo.
echo ════════════════════════════════════════════════════════════════
echo 🌐 ABRIENDO PÁGINA DE RECUPERACIÓN...
echo ════════════════════════════════════════════════════════════════
echo.

start http://localhost:8000/recuperar-contrasena

echo ✅ Página abierta en tu navegador
echo.
echo 📝 PASOS EN LA PÁGINA:
echo    1. Ingresa: cliente@test.com
echo    2. Clic en "Enviar enlace de recuperación"
echo    3. Ve a Mailtrap (opción 2 del menú)
echo    4. Copia el enlace del email
echo    5. Pégalo en tu navegador
echo    6. Ingresa nueva contraseña
echo    7. Confirma la contraseña
echo    8. Clic en "Restablecer contraseña"
echo    9. Inicia sesión con la nueva contraseña
echo.
echo ════════════════════════════════════════════════════════════════
echo.
pause
goto menu

:check_password
cls
echo.
echo ════════════════════════════════════════════════════════════════
echo 🔍 VERIFICANDO ESTADO DE LA CONTRASEÑA...
echo ════════════════════════════════════════════════════════════════
echo.

php artisan tinker --execute="$user = App\Models\User::where('email', 'cliente@test.com')->first(); echo 'Email: ' . $user->email . PHP_EOL; echo 'Nombre: ' . $user->name . PHP_EOL; echo 'Última actualización: ' . $user->updated_at . PHP_EOL;"

echo.
echo ════════════════════════════════════════════════════════════════
echo.
echo 💡 NOTA: Si cambiaste la contraseña, la fecha de actualización
echo    será reciente.
echo.
echo ════════════════════════════════════════════════════════════════
echo.
pause
goto menu

:end
cls
echo.
echo ════════════════════════════════════════════════════════════════
echo.
echo ✅ RESUMEN DEL FLUJO COMPLETO:
echo.
echo    1. Cliente va a "Olvidé mi contraseña"
echo    2. Ingresa su email
echo    3. Sistema envía email (capturado en Mailtrap)
echo    4. Cliente abre el enlace del email
echo    5. Ingresa nueva contraseña
echo    6. Sistema guarda la nueva contraseña
echo    7. Redirige al login
echo    8. Cliente inicia sesión con nueva contraseña
echo.
echo ✅ TODO ESTO YA FUNCIONA
echo.
echo 📧 URLs importantes:
echo    Recuperación: http://localhost:8000/recuperar-contrasena
echo    Mailtrap: https://mailtrap.io
echo    Login: http://localhost:8000/login
echo.
echo ════════════════════════════════════════════════════════════════
echo.
exit /b

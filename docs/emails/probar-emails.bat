@echo off
chcp 65001 >nul
color 0B
cls

echo.
echo ╔═══════════════════════════════════════════════════════════════╗
echo ║              PROBAR SISTEMA DE EMAILS                         ║
echo ╚═══════════════════════════════════════════════════════════════╝
echo.

:menu
echo.
echo 📧 OPCIONES DE PRUEBA:
echo.
echo    1. Enviar email de prueba simple
echo    2. Probar recuperación de contraseña (cliente)
echo    3. Probar recuperación de contraseña (admin)
echo    4. Ver configuración actual
echo    5. Abrir Mailtrap en el navegador
echo    6. Ver logs de Laravel
echo    0. Salir
echo.
echo ════════════════════════════════════════════════════════════════
echo.

set /p "opcion=Selecciona una opción (0-6): "

if "%opcion%"=="1" goto test_simple
if "%opcion%"=="2" goto test_customer
if "%opcion%"=="3" goto test_admin
if "%opcion%"=="4" goto show_config
if "%opcion%"=="5" goto open_mailtrap
if "%opcion%"=="6" goto show_logs
if "%opcion%"=="0" goto end
goto menu

:test_simple
cls
echo.
echo ════════════════════════════════════════════════════════════════
echo 📧 ENVIANDO EMAIL DE PRUEBA SIMPLE...
echo ════════════════════════════════════════════════════════════════
echo.

php artisan tinker --execute="Mail::raw('Este es un email de prueba desde FiftyOne', function($m) { $m->to('test@test.com')->subject('Prueba de Email'); }); echo 'Email enviado';"

echo.
echo ✅ Email enviado
echo.
echo 📧 Ve a Mailtrap para verlo:
echo    https://mailtrap.io/inboxes
echo.
pause
goto menu

:test_customer
cls
echo.
echo ════════════════════════════════════════════════════════════════
echo 📧 PROBANDO RECUPERACIÓN DE CONTRASEÑA (CLIENTE)...
echo ════════════════════════════════════════════════════════════════
echo.
echo 🔄 Generando enlace de recuperación para: cliente@test.com
echo.

php artisan tinker --execute="use Illuminate\Support\Facades\Password; $status = Password::sendResetLink(['email' => 'cliente@test.com']); echo $status;"

echo.
echo ✅ Proceso completado
echo.
echo 📧 Ve a Mailtrap para ver el email:
echo    https://mailtrap.io/inboxes
echo.
echo 🌐 O prueba desde el navegador:
echo    http://localhost:8000/recuperar-contrasena
echo.
pause
goto menu

:test_admin
cls
echo.
echo ════════════════════════════════════════════════════════════════
echo 📧 PROBANDO RECUPERACIÓN DE CONTRASEÑA (ADMIN)...
echo ════════════════════════════════════════════════════════════════
echo.
echo 🔄 Generando enlace de recuperación para: admin@fiftyone.com
echo.

php artisan tinker --execute="use Illuminate\Support\Facades\Password; $status = Password::sendResetLink(['email' => 'admin@fiftyone.com']); echo $status;"

echo.
echo ✅ Proceso completado
echo.
echo 📧 Ve a Mailtrap para ver el email:
echo    https://mailtrap.io/inboxes
echo.
echo 🌐 O prueba desde el navegador:
echo    http://localhost:8000/admin/forgot-password
echo.
pause
goto menu

:show_config
cls
echo.
echo ════════════════════════════════════════════════════════════════
echo 📋 CONFIGURACIÓN ACTUAL DE EMAILS
echo ════════════════════════════════════════════════════════════════
echo.

findstr /C:"MAIL_" .env

echo.
echo ════════════════════════════════════════════════════════════════
echo.
pause
goto menu

:open_mailtrap
cls
echo.
echo ════════════════════════════════════════════════════════════════
echo 🌐 ABRIENDO MAILTRAP EN EL NAVEGADOR...
echo ════════════════════════════════════════════════════════════════
echo.

start https://mailtrap.io/inboxes

echo ✅ Mailtrap abierto en tu navegador
echo.
pause
goto menu

:show_logs
cls
echo.
echo ════════════════════════════════════════════════════════════════
echo 📄 ÚLTIMAS 30 LÍNEAS DEL LOG DE LARAVEL
echo ════════════════════════════════════════════════════════════════
echo.

if exist storage\logs\laravel.log (
    powershell -Command "Get-Content storage\logs\laravel.log -Tail 30"
) else (
    echo ⚠️  No se encontró el archivo de log
)

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
echo ✅ ¡Gracias por usar el sistema de pruebas de emails!
echo.
echo 📧 Recuerda revisar Mailtrap para ver los emails:
echo    https://mailtrap.io/inboxes
echo.
echo ════════════════════════════════════════════════════════════════
echo.
exit /b

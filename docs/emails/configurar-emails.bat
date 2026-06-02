@echo off
chcp 65001 >nul
color 0B
title 📧 Configurar Sistema de Emails

echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║         CONFIGURAR EMAILS - RECUPERACIÓN CONTRASEÑA        ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo 📧 Este script te ayudará a configurar el envío de emails
echo    para la recuperación de contraseñas.
echo.
echo ════════════════════════════════════════════════════════════
echo 🎯 OPCIONES DISPONIBLES:
echo ════════════════════════════════════════════════════════════
echo.
echo [1] MAILTRAP (Recomendado para desarrollo)
echo     - Gratis y fácil
echo     - Captura emails sin enviarlos
echo     - Perfecto para pruebas
echo.
echo [2] GMAIL (Para emails reales)
echo     - Envía emails reales
echo     - Requiere configuración
echo.
echo [3] Ver guía completa
echo.
echo [4] Probar configuración actual
echo.
echo [5] Salir
echo.
set /p opcion="Elige una opción (1-5): "

if "%opcion%"=="1" goto mailtrap
if "%opcion%"=="2" goto gmail
if "%opcion%"=="3" goto guia
if "%opcion%"=="4" goto probar
if "%opcion%"=="5" exit /b 0

:mailtrap
echo.
echo ════════════════════════════════════════════════════════════
echo 📧 CONFIGURAR MAILTRAP
echo ════════════════════════════════════════════════════════════
echo.
echo 📝 PASOS:
echo.
echo 1. Ve a: https://mailtrap.io/register/signup
echo 2. Regístrate gratis
echo 3. Ve a "Email Testing" ^> "Inboxes"
echo 4. Clic en "My Inbox"
echo 5. Clic en "Show Credentials"
echo 6. Copia Username y Password
echo.
echo ════════════════════════════════════════════════════════════
echo.
set /p username="Ingresa tu MAIL_USERNAME de Mailtrap: "
set /p password="Ingresa tu MAIL_PASSWORD de Mailtrap: "
echo.
echo 💾 Guardando configuración...
echo.

REM Aquí normalmente actualizaríamos el .env, pero por seguridad
REM es mejor que el usuario lo haga manualmente

echo ✅ Configuración lista!
echo.
echo 📝 Ahora actualiza tu archivo .env con estos valores:
echo.
echo MAIL_MAILER=smtp
echo MAIL_HOST=sandbox.smtp.mailtrap.io
echo MAIL_PORT=2525
echo MAIL_USERNAME=%username%
echo MAIL_PASSWORD=%password%
echo MAIL_ENCRYPTION=tls
echo MAIL_FROM_ADDRESS="noreply@fiftyone.com"
echo MAIL_FROM_NAME="FiftyOne"
echo.
echo 🔧 Luego ejecuta:
echo    php artisan config:clear
echo.
pause
goto menu

:gmail
echo.
echo ════════════════════════════════════════════════════════════
echo 📧 CONFIGURAR GMAIL
echo ════════════════════════════════════════════════════════════
echo.
echo 📝 PASOS:
echo.
echo 1. Ve a: https://myaccount.google.com/security
echo 2. Activa "Verificación en 2 pasos"
echo 3. Ve a "Contraseñas de aplicaciones"
echo 4. Selecciona "Correo" y "Windows"
echo 5. Copia la contraseña generada (16 caracteres)
echo.
echo ════════════════════════════════════════════════════════════
echo.
set /p email="Ingresa tu email de Gmail: "
set /p apppass="Ingresa tu contraseña de aplicación: "
echo.
echo ✅ Configuración lista!
echo.
echo 📝 Ahora actualiza tu archivo .env con estos valores:
echo.
echo MAIL_MAILER=smtp
echo MAIL_HOST=smtp.gmail.com
echo MAIL_PORT=587
echo MAIL_USERNAME=%email%
echo MAIL_PASSWORD=%apppass%
echo MAIL_ENCRYPTION=tls
echo MAIL_FROM_ADDRESS="%email%"
echo MAIL_FROM_NAME="FiftyOne"
echo.
echo 🔧 Luego ejecuta:
echo    php artisan config:clear
echo.
pause
goto menu

:guia
echo.
echo 📖 Abriendo guía completa...
start CONFIGURAR_EMAILS.md
goto menu

:probar
echo.
echo ════════════════════════════════════════════════════════════
echo 🧪 PROBANDO CONFIGURACIÓN
echo ════════════════════════════════════════════════════════════
echo.
echo 🔧 Limpiando caché...
php artisan config:clear
php artisan cache:clear
echo.
echo 📧 Configuración actual:
php artisan tinker --execute="echo 'Mailer: ' . config('mail.default') . PHP_EOL; echo 'Host: ' . config('mail.mailers.smtp.host') . PHP_EOL; echo 'Port: ' . config('mail.mailers.smtp.port') . PHP_EOL;"
echo.
echo ════════════════════════════════════════════════════════════
echo.
echo 🧪 Para probar el envío:
echo    1. Ve a: http://localhost:8000/recuperar-contrasena
echo    2. Ingresa: cliente@test.com
echo    3. Revisa Mailtrap o tu email
echo.
pause
goto menu

:menu
echo.
echo ¿Deseas hacer algo más? (S/N)
set /p continuar=""
if /i "%continuar%"=="S" goto inicio
exit /b 0

:inicio
cls
goto :eof

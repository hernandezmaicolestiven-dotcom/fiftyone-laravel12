@echo off
chcp 65001 >nul
color 0A
cls

echo.
echo ╔═══════════════════════════════════════════════════════════════╗
echo ║         CONFIGURAR CREDENCIALES DE MAILTRAP                   ║
echo ╚═══════════════════════════════════════════════════════════════╝
echo.
echo 📧 Este script te ayudará a configurar Mailtrap en 2 minutos
echo.
echo ════════════════════════════════════════════════════════════════
echo.

REM Verificar si ya hay credenciales
findstr /C:"MAIL_USERNAME=" .env | findstr /V /C:"MAIL_USERNAME=$" >nul
if %errorlevel% equ 0 (
    echo ⚠️  ADVERTENCIA: Ya tienes credenciales configuradas en .env
    echo.
    set /p "overwrite=¿Quieres reemplazarlas? (S/N): "
    if /i not "!overwrite!"=="S" (
        echo.
        echo ❌ Configuración cancelada
        pause
        exit /b
    )
)

echo.
echo 📝 PASO 1: Obtener credenciales de Mailtrap
echo.
echo    1. Ve a: https://mailtrap.io
echo    2. Inicia sesión
echo    3. Ve a: Email Testing → Inboxes
echo    4. Clic en "My Inbox"
echo    5. Pestaña "SMTP Settings"
echo    6. Selecciona "Laravel 9+"
echo    7. Copia Username y Password
echo.
echo ════════════════════════════════════════════════════════════════
echo.

REM Solicitar Username
:ask_username
set /p "username=📧 Pega tu MAIL_USERNAME de Mailtrap: "
if "%username%"=="" (
    echo ❌ El username no puede estar vacío
    goto ask_username
)

echo.

REM Solicitar Password
:ask_password
set /p "password=🔑 Pega tu MAIL_PASSWORD de Mailtrap: "
if "%password%"=="" (
    echo ❌ El password no puede estar vacío
    goto ask_password
)

echo.
echo ════════════════════════════════════════════════════════════════
echo.
echo 📋 RESUMEN DE CONFIGURACIÓN:
echo.
echo    Host:     sandbox.smtp.mailtrap.io
echo    Port:     2525
echo    Username: %username%
echo    Password: %password%
echo    From:     noreply@fiftyone.com
echo.
set /p "confirm=¿Es correcto? (S/N): "
if /i not "%confirm%"=="S" (
    echo.
    echo ❌ Configuración cancelada
    pause
    exit /b
)

echo.
echo ════════════════════════════════════════════════════════════════
echo.
echo 🔧 Configurando .env...
echo.

REM Crear backup del .env
copy .env .env.backup.%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2% >nul 2>&1
echo ✅ Backup creado: .env.backup

REM Actualizar credenciales en .env usando PowerShell
powershell -Command "(Get-Content .env) -replace '^MAIL_USERNAME=.*', 'MAIL_USERNAME=%username%' | Set-Content .env.tmp"
move /y .env.tmp .env >nul

powershell -Command "(Get-Content .env) -replace '^MAIL_PASSWORD=.*', 'MAIL_PASSWORD=%password%' | Set-Content .env.tmp"
move /y .env.tmp .env >nul

echo ✅ Credenciales actualizadas en .env
echo.

REM Limpiar caché de Laravel
echo 🧹 Limpiando caché de Laravel...
php artisan config:clear >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ Caché limpiada
) else (
    echo ⚠️  No se pudo limpiar la caché automáticamente
    echo    Ejecuta manualmente: php artisan config:clear
)

echo.
echo ════════════════════════════════════════════════════════════════
echo.
echo ✅ ¡CONFIGURACIÓN COMPLETADA!
echo.
echo 🧪 AHORA PUEDES PROBAR:
echo.
echo    OPCIÓN 1 - Desde el navegador:
echo    1. Ve a: http://localhost:8000/recuperar-contrasena
echo    2. Ingresa: cliente@test.com
echo    3. Clic en "Enviar enlace"
echo    4. Ve a Mailtrap y verás el email
echo.
echo    OPCIÓN 2 - Con script:
echo    1. Ejecuta: probar-emails.bat
echo    2. Selecciona opción 1
echo    3. Ve a Mailtrap
echo.
echo ════════════════════════════════════════════════════════════════
echo.
echo 📧 URLs de recuperación:
echo.
echo    Clientes: http://localhost:8000/recuperar-contrasena
echo    Admin:    http://localhost:8000/admin/forgot-password
echo.
echo ════════════════════════════════════════════════════════════════
echo.
echo 💡 RECUERDA:
echo.
echo    ✅ Los emails NO se envían realmente
echo    ✅ Solo se capturan en Mailtrap
echo    ✅ Perfecto para desarrollo y pruebas
echo    ✅ Puedes ver todos los emails en mailtrap.io
echo.
echo ════════════════════════════════════════════════════════════════
echo.

pause

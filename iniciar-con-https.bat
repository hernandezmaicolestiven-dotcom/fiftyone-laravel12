@echo off
chcp 65001 >nul
color 0A
title 🚀 Iniciar FiftyOne con HTTPS

echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║         INICIAR FIFTYONE CON HTTPS LOCAL                   ║
echo ╚════════════════════════════════════════════════════════════╝
echo.

REM Verificar si el certificado existe
if not exist "ssl\fiftyone.crt" (
    echo ❌ Certificado SSL no encontrado
    echo.
    echo 📝 Primero ejecuta: generar-certificado-ssl.bat
    echo.
    pause
    exit /b 1
)

echo ✅ Certificado SSL encontrado
echo.

REM Verificar si el dominio está en hosts
findstr /C:"fiftyone.local" C:\Windows\System32\drivers\etc\hosts >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo ⚠️  El dominio fiftyone.local NO está en el archivo hosts
    echo.
    echo 📝 Agrega esta línea al archivo hosts:
    echo    127.0.0.1 fiftyone.local
    echo.
    echo 📂 Ubicación: C:\Windows\System32\drivers\etc\hosts
    echo    (Debes abrirlo como ADMINISTRADOR)
    echo.
    pause
)

echo 🔧 Configurando Laravel...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo ════════════════════════════════════════════════════════════
echo 🌐 OPCIONES PARA INICIAR CON HTTPS:
echo ════════════════════════════════════════════════════════════
echo.
echo OPCIÓN A: Servidor PHP nativo (sin SSL real)
echo    php artisan serve --host=fiftyone.local --port=8000
echo    URL: http://fiftyone.local:8000
echo.
echo OPCIÓN B: Usar NGROK (RECOMENDADO para Wompi)
echo    1. Ejecuta: php artisan serve
echo    2. En otra terminal: ngrok http 8000
echo    3. Usa la URL HTTPS que te da ngrok
echo.
echo OPCIÓN C: Usar LARAGON (MEJOR OPCIÓN)
echo    1. Instala Laragon: https://laragon.org/download/
echo    2. Copia el proyecto a C:\laragon\www\fiftyone
echo    3. Activa SSL en Laragon
echo    4. URL: https://fiftyone.test
echo.
echo ════════════════════════════════════════════════════════════
echo.
echo 💡 Para que Wompi funcione, necesitas HTTPS REAL
echo    La mejor opción es NGROK o LARAGON
echo.
echo ¿Qué opción quieres usar?
echo.
echo [1] Iniciar con php artisan serve (HTTP)
echo [2] Ver instrucciones de NGROK
echo [3] Ver instrucciones de LARAGON
echo [4] Salir
echo.
set /p opcion="Elige una opción (1-4): "

if "%opcion%"=="1" (
    echo.
    echo 🚀 Iniciando servidor Laravel...
    echo.
    echo 🌐 Tu sitio estará en: http://fiftyone.local:8000
    echo.
    echo ⚠️  NOTA: Wompi NO funcionará con HTTP
    echo    Necesitas HTTPS para pagos reales
    echo.
    php artisan serve --host=fiftyone.local --port=8000
)

if "%opcion%"=="2" (
    echo.
    echo ════════════════════════════════════════════════════════════
    echo 📝 INSTRUCCIONES NGROK:
    echo ════════════════════════════════════════════════════════════
    echo.
    echo 1. Descarga ngrok: https://ngrok.com/download
    echo 2. Crea cuenta gratis: https://dashboard.ngrok.com/signup
    echo 3. Ejecuta en una terminal:
    echo    php artisan serve
    echo.
    echo 4. En OTRA terminal:
    echo    ngrok http 8000
    echo.
    echo 5. Copia la URL HTTPS que te da (ej: https://abc123.ngrok.io)
    echo.
    echo 6. Actualiza tu .env:
    echo    APP_URL=https://abc123.ngrok.io
    echo.
    echo 7. Limpia caché:
    echo    php artisan config:clear
    echo.
    echo ✅ Listo! Wompi funcionará con esa URL
    echo.
    pause
)

if "%opcion%"=="3" (
    echo.
    echo ════════════════════════════════════════════════════════════
    echo 📝 INSTRUCCIONES LARAGON:
    echo ════════════════════════════════════════════════════════════
    echo.
    echo 1. Descarga Laragon: https://laragon.org/download/
    echo.
    echo 2. Instala Laragon (siguiente, siguiente, finalizar)
    echo.
    echo 3. Copia tu proyecto a:
    echo    C:\laragon\www\fiftyone
    echo.
    echo 4. Abre Laragon
    echo.
    echo 5. Clic derecho en el ícono de Laragon:
    echo    - Apache ^> SSL ^> Enabled
    echo    - Apache ^> Virtual Hosts ^> fiftyone.test (auto)
    echo.
    echo 6. Actualiza tu .env:
    echo    APP_URL=https://fiftyone.test
    echo.
    echo 7. Tu sitio estará en: https://fiftyone.test
    echo.
    echo ✅ Laragon crea automáticamente el certificado SSL
    echo ✅ Wompi funcionará perfectamente
    echo.
    pause
)

if "%opcion%"=="4" (
    exit /b 0
)

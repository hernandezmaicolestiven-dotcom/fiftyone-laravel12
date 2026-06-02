@echo off
chcp 65001 >nul
color 0B
title 🔒 Crear HTTPS Local para Wompi

echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║         CREAR HTTPS LOCAL EN WINDOWS                       ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo 🎯 OPCIÓN 1: LARAGON (MÁS FÁCIL - RECOMENDADA)
echo ════════════════════════════════════════════════════════════
echo.
echo 1. Descargar Laragon:
echo    👉 https://laragon.org/download/
echo.
echo 2. Instalar Laragon (siguiente, siguiente, finalizar)
echo.
echo 3. Copiar tu proyecto a: C:\laragon\www\fiftyone
echo.
echo 4. En Laragon, clic derecho en el ícono ^> Apache ^> SSL ^> Enabled
echo.
echo 5. Clic derecho ^> Apache ^> Virtual Hosts ^> fiftyone.test (auto)
echo.
echo 6. Tu sitio estará en: https://fiftyone.test
echo.
echo ✅ Laragon crea automáticamente el certificado SSL
echo.
echo ════════════════════════════════════════════════════════════
echo.
echo 🎯 OPCIÓN 2: CERTIFICADO SSL MANUAL
echo ════════════════════════════════════════════════════════════
echo.
echo 1. Instalar OpenSSL para Windows:
echo    👉 https://slproweb.com/products/Win32OpenSSL.html
echo.
echo 2. Ejecutar: generar-certificado-ssl.bat
echo.
echo 3. Instalar el certificado en Windows
echo.
echo 4. Configurar hosts: C:\Windows\System32\drivers\etc\hosts
echo    127.0.0.1 fiftyone.local
echo.
echo 5. Iniciar servidor con SSL:
echo    php artisan serve --host=fiftyone.local --port=8000
echo.
echo ════════════════════════════════════════════════════════════
echo.
echo 🎯 OPCIÓN 3: NGROK (MÁS RÁPIDA)
echo ════════════════════════════════════════════════════════════
echo.
echo 1. Descargar ngrok: https://ngrok.com/download
echo.
echo 2. Ejecutar:
echo    php artisan serve
echo    ngrok http 8000
echo.
echo 3. Usar la URL HTTPS que te da ngrok
echo.
echo ✅ Funciona inmediatamente, sin configuración
echo.
echo ════════════════════════════════════════════════════════════
echo.
echo 💡 RECOMENDACIÓN: Usa LARAGON (opción 1)
echo    Es la más fácil y funciona perfectamente en Windows
echo.
pause

@echo off
chcp 65001 >nul
cls

echo ═══════════════════════════════════════════════════════════════════════════
echo   💳 ACTIVAR WOMPI REAL
echo ═══════════════════════════════════════════════════════════════════════════
echo.

echo ✅ Wompi está configurado con tus llaves reales de SANDBOX
echo.
echo 📋 Este script va a:
echo    1. Limpiar cachés
echo    2. Recargar configuración
echo    3. Reiniciar el servidor
echo    4. Abrir el navegador
echo.

pause

echo.
echo 🗑️  PASO 1: Limpiando cachés...
echo ═══════════════════════════════════════════════════════════════════════════
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo ✅ Cachés limpiados
echo.

echo 🔄 PASO 2: Recargando configuración...
echo ═══════════════════════════════════════════════════════════════════════════
php artisan config:cache
echo ✅ Configuración recargada
echo.

echo 🛑 PASO 3: Deteniendo servidor anterior...
echo ═══════════════════════════════════════════════════════════════════════════
taskkill /F /IM php.exe >nul 2>&1
timeout /t 2 >nul
echo ✅ Servidor detenido
echo.

echo 🚀 PASO 4: Iniciando servidor...
echo ═══════════════════════════════════════════════════════════════════════════
start /B php artisan serve
timeout /t 3 >nul
echo ✅ Servidor iniciado
echo.

echo 🌐 PASO 5: Abriendo navegador...
echo ═══════════════════════════════════════════════════════════════════════════
start chrome --incognito http://localhost:8000 2>nul
if errorlevel 1 (
    start msedge --inprivate http://localhost:8000 2>nul
)
if errorlevel 1 (
    start http://localhost:8000
)
echo ✅ Navegador abierto
echo.

echo ═══════════════════════════════════════════════════════════════════════════
echo   ✅ WOMPI REAL ACTIVADO
echo ═══════════════════════════════════════════════════════════════════════════
echo.
echo 📝 Instrucciones:
echo.
echo    1. El navegador se abrió en modo incógnito
echo    2. Presiona Ctrl+Shift+R para recargar sin caché
echo    3. Agrega un producto al carrito
echo    4. Ve al checkout
echo    5. Selecciona Wompi
echo    6. Completa los datos
echo    7. Click en "Pagar"
echo    8. Serás redirigido al checkout REAL de Wompi
echo.
echo 💳 Tarjeta de prueba:
echo    Número: 4242 4242 4242 4242
echo    Fecha: 12/25
echo    CVV: 123
echo    Nombre: Tu nombre
echo.
echo 🔍 Verifica en la consola del navegador (F12):
echo    - Deberías ver: "🌐 Redirigiendo a Wompi: https://checkout.wompi.co/p/..."
echo.
echo 📊 Verifica en tu panel de Wompi:
echo    https://comercios.wompi.co/
echo.

pause

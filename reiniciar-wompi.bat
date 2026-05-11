@echo off
chcp 65001 >nul
cls

echo ═══════════════════════════════════════════════════════════════════════════
echo   🔄 REINICIAR WOMPI - Limpieza completa
echo ═══════════════════════════════════════════════════════════════════════════
echo.

echo 📋 Este script va a:
echo    1. Detener el servidor Laravel
echo    2. Limpiar caché de Laravel
echo    3. Reiniciar el servidor
echo    4. Abrir el navegador en modo incógnito
echo.

pause

echo.
echo 🛑 Deteniendo servidor Laravel...
taskkill /F /IM php.exe >nul 2>&1
timeout /t 2 >nul

echo ✅ Servidor detenido
echo.

echo 🗑️ Limpiando caché de Laravel...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo ✅ Caché limpiado
echo.

echo 🚀 Iniciando servidor Laravel...
start /B php artisan serve

echo ⏳ Esperando que el servidor inicie...
timeout /t 3 >nul

echo ✅ Servidor iniciado
echo.

echo 🌐 Abriendo navegador en modo incógnito...
echo.
echo    IMPORTANTE: 
echo    - Se abrirá el navegador en modo incógnito (sin caché)
echo    - Presiona Ctrl+Shift+R al cargar la página
echo    - Ve al checkout y prueba Wompi
echo.

REM Intentar abrir en Chrome modo incógnito
start chrome --incognito http://localhost:8000 2>nul

REM Si Chrome no está disponible, intentar Edge
if errorlevel 1 (
    start msedge --inprivate http://localhost:8000 2>nul
)

REM Si ninguno está disponible, abrir navegador por defecto
if errorlevel 1 (
    start http://localhost:8000
)

echo.
echo ═══════════════════════════════════════════════════════════════════════════
echo   ✅ LISTO
echo ═══════════════════════════════════════════════════════════════════════════
echo.
echo 📝 Instrucciones:
echo    1. El navegador se abrió en modo incógnito
echo    2. Presiona Ctrl+Shift+R para recargar sin caché
echo    3. Agrega un producto al carrito
echo    4. Ve al checkout y selecciona Wompi
echo    5. Deberías ver el checkout DEMO
echo.
echo 🧪 Página de prueba alternativa:
echo    http://localhost:8000/test-wompi-checkout.html
echo.
echo 🔍 Si sigue sin funcionar:
echo    - Lee el archivo SOLUCION_CACHE_WOMPI.md
echo    - Ejecuta: php scripts/test-wompi-direct.php
echo.

pause

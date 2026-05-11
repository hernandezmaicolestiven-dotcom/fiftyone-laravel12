@echo off
chcp 65001 >nul
cls
echo.
echo ═══════════════════════════════════════════════════════════
echo   🔍 VERIFICACIÓN DE WOMPI
echo ═══════════════════════════════════════════════════════════
echo.

echo 📋 Verificando configuración...
echo.

php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); echo '✅ Llave Pública: ' . (config('services.wompi.public_key') ? 'Configurada' : '❌ NO configurada') . PHP_EOL; echo '✅ Llave Integridad: ' . (config('services.wompi.integrity_secret') ? 'Configurada' : '❌ NO configurada') . PHP_EOL; echo '✅ Modo Sandbox: ' . (config('services.wompi.sandbox') ? 'Activado' : 'Desactivado') . PHP_EOL;"

echo.
echo ═══════════════════════════════════════════════════════════
echo   📝 INSTRUCCIONES PARA PROBAR
echo ═══════════════════════════════════════════════════════════
echo.
echo 1. Abre tu tienda en el navegador
echo 2. Presiona Ctrl+Shift+R para limpiar caché
echo 3. Agrega un producto al carrito
echo 4. Haz clic en "Pagar con Wompi"
echo 5. Se abrirá el widget oficial de Wompi
echo.
echo 💳 TARJETA DE PRUEBA (APROBADA):
echo    Número: 4242 4242 4242 4242
echo    CVV: 123
echo    Fecha: Cualquier fecha futura
echo.
echo ═══════════════════════════════════════════════════════════
echo.
pause

@echo off
chcp 65001 >nul
cls
echo.
echo ═══════════════════════════════════════════════════════════
echo   🧹 LIMPIEZA COMPLETA DE CACHÉ
echo ═══════════════════════════════════════════════════════════
echo.

echo 📋 Limpiando caché de Laravel...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo ═══════════════════════════════════════════════════════════
echo   ✅ CACHÉ DE LARAVEL LIMPIADO
echo ═══════════════════════════════════════════════════════════
echo.
echo 🌐 AHORA DEBES LIMPIAR EL CACHÉ DEL NAVEGADOR:
echo.
echo 1️⃣ CIERRA COMPLETAMENTE EL NAVEGADOR
echo    (No solo la pestaña, cierra TODO)
echo.
echo 2️⃣ ABRE EL NAVEGADOR DE NUEVO
echo.
echo 3️⃣ Ve a tu tienda y presiona: Ctrl+Shift+R
echo.
echo 4️⃣ Prueba agregar un producto y pagar con Wompi
echo    (Se abrirá un MODAL, no te redirigirá)
echo.
echo ═══════════════════════════════════════════════════════════
echo   💳 TARJETA DE PRUEBA:
echo ═══════════════════════════════════════════════════════════
echo.
echo Número: 4242 4242 4242 4242
echo CVV: 123
echo Fecha: Cualquier fecha futura
echo.
echo ═══════════════════════════════════════════════════════════
echo.
pause

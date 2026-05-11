@echo off
echo ========================================
echo LIMPIANDO TODO - WOMPI
echo ========================================
echo.

echo [1/5] Limpiando cache de configuracion...
php artisan config:clear
echo.

echo [2/5] Limpiando cache de rutas...
php artisan route:clear
echo.

echo [3/5] Limpiando cache de aplicacion...
php artisan cache:clear
echo.

echo [4/5] Limpiando cache de vistas...
php artisan view:clear
echo.

echo [5/5] Optimizando autoload...
composer dump-autoload -o
echo.

echo ========================================
echo LIMPIEZA COMPLETADA
echo ========================================
echo.
echo SIGUIENTE PASO:
echo 1. Reinicia el servidor: php artisan serve
echo 2. Abre el navegador en MODO INCOGNITO
echo 3. Ve a: http://localhost:8000
echo 4. Prueba el pago con Wompi
echo.
echo ========================================
pause

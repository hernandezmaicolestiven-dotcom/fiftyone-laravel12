@echo off
echo.
echo ========================================
echo   REINICIANDO SERVIDOR LARAVEL
echo ========================================
echo.
echo Limpiando cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo.
echo ========================================
echo   CACHE LIMPIADA
echo ========================================
echo.
echo IMPORTANTE:
echo 1. Detén el servidor actual (Ctrl + C en la terminal donde corre)
echo 2. Ejecuta: php artisan serve
echo 3. Recarga el navegador con Ctrl + Shift + R
echo.
pause

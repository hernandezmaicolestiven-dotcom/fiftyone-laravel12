@echo off
echo ========================================
echo   Limpiando Cache - FiftyOne
echo ========================================
echo.
echo Limpiando cache de aplicacion...
php artisan cache:clear
echo.
echo Limpiando cache de configuracion...
php artisan config:clear
echo.
echo Limpiando cache de rutas...
php artisan route:clear
echo.
echo Limpiando cache de vistas...
php artisan view:clear
echo.
echo ✅ Cache limpiado exitosamente
echo.
pause

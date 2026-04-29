@echo off
echo ========================================
echo   FiftyOne - Queue Worker
echo ========================================
echo.
echo Iniciando worker de colas...
echo Presiona Ctrl+C para detener
echo.
php artisan queue:work --tries=3 --timeout=90

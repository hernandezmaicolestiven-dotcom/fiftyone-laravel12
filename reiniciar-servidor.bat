@echo off
chcp 65001 >nul
cls
echo.
echo ═══════════════════════════════════════════════════════════
echo   🔄 REINICIAR SERVIDOR - FIFTYONE
echo ═══════════════════════════════════════════════════════════
echo.
echo Este script limpiará el caché y reiniciará el servidor.
echo.
echo ⚠️  IMPORTANTE: Cierra cualquier ventana que tenga
echo    "php artisan serve" ejecutándose antes de continuar.
echo.
pause
echo.
echo 🧹 Limpiando caché...
echo.

php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo.
echo ✅ Caché limpiado
echo.
echo ═══════════════════════════════════════════════════════════
echo   🚀 INICIANDO SERVIDOR
echo ═══════════════════════════════════════════════════════════
echo.
echo El servidor se iniciará en: http://localhost:8000
echo.
echo Para detener el servidor, presiona Ctrl+C
echo.
echo ═══════════════════════════════════════════════════════════
echo.

php artisan serve

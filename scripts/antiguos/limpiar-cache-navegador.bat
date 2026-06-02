@echo off
chcp 65001 >nul
cls
echo.
echo ═══════════════════════════════════════════════════════════
echo   🧹 LIMPIAR CACHÉ COMPLETO
echo ═══════════════════════════════════════════════════════════
echo.
echo Este script limpiará el caché de Laravel y te dará
echo instrucciones para limpiar el caché del navegador.
echo.
pause
echo.
echo 🔄 Limpiando caché de Laravel...
echo.

php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo.
echo ✅ Caché de Laravel limpiado
echo.
echo ═══════════════════════════════════════════════════════════
echo   🌐 AHORA LIMPIA EL CACHÉ DEL NAVEGADOR
echo ═══════════════════════════════════════════════════════════
echo.
echo OPCIÓN 1 (Recomendada):
echo   1. CIERRA COMPLETAMENTE el navegador
echo   2. Espera 5 segundos
echo   3. Abre el navegador de nuevo
echo   4. Ve a: http://localhost:8000
echo   5. Presiona Ctrl+Shift+R
echo.
echo OPCIÓN 2 (Si la opción 1 no funciona):
echo   1. Abre el navegador
echo   2. Presiona Ctrl+Shift+Delete
echo   3. Selecciona "Todo el tiempo"
echo   4. Marca "Imágenes y archivos en caché"
echo   5. Haz clic en "Borrar datos"
echo   6. Ve a: http://localhost:8000
echo.
echo ═══════════════════════════════════════════════════════════
echo.
pause

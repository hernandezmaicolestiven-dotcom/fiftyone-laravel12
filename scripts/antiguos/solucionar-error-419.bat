@echo off
chcp 65001 >nul
cls
echo.
echo ═══════════════════════════════════════════════════════════
echo   🔧 SOLUCIONAR ERROR 419 - PAGE EXPIRED
echo ═══════════════════════════════════════════════════════════
echo.
echo Este error ocurre cuando la sesión expira.
echo.
echo 🔄 Limpiando caché...
echo.

php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo.
echo ═══════════════════════════════════════════════════════════
echo   ✅ CACHÉ LIMPIADO
echo ═══════════════════════════════════════════════════════════
echo.
echo 📋 AHORA DEBES:
echo.
echo 1. Recarga la página en el navegador (F5 o Ctrl+R)
echo 2. Si el error persiste, cierra el navegador completamente
echo 3. Abre el navegador de nuevo
echo 4. Ve a: http://localhost:8000/login
echo.
echo 💡 CONSEJO:
echo    El error 419 aparece cuando dejas la página abierta
echo    mucho tiempo sin usarla. Simplemente recarga la página.
echo.
echo ═══════════════════════════════════════════════════════════
echo.
pause

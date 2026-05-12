@echo off
chcp 65001 >nul
cls
echo.
echo ═══════════════════════════════════════════════════════════
echo   🔐 RESETEAR CREDENCIALES - FIFTYONE
echo ═══════════════════════════════════════════════════════════
echo.
echo Este script restaurará las credenciales de acceso a:
echo.
echo   👨‍💼 Admin:       admin@fiftyone.com       / admin2026
echo   👤 Cliente:     cliente@test.com         / cliente2026
echo   🤝 Colaborador: colaborador@fiftyone.com / colab2026
echo.
echo ═══════════════════════════════════════════════════════════
echo.
pause
echo.
echo 🔄 Reseteando credenciales...
echo.

php artisan db:seed --class=ResetCredentialsSeeder

echo.
echo ═══════════════════════════════════════════════════════════
echo   ✅ CREDENCIALES RESETEADAS
echo ═══════════════════════════════════════════════════════════
echo.
echo Puedes iniciar sesión con las credenciales mostradas arriba.
echo.
echo 📄 Para ver las credenciales en cualquier momento, abre:
echo    CREDENCIALES_ACCESO.txt
echo.
pause

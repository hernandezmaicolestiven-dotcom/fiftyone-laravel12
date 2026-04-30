@echo off
echo ========================================
echo   VERIFICACION DE CREDENCIALES
echo   FiftyOne - Docker
echo ========================================
echo.

echo Verificando usuarios en la base de datos...
echo.

docker compose exec -T db mysql -u laravel -psecret fiftyone -e "SELECT id, name, email, role FROM users WHERE role IN ('admin', 'colaborador') ORDER BY role;"

echo.
echo ========================================
echo   CREDENCIALES CONFIRMADAS
echo ========================================
echo.
echo ADMIN:
echo   Email: admin@fiftyone.com
echo   Password: Admin123!
echo.
echo COLABORADOR:
echo   Email: colaborador@fiftyone.com
echo   Password: Colab123!
echo.
echo CLIENTE:
echo   Email: cliente@test.com
echo   Password: Cliente123!
echo.
echo ========================================
echo.
echo Presiona cualquier tecla para abrir el login de admin...
pause >nul

start http://localhost:8000/admin/login

echo.
echo Usa las credenciales de arriba para iniciar sesion.
echo.
pause

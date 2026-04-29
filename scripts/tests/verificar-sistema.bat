@echo off
cls
echo ╔════════════════════════════════════════════════════════════╗
echo ║     VERIFICACION COMPLETA DEL SISTEMA - FIFTYONE          ║
echo ╚════════════════════════════════════════════════════════════╝
echo.

echo [1/6] Verificando conexion a base de datos...
php artisan db:show --database=mysql >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ Base de datos conectada
) else (
    echo ❌ Error de conexion a base de datos
    goto :error
)
echo.

echo [2/6] Verificando usuarios en el sistema...
php artisan tinker --execute="echo 'Total usuarios: ' . App\Models\User::count() . PHP_EOL;"
echo.

echo [3/6] Verificando rutas de autenticacion...
php artisan route:list --name=login >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ Rutas de login configuradas
) else (
    echo ❌ Error en rutas
    goto :error
)
echo.

echo [4/6] Verificando middleware de seguridad...
if exist "app\Http\Middleware\AdminOnly.php" (
    echo ✅ Middleware AdminOnly existe
) else (
    echo ❌ Middleware AdminOnly no encontrado
    goto :error
)
echo.

echo [5/6] Verificando controladores...
if exist "app\Http\Controllers\Admin\AuthController.php" (
    echo ✅ AuthController (Admin) existe
) else (
    echo ❌ AuthController no encontrado
    goto :error
)
if exist "app\Http\Controllers\CustomerAuthController.php" (
    echo ✅ CustomerAuthController existe
) else (
    echo ❌ CustomerAuthController no encontrado
    goto :error
)
echo.

echo [6/6] Verificando vistas de login...
if exist "resources\views\admin\auth\login.blade.php" (
    echo ✅ Vista login admin existe
) else (
    echo ❌ Vista login admin no encontrada
    goto :error
)
if exist "resources\views\customer\auth\login.blade.php" (
    echo ✅ Vista login cliente existe
) else (
    echo ❌ Vista login cliente no encontrada
    goto :error
)
echo.

echo ╔════════════════════════════════════════════════════════════╗
echo ║                  ✅ SISTEMA VERIFICADO                     ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo 📋 CREDENCIALES DISPONIBLES:
echo.
echo 👨‍💼 ADMIN:
echo    URL: http://localhost:8000/admin/login
echo    Email: admin@fiftyone.com
echo    Pass: Admin123!
echo.
echo 👤 CLIENTE:
echo    URL: http://localhost:8000/login
echo    Email: cliente@test.com
echo    Pass: Cliente123!
echo.
echo 💡 Ver archivo CREDENCIALES.md para mas informacion
echo.
goto :end

:error
echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║                  ❌ ERROR DETECTADO                        ║
echo ╚════════════════════════════════════════════════════════════╝
echo.
echo Por favor revisa los errores anteriores.
echo.

:end
pause

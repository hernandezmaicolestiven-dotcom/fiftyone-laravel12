@echo off
chcp 65001 >nul
echo.
echo ═══════════════════════════════════════════════════════════════════
echo     ✅ VERIFICACIÓN FINAL - FIFTYONE
echo ═══════════════════════════════════════════════════════════════════
echo.

echo [1/8] Verificando contenedores Docker...
docker ps --format "table {{.Names}}\t{{.Status}}" | findstr "fiftyone"
if %errorlevel% neq 0 (
    echo ❌ ERROR: Contenedores no están corriendo
    echo Ejecuta: docker compose up -d
    pause
    exit /b 1
)
echo ✅ Contenedores corriendo
echo.

echo [2/8] Verificando Laravel...
docker exec fiftyone_app php artisan --version
if %errorlevel% neq 0 (
    echo ❌ ERROR: Laravel no responde
    pause
    exit /b 1
)
echo ✅ Laravel funcionando
echo.

echo [3/8] Verificando base de datos...
docker exec fiftyone_app php artisan tinker --execute="echo 'Productos: ' . \App\Models\Product::count(); echo PHP_EOL; echo 'Usuarios: ' . \App\Models\User::count(); echo PHP_EOL; echo 'Pedidos: ' . \App\Models\Order::count();"
if %errorlevel% neq 0 (
    echo ❌ ERROR: Base de datos no responde
    pause
    exit /b 1
)
echo ✅ Base de datos con datos
echo.

echo [4/8] Verificando credenciales...
docker exec fiftyone_app php artisan tinker --execute="echo User::where('email', 'admin@fiftyone.com')->exists() ? '✅ Admin: OK' : '❌ Admin: NO EXISTE'; echo PHP_EOL; echo User::where('email', 'cliente@test.com')->exists() ? '✅ Cliente: OK' : '❌ Cliente: NO EXISTE';"
if %errorlevel% neq 0 (
    echo ❌ ERROR: Problema con credenciales
    pause
    exit /b 1
)
echo.

echo [5/8] Verificando puerto 8000...
netstat -an | findstr "8000.*LISTENING" >nul
if %errorlevel% neq 0 (
    echo ❌ ERROR: Puerto 8000 no está escuchando
    pause
    exit /b 1
)
echo ✅ Puerto 8000 abierto y escuchando
echo.

echo [6/8] Verificando firewall...
netsh advfirewall firewall show rule name="Docker FiftyOne - Puerto 8000" | findstr "Habilitada.*S" >nul
if %errorlevel% neq 0 (
    echo ⚠️  ADVERTENCIA: Regla de firewall no encontrada o deshabilitada
    echo El instructor podría no poder acceder
) else (
    echo ✅ Firewall configurado correctamente
)
echo.

echo [7/8] Tus IPs para acceso remoto...
echo.
ipconfig | findstr "IPv4"
echo.

echo [8/8] Verificando Nginx...
docker exec fiftyone_app nginx -t 2>&1 | findstr "successful" >nul
if %errorlevel% neq 0 (
    echo ⚠️  Nginx podría tener problemas de configuración
) else (
    echo ✅ Nginx configurado correctamente
)
echo.

echo ═══════════════════════════════════════════════════════════════════
echo     ✅ VERIFICACIÓN COMPLETADA
echo ═══════════════════════════════════════════════════════════════════
echo.
echo 📋 RESUMEN PARA EL INSTRUCTOR:
echo.
echo URLs para probar (en orden):
echo   1. http://10.6.64.193:8000
echo   2. http://192.168.137.1:8000
echo   3. http://172.30.144.1:8000
echo.
echo Credenciales Admin:
echo   URL:      http://[IP]:8000/admin/login
echo   Email:    admin@fiftyone.com
echo   Password: Admin123!
echo.
echo ⚠️  IMPORTANTE: La "A" es MAYÚSCULA e incluye el "!" al final
echo.
echo 📄 Archivo para compartir: PARA_EL_INSTRUCTOR.txt
echo.
echo ═══════════════════════════════════════════════════════════════════
echo.
pause

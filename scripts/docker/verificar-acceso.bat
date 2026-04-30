@echo off
echo ========================================
echo   VERIFICACION DE ACCESO REMOTO
echo   FiftyOne - Docker
echo ========================================
echo.

echo 1. Verificando Docker...
docker compose ps
if errorlevel 1 (
    echo ❌ Docker no esta corriendo
    pause
    exit /b 1
)
echo ✅ Docker corriendo
echo.

echo 2. Obteniendo IPs disponibles...
echo.
ipconfig | findstr "IPv4"
echo.

echo 3. Verificando puerto 8000...
netstat -an | findstr ":8000.*LISTENING"
if errorlevel 1 (
    echo ❌ Puerto 8000 no esta escuchando
) else (
    echo ✅ Puerto 8000 abierto
)
echo.

echo 4. Verificando regla de firewall...
netsh advfirewall firewall show rule name="Docker FiftyOne - Puerto 8000" >nul 2>&1
if errorlevel 1 (
    echo ⚠️  Regla de firewall no encontrada
    echo    Creando regla...
    netsh advfirewall firewall add rule name="Docker FiftyOne - Puerto 8000" dir=in action=allow protocol=TCP localport=8000
) else (
    echo ✅ Firewall configurado
)
echo.

echo ========================================
echo   INFORMACION PARA EL INSTRUCTOR
echo ========================================
echo.
echo Prueba estas URLs en orden:
echo.
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr "IPv4"') do (
    for /f "tokens=1" %%b in ("%%a") do (
        echo   http://%%b:8000
    )
)
echo.
echo Credenciales Admin:
echo   Email: admin@fiftyone.com
echo   Password: Admin123!
echo.
echo Credenciales Cliente:
echo   Email: cliente@test.com
echo   Password: Cliente123!
echo.
echo ========================================
echo.
echo ✅ Todo listo para la demostracion!
echo.
echo Presiona cualquier tecla para abrir el navegador...
pause >nul

echo Abriendo navegador local...
start http://localhost:8000

echo.
echo El instructor debe usar una de las IPs mostradas arriba.
echo.
pause

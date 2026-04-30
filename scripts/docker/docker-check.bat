@echo off
REM Script de verificación de Docker para Windows
REM Ejecuta: docker-check.bat

echo 🔍 Verificando configuración de Docker para FiftyOne...
echo.

REM 1. Verificar Docker
echo 1️⃣  Verificando Docker...
docker --version >nul 2>&1
if errorlevel 1 (
    echo ✗ Docker NO instalado
) else (
    echo ✓ Docker instalado
)

docker info >nul 2>&1
if errorlevel 1 (
    echo ✗ Docker NO está corriendo
) else (
    echo ✓ Docker corriendo
)

REM 2. Verificar Docker Compose
echo.
echo 2️⃣  Verificando Docker Compose...
docker compose version >nul 2>&1
if errorlevel 1 (
    echo ✗ Docker Compose NO instalado
) else (
    echo ✓ Docker Compose instalado
)

REM 3. Verificar archivos necesarios
echo.
echo 3️⃣  Verificando archivos de configuración...

if exist "docker-compose.yml" (
    echo ✓ docker-compose.yml existe
) else (
    echo ✗ docker-compose.yml NO existe
)

if exist "Dockerfile" (
    echo ✓ Dockerfile existe
) else (
    echo ✗ Dockerfile NO existe
)

if exist "docker\nginx\default.conf" (
    echo ✓ nginx/default.conf existe
) else (
    echo ✗ nginx/default.conf NO existe
)

if exist "docker\supervisor\supervisord.conf" (
    echo ✓ supervisor/supervisord.conf existe
) else (
    echo ✗ supervisor/supervisord.conf NO existe
)

if exist ".env" (
    echo ✓ Archivo .env existe
) else (
    if exist ".env.docker" (
        echo ✓ Archivo .env.docker existe
    ) else (
        echo ✗ Archivo .env o .env.docker NO existe
    )
)

REM 4. Verificar estado de contenedores
echo.
echo 4️⃣  Verificando contenedores...

docker compose ps 2>nul | findstr "fiftyone_app" >nul
if errorlevel 1 (
    echo ⚠ Contenedor app no existe ^(ejecuta: docker compose up -d^)
) else (
    docker compose ps 2>nul | findstr "fiftyone_app" | findstr "Up" >nul
    if errorlevel 1 (
        echo ⚠ Contenedor app existe pero no está corriendo
    ) else (
        echo ✓ Contenedor app corriendo
    )
)

docker compose ps 2>nul | findstr "fiftyone_db" >nul
if errorlevel 1 (
    echo ⚠ Contenedor db no existe ^(ejecuta: docker compose up -d^)
) else (
    docker compose ps 2>nul | findstr "fiftyone_db" | findstr "Up" >nul
    if errorlevel 1 (
        echo ⚠ Contenedor db existe pero no está corriendo
    ) else (
        echo ✓ Contenedor db corriendo
    )
)

REM 5. Verificar puertos
echo.
echo 5️⃣  Verificando puertos...

netstat -an 2>nul | findstr ":8000.*LISTENING" >nul
if errorlevel 1 (
    echo ⚠ Puerto 8000 libre ^(app no está corriendo^)
) else (
    echo ✓ Puerto 8000 en uso ^(app corriendo^)
)

netstat -an 2>nul | findstr ":3307.*LISTENING" >nul
if errorlevel 1 (
    echo ⚠ Puerto 3307 libre ^(MySQL no está corriendo^)
) else (
    echo ✓ Puerto 3307 en uso ^(MySQL corriendo^)
)

REM 6. Verificar volúmenes
echo.
echo 6️⃣  Verificando volúmenes...

docker volume ls 2>nul | findstr "mysql_data" >nul
if errorlevel 1 (
    echo ⚠ Volumen mysql_data no existe
) else (
    echo ✓ Volumen mysql_data existe
)

docker volume ls 2>nul | findstr "vendor" >nul
if errorlevel 1 (
    echo ⚠ Volumen vendor no existe
) else (
    echo ✓ Volumen vendor existe
)

docker volume ls 2>nul | findstr "node_modules" >nul
if errorlevel 1 (
    echo ⚠ Volumen node_modules no existe
) else (
    echo ✓ Volumen node_modules existe
)

REM 7. Información de red
echo.
echo 7️⃣  Información de red...
echo.
echo    🌐 URL Local: http://localhost:8000
echo.
echo    📱 URL Red Local: Ejecuta 'ipconfig' para ver tu IP
echo.

echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

REM Resumen
docker compose ps 2>nul | findstr "Up" >nul
if errorlevel 1 (
    echo ⚠️  Los contenedores no están corriendo.
    echo.
    echo Para iniciar:
    echo   • Automático: docker-start.bat
    echo   • Manual: docker compose up -d
) else (
    echo ✅ Todo listo! Tu aplicación está corriendo.
    echo.
    echo Comandos útiles:
    echo   • Ver logs: docker compose logs -f
    echo   • Detener: docker compose stop
    echo   • Reiniciar: docker compose restart
)

echo.
pause

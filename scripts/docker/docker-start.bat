@echo off
REM Script de inicio rápido para Docker en Windows
REM Ejecuta: docker-start.bat

echo 🐳 Iniciando FiftyOne en Docker...
echo.

REM Verificar si Docker está corriendo
docker info >nul 2>&1
if errorlevel 1 (
    echo ❌ Error: Docker no está corriendo. Por favor inicia Docker Desktop.
    pause
    exit /b 1
)

REM Construir e iniciar contenedores
echo 📦 Construyendo contenedores...
docker compose build

echo.
echo ▶️  Iniciando contenedores...
docker compose up -d

echo.
echo ⏳ Esperando que la base de datos esté lista...
timeout /t 10 /nobreak >nul

REM Instalar dependencias
echo.
echo 📚 Instalando dependencias de Composer...
docker compose exec -T app composer install --optimize-autoloader --no-dev

REM Generar clave si no existe
echo.
echo 🔑 Verificando APP_KEY...
docker compose exec -T app php artisan key:generate --show

REM Crear enlace de storage
echo.
echo 🔗 Creando enlace de storage...
docker compose exec -T app php artisan storage:link

REM Configurar permisos
echo.
echo 🔐 Configurando permisos...
docker compose exec -T app chown -R www-data:www-data /var/www/html/storage
docker compose exec -T app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker compose exec -T app chmod -R 775 /var/www/html/storage
docker compose exec -T app chmod -R 775 /var/www/html/bootstrap/cache

REM Ejecutar migraciones
echo.
set /p MIGRATE="¿Quieres ejecutar las migraciones? (s/n): "
if /i "%MIGRATE%"=="s" (
    docker compose exec -T app php artisan migrate --force
)

REM Compilar assets
echo.
set /p ASSETS="¿Quieres compilar los assets de frontend? (s/n): "
if /i "%ASSETS%"=="s" (
    docker compose exec -T app npm install
    docker compose exec -T app npm run build
)

echo.
echo ✅ ¡Listo! Tu aplicación está corriendo en:
echo.
echo    🌐 Local: http://localhost:8000
echo.
echo    📱 Red local: Usa 'ipconfig' para ver tu IP
echo.
echo 📊 Ver logs: docker compose logs -f
echo ⏹️  Detener: docker compose stop
echo.
pause

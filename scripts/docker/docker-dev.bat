@echo off
REM Script de comandos útiles para desarrollo con Docker (Windows)
REM Uso: docker-dev.bat [comando]

if "%1"=="" goto help
if "%1"=="help" goto help
if "%1"=="logs" goto logs
if "%1"=="shell" goto shell
if "%1"=="mysql" goto mysql
if "%1"=="artisan" goto artisan
if "%1"=="composer" goto composer
if "%1"=="npm" goto npm
if "%1"=="migrate" goto migrate
if "%1"=="seed" goto seed
if "%1"=="fresh" goto fresh
if "%1"=="cache-clear" goto cache-clear
if "%1"=="optimize" goto optimize
if "%1"=="restart" goto restart
if "%1"=="rebuild" goto rebuild
if "%1"=="stop" goto stop
if "%1"=="start" goto start
if "%1"=="status" goto status
if "%1"=="clean" goto clean
if "%1"=="build-assets" goto build-assets
if "%1"=="watch-assets" goto watch-assets
if "%1"=="test" goto test
if "%1"=="permissions" goto permissions
if "%1"=="backup-db" goto backup-db
if "%1"=="restore-db" goto restore-db
goto help

:logs
echo 📊 Mostrando logs...
docker compose logs -f
goto end

:shell
echo 🐚 Accediendo al contenedor...
docker compose exec app bash
goto end

:mysql
echo 🗄️  Accediendo a MySQL...
docker compose exec db mysql -u laravel -psecret fiftyone
goto end

:artisan
shift
echo 🎨 Ejecutando: php artisan %*
docker compose exec app php artisan %*
goto end

:composer
shift
echo 📦 Ejecutando: composer %*
docker compose exec app composer %*
goto end

:npm
shift
echo 📦 Ejecutando: npm %*
docker compose exec app npm %*
goto end

:migrate
echo 🗄️  Ejecutando migraciones...
docker compose exec app php artisan migrate
goto end

:seed
echo 🌱 Ejecutando seeders...
docker compose exec app php artisan db:seed
goto end

:fresh
echo 🔄 Reiniciando base de datos...
set /p CONFIRM="⚠️  Esto eliminará todos los datos. ¿Continuar? (s/n): "
if /i "%CONFIRM%"=="s" (
    docker compose exec app php artisan migrate:fresh --seed
)
goto end

:cache-clear
echo 🧹 Limpiando caché...
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear
echo ✅ Caché limpiado
goto end

:optimize
echo ⚡ Optimizando aplicación...
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
echo ✅ Aplicación optimizada
goto end

:restart
echo 🔄 Reiniciando contenedores...
docker compose restart
echo ✅ Contenedores reiniciados
goto end

:rebuild
echo 🔨 Reconstruyendo contenedores...
docker compose down
docker compose build --no-cache
docker compose up -d
echo ✅ Contenedores reconstruidos
goto end

:stop
echo ⏹️  Deteniendo contenedores...
docker compose stop
echo ✅ Contenedores detenidos
goto end

:start
echo ▶️  Iniciando contenedores...
docker compose start
echo ✅ Contenedores iniciados
goto end

:status
echo 📊 Estado de contenedores:
docker compose ps
goto end

:clean
echo 🧹 Limpiando Docker...
set /p CONFIRM="⚠️  Esto eliminará contenedores, volúmenes y datos. ¿Continuar? (s/n): "
if /i "%CONFIRM%"=="s" (
    docker compose down -v
    echo ✅ Docker limpiado
)
goto end

:build-assets
echo 🎨 Compilando assets...
docker compose exec app npm install
docker compose exec app npm run build
echo ✅ Assets compilados
goto end

:watch-assets
echo 👀 Observando cambios en assets...
docker compose exec app npm run dev
goto end

:test
echo 🧪 Ejecutando tests...
docker compose exec app php artisan test
goto end

:permissions
echo 🔐 Configurando permisos...
docker compose exec app chown -R www-data:www-data /var/www/html/storage
docker compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker compose exec app chmod -R 775 /var/www/html/storage
docker compose exec app chmod -R 775 /var/www/html/bootstrap/cache
echo ✅ Permisos configurados
goto end

:backup-db
echo 💾 Creando backup de base de datos...
for /f "tokens=2-4 delims=/ " %%a in ('date /t') do (set mydate=%%c%%a%%b)
for /f "tokens=1-2 delims=/:" %%a in ('time /t') do (set mytime=%%a%%b)
docker compose exec -T db mysqldump -u laravel -psecret fiftyone > backup_%mydate%_%mytime%.sql
echo ✅ Backup creado: backup_%mydate%_%mytime%.sql
goto end

:restore-db
if "%2"=="" (
    echo ❌ Error: Especifica el archivo de backup
    echo Uso: docker-dev.bat restore-db backup_20260430_120000.sql
    goto end
)
echo 📥 Restaurando base de datos desde %2...
docker compose exec -T db mysql -u laravel -psecret fiftyone < %2
echo ✅ Base de datos restaurada
goto end

:help
echo 🐳 Comandos disponibles para desarrollo con Docker:
echo.
echo   logs              - Ver logs en tiempo real
echo   shell             - Acceder al contenedor de la app
echo   mysql             - Acceder a MySQL
echo   artisan [cmd]     - Ejecutar comando Artisan
echo   composer [cmd]    - Ejecutar comando Composer
echo   npm [cmd]         - Ejecutar comando NPM
echo   migrate           - Ejecutar migraciones
echo   seed              - Ejecutar seeders
echo   fresh             - Reiniciar base de datos con seeders
echo   cache-clear       - Limpiar toda la caché
echo   optimize          - Optimizar aplicación
echo   restart           - Reiniciar contenedores
echo   rebuild           - Reconstruir contenedores
echo   stop              - Detener contenedores
echo   start             - Iniciar contenedores
echo   status            - Ver estado de contenedores
echo   clean             - Limpiar todo (⚠️  elimina datos)
echo   build-assets      - Compilar assets de frontend
echo   watch-assets      - Observar cambios en assets
echo   test              - Ejecutar tests
echo   permissions       - Configurar permisos
echo   backup-db         - Crear backup de base de datos
echo   restore-db [file] - Restaurar base de datos
echo   help              - Mostrar esta ayuda
echo.
echo Ejemplos:
echo   docker-dev.bat logs
echo   docker-dev.bat artisan migrate
echo   docker-dev.bat composer require package/name
echo   docker-dev.bat npm install
echo.

:end

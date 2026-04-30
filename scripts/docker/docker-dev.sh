#!/bin/bash

# Script de comandos útiles para desarrollo con Docker
# Uso: ./docker-dev.sh [comando]

case "$1" in
    logs)
        echo "📊 Mostrando logs..."
        docker compose logs -f
        ;;
    
    shell)
        echo "🐚 Accediendo al contenedor..."
        docker compose exec app bash
        ;;
    
    mysql)
        echo "🗄️  Accediendo a MySQL..."
        docker compose exec db mysql -u laravel -psecret fiftyone
        ;;
    
    artisan)
        shift
        echo "🎨 Ejecutando: php artisan $@"
        docker compose exec app php artisan "$@"
        ;;
    
    composer)
        shift
        echo "📦 Ejecutando: composer $@"
        docker compose exec app composer "$@"
        ;;
    
    npm)
        shift
        echo "📦 Ejecutando: npm $@"
        docker compose exec app npm "$@"
        ;;
    
    migrate)
        echo "🗄️  Ejecutando migraciones..."
        docker compose exec app php artisan migrate
        ;;
    
    seed)
        echo "🌱 Ejecutando seeders..."
        docker compose exec app php artisan db:seed
        ;;
    
    fresh)
        echo "🔄 Reiniciando base de datos..."
        read -p "⚠️  Esto eliminará todos los datos. ¿Continuar? (s/n): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[SsYy]$ ]]; then
            docker compose exec app php artisan migrate:fresh --seed
        fi
        ;;
    
    cache-clear)
        echo "🧹 Limpiando caché..."
        docker compose exec app php artisan cache:clear
        docker compose exec app php artisan config:clear
        docker compose exec app php artisan view:clear
        docker compose exec app php artisan route:clear
        echo "✅ Caché limpiado"
        ;;
    
    optimize)
        echo "⚡ Optimizando aplicación..."
        docker compose exec app php artisan config:cache
        docker compose exec app php artisan route:cache
        docker compose exec app php artisan view:cache
        echo "✅ Aplicación optimizada"
        ;;
    
    restart)
        echo "🔄 Reiniciando contenedores..."
        docker compose restart
        echo "✅ Contenedores reiniciados"
        ;;
    
    rebuild)
        echo "🔨 Reconstruyendo contenedores..."
        docker compose down
        docker compose build --no-cache
        docker compose up -d
        echo "✅ Contenedores reconstruidos"
        ;;
    
    stop)
        echo "⏹️  Deteniendo contenedores..."
        docker compose stop
        echo "✅ Contenedores detenidos"
        ;;
    
    start)
        echo "▶️  Iniciando contenedores..."
        docker compose start
        echo "✅ Contenedores iniciados"
        ;;
    
    status)
        echo "📊 Estado de contenedores:"
        docker compose ps
        ;;
    
    clean)
        echo "🧹 Limpiando Docker..."
        read -p "⚠️  Esto eliminará contenedores, volúmenes y datos. ¿Continuar? (s/n): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[SsYy]$ ]]; then
            docker compose down -v
            echo "✅ Docker limpiado"
        fi
        ;;
    
    build-assets)
        echo "🎨 Compilando assets..."
        docker compose exec app npm install
        docker compose exec app npm run build
        echo "✅ Assets compilados"
        ;;
    
    watch-assets)
        echo "👀 Observando cambios en assets..."
        docker compose exec app npm run dev
        ;;
    
    test)
        echo "🧪 Ejecutando tests..."
        docker compose exec app php artisan test
        ;;
    
    permissions)
        echo "🔐 Configurando permisos..."
        docker compose exec app chown -R www-data:www-data /var/www/html/storage
        docker compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
        docker compose exec app chmod -R 775 /var/www/html/storage
        docker compose exec app chmod -R 775 /var/www/html/bootstrap/cache
        echo "✅ Permisos configurados"
        ;;
    
    backup-db)
        echo "💾 Creando backup de base de datos..."
        TIMESTAMP=$(date +%Y%m%d_%H%M%S)
        docker compose exec -T db mysqldump -u laravel -psecret fiftyone > "backup_${TIMESTAMP}.sql"
        echo "✅ Backup creado: backup_${TIMESTAMP}.sql"
        ;;
    
    restore-db)
        if [ -z "$2" ]; then
            echo "❌ Error: Especifica el archivo de backup"
            echo "Uso: ./docker-dev.sh restore-db backup_20260430_120000.sql"
            exit 1
        fi
        echo "📥 Restaurando base de datos desde $2..."
        docker compose exec -T db mysql -u laravel -psecret fiftyone < "$2"
        echo "✅ Base de datos restaurada"
        ;;
    
    help|*)
        echo "🐳 Comandos disponibles para desarrollo con Docker:"
        echo ""
        echo "  logs              - Ver logs en tiempo real"
        echo "  shell             - Acceder al contenedor de la app"
        echo "  mysql             - Acceder a MySQL"
        echo "  artisan [cmd]     - Ejecutar comando Artisan"
        echo "  composer [cmd]    - Ejecutar comando Composer"
        echo "  npm [cmd]         - Ejecutar comando NPM"
        echo "  migrate           - Ejecutar migraciones"
        echo "  seed              - Ejecutar seeders"
        echo "  fresh             - Reiniciar base de datos con seeders"
        echo "  cache-clear       - Limpiar toda la caché"
        echo "  optimize          - Optimizar aplicación"
        echo "  restart           - Reiniciar contenedores"
        echo "  rebuild           - Reconstruir contenedores"
        echo "  stop              - Detener contenedores"
        echo "  start             - Iniciar contenedores"
        echo "  status            - Ver estado de contenedores"
        echo "  clean             - Limpiar todo (⚠️  elimina datos)"
        echo "  build-assets      - Compilar assets de frontend"
        echo "  watch-assets      - Observar cambios en assets"
        echo "  test              - Ejecutar tests"
        echo "  permissions       - Configurar permisos"
        echo "  backup-db         - Crear backup de base de datos"
        echo "  restore-db [file] - Restaurar base de datos"
        echo "  help              - Mostrar esta ayuda"
        echo ""
        echo "Ejemplos:"
        echo "  ./docker-dev.sh logs"
        echo "  ./docker-dev.sh artisan migrate"
        echo "  ./docker-dev.sh composer require package/name"
        echo "  ./docker-dev.sh npm install"
        echo ""
        ;;
esac

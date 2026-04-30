#!/bin/bash

# Script de inicio rápido para Docker
# Ejecuta: bash docker-start.sh

echo "🐳 Iniciando FiftyOne en Docker..."
echo ""

# Verificar si Docker está corriendo
if ! docker info > /dev/null 2>&1; then
    echo "❌ Error: Docker no está corriendo. Por favor inicia Docker Desktop."
    exit 1
fi

# Construir e iniciar contenedores
echo "📦 Construyendo contenedores..."
docker compose build

echo ""
echo "▶️  Iniciando contenedores..."
docker compose up -d

echo ""
echo "⏳ Esperando que la base de datos esté lista..."
sleep 10

# Instalar dependencias
echo ""
echo "📚 Instalando dependencias de Composer..."
docker compose exec -T app composer install --optimize-autoloader --no-dev

# Generar clave si no existe
echo ""
echo "🔑 Verificando APP_KEY..."
docker compose exec -T app php artisan key:generate --show

# Crear enlace de storage
echo ""
echo "🔗 Creando enlace de storage..."
docker compose exec -T app php artisan storage:link

# Configurar permisos
echo ""
echo "🔐 Configurando permisos..."
docker compose exec -T app chown -R www-data:www-data /var/www/html/storage
docker compose exec -T app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker compose exec -T app chmod -R 775 /var/www/html/storage
docker compose exec -T app chmod -R 775 /var/www/html/bootstrap/cache

# Ejecutar migraciones
echo ""
echo "🗄️  Ejecutando migraciones..."
read -p "¿Quieres ejecutar las migraciones? (s/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[SsYy]$ ]]; then
    docker compose exec -T app php artisan migrate --force
fi

# Compilar assets
echo ""
echo "🎨 Compilando assets..."
read -p "¿Quieres compilar los assets de frontend? (s/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[SsYy]$ ]]; then
    docker compose exec -T app npm install
    docker compose exec -T app npm run build
fi

echo ""
echo "✅ ¡Listo! Tu aplicación está corriendo en:"
echo ""
echo "   🌐 Local: http://localhost:8000"
echo ""
echo "   📱 Red local: http://$(hostname -I | awk '{print $1}'):8000"
echo ""
echo "📊 Ver logs: docker compose logs -f"
echo "⏹️  Detener: docker compose stop"
echo ""

# 🐳 Guía de Docker para FiftyOne Laravel

Esta guía te ayudará a ejecutar el proyecto FiftyOne en Docker sin modificar ningún archivo de código existente.

## 🚀 Inicio Rápido

¿Tienes prisa? Usa los scripts automáticos:

**Windows:**
```bash
docker-start.bat
```

**Linux/Mac:**
```bash
chmod +x docker-start.sh
./docker-start.sh
```

O sigue la guía completa abajo ⬇️

## 📋 Requisitos Previos

- Docker Desktop instalado ([Descargar aquí](https://www.docker.com/products/docker-desktop))
- Docker Compose (incluido en Docker Desktop)
- Git (opcional, para clonar el proyecto)

## 🏗️ Arquitectura de Contenedores

El proyecto usa 2 contenedores:

1. **app** - Aplicación Laravel completa (PHP 8.2 + Nginx + Node.js)
2. **db** - Base de datos MySQL 8.0

## 🚀 Paso 1: Preparar el Proyecto

### 1.1 Copiar el archivo de entorno

```bash
# Opción A: Si ya tienes un .env configurado, úsalo
# No necesitas hacer nada

# Opción B: Si no tienes .env, copia el de ejemplo de Docker
cp .env.docker .env
```

### 1.2 Generar la clave de aplicación (si es necesario)

```bash
# Si tu .env no tiene APP_KEY, genera una después de construir los contenedores
```

## 🔨 Paso 2: Construir los Contenedores

```bash
# Construir las imágenes de Docker
docker compose build

# Esto puede tardar 5-10 minutos la primera vez
```

## ▶️ Paso 3: Iniciar los Contenedores

```bash
# Iniciar todos los contenedores en segundo plano
docker compose up -d

# Ver los logs en tiempo real (opcional)
docker compose logs -f

# Presiona Ctrl+C para salir de los logs (los contenedores seguirán corriendo)
```

## 🔑 Paso 4: Configurar la Aplicación

### 4.1 Instalar dependencias de PHP

```bash
# Entrar al contenedor de la aplicación
docker compose exec app bash

# Dentro del contenedor, instalar dependencias
composer install --optimize-autoloader --no-dev

# Salir del contenedor
exit
```

### 4.2 Generar clave de aplicación (si es necesario)

```bash
docker compose exec app php artisan key:generate
```

### 4.3 Crear el enlace de storage

```bash
docker compose exec app php artisan storage:link
```

### 4.4 Configurar permisos

```bash
docker compose exec app chown -R www-data:www-data /var/www/html/storage
docker compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker compose exec app chmod -R 775 /var/www/html/storage
docker compose exec app chmod -R 775 /var/www/html/bootstrap/cache
```

## 🗄️ Paso 5: Configurar la Base de Datos

### 5.1 Ejecutar migraciones

```bash
# Ejecutar todas las migraciones
docker compose exec app php artisan migrate

# Si quieres ejecutar migraciones con datos de prueba
docker compose exec app php artisan migrate:fresh --seed
```

### 5.2 Ejecutar seeders específicos (opcional)

```bash
# Ejecutar un seeder específico
docker compose exec app php artisan db:seed --class=ResetCredentialsSeeder

# Ejecutar todos los seeders
docker compose exec app php artisan db:seed
```

## 🌐 Paso 6: Acceder a la Aplicación

### Desde el mismo equipo:

```
http://localhost:8000
```

### Desde otro equipo en la red local:

1. **Obtener la IP del host:**

   **En Windows:**
   ```bash
   ipconfig
   # Busca "Dirección IPv4" en tu adaptador de red activo
   # Ejemplo: 192.168.1.100
   ```

   **En Linux/Mac:**
   ```bash
   ip addr show
   # o
   ifconfig
   # Busca la IP de tu interfaz de red (eth0, wlan0, etc.)
   ```

2. **Acceder desde otro equipo:**
   ```
   http://192.168.1.100:8000
   ```
   (Reemplaza `192.168.1.100` con tu IP real)

3. **Asegúrate de que el firewall permita conexiones al puerto 8000**

   **En Windows:**
   - Abre "Firewall de Windows Defender"
   - Clic en "Configuración avanzada"
   - Reglas de entrada → Nueva regla
   - Puerto → TCP → 8000 → Permitir conexión

## 🔍 Verificar Configuración

Antes de iniciar, puedes verificar que todo está configurado correctamente:

**Windows:**
```bash
docker-check.bat
```

**Linux/Mac:**
```bash
chmod +x docker-check.sh
./docker-check.sh
```

Este script verifica:
- ✅ Docker instalado y corriendo
- ✅ Archivos de configuración presentes
- ✅ Estado de contenedores
- ✅ Puertos disponibles
- ✅ Volúmenes creados

## 🛠️ Scripts de Desarrollo

Para facilitar el desarrollo, se incluyen scripts con comandos útiles:

**Windows:**
```bash
# Ver todos los comandos disponibles
docker-dev.bat help

# Ejemplos de uso
docker-dev.bat logs              # Ver logs
docker-dev.bat shell             # Acceder al contenedor
docker-dev.bat artisan migrate   # Ejecutar migraciones
docker-dev.bat cache-clear       # Limpiar caché
```

**Linux/Mac:**
```bash
# Dar permisos de ejecución
chmod +x docker-dev.sh

# Ver todos los comandos disponibles
./docker-dev.sh help

# Ejemplos de uso
./docker-dev.sh logs              # Ver logs
./docker-dev.sh shell             # Acceder al contenedor
./docker-dev.sh artisan migrate   # Ejecutar migraciones
./docker-dev.sh cache-clear       # Limpiar caché
```

## 🛠️ Comandos Útiles

### Ver estado de los contenedores

```bash
docker compose ps
```

### Ver logs

```bash
# Logs de todos los contenedores
docker compose logs

# Logs de un contenedor específico
docker compose logs app
docker compose logs db

# Seguir logs en tiempo real
docker compose logs -f app
```

### Ejecutar comandos de Artisan

```bash
# Limpiar caché
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear

# Optimizar para producción
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

### Ejecutar comandos de Composer

```bash
# Instalar una nueva dependencia
docker compose exec app composer require nombre/paquete

# Actualizar dependencias
docker compose exec app composer update
```

### Ejecutar comandos de NPM

```bash
# Instalar dependencias de Node
docker compose exec app npm install

# Compilar assets
docker compose exec app npm run build

# Modo desarrollo con watch
docker compose exec app npm run dev
```

### Acceder a MySQL desde el host

```bash
# Usando MySQL Workbench o cualquier cliente MySQL
Host: localhost
Port: 3307  # ⚠️ Nota: Puerto 3307, no 3306
Database: fiftyone
Username: laravel
Password: secret

# O desde línea de comandos
mysql -h 127.0.0.1 -P 3307 -u laravel -p
# Contraseña: secret
```

### Acceder a MySQL desde dentro del contenedor

```bash
docker compose exec db mysql -u laravel -p fiftyone
# Contraseña: secret
```

## 🔄 Reiniciar los Contenedores

```bash
# Reiniciar todos los contenedores
docker compose restart

# Reiniciar un contenedor específico
docker compose restart app
docker compose restart db
```

## ⏹️ Detener los Contenedores

```bash
# Detener sin eliminar
docker compose stop

# Detener y eliminar contenedores (los datos persisten)
docker compose down

# Detener, eliminar contenedores Y volúmenes (⚠️ ELIMINA LA BASE DE DATOS)
docker compose down -v
```

## 🧹 Limpiar Todo

```bash
# Eliminar contenedores, redes e imágenes
docker compose down --rmi all

# Eliminar TODO incluyendo volúmenes (⚠️ CUIDADO: Elimina la base de datos)
docker compose down -v --rmi all

# Limpiar sistema Docker completo (opcional)
docker system prune -a --volumes
```

## 🐛 Solución de Problemas

### Error: "Port 8000 is already in use"

```bash
# Cambiar el puerto en docker-compose.yml
ports:
  - "8080:80"  # Usa 8080 en lugar de 8000
```

### Error: "Port 3307 is already in use"

```bash
# Cambiar el puerto de MySQL en docker-compose.yml
ports:
  - "3308:3306"  # Usa 3308 en lugar de 3307
```

### Error de permisos en storage/

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### La aplicación no carga o muestra error 500

```bash
# Verificar logs
docker compose logs app

# Limpiar caché
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear

# Verificar permisos
docker compose exec app ls -la storage/
```

### No puedo conectarme desde otro equipo

1. Verifica que el firewall permita el puerto 8000
2. Asegúrate de usar la IP correcta del host
3. Verifica que ambos equipos estén en la misma red
4. Prueba hacer ping al host desde el otro equipo

### La base de datos no se conecta

```bash
# Verificar que el contenedor de DB esté corriendo
docker compose ps

# Ver logs de MySQL
docker compose logs db

# Verificar variables de entorno
docker compose exec app env | grep DB_
```

## 📊 Monitoreo

### Ver uso de recursos

```bash
# Ver estadísticas en tiempo real
docker stats

# Ver solo los contenedores de FiftyOne
docker stats fiftyone_app fiftyone_db
```

### Inspeccionar contenedores

```bash
# Ver detalles de un contenedor
docker inspect fiftyone_app

# Ver la red
docker network inspect fiftyone_fiftyone_network
```

## 🔐 Seguridad

### Para producción, cambia las credenciales:

1. Edita `docker-compose.yml` y cambia:
   - `MYSQL_ROOT_PASSWORD`
   - `MYSQL_PASSWORD`
   - `DB_PASSWORD`

2. Actualiza tu `.env` con las mismas credenciales

3. Reconstruye los contenedores:
   ```bash
   docker compose down -v
   docker compose up -d
   ```

## 🚀 Despliegue en Producción

Para producción, usa el archivo `docker-compose.prod.yml`:

### 1. Configurar variables de entorno

```bash
# Copiar el archivo de ejemplo
cp .env.docker.production .env

# Editar y configurar:
# - APP_KEY (generar con: php artisan key:generate)
# - DB_PASSWORD (contraseña segura)
# - MAIL_* (configuración de correo)
# - APP_URL (tu dominio)
```

### 2. Configurar credenciales de base de datos

```bash
# Crear archivo .env.prod con las credenciales
echo "DB_PASSWORD=tu_contraseña_segura" > .env.prod
echo "MYSQL_PASSWORD=tu_contraseña_segura" >> .env.prod
echo "MYSQL_ROOT_PASSWORD=tu_contraseña_root_segura" >> .env.prod
```

### 3. Iniciar en modo producción

```bash
# Construir e iniciar
docker compose -f docker-compose.prod.yml --env-file .env.prod up -d

# Instalar dependencias de producción
docker compose -f docker-compose.prod.yml exec app composer install --optimize-autoloader --no-dev

# Compilar assets
docker compose -f docker-compose.prod.yml exec app npm install
docker compose -f docker-compose.prod.yml exec app npm run build

# Ejecutar migraciones
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Optimizar aplicación
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache
```

### 4. Configurar SSL/HTTPS (Opcional)

Para habilitar HTTPS, necesitas:
1. Certificados SSL (Let's Encrypt, Cloudflare, etc.)
2. Modificar `docker/nginx/default.conf` para incluir configuración SSL
3. Mapear el puerto 443 en `docker-compose.prod.yml`

### 5. Monitoreo en Producción

```bash
# Ver logs
docker compose -f docker-compose.prod.yml logs -f

# Ver estado de salud
docker compose -f docker-compose.prod.yml ps

# Ver uso de recursos
docker stats
```

## 📝 Notas Importantes

- ✅ El código fuente NO se copia al contenedor, usa bind mount (cambios en tiempo real)
- ✅ Los datos de MySQL persisten en un volumen nombrado
- ✅ El puerto 3307 en el host mapea al 3306 del contenedor
- ✅ Puedes seguir editando el código normalmente, los cambios se reflejan inmediatamente
- ✅ Los archivos `vendor/` y `node_modules/` usan volúmenes para mejor rendimiento

## 🆘 Soporte

Si tienes problemas:

1. Revisa los logs: `docker compose logs -f`
2. Verifica el estado: `docker compose ps`
3. Reinicia los contenedores: `docker compose restart`
4. Reconstruye si es necesario: `docker compose up -d --build`

---

**¡Listo!** Tu aplicación FiftyOne ahora corre en Docker 🎉

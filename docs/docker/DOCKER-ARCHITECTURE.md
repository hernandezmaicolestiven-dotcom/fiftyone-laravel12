# 🏗️ Arquitectura Docker - FiftyOne

Este documento explica la arquitectura de contenedores del proyecto FiftyOne.

## 📊 Diagrama de Arquitectura

```
┌─────────────────────────────────────────────────────────────────┐
│                         HOST MACHINE                             │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │                    Docker Network                           │ │
│  │                  (fiftyone_network)                         │ │
│  │                                                             │ │
│  │  ┌──────────────────────────┐  ┌──────────────────────┐   │ │
│  │  │   Container: app         │  │  Container: db       │   │ │
│  │  │   (fiftyone_app)         │  │  (fiftyone_db)       │   │ │
│  │  │                          │  │                      │   │ │
│  │  │  ┌────────────────────┐  │  │  ┌────────────────┐ │   │ │
│  │  │  │   Supervisor       │  │  │  │   MySQL 8.0    │ │   │ │
│  │  │  │                    │  │  │  │                │ │   │ │
│  │  │  │  ┌──────────────┐  │  │  │  │  Database:     │ │   │ │
│  │  │  │  │   Nginx      │  │  │  │  │  fiftyone      │ │   │ │
│  │  │  │  │   (Port 80)  │  │  │  │  │                │ │   │ │
│  │  │  │  └──────────────┘  │  │  │  │  User: laravel │ │   │ │
│  │  │  │                    │  │  │  │  Pass: secret  │ │   │ │
│  │  │  │  ┌──────────────┐  │  │  │  └────────────────┘ │   │ │
│  │  │  │  │  PHP-FPM     │  │  │  │                      │   │ │
│  │  │  │  │  (PHP 8.2)   │  │  │  │  Port: 3306        │   │ │
│  │  │  │  └──────────────┘  │  │  │  (internal)         │   │ │
│  │  │  └────────────────────┘  │  │                      │   │ │
│  │  │                          │  │  Volume:             │   │ │
│  │  │  Composer                │  │  mysql_data          │   │ │
│  │  │  Node.js + NPM           │  │  (persistent)        │   │ │
│  │  │                          │  │                      │   │ │
│  │  │  Bind Mount:             │  └──────────────────────┘   │ │
│  │  │  ./  →  /var/www/html    │                             │ │
│  │  │                          │                             │ │
│  │  │  Volumes:                │                             │ │
│  │  │  - vendor                │                             │ │
│  │  │  - node_modules          │                             │ │
│  │  │                          │                             │ │
│  │  │  Port: 80 (internal)     │                             │ │
│  │  └──────────────────────────┘                             │ │
│  │                                                            │ │
│  └────────────────────────────────────────────────────────────┘ │
│                                                                  │
│  Port Mappings:                                                 │
│  - 8000:80   (app → host)                                       │
│  - 3307:3306 (db → host)                                        │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
         │                                    │
         │                                    │
         ▼                                    ▼
   http://localhost:8000            mysql://localhost:3307
   (Aplicación Web)                 (Base de Datos)
```

## 🔧 Componentes

### 1. Contenedor `app` (Aplicación)

**Imagen base:** `php:8.2-fpm`

**Servicios incluidos:**
- **Nginx** - Servidor web (puerto 80)
- **PHP-FPM** - Procesador PHP
- **Supervisor** - Gestiona Nginx y PHP-FPM
- **Composer** - Gestor de dependencias PHP
- **Node.js + NPM** - Para compilar assets

**Extensiones PHP:**
- pdo_mysql
- mbstring
- exif
- pcntl
- bcmath
- gd

**Volúmenes:**
- Bind mount: `./` → `/var/www/html` (código fuente)
- Named volume: `vendor` (dependencias PHP)
- Named volume: `node_modules` (dependencias Node)

**Puerto expuesto:**
- 80 (interno) → 8000 (host)

### 2. Contenedor `db` (Base de Datos)

**Imagen:** `mysql:8.0`

**Configuración:**
- Database: `fiftyone`
- User: `laravel`
- Password: `secret`
- Root Password: `root`

**Volúmenes:**
- Named volume: `mysql_data` (datos persistentes)
- Config mount: `docker/mysql/my.cnf` (configuración)

**Puerto expuesto:**
- 3306 (interno) → 3307 (host)

## 🌐 Red

**Nombre:** `fiftyone_network`  
**Driver:** `bridge`

Los contenedores se comunican entre sí usando nombres de servicio:
- `app` puede acceder a `db` usando el hostname `db`
- `db` puede acceder a `app` usando el hostname `app`

## 💾 Volúmenes

### Named Volumes (Gestionados por Docker)

| Volumen | Propósito | Persistencia |
|---------|-----------|--------------|
| `mysql_data` | Datos de MySQL | ✅ Persiste entre reinicios |
| `vendor` | Dependencias PHP | ✅ Mejor rendimiento |
| `node_modules` | Dependencias Node | ✅ Mejor rendimiento |

### Bind Mounts (Carpetas del host)

| Host | Contenedor | Propósito |
|------|------------|-----------|
| `./` | `/var/www/html` | Código fuente (desarrollo en tiempo real) |
| `docker/nginx/default.conf` | `/etc/nginx/sites-available/default` | Config Nginx |
| `docker/supervisor/supervisord.conf` | `/etc/supervisor/conf.d/supervisord.conf` | Config Supervisor |
| `docker/mysql/my.cnf` | `/etc/mysql/conf.d/my.cnf` | Config MySQL |

## 🔄 Flujo de Peticiones

```
1. Usuario → http://localhost:8000
                    ↓
2. Host → Puerto 8000 mapeado a puerto 80 del contenedor app
                    ↓
3. Nginx (contenedor app) → Recibe la petición HTTP
                    ↓
4. Nginx → Verifica si es archivo estático (CSS, JS, imágenes)
   ├─ Sí → Sirve directamente desde /var/www/html/public
   └─ No → Pasa a PHP-FPM
                    ↓
5. PHP-FPM → Ejecuta Laravel (index.php)
                    ↓
6. Laravel → Procesa la petición
   ├─ Rutas
   ├─ Middleware
   ├─ Controladores
   └─ Vistas
                    ↓
7. Laravel → Conecta a MySQL si es necesario
   └─ Hostname: db
   └─ Puerto: 3306 (interno)
                    ↓
8. MySQL (contenedor db) → Ejecuta consultas
                    ↓
9. Laravel → Genera respuesta HTML/JSON
                    ↓
10. PHP-FPM → Devuelve respuesta a Nginx
                    ↓
11. Nginx → Envía respuesta al usuario
                    ↓
12. Usuario → Ve la página
```

## 🔌 Conexiones

### Desde el Host (tu computadora)

**Aplicación Web:**
```
URL: http://localhost:8000
```

**Base de Datos:**
```
Host: localhost (o 127.0.0.1)
Port: 3307
Database: fiftyone
Username: laravel
Password: secret
```

### Desde el Contenedor `app`

**Base de Datos:**
```
Host: db
Port: 3306
Database: fiftyone
Username: laravel
Password: secret
```

### Desde Otro Equipo en la Red

**Aplicación Web:**
```
URL: http://IP_DEL_HOST:8000
Ejemplo: http://192.168.1.100:8000
```

**Base de Datos:**
```
Host: IP_DEL_HOST
Port: 3307
Database: fiftyone
Username: laravel
Password: secret
```

## 🚀 Proceso de Inicio

```
1. docker compose up -d
   ↓
2. Docker crea la red fiftyone_network
   ↓
3. Docker crea los volúmenes (si no existen)
   ↓
4. Docker inicia el contenedor db
   ├─ MySQL se inicializa
   ├─ Crea la base de datos fiftyone
   └─ Crea el usuario laravel
   ↓
5. Docker inicia el contenedor app
   ├─ Supervisor inicia
   ├─ PHP-FPM inicia (puerto 9000)
   └─ Nginx inicia (puerto 80)
   ↓
6. Contenedores listos
   ├─ app espera conexiones en puerto 80
   └─ db espera conexiones en puerto 3306
   ↓
7. Docker mapea puertos al host
   ├─ 8000 → app:80
   └─ 3307 → db:3306
   ↓
8. Sistema listo para usar
```

## 📦 Gestión de Dependencias

### Composer (PHP)

```bash
# Instalar dependencias
docker compose exec app composer install

# Agregar paquete
docker compose exec app composer require vendor/package

# Actualizar dependencias
docker compose exec app composer update
```

### NPM (Node.js)

```bash
# Instalar dependencias
docker compose exec app npm install

# Compilar assets
docker compose exec app npm run build

# Modo desarrollo
docker compose exec app npm run dev
```

## 🔐 Seguridad

### Aislamiento

- Cada contenedor corre en su propio espacio aislado
- Los contenedores solo pueden comunicarse a través de la red Docker
- El código fuente no se copia al contenedor (bind mount)

### Puertos

- Solo los puertos mapeados son accesibles desde el host
- Puerto 9000 (PHP-FPM) no está expuesto al host
- Puerto 3306 (MySQL interno) no está expuesto al host

### Credenciales

⚠️ **Importante:** Las credenciales por defecto son para desarrollo.

**Para producción, DEBES cambiar:**
- `DB_PASSWORD`
- `MYSQL_PASSWORD`
- `MYSQL_ROOT_PASSWORD`
- `APP_KEY`

## 🔄 Ciclo de Vida

### Desarrollo

```bash
# Iniciar
docker compose up -d

# Trabajar normalmente
# Los cambios en el código se reflejan inmediatamente

# Ver logs
docker compose logs -f

# Detener
docker compose stop

# Reiniciar
docker compose restart
```

### Producción

```bash
# Iniciar con configuración de producción
docker compose -f docker-compose.prod.yml up -d

# Optimizar
docker compose exec app php artisan optimize

# Monitorear
docker compose logs -f
docker stats
```

## 📊 Monitoreo

### Ver Estado

```bash
# Estado de contenedores
docker compose ps

# Uso de recursos
docker stats

# Logs en tiempo real
docker compose logs -f

# Logs de un servicio específico
docker compose logs -f app
docker compose logs -f db
```

### Healthchecks (Producción)

El archivo `docker-compose.prod.yml` incluye healthchecks:

**App:**
- Verifica que Nginx responda en el puerto 80
- Intervalo: 30 segundos
- Timeout: 10 segundos
- Reintentos: 3

**DB:**
- Verifica que MySQL responda a `mysqladmin ping`
- Intervalo: 10 segundos
- Timeout: 5 segundos
- Reintentos: 5

## 🛠️ Mantenimiento

### Backups

```bash
# Backup de base de datos
docker compose exec -T db mysqldump -u laravel -psecret fiftyone > backup.sql

# Restaurar backup
docker compose exec -T db mysql -u laravel -psecret fiftyone < backup.sql
```

### Limpieza

```bash
# Detener y eliminar contenedores
docker compose down

# Eliminar también volúmenes (⚠️ elimina datos)
docker compose down -v

# Limpiar imágenes no usadas
docker image prune -a

# Limpiar todo el sistema Docker
docker system prune -a --volumes
```

### Actualización

```bash
# Reconstruir imágenes
docker compose build --no-cache

# Reiniciar con nuevas imágenes
docker compose up -d --build
```

## 📚 Referencias

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Laravel Docker Best Practices](https://laravel.com/docs/deployment#docker)
- [Nginx Configuration](https://nginx.org/en/docs/)
- [MySQL Docker Hub](https://hub.docker.com/_/mysql)

---

**Última actualización:** 30 de Abril de 2026


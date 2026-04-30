# 📁 Archivos Docker del Proyecto

Este documento lista todos los archivos relacionados con Docker en el proyecto FiftyOne.

## 🐳 Archivos Principales

### Configuración de Contenedores

| Archivo | Descripción |
|---------|-------------|
| `Dockerfile` | Define la imagen del contenedor de la aplicación (PHP 8.2 + Nginx + Node.js) |
| `docker-compose.yml` | Configuración de desarrollo (app + db) |
| `docker-compose.prod.yml` | Configuración de producción con healthchecks |
| `.dockerignore` | Archivos a ignorar al construir la imagen |

### Variables de Entorno

| Archivo | Descripción |
|---------|-------------|
| `.env.docker` | Variables de entorno para desarrollo en Docker |
| `.env.docker.production` | Variables de entorno para producción |

## 📂 Carpeta docker/

### Configuraciones de Servicios

| Archivo | Descripción |
|---------|-------------|
| `docker/nginx/default.conf` | Configuración de Nginx para Laravel |
| `docker/supervisor/supervisord.conf` | Supervisor para gestionar PHP-FPM y Nginx |
| `docker/mysql/my.cnf` | Configuración optimizada de MySQL |
| `docker/README.md` | Documentación de la carpeta docker |
| `docker/.gitignore` | Archivos a ignorar en la carpeta docker |

## 🚀 Scripts de Inicio

### Scripts Automáticos

| Archivo | Plataforma | Descripción |
|---------|------------|-------------|
| `docker-start.sh` | Linux/Mac | Script de inicio rápido con configuración automática |
| `docker-start.bat` | Windows | Script de inicio rápido con configuración automática |

### Scripts de Verificación

| Archivo | Plataforma | Descripción |
|---------|------------|-------------|
| `docker-check.sh` | Linux/Mac | Verifica la configuración de Docker |
| `docker-check.bat` | Windows | Verifica la configuración de Docker |

### Scripts de Desarrollo

| Archivo | Plataforma | Descripción |
|---------|------------|-------------|
| `docker-dev.sh` | Linux/Mac | Comandos útiles para desarrollo (logs, shell, artisan, etc.) |
| `docker-dev.bat` | Windows | Comandos útiles para desarrollo (logs, shell, artisan, etc.) |

## 📚 Documentación

| Archivo | Descripción |
|---------|-------------|
| `DOCKER.md` | Guía completa de Docker (instalación, uso, troubleshooting) |
| `DOCKER-QUICKSTART.md` | Guía de inicio rápido (3 minutos) |
| `DOCKER-FILES.md` | Este archivo - índice de archivos Docker |

## 🎯 Flujo de Trabajo Recomendado

### Para Desarrollo

1. **Inicio rápido:**
   ```bash
   # Windows
   docker-start.bat
   
   # Linux/Mac
   ./docker-start.sh
   ```

2. **Verificar estado:**
   ```bash
   # Windows
   docker-check.bat
   
   # Linux/Mac
   ./docker-check.sh
   ```

3. **Comandos de desarrollo:**
   ```bash
   # Windows
   docker-dev.bat [comando]
   
   # Linux/Mac
   ./docker-dev.sh [comando]
   ```

### Para Producción

1. **Configurar entorno:**
   ```bash
   cp .env.docker.production .env
   # Editar .env con tus credenciales
   ```

2. **Iniciar en producción:**
   ```bash
   docker compose -f docker-compose.prod.yml up -d
   ```

3. **Optimizar:**
   ```bash
   docker compose -f docker-compose.prod.yml exec app php artisan optimize
   ```

## 📊 Estructura Visual

```
proyecto/
├── 🐳 Docker Core
│   ├── Dockerfile
│   ├── docker-compose.yml
│   ├── docker-compose.prod.yml
│   └── .dockerignore
│
├── 🔧 Configuraciones
│   └── docker/
│       ├── nginx/
│       │   └── default.conf
│       ├── supervisor/
│       │   └── supervisord.conf
│       ├── mysql/
│       │   └── my.cnf
│       └── README.md
│
├── 🌍 Variables de Entorno
│   ├── .env.docker
│   └── .env.docker.production
│
├── 🚀 Scripts
│   ├── docker-start.sh / .bat
│   ├── docker-check.sh / .bat
│   └── docker-dev.sh / .bat
│
└── 📚 Documentación
    ├── DOCKER.md
    ├── DOCKER-QUICKSTART.md
    └── DOCKER-FILES.md (este archivo)
```

## 🔍 Búsqueda Rápida

### ¿Necesitas...?

- **Iniciar el proyecto:** → `docker-start.sh` o `docker-start.bat`
- **Ver si todo está bien:** → `docker-check.sh` o `docker-check.bat`
- **Ejecutar comandos:** → `docker-dev.sh` o `docker-dev.bat`
- **Configurar Nginx:** → `docker/nginx/default.conf`
- **Configurar MySQL:** → `docker/mysql/my.cnf`
- **Variables de entorno:** → `.env.docker` o `.env.docker.production`
- **Guía completa:** → `DOCKER.md`
- **Inicio rápido:** → `DOCKER-QUICKSTART.md`

## ⚠️ Archivos que NO debes modificar

A menos que sepas lo que estás haciendo:

- ❌ `Dockerfile` - Imagen base del contenedor
- ❌ `docker/supervisor/supervisord.conf` - Gestión de procesos
- ❌ `docker/nginx/default.conf` - Configuración de Nginx

## ✅ Archivos que SÍ puedes modificar

- ✅ `.env.docker` - Variables de entorno de desarrollo
- ✅ `.env.docker.production` - Variables de entorno de producción
- ✅ `docker/mysql/my.cnf` - Optimización de MySQL
- ✅ `docker-compose.yml` - Puertos, volúmenes, etc.

## 🆘 Ayuda

Si tienes problemas:

1. Revisa `DOCKER.md` - Sección "Solución de Problemas"
2. Ejecuta `docker-check.sh` o `docker-check.bat`
3. Revisa los logs: `docker-dev.sh logs` o `docker-dev.bat logs`

---

**Última actualización:** 30 de Abril de 2026


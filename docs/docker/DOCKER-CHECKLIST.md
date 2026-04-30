# ✅ Checklist de Docker - FiftyOne

## 📋 Verificación Pre-Inicio

Marca cada ítem antes de iniciar los contenedores:

### 1. Requisitos del Sistema

- [ ] Docker Desktop instalado
- [ ] Docker Desktop está corriendo
- [ ] Docker Compose disponible (viene con Docker Desktop)
- [ ] Al menos 4GB de RAM disponible
- [ ] Al menos 10GB de espacio en disco

**Verificar:**
```bash
docker --version
docker compose version
docker info
```

### 2. Archivos de Configuración

- [ ] `Dockerfile` existe
- [ ] `docker-compose.yml` existe
- [ ] `docker/nginx/default.conf` existe
- [ ] `docker/supervisor/supervisord.conf` existe
- [ ] `docker/mysql/my.cnf` existe
- [ ] `.env` o `.env.docker` existe

**Verificar:**
```bash
# Windows
docker-check.bat

# Linux/Mac
./docker-check.sh
```

### 3. Puertos Disponibles

- [ ] Puerto 8000 está libre (o cambiar en docker-compose.yml)
- [ ] Puerto 3307 está libre (o cambiar en docker-compose.yml)

**Verificar puertos en uso:**

**Windows:**
```bash
netstat -an | findstr ":8000"
netstat -an | findstr ":3307"
```

**Linux/Mac:**
```bash
lsof -i :8000
lsof -i :3307
```

Si están ocupados, edita `docker-compose.yml` y cambia los puertos.

### 4. Permisos de Scripts (Linux/Mac)

- [ ] Scripts tienen permisos de ejecución

**Dar permisos:**
```bash
chmod +x docker-start.sh
chmod +x docker-check.sh
chmod +x docker-dev.sh
```

## 🚀 Checklist de Inicio

### Opción A: Inicio Automático

- [ ] Ejecutar script de inicio
  ```bash
  # Windows
  docker-start.bat
  
  # Linux/Mac
  ./docker-start.sh
  ```

- [ ] Esperar a que termine (puede tardar 5-10 minutos la primera vez)
- [ ] Verificar que no hay errores en la salida

### Opción B: Inicio Manual

- [ ] 1. Construir imágenes
  ```bash
  docker compose build
  ```

- [ ] 2. Iniciar contenedores
  ```bash
  docker compose up -d
  ```

- [ ] 3. Esperar 10 segundos para que MySQL inicie
  ```bash
  # Windows
  timeout /t 10
  
  # Linux/Mac
  sleep 10
  ```

- [ ] 4. Instalar dependencias de Composer
  ```bash
  docker compose exec app composer install --optimize-autoloader --no-dev
  ```

- [ ] 5. Generar APP_KEY (si no existe)
  ```bash
  docker compose exec app php artisan key:generate
  ```

- [ ] 6. Crear enlace de storage
  ```bash
  docker compose exec app php artisan storage:link
  ```

- [ ] 7. Configurar permisos
  ```bash
  docker compose exec app chown -R www-data:www-data /var/www/html/storage
  docker compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
  docker compose exec app chmod -R 775 /var/www/html/storage
  docker compose exec app chmod -R 775 /var/www/html/bootstrap/cache
  ```

- [ ] 8. Ejecutar migraciones
  ```bash
  docker compose exec app php artisan migrate
  ```

- [ ] 9. (Opcional) Ejecutar seeders
  ```bash
  docker compose exec app php artisan db:seed
  ```

- [ ] 10. (Opcional) Compilar assets
  ```bash
  docker compose exec app npm install
  docker compose exec app npm run build
  ```

## ✅ Verificación Post-Inicio

### 1. Contenedores Corriendo

- [ ] Contenedor `fiftyone_app` está corriendo
- [ ] Contenedor `fiftyone_db` está corriendo

**Verificar:**
```bash
docker compose ps
```

Deberías ver ambos contenedores con estado "Up".

### 2. Logs Sin Errores

- [ ] No hay errores en los logs de app
- [ ] No hay errores en los logs de db

**Verificar:**
```bash
docker compose logs app
docker compose logs db
```

### 3. Aplicación Accesible

- [ ] La aplicación carga en http://localhost:8000
- [ ] No hay error 500 o 404
- [ ] Los estilos CSS cargan correctamente
- [ ] Las imágenes cargan correctamente

**Verificar en el navegador:**
```
http://localhost:8000
```

### 4. Base de Datos Conectada

- [ ] La aplicación se conecta a la base de datos
- [ ] Las migraciones se ejecutaron correctamente
- [ ] Puedes acceder a MySQL desde el host

**Verificar conexión:**
```bash
docker compose exec app php artisan migrate:status
```

**Conectar a MySQL:**
```bash
mysql -h 127.0.0.1 -P 3307 -u laravel -p
# Contraseña: secret
```

### 5. Volúmenes Creados

- [ ] Volumen `mysql_data` existe
- [ ] Volumen `vendor` existe
- [ ] Volumen `node_modules` existe

**Verificar:**
```bash
docker volume ls | grep fiftyone
```

## 🌐 Checklist de Acceso Remoto

Para acceder desde otro equipo en la red:

### 1. Obtener IP del Host

- [ ] Obtener la IP local del host

**Windows:**
```bash
ipconfig
# Buscar "Dirección IPv4"
```

**Linux/Mac:**
```bash
hostname -I
# o
ip addr show
```

### 2. Configurar Firewall

- [ ] Permitir puerto 8000 en el firewall

**Windows:**
1. Abrir "Firewall de Windows Defender"
2. Configuración avanzada
3. Reglas de entrada → Nueva regla
4. Puerto → TCP → 8000 → Permitir conexión

**Linux:**
```bash
sudo ufw allow 8000/tcp
```

### 3. Probar Acceso

- [ ] Acceder desde otro equipo: `http://TU_IP:8000`
- [ ] La aplicación carga correctamente
- [ ] Todas las funcionalidades funcionan

## 🔧 Checklist de Desarrollo

### Comandos Básicos Funcionando

- [ ] Ver logs: `docker compose logs -f`
- [ ] Acceder al contenedor: `docker compose exec app bash`
- [ ] Ejecutar Artisan: `docker compose exec app php artisan`
- [ ] Ejecutar Composer: `docker compose exec app composer`
- [ ] Ejecutar NPM: `docker compose exec app npm`

### Scripts de Desarrollo

- [ ] `docker-dev.sh` o `docker-dev.bat` funciona
- [ ] Comando `logs` funciona
- [ ] Comando `shell` funciona
- [ ] Comando `artisan` funciona
- [ ] Comando `cache-clear` funciona

## 🐛 Checklist de Troubleshooting

Si algo no funciona:

- [ ] Revisar logs: `docker compose logs -f`
- [ ] Verificar estado: `docker compose ps`
- [ ] Verificar puertos: `netstat -an | findstr ":8000"`
- [ ] Verificar permisos: `docker compose exec app ls -la storage/`
- [ ] Limpiar caché: `docker compose exec app php artisan cache:clear`
- [ ] Reiniciar contenedores: `docker compose restart`
- [ ] Reconstruir si es necesario: `docker compose up -d --build`

## 📊 Checklist de Rendimiento

- [ ] La aplicación carga en menos de 3 segundos
- [ ] Las consultas a la base de datos son rápidas
- [ ] Los assets (CSS/JS) cargan correctamente
- [ ] No hay memory leaks en los contenedores

**Verificar uso de recursos:**
```bash
docker stats
```

## 🔐 Checklist de Seguridad (Producción)

Si vas a usar en producción:

- [ ] Cambiar `DB_PASSWORD` en `.env`
- [ ] Cambiar `MYSQL_PASSWORD` en `docker-compose.yml`
- [ ] Cambiar `MYSQL_ROOT_PASSWORD` en `docker-compose.yml`
- [ ] Generar nuevo `APP_KEY`
- [ ] Configurar `APP_DEBUG=false`
- [ ] Configurar `APP_ENV=production`
- [ ] Configurar correo electrónico real
- [ ] Configurar SSL/HTTPS
- [ ] Configurar backups automáticos
- [ ] Configurar monitoreo

## ✨ Checklist Final

- [ ] Todo funciona correctamente
- [ ] La documentación está clara
- [ ] Los scripts funcionan
- [ ] Puedes acceder desde otro equipo
- [ ] Los datos persisten después de reiniciar
- [ ] Sabes cómo detener y reiniciar los contenedores

## 🎉 ¡Listo!

Si todos los ítems están marcados, tu proyecto FiftyOne está completamente dockerizado y funcionando.

---

## 📝 Notas

- Este checklist es para verificación inicial
- Guarda este archivo para futuras referencias
- Si algo falla, revisa [DOCKER.md](DOCKER.md) para soluciones

---

**Última actualización:** 30 de Abril de 2026


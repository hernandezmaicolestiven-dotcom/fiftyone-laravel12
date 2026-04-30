# ❓ Preguntas Frecuentes - Docker

## 🐳 General

### ¿Qué es Docker?

Docker es una plataforma que permite ejecutar aplicaciones en contenedores aislados. Piensa en un contenedor como una "caja" que incluye todo lo necesario para ejecutar una aplicación (código, dependencias, configuración).

### ¿Por qué usar Docker para FiftyOne?

- ✅ **No necesitas instalar** PHP, MySQL, Composer, Node.js en tu máquina
- ✅ **Funciona igual** en Windows, Mac y Linux
- ✅ **Fácil de compartir** con otros desarrolladores
- ✅ **Aislado** - No interfiere con otras instalaciones
- ✅ **Portátil** - Lleva tu proyecto a cualquier máquina

### ¿Necesito conocimientos de Docker?

No. Los scripts automáticos (`docker-start.bat` o `docker-start.sh`) hacen todo por ti. Solo necesitas:
1. Instalar Docker Desktop
2. Ejecutar el script
3. Listo

## 🚀 Instalación

### ¿Cómo instalo Docker?

1. Descarga Docker Desktop: https://www.docker.com/products/docker-desktop
2. Instala siguiendo el asistente
3. Reinicia tu computadora
4. Abre Docker Desktop y espera a que inicie

### ¿Docker Desktop es gratis?

Sí, para uso personal y pequeñas empresas (menos de 250 empleados y menos de $10M en ingresos anuales).

### ¿Cuánto espacio necesito?

- Docker Desktop: ~500MB
- Imágenes del proyecto: ~1GB
- Volúmenes de datos: ~500MB
- **Total:** ~2GB

### ¿Cuánta RAM necesito?

Mínimo 4GB de RAM. Recomendado 8GB o más.

## 🔧 Uso

### ¿Cómo inicio el proyecto?

**Opción 1 - Automático (recomendado):**
```bash
# Windows
docker-start.bat

# Linux/Mac
./docker-start.sh
```

**Opción 2 - Manual:**
```bash
docker compose up -d
```

### ¿Cómo detengo el proyecto?

```bash
docker compose stop
```

### ¿Cómo reinicio el proyecto?

```bash
docker compose restart
```

### ¿Cómo veo los logs?

```bash
docker compose logs -f
```

Presiona `Ctrl+C` para salir (los contenedores siguen corriendo).

### ¿Cómo accedo al contenedor?

```bash
docker compose exec app bash
```

Para salir, escribe `exit`.

### ¿Cómo ejecuto comandos de Artisan?

```bash
docker compose exec app php artisan [comando]
```

Ejemplos:
```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan cache:clear
docker compose exec app php artisan db:seed
```

### ¿Cómo ejecuto comandos de Composer?

```bash
docker compose exec app composer [comando]
```

Ejemplos:
```bash
docker compose exec app composer install
docker compose exec app composer require vendor/package
docker compose exec app composer update
```

### ¿Cómo ejecuto comandos de NPM?

```bash
docker compose exec app npm [comando]
```

Ejemplos:
```bash
docker compose exec app npm install
docker compose exec app npm run build
docker compose exec app npm run dev
```

## 🌐 Acceso

### ¿En qué URL accedo a la aplicación?

```
http://localhost:8000
```

### ¿Puedo cambiar el puerto 8000?

Sí. Edita `docker-compose.yml`:

```yaml
ports:
  - "8080:80"  # Cambia 8000 por 8080 (o el que quieras)
```

Luego reinicia:
```bash
docker compose down
docker compose up -d
```

### ¿Cómo accedo desde otro equipo en la red?

1. Obtén tu IP:
   - Windows: `ipconfig`
   - Linux/Mac: `hostname -I`

2. Accede desde el otro equipo:
   ```
   http://TU_IP:8000
   ```

3. Si no funciona, verifica el firewall (debe permitir puerto 8000).

### ¿Cómo me conecto a MySQL desde el host?

```
Host: localhost (o 127.0.0.1)
Port: 3307  ⚠️ Nota: 3307, no 3306
Database: fiftyone
Username: laravel
Password: secret
```

Puedes usar MySQL Workbench, DBeaver, o cualquier cliente MySQL.

### ¿Por qué el puerto 3307 y no 3306?

Para evitar conflictos si ya tienes MySQL instalado en tu máquina (que usa el puerto 3306).

## 💾 Datos

### ¿Dónde se guardan los datos?

Los datos de MySQL se guardan en un volumen Docker llamado `mysql_data`. Este volumen persiste incluso si detienes o eliminas los contenedores.

### ¿Pierdo los datos si detengo los contenedores?

No. Los datos persisten en volúmenes Docker.

### ¿Cómo hago backup de la base de datos?

```bash
docker compose exec -T db mysqldump -u laravel -psecret fiftyone > backup.sql
```

### ¿Cómo restauro un backup?

```bash
docker compose exec -T db mysql -u laravel -psecret fiftyone < backup.sql
```

### ¿Cómo elimino todos los datos?

⚠️ **Cuidado:** Esto elimina la base de datos completa.

```bash
docker compose down -v
```

El flag `-v` elimina los volúmenes (datos).

## 🔄 Desarrollo

### ¿Los cambios en el código se reflejan inmediatamente?

Sí. El código fuente usa un "bind mount", lo que significa que los cambios en tu editor se reflejan inmediatamente en el contenedor.

### ¿Necesito reiniciar después de cambiar código PHP?

No. Los cambios se reflejan automáticamente.

### ¿Necesito recompilar assets después de cambiar CSS/JS?

Sí. Ejecuta:
```bash
docker compose exec app npm run build
```

O en modo desarrollo (watch):
```bash
docker compose exec app npm run dev
```

### ¿Cómo limpio la caché de Laravel?

```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear
```

O usa el script:
```bash
# Windows
docker-dev.bat cache-clear

# Linux/Mac
./docker-dev.sh cache-clear
```

### ¿Cómo ejecuto las migraciones?

```bash
docker compose exec app php artisan migrate
```

### ¿Cómo ejecuto los seeders?

```bash
docker compose exec app php artisan db:seed
```

### ¿Cómo reinicio la base de datos?

⚠️ **Cuidado:** Esto elimina todos los datos.

```bash
docker compose exec app php artisan migrate:fresh --seed
```

## 🐛 Problemas Comunes

### Error: "Port 8000 is already in use"

**Solución 1:** Detén lo que esté usando el puerto 8000.

**Solución 2:** Cambia el puerto en `docker-compose.yml`:
```yaml
ports:
  - "8080:80"  # Usa 8080 en lugar de 8000
```

### Error: "Port 3307 is already in use"

Cambia el puerto en `docker-compose.yml`:
```yaml
ports:
  - "3308:3306"  # Usa 3308 en lugar de 3307
```

### Error: "Cannot connect to the Docker daemon"

Docker Desktop no está corriendo. Abre Docker Desktop y espera a que inicie.

### Error de permisos en storage/

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### La aplicación muestra error 500

1. Verifica los logs:
   ```bash
   docker compose logs app
   ```

2. Limpia la caché:
   ```bash
   docker compose exec app php artisan cache:clear
   docker compose exec app php artisan config:clear
   ```

3. Verifica que el `.env` esté configurado correctamente.

### La aplicación no carga (página en blanco)

1. Verifica que los contenedores estén corriendo:
   ```bash
   docker compose ps
   ```

2. Verifica los logs:
   ```bash
   docker compose logs -f
   ```

3. Reinicia los contenedores:
   ```bash
   docker compose restart
   ```

### No puedo acceder desde otro equipo

1. Verifica que ambos equipos estén en la misma red.

2. Verifica el firewall:
   - Windows: Permitir puerto 8000
   - Linux: `sudo ufw allow 8000/tcp`

3. Usa la IP correcta (no `localhost`).

### MySQL no se conecta

1. Verifica que el contenedor de DB esté corriendo:
   ```bash
   docker compose ps
   ```

2. Verifica las credenciales en `.env`:
   ```env
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=fiftyone
   DB_USERNAME=laravel
   DB_PASSWORD=secret
   ```

3. Verifica los logs de MySQL:
   ```bash
   docker compose logs db
   ```

### Los assets (CSS/JS) no cargan

1. Compila los assets:
   ```bash
   docker compose exec app npm install
   docker compose exec app npm run build
   ```

2. Limpia la caché:
   ```bash
   docker compose exec app php artisan view:clear
   ```

3. Verifica que el enlace de storage exista:
   ```bash
   docker compose exec app php artisan storage:link
   ```

## 🔐 Seguridad

### ¿Las credenciales por defecto son seguras?

No. Son para desarrollo. Para producción, **DEBES cambiar**:
- `DB_PASSWORD`
- `MYSQL_PASSWORD`
- `MYSQL_ROOT_PASSWORD`
- `APP_KEY`

### ¿Cómo cambio las credenciales?

1. Edita `.env`:
   ```env
   DB_PASSWORD=tu_contraseña_segura
   ```

2. Edita `docker-compose.yml`:
   ```yaml
   environment:
     MYSQL_PASSWORD: tu_contraseña_segura
     MYSQL_ROOT_PASSWORD: tu_contraseña_root_segura
   ```

3. Reconstruye:
   ```bash
   docker compose down -v
   docker compose up -d
   ```

### ¿Cómo habilito HTTPS?

Necesitas:
1. Certificados SSL
2. Modificar `docker/nginx/default.conf` para incluir configuración SSL
3. Mapear el puerto 443 en `docker-compose.yml`

Ver documentación de Nginx para más detalles.

## 🚀 Producción

### ¿Puedo usar Docker en producción?

Sí. Usa `docker-compose.prod.yml`:

```bash
docker compose -f docker-compose.prod.yml up -d
```

### ¿Qué diferencias hay entre desarrollo y producción?

**Desarrollo (`docker-compose.yml`):**
- `APP_DEBUG=true`
- Puerto 8000
- Sin healthchecks
- Restart: `unless-stopped`

**Producción (`docker-compose.prod.yml`):**
- `APP_DEBUG=false`
- Puerto 80 y 443
- Con healthchecks
- Restart: `always`
- Optimizaciones de rendimiento

### ¿Cómo optimizo para producción?

```bash
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app composer install --optimize-autoloader --no-dev
```

## 📊 Rendimiento

### ¿Docker es más lento que instalación nativa?

En desarrollo, puede ser ligeramente más lento (5-10%). En producción, el rendimiento es similar.

### ¿Cómo mejoro el rendimiento?

1. Asigna más recursos a Docker Desktop:
   - Settings → Resources → Aumenta CPU y RAM

2. Usa volúmenes para `vendor` y `node_modules` (ya configurado).

3. En Windows, usa WSL 2 backend (recomendado por Docker).

### ¿Cuánta RAM usa Docker?

Depende de la carga, pero típicamente:
- Contenedor app: 200-500MB
- Contenedor db: 200-400MB
- **Total:** 400-900MB

## 🧹 Limpieza

### ¿Cómo libero espacio?

```bash
# Eliminar contenedores detenidos
docker container prune

# Eliminar imágenes no usadas
docker image prune -a

# Eliminar volúmenes no usados
docker volume prune

# Eliminar todo (⚠️ cuidado)
docker system prune -a --volumes
```

### ¿Cómo elimino completamente el proyecto?

```bash
# Detener y eliminar todo
docker compose down -v --rmi all

# Esto elimina:
# - Contenedores
# - Volúmenes (datos)
# - Imágenes
# - Red
```

## 📚 Recursos

### ¿Dónde aprendo más sobre Docker?

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Docker Tutorial](https://www.docker.com/101-tutorial)

### ¿Dónde está la documentación de FiftyOne Docker?

- [DOCKER.md](DOCKER.md) - Guía completa
- [DOCKER-QUICKSTART.md](DOCKER-QUICKSTART.md) - Inicio rápido
- [DOCKER-ARCHITECTURE.md](DOCKER-ARCHITECTURE.md) - Arquitectura
- [DOCKER-FILES.md](DOCKER-FILES.md) - Índice de archivos
- [DOCKER-CHECKLIST.md](DOCKER-CHECKLIST.md) - Checklist de verificación

### ¿Necesito ayuda?

1. Revisa esta FAQ
2. Revisa [DOCKER.md](DOCKER.md) - Sección "Solución de Problemas"
3. Ejecuta `docker-check.sh` o `docker-check.bat`
4. Revisa los logs: `docker compose logs -f`

---

**¿No encuentras tu pregunta?** Abre un issue en GitHub o consulta la documentación completa en [DOCKER.md](DOCKER.md).

**Última actualización:** 30 de Abril de 2026


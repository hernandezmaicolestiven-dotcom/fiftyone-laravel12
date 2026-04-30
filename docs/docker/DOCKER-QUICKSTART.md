# 🚀 Inicio Rápido con Docker

## ⚡ Opción 1: Script Automático (Recomendado)

### En Windows:
```bash
docker-start.bat
```

### En Linux/Mac:
```bash
chmod +x docker-start.sh
./docker-start.sh
```

## 📝 Opción 2: Manual (3 comandos)

```bash
# 1. Iniciar contenedores
docker compose up -d

# 2. Instalar dependencias
docker compose exec app composer install --optimize-autoloader --no-dev

# 3. Ejecutar migraciones
docker compose exec app php artisan migrate
```

## 🌐 Acceder a la Aplicación

- **Local:** http://localhost:8000
- **Red local:** http://TU_IP:8000

### Obtener tu IP:

**Windows:**
```bash
ipconfig
```

**Linux/Mac:**
```bash
hostname -I
```

## 🛠️ Comandos Útiles

```bash
# Ver logs
docker compose logs -f

# Detener
docker compose stop

# Reiniciar
docker compose restart

# Ejecutar comandos Artisan
docker compose exec app php artisan [comando]

# Acceder al contenedor
docker compose exec app bash

# Limpiar todo
docker compose down -v
```

## 📚 Documentación Completa

Ver [DOCKER.md](DOCKER.md) para la guía completa.

## ⚠️ Notas Importantes

- ✅ El código NO se copia, usa bind mount (cambios en tiempo real)
- ✅ MySQL usa puerto **3307** en el host (no 3306)
- ✅ Los datos persisten en volúmenes Docker
- ✅ Puedes editar el código normalmente

## 🔧 Credenciales de Base de Datos

```
Host: localhost (o 'db' desde el contenedor)
Port: 3307 (desde el host) / 3306 (desde el contenedor)
Database: fiftyone
Username: laravel
Password: secret
```

## 🆘 Problemas Comunes

### Puerto 8000 ocupado
```bash
# Edita docker-compose.yml y cambia:
ports:
  - "8080:80"  # Usa 8080 en lugar de 8000
```

### Error de permisos
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### La app no carga
```bash
# Limpiar caché
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear

# Ver logs
docker compose logs app
```

---

**¿Necesitas ayuda?** Revisa [DOCKER.md](DOCKER.md) para más detalles o [DOCKER-INDEX.md](DOCKER-INDEX.md) para el índice completo de documentación.

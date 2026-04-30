# 📁 Carpeta Docker

Esta carpeta contiene las configuraciones necesarias para ejecutar FiftyOne en Docker.

## 📂 Estructura

```
docker/
├── nginx/
│   └── default.conf          # Configuración de Nginx para Laravel
├── supervisor/
│   └── supervisord.conf      # Configuración de Supervisor (PHP-FPM + Nginx)
└── mysql/
    └── my.cnf                # Configuración optimizada de MySQL
```

## 🔧 Archivos de Configuración

### nginx/default.conf

Configuración de Nginx para servir la aplicación Laravel:
- Escucha en el puerto 80
- Root en `/var/www/html/public`
- Configuración de FastCGI para PHP-FPM
- Headers de seguridad
- Manejo de archivos estáticos

### supervisor/supervisord.conf

Supervisor gestiona dos procesos dentro del contenedor:
1. **PHP-FPM** - Procesa las peticiones PHP
2. **Nginx** - Servidor web que recibe las peticiones HTTP

Ambos procesos se inician automáticamente y se reinician si fallan.

### mysql/my.cnf

Configuración optimizada de MySQL para Laravel:
- Charset UTF8MB4 (soporte completo de Unicode)
- Buffer pool de InnoDB optimizado
- Logs de consultas lentas habilitados
- Configuración de seguridad
- Timezone configurado

## 🚫 No Modificar

⚠️ **Importante:** Estos archivos son parte de la configuración de Docker y no deben modificarse a menos que sepas lo que estás haciendo.

Si necesitas hacer cambios:
1. Haz una copia de seguridad del archivo original
2. Realiza los cambios necesarios
3. Reconstruye los contenedores: `docker compose build`
4. Reinicia los contenedores: `docker compose up -d`

## 📚 Más Información

Ver [DOCKER.md](../DOCKER.md) en la raíz del proyecto para la guía completa de uso.


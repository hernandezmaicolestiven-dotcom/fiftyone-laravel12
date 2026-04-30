# FiftyOne — Panel de Administración

Sistema de gestión para tienda de ropa oversize. Incluye panel admin completo, tienda pública con carrito, reportes, notificaciones y más.

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?logo=php)
![Tests](https://github.com/hernandezmaicolestiven-dotcom/fiftyone-laravel12/actions/workflows/ci.yml/badge.svg)

---

## Requisitos

| Herramienta | Versión mínima |
|-------------|---------------|
| PHP         | 8.2           |
| Composer    | 2.x           |
| MySQL       | 8.0           |
| Node.js     | 18.x          |

---

## 🐳 Instalación con Docker (Recomendado)

La forma más rápida de ejecutar el proyecto. No necesitas instalar PHP, MySQL, ni Node.js.

### Requisitos
- Docker Desktop ([Descargar](https://www.docker.com/products/docker-desktop))

### Inicio Rápido

**Windows:**
```bash
scripts/docker/docker-start.bat
```

**Linux/Mac:**
```bash
chmod +x scripts/docker/docker-start.sh
./scripts/docker/docker-start.sh
```

Accede a: http://localhost:8000

📚 **Documentación completa:** [docs/docker/DOCKER.md](docs/docker/DOCKER.md) | [Guía rápida](docs/docker/DOCKER-QUICKSTART.md) | [Resumen](docs/docker/DOCKER-SUMMARY.md)

---

## 💻 Instalación local (Sin Docker)

### 1. Clonar el repositorio

```bash
git clone https://github.com/hernandezmaicolestiven-dotcom/fiftyone-laravel12.git
cd fiftyone-laravel12
```

### 2. Instalar dependencias

```bash
composer install
npm install
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` con tus credenciales de base de datos:

```env
DB_DATABASE=fiftyone
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### 4. Base de datos

```bash
php artisan migrate
php artisan db:seed
```

### 5. Storage

```bash
php artisan storage:link
```

### 6. Iniciar el servidor

```bash
php artisan serve
```

Abre [http://localhost:8000](http://localhost:8000)

**Credenciales de acceso:**

Ver documentación completa en [`docs/credenciales/CREDENCIALES_RAPIDO.txt`](docs/credenciales/CREDENCIALES_RAPIDO.txt)

- **Admin**: http://localhost:8000/admin/login
  - Email: `admin@fiftyone.com`
  - Password: `Admin123!`

- **Cliente**: http://localhost:8000/login
  - Email: `cliente@test.com`
  - Password: `Cliente123!`

---

## 📚 Documentación

Toda la documentación está organizada en la carpeta [`docs/`](docs/):

- **[Guías de uso](docs/guias/)** - Tutoriales y configuración
- **[Credenciales](docs/credenciales/)** - Información de acceso
- **[Scripts](scripts/)** - Utilidades y pruebas
- **[API](docs/api.md)** - Documentación de endpoints

### Guías Principales

- [🐳 Docker](docs/docker/DOCKER.md) - Ejecutar el proyecto en Docker
- [Sistema de Pagos](docs/guias/SISTEMA_PAGOS_COMPLETO.md) - 6 métodos de pago implementados
- [Sistema de Registro y Login](docs/guias/SISTEMA_REGISTRO_LOGIN.md)
- [Recuperación de Contraseña](docs/guias/RECUPERACION_CONTRASENA.md)
- [Sistema de Facturación](docs/guias/SISTEMA_FACTURACION.md)
- [Configurar Correo Real](docs/guias/CONFIGURAR_CORREO_REAL.md)

### Scripts Útiles

```bash
# Iniciar worker de colas (necesario para correos)
scripts/start-queue-worker.bat

# Verificar que todo funcione
scripts/tests/verificar-sistema.bat

# Abrir login de admin
scripts/abrir-login-admin.bat

# Obtener enlace de recuperación de contraseña
scripts/get-reset-link.bat
```

---

## Estructura del proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Controladores del panel admin
│   │   └── OrderController.php  # Endpoint público de pedidos
│   ├── Middleware/
│   │   ├── SecurityHeaders.php  # Headers de seguridad HTTP
│   │   └── SanitizeInput.php    # Sanitización XSS
│   └── Requests/           # Form Requests con validación
├── Models/                 # Eloquent models
├── Notifications/          # Emails automáticos
├── Services/
│   ├── OrderService.php         # Lógica de negocio de pedidos
│   └── ProductImportService.php # Importación CSV/XLSX
resources/
├── views/
│   ├── admin/              # Vistas del panel admin
│   └── welcome.blade.php   # Landing pública (React)
tests/
├── Feature/                # Pruebas de integración
└── Unit/                   # Pruebas unitarias
```

---

## Ejecutar pruebas

```bash
php artisan test
```

Para un reporte detallado:

```bash
php artisan test --coverage
```

---

## Variables de entorno importantes

| Variable | Descripción |
|----------|-------------|
| `APP_KEY` | Clave de cifrado — generar con `php artisan key:generate` |
| `APP_ENV` | `local` en desarrollo, `production` en producción |
| `APP_DEBUG` | `false` en producción siempre |
| `DB_*` | Credenciales de base de datos |
| `MAIL_*` | Configuración SMTP para emails automáticos |
| `BCRYPT_ROUNDS` | Rondas de bcrypt (mínimo 12) |

Ver `.env.example` para la lista completa con comentarios.

---

## Despliegue en Railway

1. Conecta tu repositorio de GitHub en [railway.app](https://railway.app)
2. Agrega un servicio **MySQL**
3. Configura las variables de entorno (ver sección anterior)
4. Railway detecta Laravel automáticamente con el `Procfile`

Ver [`railway.json`](./railway.json) para la configuración completa.

---

## Módulos del sistema

| Módulo | Descripción |
|--------|-------------|
| **Dashboard** | Estadísticas, gráfica de pedidos/ingresos, alertas de stock |
| **Productos** | CRUD, importar CSV/Excel, exportar CSV/Excel |
| **Pedidos** | Gestión de estados, filtros de fecha, exportar CSV/PDF |
| **Categorías** | CRUD de categorías |
| **Usuarios** | CRUD, importar CSV, filtros |
| **Reportes** | Ventas, inventario, productos más vendidos |
| **Notificaciones** | Campana interna + emails automáticos |
| **Tienda pública** | Landing React con carrito y checkout |

---

## Seguridad

- Contraseñas cifradas con **bcrypt** (12 rondas)
- Protección **CSRF** en todos los formularios
- **Rate limiting** en login: 5 intentos / 60 segundos
- **Security headers** HTTP en todas las respuestas
- Sanitización **XSS** en inputs
- Validación de **MIME real** en uploads de archivos
- Consultas con **Eloquent ORM** (sin SQL raw)

---

## Licencia

MIT

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

## Instalación local

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

**Credenciales de acceso al panel:**
- URL: `/admin/login`
- Email: `admin@fiftyone.com`
- Contraseña: `admin123`

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

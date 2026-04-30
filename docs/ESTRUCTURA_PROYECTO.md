# 📁 Estructura del Proyecto FiftyOne

## 🎯 Organización de Archivos

El proyecto está organizado de la siguiente manera:

```
fiftyone-laravel12/
│
├── 📁 app/                          # Código de la aplicación
│   ├── Http/Controllers/            # Controladores
│   ├── Models/                      # Modelos Eloquent
│   ├── Services/                    # Lógica de negocio
│   └── Notifications/               # Notificaciones por email
│
├── 📁 database/                     # Base de datos
│   ├── migrations/                  # Migraciones
│   ├── seeders/                     # Seeders de datos
│   └── factories/                   # Factories para testing
│
├── 📁 resources/                    # Recursos frontend
│   └── views/                       # Vistas Blade
│       ├── admin/                   # Panel de administración
│       ├── customer/                # Área de clientes
│       ├── invoice/                 # Facturas
│       └── pages/                   # Páginas públicas
│
├── 📁 routes/                       # Rutas de la aplicación
│   └── web.php                      # Rutas web
│
├── 📁 public/                       # Archivos públicos
│   ├── images/                      # Imágenes
│   └── storage/                     # Storage público (link)
│
├── 📁 storage/                      # Almacenamiento
│   ├── app/                         # Archivos de la app
│   ├── logs/                        # Logs del sistema
│   └── framework/                   # Cache, sesiones, vistas
│
├── 📁 docs/                         # 📚 DOCUMENTACIÓN
│   ├── README.md                    # Índice de documentación
│   ├── api.md                       # Documentación de API
│   ├── guias/                       # Guías de uso
│   │   ├── SISTEMA_PAGOS_COMPLETO.md
│   │   ├── SISTEMA_REGISTRO_LOGIN.md
│   │   ├── SISTEMA_AVATARES.md
│   │   ├── SISTEMA_FACTURACION.md
│   │   ├── RECUPERACION_CONTRASENA.md
│   │   ├── CONFIGURAR_CORREO_REAL.md
│   │   ├── SOLUCION_CORREOS.md
│   │   ├── INICIO_RAPIDO_COLAS.md
│   │   ├── CART_WHATSAPP_FIXES.md
│   │   ├── FLUJO_REGISTRO_ACTUALIZADO.md
│   │   ├── CORRECCIONES_ORTOGRAFIA.md
│   │   └── INSTRUCCIONES_SEEDER.md
│   └── credenciales/                # Información de acceso
│       ├── CREDENCIALES.md
│       ├── CREDENCIALES_RAPIDO.txt
│       ├── RESUMEN_FINAL.md
│       └── RESUMEN_RECUPERACION_PASSWORD.md
│
├── 📁 scripts/                      # 🛠️ SCRIPTS DE UTILIDAD
│   ├── README.md                    # Documentación de scripts
│   ├── abrir-login-admin.bat        # Abre login de admin
│   ├── abrir-login-cliente.bat      # Abre login de cliente
│   ├── get-reset-link.bat           # Obtiene enlace de recuperación
│   ├── start-queue-worker.bat       # Inicia worker de colas
│   └── tests/                       # Scripts de prueba
│       ├── PRUEBA_NUEVO_FLUJO.md
│       ├── PRUEBA_SISTEMA_PAGOS.md
│       ├── test-login.bat
│       ├── test-registro-completo.php
│       ├── verificar-sistema.bat
│       └── verificar_productos.php
│
├── 📁 config/                       # Configuración de Laravel
├── 📁 bootstrap/                    # Bootstrap de Laravel
├── 📁 tests/                        # Tests automatizados
├── 📁 vendor/                       # Dependencias de Composer
│
├── 📄 .env                          # Variables de entorno
├── 📄 .env.example                  # Ejemplo de variables
├── 📄 composer.json                 # Dependencias PHP
├── 📄 package.json                  # Dependencias Node
├── 📄 artisan                       # CLI de Laravel
├── 📄 README.md                     # README principal
└── 📄 ESTRUCTURA_PROYECTO.md        # Este archivo
```

---

## 📚 Dónde Encontrar Qué

### ¿Necesitas credenciales de acceso?
👉 [`docs/credenciales/CREDENCIALES_RAPIDO.txt`](docs/credenciales/CREDENCIALES_RAPIDO.txt)

### ¿Quieres entender el sistema de pagos?
👉 [`docs/guias/SISTEMA_PAGOS_COMPLETO.md`](docs/guias/SISTEMA_PAGOS_COMPLETO.md)

### ¿Necesitas configurar correos?
👉 [`docs/guias/CONFIGURAR_CORREO_REAL.md`](docs/guias/CONFIGURAR_CORREO_REAL.md)

### ¿Quieres probar el sistema?
👉 [`scripts/tests/`](scripts/tests/)

### ¿Necesitas iniciar el worker de colas?
👉 [`scripts/start-queue-worker.bat`](scripts/start-queue-worker.bat)

### ¿Quieres ver todas las guías?
👉 [`docs/README.md`](docs/README.md)

---

## 🚀 Inicio Rápido

1. **Instalar dependencias:**
   ```bash
   composer install
   ```

2. **Configurar entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Base de datos:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Iniciar servidor:**
   ```bash
   php artisan serve
   ```

5. **Iniciar worker de colas:**
   ```bash
   scripts/start-queue-worker.bat
   ```

6. **Acceder:**
   - Tienda: http://localhost:8000
   - Admin: http://localhost:8000/admin/login

---

## 📖 Documentación Completa

Ver [`docs/README.md`](docs/README.md) para el índice completo de documentación.

---

## 🛠️ Scripts Disponibles

Ver [`scripts/README.md`](scripts/README.md) para la lista completa de scripts.

---

## ✅ Estado del Sistema

- ✅ Sistema de Autenticación - 100% Funcional
- ✅ Sistema de Pagos (6 métodos) - 100% Funcional
- ✅ Sistema de Facturación - 100% Funcional
- ✅ Sistema de Avatares - 100% Funcional
- ✅ Recuperación de Contraseña - 100% Funcional
- ✅ Sistema de Colas - 100% Funcional
- ✅ Carrito y Checkout - 100% Funcional

---

## 📝 Notas Importantes

- Toda la documentación está en [`docs/`](docs/)
- Todos los scripts están en [`scripts/`](scripts/)
- Las credenciales están en [`docs/credenciales/`](docs/credenciales/)
- Los archivos de configuración están en la raíz (`.env`, `composer.json`, etc.)

---

**Última actualización:** 28 de Abril, 2026
**Versión:** 1.0

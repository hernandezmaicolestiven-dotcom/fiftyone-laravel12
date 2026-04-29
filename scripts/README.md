# 🛠️ Scripts de FiftyOne

Scripts de utilidad y pruebas para el sistema FiftyOne.

## 📁 Estructura

```
scripts/
├── README.md                      # Este archivo
├── abrir-login-admin.bat          # Abre login de admin en navegador
├── abrir-login-cliente.bat        # Abre login de cliente en navegador
├── get-reset-link.bat             # Extrae enlace de recuperación de contraseña
├── start-queue-worker.bat         # Inicia worker de colas
└── tests/                         # Scripts de prueba
    ├── PRUEBA_NUEVO_FLUJO.md
    ├── PRUEBA_SISTEMA_PAGOS.md
    ├── test-login.bat
    ├── test-registro-completo.php
    ├── verificar-sistema.bat
    └── verificar_productos.php
```

---

## 🚀 Scripts de Utilidad

### `abrir-login-admin.bat`
Abre el navegador en la página de login de administrador.

**Uso:**
```bash
abrir-login-admin.bat
```

**Credenciales mostradas:**
- Email: admin@fiftyone.com
- Password: Admin123!

---

### `abrir-login-cliente.bat`
Abre el navegador en la página de login de cliente.

**Uso:**
```bash
abrir-login-cliente.bat
```

**Credenciales mostradas:**
- Email: cliente@test.com
- Password: Cliente123!

---

### `get-reset-link.bat`
Extrae el último enlace de recuperación de contraseña de los logs.

**Uso:**
```bash
get-reset-link.bat
```

**Cuándo usar:**
- Cuando un usuario solicita recuperar contraseña
- En desarrollo con MAIL_MAILER=log
- Para obtener el enlace sin configurar SMTP

---

### `start-queue-worker.bat`
Inicia el worker de colas para procesar correos en segundo plano.

**Uso:**
```bash
start-queue-worker.bat
```

**Importante:**
- Mantén esta terminal abierta mientras uses la aplicación
- Necesario para que funcione el envío de correos
- Si cierras la terminal, los correos no se procesarán

---

## 🧪 Scripts de Prueba

### `tests/verificar-sistema.bat`
Verifica que todos los componentes del sistema estén funcionando.

**Uso:**
```bash
cd tests
verificar-sistema.bat
```

**Verifica:**
- ✅ Conexión a base de datos
- ✅ Usuarios en el sistema
- ✅ Rutas de autenticación
- ✅ Middleware de seguridad
- ✅ Controladores
- ✅ Vistas de login

---

### `tests/test-login.bat`
Prueba las credenciales de admin y cliente.

**Uso:**
```bash
cd tests
test-login.bat
```

---

### `tests/test-registro-completo.php`
Prueba completa del sistema de registro.

**Uso:**
```bash
cd tests
php test-registro-completo.php
```

**Prueba:**
- Creación de usuario
- Guardado en base de datos
- Hash de contraseña
- Login con credenciales

---

### `tests/verificar_productos.php`
Verifica los productos en la base de datos.

**Uso:**
```bash
cd tests
php verificar_productos.php
```

---

### `tests/PRUEBA_SISTEMA_PAGOS.md`
Guía completa para probar todos los métodos de pago.

**Métodos probados:**
- Nequi
- Daviplata
- PSE
- Bancolombia
- Efecty
- Tarjeta

---

### `tests/PRUEBA_NUEVO_FLUJO.md`
Guía para probar el nuevo flujo de registro (sin login automático).

---

## 📝 Notas

### Para Desarrollo
- Usa `start-queue-worker.bat` siempre que trabajes con correos
- Usa `get-reset-link.bat` para obtener enlaces de recuperación
- Usa `verificar-sistema.bat` si algo no funciona

### Para Producción
- Configura Supervisor para mantener el worker corriendo
- No uses los scripts de prueba en producción
- Cambia las credenciales por defecto

---

## 🔧 Requisitos

- PHP 8.2+
- Composer
- MySQL/MariaDB
- Servidor web (Apache/Nginx o `php artisan serve`)

---

## 🆘 Troubleshooting

### El worker no inicia
```bash
# Verifica que no haya otro worker corriendo
tasklist | findstr php

# Limpia trabajos fallidos
php artisan queue:flush

# Intenta de nuevo
start-queue-worker.bat
```

### Los scripts .bat no funcionan
- Asegúrate de estar en Windows
- Ejecuta desde la carpeta correcta
- Verifica que PHP esté en el PATH

### Los scripts .php no funcionan
```bash
# Verifica la versión de PHP
php -v

# Verifica que estés en la raíz del proyecto
php artisan --version
```

---

**Última actualización:** 28 de Abril, 2026

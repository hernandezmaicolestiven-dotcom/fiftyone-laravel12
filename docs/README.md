# 📚 Documentación FiftyOne

Bienvenido a la documentación completa del sistema FiftyOne.

```
docs/
├── README.md                    # Este archivo
├── docker/                      # 🐳 Documentación de Docker
│   ├── DOCKER.md               # Guía completa
│   ├── DOCKER-QUICKSTART.md    # Inicio rápido
│   ├── DOCKER-ARCHITECTURE.md  # Arquitectura técnica
│   ├── DOCKER-FAQ.md           # Preguntas frecuentes
│   ├── ACCESO-INSTRUCTOR.md    # Acceso remoto
│   └── CREDENCIALES.txt        # Credenciales Docker
├── api.md                       # Documentación de API
├── guias/                       # Guías de uso y configuración
│   ├── SISTEMA_PAGOS_COMPLETO.md
│   ├── SISTEMA_REGISTRO_LOGIN.md
│   ├── SISTEMA_AVATARES.md
│   ├── SISTEMA_FACTURACION.md
│   ├── RECUPERACION_CONTRASENA.md
│   ├── CONFIGURAR_CORREO_REAL.md
│   ├── SOLUCION_CORREOS.md
│   ├── INICIO_RAPIDO_COLAS.md
│   ├── CART_WHATSAPP_FIXES.md
│   ├── FLUJO_REGISTRO_ACTUALIZADO.md
│   └── CORRECCIONES_ORTOGRAFIA.md
├── credenciales/                # Información de acceso
│   ├── CREDENCIALES.md
│   ├── CREDENCIALES_RAPIDO.txt
│   ├── RESUMEN_FINAL.md
│   └── RESUMEN_RECUPERACION_PASSWORD.md
├── ORGANIZACION_COMPLETADA.md   # Historial de organización
├── ESTRUCTURA_PROYECTO.md       # Estructura del proyecto
└── LOOKS_DEL_DIA_ARREGLADO.md  # Documentación de Looks
```

---

## 🐳 Docker (Nuevo)

- **[Guía Completa de Docker](docker/DOCKER.md)** - Todo sobre Docker
- **[Inicio Rápido](docker/DOCKER-QUICKSTART.md)** - Empieza en 3 minutos
- **[Arquitectura](docker/DOCKER-ARCHITECTURE.md)** - Cómo funciona
- **[FAQ](docker/DOCKER-FAQ.md)** - Preguntas frecuentes
- **[Acceso para Instructor](docker/ACCESO-INSTRUCTOR.md)** - Acceso remoto
- **[Verificación para Instructor](docker/VERIFICACION_INSTRUCTOR.md)** - ✅ Checklist completo
- **[Credenciales Docker](docker/CREDENCIALES.txt)** - Usuarios y contraseñas

---

## 📁 Estructura de Documentación

```

---

## 🚀 Inicio Rápido

### 1. Credenciales de Acceso
Ver: [`credenciales/CREDENCIALES_RAPIDO.txt`](credenciales/CREDENCIALES_RAPIDO.txt)

**Admin:**
- URL: http://localhost:8000/admin/login
- Email: admin@fiftyone.com
- Password: Admin123!

**Cliente:**
- URL: http://localhost:8000/login
- Email: cliente@test.com
- Password: Cliente123!

### 2. Iniciar el Sistema
```bash
# Servidor web
php artisan serve

# Worker de colas (en otra terminal)
cd scripts
start-queue-worker.bat
```

---

## 📖 Guías por Tema

### 🔐 Autenticación y Usuarios
- [Sistema de Registro y Login](guias/SISTEMA_REGISTRO_LOGIN.md)
- [Recuperación de Contraseña](guias/RECUPERACION_CONTRASENA.md)
- [Flujo de Registro Actualizado](guias/FLUJO_REGISTRO_ACTUALIZADO.md)
- [Credenciales del Sistema](credenciales/CREDENCIALES.md)

### 💳 Sistema de Pagos
- [Sistema de Pagos Completo](guias/SISTEMA_PAGOS_COMPLETO.md) - **IMPORTANTE**
- Métodos disponibles: Nequi, Daviplata, PSE, Bancolombia, Efecty, Tarjeta

### 📧 Correos y Notificaciones
- [Configurar Correo Real](guias/CONFIGURAR_CORREO_REAL.md)
- [Solución: Por qué no llegan los correos](guias/SOLUCION_CORREOS.md)
- [Sistema de Colas](guias/INICIO_RAPIDO_COLAS.md)

### 🛒 Carrito y Checkout
- [Fixes de Carrito y WhatsApp](guias/CART_WHATSAPP_FIXES.md)

### 👤 Perfiles y Avatares
- [Sistema de Avatares](guias/SISTEMA_AVATARES.md)

### 📄 Facturación
- [Sistema de Facturación](guias/SISTEMA_FACTURACION.md)

### 🔧 Mantenimiento
- [Correcciones de Ortografía](guias/CORRECCIONES_ORTOGRAFIA.md)
- [Instrucciones de Seeders](guias/INSTRUCCIONES_SEEDER.md)

---

## 🛠️ Scripts Disponibles

Ver carpeta [`../scripts/`](../scripts/)

### Scripts de Utilidad
- `abrir-login-admin.bat` - Abre el login de administrador
- `abrir-login-cliente.bat` - Abre el login de cliente
- `get-reset-link.bat` - Extrae enlace de recuperación de contraseña
- `start-queue-worker.bat` - Inicia el worker de colas

### Scripts de Prueba
Ver carpeta [`../scripts/tests/`](../scripts/tests/)
- `verificar-sistema.bat` - Verifica que todo funcione
- `test-login.bat` - Prueba de login
- `test-registro-completo.php` - Prueba de registro
- `PRUEBA_SISTEMA_PAGOS.md` - Guía de pruebas de pagos

---

## 📊 Estado del Sistema

✅ **Sistema de Autenticación** - 100% Funcional
✅ **Sistema de Pagos** - 100% Funcional (6 métodos)
✅ **Sistema de Facturación** - 100% Funcional
✅ **Sistema de Avatares** - 100% Funcional
✅ **Recuperación de Contraseña** - 100% Funcional
✅ **Sistema de Colas** - 100% Funcional
✅ **Carrito y Checkout** - 100% Funcional

---

## 🆘 Soporte

Si tienes problemas:

1. Revisa la guía correspondiente en [`guias/`](guias/)
2. Ejecuta `scripts/tests/verificar-sistema.bat`
3. Revisa los logs: `storage/logs/laravel.log`
4. Consulta las credenciales en [`credenciales/`](credenciales/)

---

## 📝 Notas Importantes

- Mantén el worker de colas corriendo para que funcionen los correos
- Las credenciales por defecto son para desarrollo
- Cambia las contraseñas en producción
- Configura SMTP real para envío de correos en producción

---

**Última actualización:** 30 de Abril, 2026
**Versión:** 1.0
**Estado:** 🟢 Producción Ready

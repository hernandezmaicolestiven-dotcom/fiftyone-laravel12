# Sistema de Recuperación de Contraseña - Clientes

## ✅ Implementación Completa

Se ha agregado el sistema completo de recuperación de contraseña para clientes de la tienda.

## 📋 Funcionalidades

1. **Solicitar recuperación**: Los clientes pueden solicitar un enlace de recuperación desde el login
2. **Envío de correo**: Se envía un correo con un token único y enlace de restablecimiento
3. **Restablecer contraseña**: El cliente puede crear una nueva contraseña usando el enlace

## 🔗 Rutas Creadas

- `GET /recuperar-contrasena` - Formulario para solicitar recuperación
- `POST /recuperar-contrasena` - Enviar enlace de recuperación
- `GET /restablecer-contrasena/{token}` - Formulario para nueva contraseña
- `POST /restablecer-contrasena` - Procesar nueva contraseña

## 📁 Archivos Creados/Modificados

### Vistas Nuevas:
- `resources/views/customer/auth/forgot-password.blade.php` - Solicitar recuperación
- `resources/views/customer/auth/reset-password.blade.php` - Restablecer contraseña

### Modificados:
- `resources/views/customer/auth/login.blade.php` - Agregado enlace "¿Olvidaste tu contraseña?"
- `app/Http/Controllers/CustomerAuthController.php` - Agregados 4 métodos nuevos
- `routes/web.php` - Agregadas 4 rutas nuevas

## 🎨 Diseño

Las vistas mantienen el mismo diseño moderno y consistente del login:
- Tailwind CSS
- Font Awesome icons
- Diseño responsive
- Mensajes de error/éxito
- Toggle para mostrar/ocultar contraseña

## 📧 Configuración de Correo

Para que funcione en producción, configura en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.tuservidor.com
MAIL_PORT=587
MAIL_USERNAME=tu_usuario
MAIL_PASSWORD=tu_contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tudominio.com"
MAIL_FROM_NAME="FiftyOne"
```

### Servicios de correo recomendados:
- Mailgun
- SendGrid
- Amazon SES
- Resend
- Mailtrap (para testing)

## ⚡ Sistema de Colas (IMPORTANTE)

El envío de correos ahora usa colas para ser instantáneo. **DEBES mantener el worker corriendo**:

### En desarrollo:
```bash
php artisan queue:work
```

### En producción (recomendado usar Supervisor):

1. Instala Supervisor:
```bash
sudo apt-get install supervisor
```

2. Crea el archivo `/etc/supervisor/conf.d/laravel-worker.conf`:
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /ruta/a/tu/proyecto/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=tu_usuario
numprocs=2
redirect_stderr=true
stdout_logfile=/ruta/a/tu/proyecto/storage/logs/worker.log
stopwaitsecs=3600
```

3. Inicia Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### Alternativa simple (sin Supervisor):
Puedes usar `nohup` para mantener el worker en segundo plano:
```bash
nohup php artisan queue:work --sleep=3 --tries=3 > storage/logs/queue.log 2>&1 &
```

## 🧪 Testing Local

Para probar localmente sin configurar SMTP real:

1. En `.env` usa:
```env
MAIL_MAILER=log
QUEUE_CONNECTION=database
```

2. Inicia el worker de colas en una terminal:
```bash
php artisan queue:work
```

3. Los correos se guardarán en `storage/logs/laravel.log`

4. El envío será instantáneo para el usuario (no tendrá que esperar)

## 🔒 Seguridad

- Los tokens expiran automáticamente (configurado en `config/auth.php`)
- Los tokens son únicos y de un solo uso
- Las contraseñas se hashean con bcrypt
- Validación de email y contraseña en ambos pasos

## 📝 Flujo de Usuario

1. Usuario hace clic en "¿Olvidaste tu contraseña?" en el login
2. Ingresa su email y envía el formulario
3. Recibe un correo con un enlace único
4. Hace clic en el enlace (válido por 60 minutos)
5. Ingresa su nueva contraseña dos veces
6. Es redirigido al login con mensaje de éxito
7. Puede iniciar sesión con su nueva contraseña

## ✨ Características

- Mensajes en español
- Validación de formularios
- Feedback visual (errores/éxito)
- Diseño consistente con el resto de la app
- Responsive para móviles
- Accesible desde el login

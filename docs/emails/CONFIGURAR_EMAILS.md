# 📧 CONFIGURAR EMAILS PARA RECUPERACIÓN DE CONTRASEÑA

## 🎯 OBJETIVO
Hacer que el sistema de "Olvidé mi contraseña" envíe emails REALES.

---

## ⚡ OPCIÓN 1: MAILTRAP (RECOMENDADA PARA DESARROLLO)

**Ventajas:**
- ✅ Gratis
- ✅ Fácil de configurar (2 minutos)
- ✅ Captura todos los emails
- ✅ No envía emails reales (seguro para pruebas)
- ✅ Interfaz web para ver los emails

### Paso 1: Crear cuenta en Mailtrap
1. Ve a: https://mailtrap.io/register/signup
2. Regístrate gratis (con email o GitHub)
3. Confirma tu email

### Paso 2: Obtener credenciales
1. Entra a tu cuenta de Mailtrap
2. Ve a "Email Testing" → "Inboxes"
3. Clic en "My Inbox" (o crea uno nuevo)
4. Clic en "Show Credentials"
5. Selecciona "Laravel 9+"

Verás algo como:
```
Host: sandbox.smtp.mailtrap.io
Port: 2525
Username: 1a2b3c4d5e6f7g
Password: 9h8i7j6k5l4m3n
```

### Paso 3: Configurar en tu .env
Abre tu archivo `.env` y actualiza:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=TU_USERNAME_AQUI
MAIL_PASSWORD=TU_PASSWORD_AQUI
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@fiftyone.com"
MAIL_FROM_NAME="FiftyOne"
```

### Paso 4: Limpiar caché
```bash
php artisan config:clear
php artisan cache:clear
```

### Paso 5: Probar
1. Ve a: http://localhost:8000/recuperar-contrasena
2. Ingresa: `cliente@test.com`
3. Clic en "Enviar enlace de recuperación"
4. Ve a Mailtrap → verás el email ahí

---

## 📧 OPCIÓN 2: GMAIL (PARA EMAILS REALES)

**Ventajas:**
- ✅ Envía emails REALES
- ✅ Gratis
- ⚠️ Requiere configuración de seguridad

### Paso 1: Habilitar "Contraseñas de aplicación" en Gmail
1. Ve a: https://myaccount.google.com/security
2. Activa "Verificación en 2 pasos" (si no está activa)
3. Ve a "Contraseñas de aplicaciones"
4. Selecciona "Correo" y "Windows"
5. Copia la contraseña generada (16 caracteres)

### Paso 2: Configurar en tu .env
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicacion-aqui
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="tu-email@gmail.com"
MAIL_FROM_NAME="FiftyOne"
```

### Paso 3: Limpiar caché
```bash
php artisan config:clear
php artisan cache:clear
```

### Paso 4: Probar
1. Ve a: http://localhost:8000/recuperar-contrasena
2. Ingresa un email REAL (tuyo)
3. Revisa tu bandeja de entrada

---

## 🚀 OPCIÓN 3: CONFIGURACIÓN RÁPIDA CON SCRIPT

He creado un script que te ayuda a configurar:

```bash
configurar-emails.bat
```

---

## 🧪 PROBAR EL SISTEMA

### Para CLIENTES:
1. Ve a: http://localhost:8000/recuperar-contrasena
2. Ingresa: `cliente@test.com`
3. Clic en "Enviar enlace"
4. Revisa Mailtrap (o tu email)
5. Clic en el enlace del email
6. Ingresa nueva contraseña
7. Inicia sesión con la nueva contraseña

### Para ADMIN:
1. Ve a: http://localhost:8000/admin/forgot-password
2. Ingresa: `admin@fiftyone.com`
3. Clic en "Enviar enlace"
4. Revisa Mailtrap (o tu email)
5. Clic en el enlace del email
6. Ingresa nueva contraseña
7. Inicia sesión con la nueva contraseña

---

## 📝 CREDENCIALES ACTUALES

### Admin:
- Email: admin@fiftyone.com
- Password: admin2026

### Cliente:
- Email: cliente@test.com
- Password: cliente2026

### Colaborador:
- Email: colaborador@fiftyone.com
- Password: colab2026

---

## ⚠️ IMPORTANTE

### Si usas Mailtrap:
- ✅ Los emails NO se envían realmente
- ✅ Solo se capturan en Mailtrap
- ✅ Perfecto para desarrollo y pruebas
- ✅ No necesitas emails reales

### Si usas Gmail:
- ✅ Los emails SÍ se envían realmente
- ⚠️ Usa tu email personal
- ⚠️ No compartas tu contraseña de aplicación
- ✅ Perfecto para producción

---

## 🔍 SOLUCIÓN DE PROBLEMAS

### "Connection refused"
- Verifica que el MAIL_HOST sea correcto
- Verifica que el MAIL_PORT sea correcto
- Ejecuta: `php artisan config:clear`

### "Authentication failed"
- Verifica MAIL_USERNAME
- Verifica MAIL_PASSWORD
- Si usas Gmail, verifica la contraseña de aplicación

### "No recibo el email"
- Si usas Mailtrap, revisa la bandeja en mailtrap.io
- Si usas Gmail, revisa spam
- Verifica que el email exista en la base de datos

### "El enlace no funciona"
- Verifica que APP_URL en .env sea correcto
- El enlace expira en 60 minutos
- Ejecuta: `php artisan config:clear`

---

## 📊 VERIFICAR CONFIGURACIÓN

```bash
# Ver configuración actual
php artisan tinker
>>> config('mail')

# Probar envío de email
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@test.com')->subject('Test'));
```

---

## 🎯 MI RECOMENDACIÓN

**Para tu demostración:**
1. ✅ Usa **MAILTRAP** (más fácil y seguro)
2. ✅ Crea cuenta en 2 minutos
3. ✅ Configura credenciales en .env
4. ✅ Prueba con `cliente@test.com`
5. ✅ Muestra el email en Mailtrap

**Ventajas para la demo:**
- Puedes mostrar el email en tiempo real
- No necesitas email real
- Funciona al 100%
- Muy profesional

---

## 📞 SOPORTE

Si tienes problemas:
1. Verifica `.env`
2. Ejecuta `php artisan config:clear`
3. Revisa `storage/logs/laravel.log`
4. Prueba con `php artisan tinker`

---

**¿Listo para configurar? Sigue los pasos de OPCIÓN 1 (Mailtrap)** 🚀

# 📧 Configurar Correo Real

## 🎯 Situación Actual

✅ El sistema funciona perfectamente
✅ Los correos se generan correctamente
❌ No llegan a tu bandeja porque usas `MAIL_MAILER=log`

## 🔧 Solución: Configurar SMTP Real

### Opción 1: Mailtrap (RECOMENDADO para desarrollo)

Mailtrap es gratis y perfecto para desarrollo. Captura todos los correos sin enviarlos realmente.

1. Ve a https://mailtrap.io y crea una cuenta gratis
2. Crea un inbox
3. Copia las credenciales SMTP
4. Actualiza tu `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username_de_mailtrap
MAIL_PASSWORD=tu_password_de_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@fiftyone.com"
MAIL_FROM_NAME="FiftyOne"
```

### Opción 2: Gmail (para pruebas rápidas)

⚠️ Gmail requiere configuración especial de seguridad

1. Ve a tu cuenta de Google
2. Activa la verificación en 2 pasos
3. Genera una "Contraseña de aplicación" en https://myaccount.google.com/apppasswords
4. Actualiza tu `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña_de_aplicacion_de_16_digitos
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="tu_email@gmail.com"
MAIL_FROM_NAME="FiftyOne"
```

### Opción 3: Resend (RECOMENDADO para producción)

Resend es moderno, fácil y tiene plan gratis (3,000 correos/mes)

1. Ve a https://resend.com y crea una cuenta
2. Verifica tu dominio (o usa el dominio de prueba)
3. Crea una API Key
4. Actualiza tu `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend
MAIL_PASSWORD=tu_api_key_de_resend
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tudominio.com"
MAIL_FROM_NAME="FiftyOne"
```

## 🚀 Después de Configurar

1. Guarda los cambios en `.env`
2. Limpia la caché de configuración:
```bash
php artisan config:clear
```

3. Reinicia el worker de colas:
```bash
# Detén el worker actual (Ctrl+C)
# Inicia uno nuevo
php artisan queue:work
```

4. Prueba de nuevo la recuperación de contraseña
5. ¡Ahora SÍ te llegará el correo a tu bandeja!

## 🧪 Probar sin Configurar SMTP

Si no quieres configurar SMTP ahora, puedes:

1. Buscar el enlace en los logs:
```bash
Get-Content storage/logs/laravel.log -Tail 200 | Select-String "restablecer-contrasena"
```

2. Copiar el enlace completo
3. Pegarlo en tu navegador
4. ¡Listo! Puedes restablecer la contraseña

## 📊 Comparación de Servicios

| Servicio | Gratis | Fácil | Producción | Recomendado Para |
|----------|--------|-------|------------|------------------|
| Mailtrap | ✅ | ✅✅✅ | ❌ | Desarrollo |
| Gmail | ✅ | ⚠️ | ❌ | Pruebas rápidas |
| Resend | ✅ | ✅✅ | ✅ | Producción |
| Mailgun | ⚠️ | ✅ | ✅ | Producción |
| SendGrid | ⚠️ | ✅ | ✅ | Producción |

## ✅ Verificar que Funciona

Después de configurar, verás en el worker:
```
[2024-04-28 10:30:45][1] Processing: Illuminate\Notifications\SendQueuedNotifications
[2024-04-28 10:30:46][1] Processed:  Illuminate\Notifications\SendQueuedNotifications
```

Y el correo llegará a tu bandeja en segundos.

## 🐛 Troubleshooting

**Error: Connection refused**
- Verifica MAIL_HOST y MAIL_PORT
- Verifica que tu firewall no bloquee el puerto

**Error: Authentication failed**
- Verifica MAIL_USERNAME y MAIL_PASSWORD
- En Gmail, usa contraseña de aplicación, no tu contraseña normal

**El correo llega a spam**
- Normal en desarrollo
- En producción, configura SPF, DKIM y DMARC

**No llega nada**
- Verifica que el worker esté corriendo
- Revisa `storage/logs/laravel.log` para errores
- Ejecuta `php artisan queue:failed` para ver trabajos fallidos

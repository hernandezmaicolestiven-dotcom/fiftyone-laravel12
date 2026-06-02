# 🔐 ACTUALIZAR CONTRASEÑA DE APLICACIÓN DE GMAIL

**Fecha:** 1 de junio de 2026  
**Problema:** La contraseña de aplicación de Gmail ha expirado

---

## ⚠️ Error actual

```
Failed to authenticate on SMTP server with username "hernandezmaicolestiven@gmail.com"
Expected response code "235" but got code "535"
Username and Password not accepted
```

**Causa:** La contraseña de aplicación en `.env` ya no es válida.

---

## 📋 Pasos para generar nueva contraseña

### 1. **Ir a la configuración de Google**
   - Abre: https://myaccount.google.com/security
   - Inicia sesión con tu cuenta de Gmail

### 2. **Activar verificación en 2 pasos** (si no está activada)
   - Busca "Verificación en 2 pasos"
   - Haz clic en "Activar"
   - Sigue los pasos

### 3. **Generar contraseña de aplicación**
   - Ve a: https://myaccount.google.com/apppasswords
   - O busca "Contraseñas de aplicaciones" en la configuración
   - Selecciona "Correo" como aplicación
   - Selecciona "Otro (nombre personalizado)"
   - Escribe: "FiftyOne Laravel"
   - Haz clic en "Generar"

### 4. **Copiar la contraseña**
   - Google te mostrará una contraseña de 16 caracteres
   - Ejemplo: `abcd efgh ijkl mnop`
   - Cópiala (sin espacios)

### 5. **Actualizar el archivo `.env`**
   ```env
   MAIL_PASSWORD=abcdefghijklmnop
   ```
   (Reemplaza con tu contraseña real, sin espacios)

### 6. **Limpiar caché**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### 7. **Probar el envío**
   ```bash
   php scripts/send-test-password-reset.php
   ```

---

## 🔧 Alternativa: Usar Mailtrap (para desarrollo)

Si no quieres usar Gmail, puedes usar Mailtrap:

### 1. **Crear cuenta en Mailtrap**
   - Ve a: https://mailtrap.io
   - Regístrate gratis

### 2. **Obtener credenciales**
   - Ve a tu inbox
   - Copia las credenciales SMTP

### 3. **Actualizar `.env`**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=tu_username_mailtrap
   MAIL_PASSWORD=tu_password_mailtrap
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="noreply@fiftyone.com"
   ```

### 4. **Limpiar caché y probar**
   ```bash
   php artisan config:clear
   php scripts/send-test-password-reset.php
   ```

---

## ✅ Verificación

Después de actualizar la contraseña, verifica que funcione:

```bash
# 1. Limpiar tokens antiguos
php artisan tinker --execute="DB::table('password_reset_tokens')->truncate();"

# 2. Enviar email de prueba
php scripts/send-test-password-reset.php

# 3. Revisar tu bandeja de entrada
```

---

## 📝 Configuración actual en `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=hernandezmaicolestiven@gmail.com
MAIL_PASSWORD=rnfvzmhhlvmddcck  ← ESTA CONTRASEÑA YA NO ES VÁLIDA
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hernandezmaicolestiven@gmail.com
```

---

## 🚨 Importante

- **NO compartas** tu contraseña de aplicación
- **NO subas** el archivo `.env` a GitHub
- **Genera una nueva** contraseña si la anterior se compromete
- **Usa Mailtrap** para desarrollo si no quieres usar Gmail

---

## 📞 Soporte

Si tienes problemas:

1. Verifica que la verificación en 2 pasos esté activada
2. Asegúrate de copiar la contraseña sin espacios
3. Limpia el caché después de cambiar `.env`
4. Revisa los logs en `storage/logs/laravel.log`

---

**Estado:** ⚠️ REQUIERE ACTUALIZACIÓN DE CONTRASEÑA

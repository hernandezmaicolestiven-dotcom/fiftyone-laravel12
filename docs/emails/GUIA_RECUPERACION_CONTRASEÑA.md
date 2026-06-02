# 🔐 GUÍA COMPLETA: SISTEMA DE RECUPERACIÓN DE CONTRASEÑA

**Fecha:** 1 de junio de 2026  
**Estado:** ✅ Implementado - ⚠️ Requiere configuración de email

---

## 📋 ¿Qué hace este sistema?

Cuando un cliente olvida su contraseña:

1. **Va a la página de recuperación** (`/recuperar-contrasena`)
2. **Ingresa su email**
3. **Recibe un email** con una contraseña temporal (ej: `Temp6195!`)
4. **Inicia sesión** con la contraseña temporal
5. **Cambia su contraseña** desde "Mi Cuenta"

---

## ✅ Lo que YA está implementado

### 1. **Controlador** (`CustomerAuthController.php`)
- ✅ Método `sendResetLink()` que genera contraseña temporal
- ✅ Actualiza la contraseña en la base de datos
- ✅ Envía email con la contraseña temporal
- ✅ Manejo de errores

### 2. **Email** (`TemporaryPasswordMail.php`)
- ✅ Clase Mailable configurada
- ✅ Asunto: "🔐 Tu contraseña temporal - FiftyOne"
- ✅ Pasa usuario y contraseña temporal a la vista

### 3. **Vista del email** (`temporary-password.blade.php`)
- ✅ Diseño profesional con gradientes
- ✅ Contraseña temporal destacada
- ✅ Instrucciones paso a paso
- ✅ Botón para iniciar sesión
- ✅ Advertencia de seguridad

### 4. **Formulario de recuperación** (`forgot-password.blade.php`)
- ✅ Campo de email
- ✅ Validación
- ✅ Mensajes de éxito/error
- ✅ Enlace de regreso al login

### 5. **Ruta**
- ✅ `GET /recuperar-contrasena` → Muestra formulario
- ✅ `POST /recuperar-contrasena` → Envía contraseña temporal

---

## ⚠️ Lo que FALTA configurar

### **Configuración de email (CRÍTICO)**

El sistema está completo pero **no puede enviar emails** porque la contraseña de Gmail ha expirado.

**Error actual:**
```
535-5.7.8 Username and Password not accepted
```

---

## 🔧 SOLUCIÓN 1: Actualizar contraseña de Gmail (RECOMENDADO)

### Paso 1: Generar nueva contraseña de aplicación

1. **Ve a:** https://myaccount.google.com/apppasswords
2. **Inicia sesión** con tu cuenta de Gmail
3. **Selecciona:**
   - Aplicación: "Correo"
   - Dispositivo: "Otro (nombre personalizado)"
   - Nombre: "FiftyOne Laravel"
4. **Haz clic en "Generar"**
5. **Copia la contraseña** (16 caracteres sin espacios)

### Paso 2: Actualizar archivo `.env`

Abre el archivo `.env` y actualiza esta línea:

```env
MAIL_PASSWORD=tu_nueva_contraseña_aqui
```

**Ejemplo:**
```env
MAIL_PASSWORD=abcdefghijklmnop
```

### Paso 3: Limpiar caché

```bash
php artisan config:clear
php artisan cache:clear
```

### Paso 4: Probar el sistema

```bash
php scripts/test-email-recovery.php
```

---

## 🔧 SOLUCIÓN 2: Usar Mailtrap (para desarrollo)

Si no quieres usar Gmail, puedes usar Mailtrap (servicio gratuito para pruebas):

### Paso 1: Crear cuenta en Mailtrap

1. **Ve a:** https://mailtrap.io
2. **Regístrate gratis**
3. **Ve a tu inbox**
4. **Copia las credenciales SMTP**

### Paso 2: Actualizar archivo `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@fiftyone.com"
MAIL_FROM_NAME="FiftyOne"
```

### Paso 3: Limpiar caché y probar

```bash
php artisan config:clear
php scripts/test-email-recovery.php
```

---

## 🧪 Cómo probar el sistema completo

### Opción 1: Usar el script de prueba

```bash
php scripts/test-email-recovery.php
```

Este script:
- ✅ Verifica la configuración de email
- ✅ Genera una contraseña temporal
- ✅ Actualiza la contraseña en BD
- ✅ Intenta enviar el email
- ✅ Muestra diagnóstico detallado si hay errores

### Opción 2: Probar desde el navegador

1. **Inicia el servidor:**
   ```bash
   php artisan serve
   ```

2. **Ve a:** http://localhost:8000/recuperar-contrasena

3. **Ingresa el email:** hernandezmaicolestiven@gmail.com

4. **Haz clic en "Enviar contraseña temporal"**

5. **Revisa tu bandeja de entrada** (o Mailtrap si lo configuraste)

6. **Copia la contraseña temporal** del email

7. **Ve a:** http://localhost:8000/login

8. **Inicia sesión** con la contraseña temporal

9. **Ve a "Mi Cuenta"** y cambia tu contraseña

---

## 📧 Cómo se ve el email

El email que recibe el usuario tiene:

- **Asunto:** 🔐 Tu contraseña temporal - FiftyOne
- **Diseño:** Profesional con gradientes morados
- **Contenido:**
  - Saludo personalizado
  - Contraseña temporal destacada en un recuadro
  - Instrucciones paso a paso
  - Botón para iniciar sesión
  - Advertencia de seguridad
  - Footer con enlaces

**Ejemplo de contraseña temporal:** `Temp6195!`

---

## 🔍 Verificar que todo funciona

### 1. **Verificar configuración de email**

```bash
php artisan tinker
```

```php
config('mail.mailers.smtp.host');
config('mail.mailers.smtp.username');
config('mail.from.address');
```

### 2. **Verificar que la contraseña se actualiza en BD**

```bash
php artisan tinker
```

```php
$user = User::where('email', 'hernandezmaicolestiven@gmail.com')->first();
Hash::check('Temp1234!', $user->password); // Reemplaza con tu contraseña temporal
```

### 3. **Verificar logs**

```bash
tail -f storage/logs/laravel.log
```

---

## 🚨 Solución de problemas

### Error: "Username and Password not accepted"

**Causa:** La contraseña de aplicación de Gmail no es válida.

**Solución:**
1. Genera una nueva contraseña de aplicación
2. Actualiza `MAIL_PASSWORD` en `.env`
3. Ejecuta `php artisan config:clear`

### Error: "Connection refused"

**Causa:** No se puede conectar al servidor SMTP.

**Solución:**
1. Verifica tu conexión a internet
2. Verifica `MAIL_HOST` y `MAIL_PORT` en `.env`
3. Intenta cambiar el puerto de 587 a 465
4. Intenta cambiar la encriptación de `tls` a `ssl`

### El email no llega

**Causa:** Puede estar en spam o la configuración es incorrecta.

**Solución:**
1. Revisa la carpeta de spam
2. Verifica que `MAIL_FROM_ADDRESS` sea válido
3. Usa Mailtrap para desarrollo
4. Revisa los logs en `storage/logs/laravel.log`

### La contraseña temporal no funciona

**Causa:** La contraseña no se actualizó correctamente en BD.

**Solución:**
1. Ejecuta el script de prueba: `php scripts/test-email-recovery.php`
2. Verifica que la contraseña se haya actualizado en la tabla `users`
3. Asegúrate de copiar la contraseña exactamente como aparece en el email

---

## 📁 Archivos del sistema

```
app/
├── Http/Controllers/
│   └── CustomerAuthController.php          # Lógica de recuperación
├── Mail/
│   └── TemporaryPasswordMail.php           # Clase del email
└── Models/
    └── User.php                             # Modelo de usuario

resources/views/
├── customer/auth/
│   ├── forgot-password.blade.php           # Formulario de recuperación
│   └── login.blade.php                     # Formulario de login
└── emails/
    └── temporary-password.blade.php        # Template del email

routes/
└── web.php                                  # Rutas

scripts/
└── test-email-recovery.php                 # Script de prueba

.env                                         # Configuración de email
```

---

## 🎯 Flujo completo del sistema

```
Usuario olvida contraseña
         ↓
Va a /recuperar-contrasena
         ↓
Ingresa su email
         ↓
Sistema genera contraseña temporal (Temp####!)
         ↓
Sistema actualiza contraseña en BD
         ↓
Sistema envía email con contraseña temporal
         ↓
Usuario recibe email
         ↓
Usuario copia contraseña temporal
         ↓
Usuario va a /login
         ↓
Usuario inicia sesión con contraseña temporal
         ↓
Usuario va a "Mi Cuenta"
         ↓
Usuario cambia su contraseña
         ↓
✅ Proceso completado
```

---

## ✅ Checklist de implementación

- [x] Controlador con lógica de contraseña temporal
- [x] Clase Mailable para el email
- [x] Vista del email con diseño profesional
- [x] Formulario de recuperación
- [x] Ruta configurada
- [x] Script de prueba
- [ ] **Configurar email (Gmail o Mailtrap)** ← PENDIENTE
- [ ] Probar envío de email
- [ ] Probar inicio de sesión con contraseña temporal
- [ ] Probar cambio de contraseña desde "Mi Cuenta"

---

## 📞 Siguiente paso

**ACCIÓN REQUERIDA:** Actualizar la contraseña de Gmail

1. Ve a: https://myaccount.google.com/apppasswords
2. Genera una nueva contraseña de aplicación
3. Actualiza `MAIL_PASSWORD` en `.env`
4. Ejecuta: `php artisan config:clear`
5. Prueba: `php scripts/test-email-recovery.php`

**O usa Mailtrap** para desarrollo (más fácil y rápido)

---

**Estado:** ⚠️ Sistema implementado - Requiere configuración de email para funcionar


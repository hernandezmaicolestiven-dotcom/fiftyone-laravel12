# 📧 CÓMO OBTENER CREDENCIALES DE MAILTRAP

## 🎯 Objetivo
Encontrar tu **Username** y **Password** de Mailtrap para configurar el sistema de emails.

---

## 📍 UBICACIÓN DE LAS CREDENCIALES

### Paso 1: Entrar a Mailtrap
1. Ve a: **https://mailtrap.io**
2. Inicia sesión con tu cuenta

### Paso 2: Ir a Email Testing
En el menú lateral izquierdo, busca:
```
📧 Email Testing
   └─ Inboxes  ← HAZ CLIC AQUÍ
```

### Paso 3: Seleccionar tu Inbox
Verás una lista de "Inboxes". Por defecto hay uno llamado **"My Inbox"**.

**Haz clic en "My Inbox"** (o el nombre que tenga tu inbox).

### Paso 4: Ir a SMTP Settings
Una vez dentro del inbox, verás pestañas en la parte superior:

```
[Messages] [SMTP Settings] [POP3 Settings] [HTTP API]
              ↑
         HAZ CLIC AQUÍ
```

### Paso 5: Seleccionar Laravel
En la pestaña **"SMTP Settings"**, verás un dropdown que dice:
```
Select your integration: [Dropdown ▼]
```

**Selecciona: "Laravel 9+"**

### Paso 6: Copiar las Credenciales
Verás algo como esto:

```
┌─────────────────────────────────────────────────────────┐
│ Host:     sandbox.smtp.mailtrap.io                      │
│ Port:     2525                                          │
│ Username: 1a2b3c4d5e6f7g    ← COPIA ESTO               │
│ Password: 9h8i7j6k5l4m3n    ← COPIA ESTO               │
│ Auth:     PLAIN, LOGIN and CRAM-MD5                     │
│ TLS:      Optional (STARTTLS on all ports)             │
└─────────────────────────────────────────────────────────┘
```

**Copia el Username y el Password.**

---

## ✅ CONFIGURAR EN TU PROYECTO

### Opción 1: Usar el script automático (RECOMENDADO)
1. Ejecuta: `configurar-mailtrap.bat`
2. Pega tu Username cuando te lo pida
3. Pega tu Password cuando te lo pida
4. ¡Listo!

### Opción 2: Configurar manualmente
1. Abre tu archivo `.env`
2. Busca estas líneas:
   ```env
   MAIL_USERNAME=
   MAIL_PASSWORD=
   ```
3. Pega tus credenciales:
   ```env
   MAIL_USERNAME=1a2b3c4d5e6f7g
   MAIL_PASSWORD=9h8i7j6k5l4m3n
   ```
4. Guarda el archivo
5. Ejecuta en tu terminal:
   ```bash
   php artisan config:clear
   ```

---

## 🧪 PROBAR QUE FUNCIONA

### Opción 1: Desde el navegador
1. Ve a: http://localhost:8000/recuperar-contrasena
2. Ingresa: `cliente@test.com`
3. Clic en "Enviar enlace de recuperación"
4. Ve a Mailtrap → verás el email ahí

### Opción 2: Con el script
1. Ejecuta: `probar-emails.bat`
2. Selecciona opción 2
3. Ve a Mailtrap
4. Verás el email ahí

---

## 🔍 SOLUCIÓN DE PROBLEMAS

### No encuentro "SMTP Settings"
- Asegúrate de estar dentro de un inbox (haz clic en "My Inbox")
- Busca las pestañas en la parte superior de la página
- Si no las ves, busca un botón "Show Credentials" o "Integration"

### No veo las credenciales
- Asegúrate de haber seleccionado "Laravel 9+" en el dropdown
- Si no aparece el dropdown, busca un botón "Show Credentials"

### Las credenciales no funcionan
- Verifica que las hayas copiado completas (sin espacios extra)
- Asegúrate de haber ejecutado `php artisan config:clear`
- Revisa que en `.env` no haya espacios antes o después de las credenciales

---

## 📸 REFERENCIA VISUAL

La interfaz de Mailtrap se ve así:

```
┌─────────────────────────────────────────────────────────────┐
│ Mailtrap                                    [Tu cuenta] ▼   │
├──────────┬──────────────────────────────────────────────────┤
│          │                                                  │
│ Email    │  My Inbox                                        │
│ Testing  │  ┌────────────────────────────────────────────┐ │
│  └Inboxes│  │ [Messages] [SMTP Settings] [POP3] [HTTP]  │ │
│          │  │                                            │ │
│ Email    │  │  Select your integration: [Laravel 9+ ▼]  │ │
│ Sending  │  │                                            │ │
│          │  │  Host: sandbox.smtp.mailtrap.io           │ │
│ Billing  │  │  Port: 2525                               │ │
│          │  │  Username: 1a2b3c4d5e6f7g                 │ │
│ Settings │  │  Password: 9h8i7j6k5l4m3n                 │ │
│          │  └────────────────────────────────────────────┘ │
└──────────┴──────────────────────────────────────────────────┘
```

---

## 🎯 RESUMEN RÁPIDO

1. **Email Testing** → **Inboxes**
2. Clic en **"My Inbox"**
3. Pestaña **"SMTP Settings"**
4. Seleccionar **"Laravel 9+"**
5. Copiar **Username** y **Password**
6. Ejecutar **`configurar-mailtrap.bat`**
7. Pegar credenciales
8. ¡Listo!

---

## 💡 VENTAJAS DE MAILTRAP

✅ **No envía emails reales** (seguro para pruebas)  
✅ **Captura todos los emails** en una bandeja virtual  
✅ **Interfaz web** para verlos  
✅ **Gratis** para desarrollo  
✅ **Perfecto para demostración**  

---

## 📞 AYUDA ADICIONAL

Si sigues teniendo problemas, dime qué ves en tu pantalla:

- **A)** Veo el menú lateral pero no encuentro "Inboxes"
- **B)** Estoy en "Inboxes" pero no veo "SMTP Settings"
- **C)** Veo "SMTP Settings" pero no aparecen las credenciales
- **D)** Ya las encontré y las copié

---

## ✨ CUANDO LAS TENGAS

Ejecuta:
```bash
configurar-mailtrap.bat
```

Y pega tus credenciales cuando te las pida.

¡En 30 segundos estará todo funcionando! 🚀

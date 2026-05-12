# 🚀 CÓMO ACTIVAR WOMPI REAL - GUÍA COMPLETA

## 📋 PROBLEMA ACTUAL
El widget de Wompi NO funciona desde `localhost` porque requiere:
- ✅ Conexión HTTPS (certificado SSL)
- ✅ Dominio válido (no puede ser localhost o IP)

## 🎯 SOLUCIONES DISPONIBLES

---

## ✨ OPCIÓN 1: NGROK (MÁS RÁPIDA - GRATIS)
**Tiempo: 5 minutos | Costo: GRATIS**

Ngrok crea un túnel HTTPS desde tu localhost hacia internet.

### Paso 1: Descargar ngrok
1. Ve a: https://ngrok.com/download
2. Descarga la versión para Windows
3. Descomprime el archivo `ngrok.exe`

### Paso 2: Crear cuenta (gratis)
1. Regístrate en: https://dashboard.ngrok.com/signup
2. Copia tu token de autenticación

### Paso 3: Configurar ngrok
```bash
# Abre CMD o PowerShell en la carpeta donde está ngrok.exe
ngrok config add-authtoken TU_TOKEN_AQUI
```

### Paso 4: Iniciar el túnel
```bash
# En una terminal, inicia tu servidor Laravel
php artisan serve

# En OTRA terminal, inicia ngrok
ngrok http 8000
```

### Paso 5: Usar la URL de ngrok
Ngrok te dará una URL como: `https://abc123.ngrok.io`

**Actualiza tu `.env`:**
```env
APP_URL=https://abc123.ngrok.io
```

```bash
php artisan config:clear
```

### Paso 6: Configurar webhooks en Wompi
1. Ve a tu cuenta de Wompi
2. Configura el webhook: `https://abc123.ngrok.io/api/wompi/webhook`

✅ **LISTO! Ahora Wompi funcionará con pagos reales**

**NOTA:** La URL de ngrok cambia cada vez que lo reinicias (versión gratis). Para URL permanente necesitas la versión de pago ($8/mes).

---

## 🌐 OPCIÓN 2: RAILWAY (HOSTING GRATIS)
**Tiempo: 15 minutos | Costo: GRATIS (con límites)**

Railway ofrece hosting gratuito con HTTPS automático.

### Paso 1: Crear cuenta
1. Ve a: https://railway.app
2. Regístrate con GitHub

### Paso 2: Preparar tu proyecto
```bash
# Asegúrate de tener un Procfile
echo "web: php artisan serve --host=0.0.0.0 --port=$PORT" > Procfile

# Commit los cambios
git add .
git commit -m "Preparar para Railway"
```

### Paso 3: Desplegar
1. En Railway, haz clic en "New Project"
2. Selecciona "Deploy from GitHub repo"
3. Conecta tu repositorio
4. Railway detectará Laravel automáticamente

### Paso 4: Configurar variables de entorno
En Railway, ve a "Variables" y agrega:
```
APP_KEY=tu_app_key
DB_CONNECTION=mysql
DB_HOST=tu_host_mysql
DB_DATABASE=tu_database
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
WOMPI_PUBLIC_KEY=pub_prod_ddFHxUwUp7QogxAzPOLkWUADlI7Ny1VB
WOMPI_PRIVATE_KEY=prv_prod_eq3pH2zdcxzaaxJgacfdxeNCcAAOb2c0
WOMPI_INTEGRITY_SECRET=prod_integrity_BGF2If8aU6F5EPxXyM0cf4uFy4prr6VJ
WOMPI_EVENTS_SECRET=prod_events_NU36ScgMzZTJskBU0AeTMpF5284X3SJF
WOMPI_SANDBOX=false
```

### Paso 5: Obtener tu URL
Railway te dará una URL como: `https://tu-proyecto.up.railway.app`

✅ **LISTO! Tu tienda está en producción con HTTPS**

---

## 💎 OPCIÓN 3: HEROKU (HOSTING GRATIS)
**Tiempo: 20 minutos | Costo: GRATIS (con límites)**

### Paso 1: Crear cuenta
1. Ve a: https://heroku.com
2. Regístrate gratis

### Paso 2: Instalar Heroku CLI
Descarga desde: https://devcenter.heroku.com/articles/heroku-cli

### Paso 3: Desplegar
```bash
# Login
heroku login

# Crear app
heroku create tu-tienda-fiftyone

# Agregar buildpack de PHP
heroku buildpacks:set heroku/php

# Configurar variables
heroku config:set APP_KEY=tu_app_key
heroku config:set WOMPI_PUBLIC_KEY=pub_prod_ddFHxUwUp7QogxAzPOLkWUADlI7Ny1VB
heroku config:set WOMPI_PRIVATE_KEY=prv_prod_eq3pH2zdcxzaaxJgacfdxeNCcAAOb2c0
heroku config:set WOMPI_SANDBOX=false

# Desplegar
git push heroku main
```

✅ **Tu URL será:** `https://tu-tienda-fiftyone.herokuapp.com`

---

## 🏢 OPCIÓN 4: HOSTING TRADICIONAL
**Tiempo: Variable | Costo: Desde $5/mes**

### Proveedores recomendados en Colombia:
- **Hostinger** - Desde $3/mes - https://hostinger.co
- **SiteGround** - Desde $7/mes - https://siteground.com
- **DigitalOcean** - Desde $5/mes - https://digitalocean.com
- **Vultr** - Desde $5/mes - https://vultr.com

### Requisitos mínimos:
- PHP 8.2+
- MySQL 8.0+
- Composer
- SSL/HTTPS (Let's Encrypt gratis)
- 1GB RAM mínimo

---

## 🎯 RECOMENDACIÓN SEGÚN TU CASO

### Para DEMOSTRACIÓN/PRUEBAS (hoy mismo):
✅ **USA NGROK** - 5 minutos, gratis, funciona inmediatamente

### Para PROYECTO ACADÉMICO:
✅ **USA RAILWAY** - Gratis, permanente, fácil de configurar

### Para PRODUCCIÓN REAL:
✅ **USA HOSTING TRADICIONAL** - Más control, mejor rendimiento

---

## 📝 PASOS DESPUÉS DE TENER HTTPS

### 1. Actualizar APP_URL en .env
```env
APP_URL=https://tu-dominio.com
```

### 2. Limpiar caché
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 3. Configurar webhooks en Wompi
Ve a tu cuenta de Wompi y configura:
- **URL del webhook:** `https://tu-dominio.com/api/wompi/webhook`
- **Eventos:** Todos los eventos de transacción

### 4. Probar el pago
1. Ve a tu tienda: `https://tu-dominio.com`
2. Agrega productos al carrito
3. Procede al checkout
4. Selecciona Wompi
5. El widget se abrirá correctamente
6. Completa el pago con tarjeta de prueba o real

---

## 🧪 TARJETAS DE PRUEBA WOMPI

Para probar sin gastar dinero real:

**Tarjeta aprobada:**
- Número: `4242 4242 4242 4242`
- Fecha: Cualquier fecha futura
- CVV: Cualquier 3 dígitos

**Tarjeta rechazada:**
- Número: `4111 1111 1111 1111`

---

## ⚠️ IMPORTANTE

1. **NUNCA subas el archivo `.env` a GitHub** - Ya está en `.gitignore`
2. **Las llaves de Wompi son REALES** - Los pagos cobran dinero real
3. **Configura los webhooks** - Para que los pedidos se actualicen automáticamente
4. **Haz backup de la base de datos** - Antes de subir a producción

---

## 🆘 SOLUCIÓN DE PROBLEMAS

### El widget no se abre
- Verifica que la URL sea HTTPS (no HTTP)
- Verifica que no sea localhost o 127.0.0.1
- Abre la consola del navegador (F12) y busca errores

### Los pagos no se reflejan
- Verifica que los webhooks estén configurados
- Revisa los logs: `storage/logs/laravel.log`
- Verifica que `WOMPI_EVENTS_SECRET` sea correcto

### Error 500
- Revisa los logs del servidor
- Verifica que todas las variables de entorno estén configuradas
- Ejecuta `php artisan migrate` en el servidor

---

## 📞 SOPORTE

Si tienes problemas:
1. Revisa los logs: `storage/logs/laravel.log`
2. Verifica la consola del navegador (F12)
3. Contacta soporte de Wompi: https://wompi.com/soporte

---

**¿Cuál opción prefieres? Te ayudo a configurarla paso a paso.**

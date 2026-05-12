# 🔒 CREAR HTTPS LOCAL EN WINDOWS PARA WOMPI

## ⚠️ PROBLEMA
Wompi requiere HTTPS para funcionar. `php artisan serve` solo crea HTTP, no HTTPS.

## 🎯 SOLUCIONES PARA WINDOWS

---

## ✨ OPCIÓN 1: LARAGON (⭐ RECOMENDADA)
**Tiempo: 10 minutos | Dificultad: Fácil | Costo: GRATIS**

Laragon es como XAMPP pero moderno y con SSL automático.

### Paso 1: Descargar Laragon
1. Ve a: https://laragon.org/download/
2. Descarga "Laragon Full" (incluye PHP, MySQL, Apache)
3. Instala (siguiente, siguiente, finalizar)

### Paso 2: Copiar tu proyecto
```bash
# Copia tu carpeta del proyecto a:
C:\laragon\www\fiftyone
```

### Paso 3: Activar SSL en Laragon
1. Abre Laragon
2. Clic derecho en el ícono de Laragon (bandeja del sistema)
3. **Apache** → **SSL** → **Enabled** ✅
4. **Apache** → **Virtual Hosts** → **fiftyone.test (auto)**

### Paso 4: Configurar Laravel
Actualiza tu `.env`:
```env
APP_URL=https://fiftyone.test
```

Limpia caché:
```bash
php artisan config:clear
php artisan cache:clear
```

### Paso 5: Acceder
Abre tu navegador: **https://fiftyone.test**

✅ **LISTO!** Wompi funcionará perfectamente.

**VENTAJAS:**
- ✅ SSL automático (certificado válido)
- ✅ Dominio local bonito (.test)
- ✅ Incluye MySQL, phpMyAdmin
- ✅ Fácil de usar
- ✅ Wompi funciona al 100%

---

## 🚀 OPCIÓN 2: NGROK (⚡ MÁS RÁPIDA)
**Tiempo: 5 minutos | Dificultad: Muy fácil | Costo: GRATIS**

Ngrok crea un túnel HTTPS desde tu PC hacia internet.

### Paso 1: Descargar ngrok
1. Ve a: https://ngrok.com/download
2. Descarga para Windows
3. Descomprime `ngrok.exe` en cualquier carpeta

### Paso 2: Crear cuenta
1. Regístrate gratis: https://dashboard.ngrok.com/signup
2. Copia tu token de autenticación

### Paso 3: Configurar ngrok
Abre CMD en la carpeta donde está `ngrok.exe`:
```bash
ngrok config add-authtoken TU_TOKEN_AQUI
```

### Paso 4: Iniciar Laravel
En una terminal:
```bash
php artisan serve
```

### Paso 5: Iniciar ngrok
En OTRA terminal (en la carpeta de ngrok):
```bash
ngrok http 8000
```

### Paso 6: Usar la URL
Ngrok te dará una URL como: `https://abc123.ngrok.io`

Actualiza tu `.env`:
```env
APP_URL=https://abc123.ngrok.io
```

```bash
php artisan config:clear
```

✅ **LISTO!** Accede a tu tienda con esa URL.

**VENTAJAS:**
- ✅ Muy rápido de configurar
- ✅ HTTPS real
- ✅ Funciona desde cualquier dispositivo
- ✅ Wompi funciona al 100%

**DESVENTAJAS:**
- ⚠️ La URL cambia cada vez que reinicias ngrok
- ⚠️ Requiere internet

---

## 🔐 OPCIÓN 3: CERTIFICADO SSL MANUAL
**Tiempo: 20 minutos | Dificultad: Media | Costo: GRATIS**

Crear un certificado SSL autofirmado.

### Paso 1: Instalar OpenSSL
1. Descarga: https://slproweb.com/products/Win32OpenSSL.html
2. Instala "Win64 OpenSSL v3.x.x Light"

### Paso 2: Generar certificado
Ejecuta el script:
```bash
generar-certificado-ssl.bat
```

O manualmente:
```bash
mkdir ssl
cd ssl
openssl genrsa -out fiftyone.key 2048
openssl req -new -x509 -key fiftyone.key -out fiftyone.crt -days 365 -subj "/C=CO/ST=Bogota/L=Bogota/O=FiftyOne/CN=fiftyone.local"
```

### Paso 3: Instalar certificado en Windows
1. Doble clic en `ssl\fiftyone.crt`
2. Clic en "Instalar certificado"
3. Seleccionar "Equipo local"
4. Siguiente → Siguiente → Finalizar

### Paso 4: Configurar archivo hosts
Abre como **ADMINISTRADOR**:
```
C:\Windows\System32\drivers\etc\hosts
```

Agrega al final:
```
127.0.0.1 fiftyone.local
```

Guarda y cierra.

### Paso 5: Actualizar .env
```env
APP_URL=https://fiftyone.local:8000
```

### Paso 6: Iniciar servidor
```bash
php artisan serve --host=fiftyone.local --port=8000
```

### Paso 7: Acceder
Abre: **http://fiftyone.local:8000**

⚠️ **PROBLEMA:** `php artisan serve` NO soporta SSL real.

**DESVENTAJAS:**
- ❌ `php artisan serve` no soporta SSL
- ❌ Wompi NO funcionará
- ❌ Mucha configuración manual

---

## 📊 COMPARACIÓN

| Característica | Laragon | Ngrok | SSL Manual |
|---------------|---------|-------|------------|
| Tiempo setup | 10 min | 5 min | 20 min |
| Dificultad | Fácil | Muy fácil | Media |
| SSL real | ✅ Sí | ✅ Sí | ❌ No |
| Wompi funciona | ✅ Sí | ✅ Sí | ❌ No |
| URL permanente | ✅ Sí | ❌ No | ✅ Sí |
| Requiere internet | ❌ No | ✅ Sí | ❌ No |
| Dominio bonito | ✅ .test | ❌ ngrok.io | ✅ .local |

---

## 🏆 RECOMENDACIÓN FINAL

### Para DESARROLLO LOCAL:
✅ **USA LARAGON**
- Fácil de instalar
- SSL automático
- Dominio bonito
- Wompi funciona perfectamente

### Para DEMOSTRACIÓN RÁPIDA:
✅ **USA NGROK**
- Listo en 5 minutos
- Puedes compartir la URL
- Wompi funciona perfectamente

### Para PRODUCCIÓN:
✅ **USA HOSTING REAL**
- Railway, Heroku, o hosting tradicional
- SSL incluido
- Mejor rendimiento

---

## 🚀 INICIO RÁPIDO

### Con Laragon:
```bash
# 1. Instala Laragon
# 2. Copia proyecto a C:\laragon\www\fiftyone
# 3. Activa SSL en Laragon
# 4. Abre https://fiftyone.test
```

### Con Ngrok:
```bash
# Terminal 1
php artisan serve

# Terminal 2
ngrok http 8000

# Usa la URL HTTPS que te da ngrok
```

---

## 🆘 SOLUCIÓN DE PROBLEMAS

### Laragon no inicia
- Verifica que no tengas XAMPP o WAMP corriendo
- Cierra Skype (usa el puerto 80)
- Ejecuta Laragon como administrador

### Ngrok dice "command not found"
- Asegúrate de estar en la carpeta donde está `ngrok.exe`
- O agrega ngrok al PATH de Windows

### El certificado no es confiable
- Es normal con certificados autofirmados
- Haz clic en "Avanzado" → "Continuar al sitio"
- O usa Laragon que genera certificados válidos

### Wompi no se abre
- Verifica que la URL sea HTTPS (no HTTP)
- Verifica que no sea localhost o 127.0.0.1
- Abre la consola del navegador (F12) y busca errores

---

## 📝 SCRIPTS DISPONIBLES

- `crear-https-local.bat` - Menú con todas las opciones
- `generar-certificado-ssl.bat` - Genera certificado SSL
- `iniciar-con-https.bat` - Inicia servidor con opciones
- `configurar-ngrok.bat` - Instrucciones de ngrok

---

## 💡 CONSEJO FINAL

Para tu proyecto académico, te recomiendo:

1. **Desarrollo diario:** Usa Laragon
2. **Demostración al instructor:** Usa Ngrok
3. **Entrega final:** Sube a Railway o Heroku

Así tendrás lo mejor de ambos mundos: comodidad local + demostración profesional.

---

**¿Necesitas ayuda? Ejecuta:** `crear-https-local.bat`

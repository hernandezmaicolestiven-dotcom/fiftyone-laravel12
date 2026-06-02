# 🔧 CONFIGURAR WOMPI CON LLAVES REALES

## 📋 PASO 1: Obtener las llaves de Wompi

Deberías tener estas llaves de tu cuenta en https://comercios.wompi.co/

1. **Public Key** (Llave pública)
   - Formato: `pub_test_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX`
   - Se usa en el frontend

2. **Private Key** (Llave privada)
   - Formato: `prv_test_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX`
   - Se usa en el backend (NUNCA exponerla)

3. **Integrity Secret** (Secreto de integridad)
   - Formato: `test_integrity_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX`
   - Se usa para firmar transacciones

4. **Events Secret** (Secreto de eventos)
   - Formato: `test_events_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX`
   - Se usa para validar webhooks

---

## 🔧 PASO 2: Actualizar el archivo .env

Abre el archivo `.env` y reemplaza estas líneas:

```env
# ── WOMPI PAYMENT GATEWAY ───────────────────────────────────────────────────
WOMPI_PUBLIC_KEY=TU_PUBLIC_KEY_AQUI
WOMPI_PRIVATE_KEY=TU_PRIVATE_KEY_AQUI
WOMPI_INTEGRITY_SECRET=TU_INTEGRITY_SECRET_AQUI
WOMPI_EVENTS_SECRET=TU_EVENTS_SECRET_AQUI
WOMPI_SANDBOX=true
```

**IMPORTANTE**: 
- Mantén `WOMPI_SANDBOX=true` si estás usando llaves de prueba (test)
- Cambia a `WOMPI_SANDBOX=false` solo cuando uses llaves de producción (prod)

---

## 🔧 PASO 3: Modificar el servicio de Wompi

Necesitas eliminar la detección del modo DEMO.

Abre el archivo: `app/Services/WompiService.php`

Busca este método:

```php
public function getCheckoutUrl(): string
{
    // MODO DEMO: Usar checkout local si las llaves son de ejemplo
    if ($this->publicKey === 'pub_test_g9nwhQUmlbbvKLyfKUWxgSi5iWiXGhzv') {
        return url('/wompi-demo.html');
    }
    
    return $this->isSandbox
        ? 'https://checkout.wompi.co/p/'
        : 'https://checkout.wompi.co/p/';
}
```

Y reemplázalo por:

```php
public function getCheckoutUrl(): string
{
    return $this->isSandbox
        ? 'https://checkout.wompi.co/p/'
        : 'https://checkout.wompi.co/p/';
}
```

---

## 🔧 PASO 4: Modificar el frontend

Abre el archivo: `resources/views/welcome.blade.php`

Busca esta sección (alrededor de la línea 492):

```javascript
if (wompiData.success && wompiData.checkout_data) {
  // MODO DEMO: Usar checkout local
  const checkoutUrl = '/wompi-demo.html?' + new URLSearchParams({
    'amount-in-cents': wompiData.checkout_data.amount_in_cents,
    'reference': wompiData.checkout_data.reference,
    'redirect-url': wompiData.checkout_data.redirect_url,
  }).toString();

  console.log('🌐 Redirigiendo a Wompi DEMO:', checkoutUrl);
  
  // Redirigir al checkout DEMO
  window.location.href = checkoutUrl;
}
```

Y reemplázalo por:

```javascript
if (wompiData.success && wompiData.checkout_data) {
  // Construir URL del checkout REAL de Wompi
  const checkoutUrl = wompiData.checkout_url + '?' + new URLSearchParams({
    'public-key': wompiData.checkout_data.public_key,
    'currency': wompiData.checkout_data.currency,
    'amount-in-cents': wompiData.checkout_data.amount_in_cents,
    'reference': wompiData.checkout_data.reference,
    'signature:integrity': wompiData.checkout_data.signature,
    'redirect-url': wompiData.checkout_data.redirect_url,
    'customer-data:email': wompiData.checkout_data.customer_email,
    'customer-data:full-name': wompiData.checkout_data.customer_data.name,
    'customer-data:phone-number': wompiData.checkout_data.customer_data.phone,
  }).toString();

  console.log('🌐 Redirigiendo a Wompi REAL:', checkoutUrl);
  
  // Redirigir al checkout REAL de Wompi
  window.location.href = checkoutUrl;
}
```

---

## 🔧 PASO 5: Limpiar caché

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## 🔧 PASO 6: Configurar webhooks en Wompi

1. Ve a tu panel de Wompi: https://comercios.wompi.co/
2. Busca la sección de "Webhooks" o "Eventos"
3. Configura esta URL:
   ```
   https://tudominio.com/api/wompi/webhook
   ```
   
   **NOTA**: Si estás en desarrollo local, necesitas usar un servicio como:
   - ngrok: https://ngrok.com/
   - localtunnel: https://localtunnel.github.io/www/
   
   Para exponer tu localhost a internet temporalmente.

---

## 🧪 PASO 7: Probar

1. Reinicia el servidor:
   ```bash
   php artisan serve
   ```

2. Limpia el caché del navegador:
   - Presiona `Ctrl + Shift + R`

3. Prueba el checkout:
   - Agrega un producto al carrito
   - Ve al checkout
   - Selecciona Wompi
   - Deberías ser redirigido al checkout REAL de Wompi

4. Usa tarjetas de prueba de Wompi:
   - **Aprobada**: 4242 4242 4242 4242
   - **Rechazada**: 4111 1111 1111 1111
   - Fecha: Cualquier fecha futura
   - CVV: Cualquier 3 dígitos

---

## ⚠️ IMPORTANTE

### Para desarrollo local:

Si estás probando en `localhost`, los webhooks NO funcionarán porque Wompi no puede acceder a tu computadora.

**Soluciones**:

1. **ngrok** (Recomendado):
   ```bash
   ngrok http 8000
   ```
   Te dará una URL pública como: `https://abc123.ngrok.io`
   
   Usa esa URL en la configuración de webhooks de Wompi:
   ```
   https://abc123.ngrok.io/api/wompi/webhook
   ```

2. **Desplegar en un servidor real**:
   - Heroku
   - DigitalOcean
   - AWS
   - Etc.

### Para producción:

1. Cambia `WOMPI_SANDBOX=false` en `.env`
2. Usa llaves de producción (empiezan con `pub_prod_` y `prv_prod_`)
3. Configura webhooks con tu dominio real
4. Asegúrate de tener HTTPS (certificado SSL)

---

## 📝 RESUMEN DE CAMBIOS

| Archivo | Cambio |
|---------|--------|
| `.env` | Actualizar llaves reales |
| `app/Services/WompiService.php` | Eliminar detección de modo DEMO |
| `resources/views/welcome.blade.php` | Cambiar a checkout real de Wompi |

---

## ✅ VERIFICACIÓN

Después de hacer los cambios, ejecuta:

```bash
php scripts/test-wompi-direct.php
```

Deberías ver:
- ✅ Public Key: `pub_test_TU_LLAVE`
- ✅ Checkout URL: `https://checkout.wompi.co/p/`

---

**Fecha**: 11 de mayo de 2026
**Versión**: 3.0.0 (Wompi Real)

# 🚀 Integración Wompi - FiftyOne

## 📋 Índice
1. [Descripción](#descripción)
2. [Arquitectura](#arquitectura)
3. [Configuración](#configuración)
4. [Flujo de Pago](#flujo-de-pago)
5. [Testing en Sandbox](#testing-en-sandbox)
6. [Producción](#producción)
7. [Webhooks](#webhooks)
8. [Troubleshooting](#troubleshooting)

---

## 📖 Descripción

Integración completa con **Wompi**, la pasarela de pagos líder en Colombia. Permite procesar pagos online de forma segura con múltiples métodos de pago.

### Características implementadas:
- ✅ Checkout embebido de Wompi
- ✅ Firma de integridad (backend)
- ✅ Webhooks automáticos
- ✅ Validación de transacciones
- ✅ Actualización automática de órdenes
- ✅ Logs completos
- ✅ Modo Sandbox y Producción
- ✅ Interfaz moderna integrada

---

## 🏗️ Arquitectura

```
┌─────────────┐
│   Cliente   │
│  (React)    │
└──────┬──────┘
       │ 1. Crear orden
       ▼
┌─────────────────┐
│   Laravel API   │
│  OrderController│
└──────┬──────────┘
       │ 2. Crear transacción Wompi
       ▼
┌─────────────────┐
│  WompiService   │
│  (Backend)      │
└──────┬──────────┘
       │ 3. Generar firma
       │ 4. Retornar datos checkout
       ▼
┌─────────────────┐
│   Cliente       │
│  Redirige a     │
│  Wompi Checkout │
└──────┬──────────┘
       │ 5. Usuario paga
       ▼
┌─────────────────┐
│  Wompi          │
│  Procesa pago   │
└──────┬──────────┘
       │ 6. Webhook
       ▼
┌─────────────────┐
│  WompiController│
│  /webhook       │
└──────┬──────────┘
       │ 7. Actualizar orden
       ▼
┌─────────────────┐
│   Base de Datos │
│  orders         │
│  wompi_payments │
└─────────────────┘
```

### Componentes principales:

1. **WompiService** (`app/Services/WompiService.php`)
   - Lógica central de integración
   - Generación de firmas
   - Consultas a API de Wompi
   - Validación de webhooks

2. **WompiController** (`app/Http/Controllers/WompiController.php`)
   - Endpoints públicos
   - Manejo de callbacks
   - Procesamiento de webhooks

3. **WompiPayment Model** (`app/Models/WompiPayment.php`)
   - Almacenamiento de transacciones
   - Relación con órdenes
   - Estados de pago

4. **Frontend React** (integrado en `welcome.blade.php`)
   - Selección de método de pago
   - Redirección a Wompi
   - Manejo de respuestas

---

## ⚙️ Configuración

### 1. Variables de entorno

Agrega estas variables a tu archivo `.env`:

```bash
# ── WOMPI PAYMENT GATEWAY ───────────────────────────────────────────────────
# Obtén tus llaves en: https://comercios.wompi.co/

# SANDBOX (Pruebas)
WOMPI_PUBLIC_KEY=pub_test_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
WOMPI_PRIVATE_KEY=prv_test_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
WOMPI_INTEGRITY_SECRET=test_integrity_XXXXXXXXXXXXXXXX
WOMPI_EVENTS_SECRET=test_events_XXXXXXXXXXXXXXXX
WOMPI_SANDBOX=true
```

### 2. Obtener llaves de Wompi

1. Regístrate en [https://comercios.wompi.co/](https://comercios.wompi.co/)
2. Ve a **Configuración → API Keys**
3. Copia las llaves de **Sandbox** (prefijo `pub_test_` y `prv_test_`)
4. Copia los secretos de **Integridad** y **Eventos**

### 3. Ejecutar migraciones

```bash
php artisan migrate
```

Esto creará la tabla `wompi_payments` con todos los campos necesarios.

### 4. Configurar webhook en Wompi

1. Ve a tu panel de Wompi → **Webhooks**
2. Agrega la URL: `https://tudominio.com/api/wompi/webhook`
3. Selecciona los eventos:
   - `transaction.updated`
   - `transaction.approved`
   - `transaction.declined`

**IMPORTANTE:** El webhook debe ser accesible públicamente. En desarrollo local usa **ngrok** o similar.

---

## 💳 Flujo de Pago

### Paso a paso:

1. **Usuario agrega productos al carrito**
   - Frontend React maneja el carrito en localStorage

2. **Usuario hace checkout**
   - Completa datos de envío
   - Selecciona "Wompi" como método de pago

3. **Se crea la orden**
   - POST `/orders` crea la orden en la base de datos
   - Estado inicial: `pending`

4. **Se crea transacción Wompi**
   - POST `/api/wompi/create-transaction`
   - `WompiService` genera:
     - Referencia única
     - Firma de integridad
     - Datos del checkout

5. **Redirección a Wompi**
   - Usuario es redirigido a `checkout.wompi.co`
   - Completa el pago en la plataforma de Wompi

6. **Wompi procesa el pago**
   - Tarjeta de crédito/débito
   - PSE
   - Nequi
   - Otros métodos

7. **Callback y Webhook**
   - Wompi redirige al usuario: `/wompi/callback`
   - Wompi envía webhook: `/api/wompi/webhook`

8. **Actualización de orden**
   - Estado del pago se actualiza
   - Orden cambia a `confirmed` o `cancelled`
   - Se envía notificación al cliente

---

## 🧪 Testing en Sandbox

### Tarjetas de prueba

Wompi proporciona tarjetas de prueba para simular diferentes escenarios:

#### ✅ Transacción aprobada
```
Número: 4242 4242 4242 4242
CVV: 123
Fecha: Cualquier fecha futura
Nombre: Cualquier nombre
```

#### ❌ Transacción rechazada
```
Número: 4111 1111 1111 1111
CVV: 123
Fecha: Cualquier fecha futura
```

#### ⏳ Transacción pendiente
```
Número: 5555 5555 5555 4444
CVV: 123
Fecha: Cualquier fecha futura
```

### Datos de prueba PSE

- **Banco:** Banco de Pruebas
- **Tipo de persona:** Natural
- **Documento:** 123456789
- **Resultado:** Selecciona "Aprobada" en el simulador

### Probar el flujo completo

1. Inicia sesión en tu tienda
2. Agrega productos al carrito
3. Ve al checkout
4. Completa los datos de envío
5. Selecciona **Wompi** como método de pago
6. Usa una tarjeta de prueba
7. Verifica que:
   - La orden se crea correctamente
   - Eres redirigido a Wompi
   - El pago se procesa
   - Regresas a tu sitio
   - La orden se actualiza

### Verificar logs

```bash
# Ver logs de Laravel
tail -f storage/logs/laravel.log | grep -i wompi

# Buscar transacciones en la base de datos
php artisan tinker
>>> \App\Models\WompiPayment::latest()->get()
```

---

## 🚀 Producción

### Checklist antes de ir a producción:

- [ ] Obtener llaves de **producción** de Wompi
- [ ] Actualizar `.env` con llaves de producción
- [ ] Cambiar `WOMPI_SANDBOX=false`
- [ ] Configurar webhook en producción
- [ ] Probar con transacción real pequeña
- [ ] Configurar monitoreo de errores
- [ ] Revisar logs regularmente

### Cambiar a producción

1. **Obtener llaves de producción:**
   - Ve a Wompi → Configuración → API Keys
   - Copia las llaves con prefijo `pub_prod_` y `prv_prod_`

2. **Actualizar `.env`:**
```bash
WOMPI_PUBLIC_KEY=pub_prod_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
WOMPI_PRIVATE_KEY=prv_prod_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
WOMPI_INTEGRITY_SECRET=prod_integrity_XXXXXXXXXXXXXXXX
WOMPI_EVENTS_SECRET=prod_events_XXXXXXXXXXXXXXXX
WOMPI_SANDBOX=false
```

3. **Limpiar caché:**
```bash
php artisan config:clear
php artisan cache:clear
```

4. **Configurar webhook de producción:**
   - URL: `https://tudominio.com/api/wompi/webhook`
   - Eventos: `transaction.*`

---

## 🔔 Webhooks

### ¿Qué son los webhooks?

Los webhooks son notificaciones automáticas que Wompi envía a tu servidor cuando ocurre un evento (pago aprobado, rechazado, etc.).

### Endpoint del webhook

```
POST /api/wompi/webhook
```

### Seguridad

El webhook valida:
1. **Firma del evento** (header `X-Event-Checksum`)
2. **IP de origen** (opcional, puedes agregar whitelist)
3. **Duplicados** (evita procesar el mismo evento dos veces)

### Eventos procesados

- `transaction.updated` - Transacción actualizada
- `transaction.approved` - Pago aprobado
- `transaction.declined` - Pago rechazado
- `transaction.voided` - Pago anulado

### Logs del webhook

Todos los webhooks se registran en:
- `storage/logs/laravel.log`
- Tabla `wompi_payments` (campo `webhook_data`)

### Testing de webhooks localmente

Usa **ngrok** para exponer tu servidor local:

```bash
# Instalar ngrok
brew install ngrok  # macOS
# o descarga desde https://ngrok.com/

# Exponer puerto 8000
ngrok http 8000

# Copiar la URL HTTPS generada
# Ejemplo: https://abc123.ngrok.io

# Configurar en Wompi:
# https://abc123.ngrok.io/api/wompi/webhook
```

---

## 🐛 Troubleshooting

### Problema: "WOMPI_PUBLIC_KEY no está configurada"

**Solución:**
```bash
# Verificar que las variables estén en .env
cat .env | grep WOMPI

# Limpiar caché de configuración
php artisan config:clear
```

### Problema: "Firma de integridad inválida"

**Causas comunes:**
- Llave de integridad incorrecta
- Monto en centavos mal calculado
- Referencia duplicada

**Solución:**
```bash
# Verificar logs
tail -f storage/logs/laravel.log | grep -i "integrity"

# Regenerar transacción
php artisan tinker
>>> $payment = \App\Models\WompiPayment::find(ID);
>>> $payment->delete();
# Crear nueva transacción desde el frontend
```

### Problema: "Webhook no se recibe"

**Verificar:**
1. URL del webhook configurada en Wompi
2. Servidor accesible públicamente
3. No hay firewall bloqueando
4. Logs de Laravel

**Testing manual del webhook:**
```bash
curl -X POST https://tudominio.com/api/wompi/webhook \
  -H "Content-Type: application/json" \
  -H "X-Event-Checksum: test_signature" \
  -d '{
    "event": "transaction.updated",
    "data": {
      "transaction": {
        "id": "test-123",
        "reference": "ORDER-1-20260511-ABC123",
        "status": "APPROVED"
      }
    }
  }'
```

### Problema: "Transacción queda en PENDING"

**Causas:**
- Webhook no llegó
- Error en procesamiento
- Timeout de red

**Solución:**
```bash
# Consultar estado manualmente
php artisan tinker
>>> $service = app(\App\Services\WompiService::class);
>>> $status = $service->getTransactionStatus('TRANSACTION_ID');
>>> print_r($status);
```

### Problema: "Error al redirigir a Wompi"

**Verificar:**
- Llave pública correcta
- Firma de integridad válida
- Monto en centavos (no en pesos)
- URL de callback válida

---

## 📊 Monitoreo

### Métricas importantes:

1. **Tasa de conversión:**
   ```sql
   SELECT 
     COUNT(*) as total_transacciones,
     SUM(CASE WHEN status = 'APPROVED' THEN 1 ELSE 0 END) as aprobadas,
     (SUM(CASE WHEN status = 'APPROVED' THEN 1 ELSE 0 END) * 100.0 / COUNT(*)) as tasa_aprobacion
   FROM wompi_payments
   WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY);
   ```

2. **Tiempo promedio de procesamiento:**
   ```sql
   SELECT 
     AVG(TIMESTAMPDIFF(SECOND, created_at, webhook_received_at)) as segundos_promedio
   FROM wompi_payments
   WHERE webhook_received_at IS NOT NULL;
   ```

3. **Métodos de pago más usados:**
   ```sql
   SELECT 
     payment_method,
     COUNT(*) as cantidad,
     SUM(amount) as monto_total
   FROM wompi_payments
   WHERE status = 'APPROVED'
   GROUP BY payment_method
   ORDER BY cantidad DESC;
   ```

---

## 📚 Recursos adicionales

- [Documentación oficial de Wompi](https://docs.wompi.co/)
- [Panel de comercios](https://comercios.wompi.co/)
- [Soporte Wompi](https://wompi.co/contacto)
- [Estado del servicio](https://status.wompi.co/)

---

## 🔐 Seguridad

### Buenas prácticas implementadas:

✅ Llaves privadas solo en backend
✅ Firma de integridad en todas las transacciones
✅ Validación de webhooks
✅ Sanitización de inputs
✅ Logs sin datos sensibles
✅ HTTPS obligatorio en producción
✅ Rate limiting en endpoints
✅ Validación de montos

### Nunca hagas esto:

❌ Exponer llaves privadas en frontend
❌ Generar firmas en JavaScript
❌ Confiar en datos del cliente sin validar
❌ Procesar webhooks sin validar firma
❌ Guardar datos de tarjetas
❌ Usar HTTP en producción

---

## 🎯 Próximas mejoras

- [ ] Panel de administración de pagos
- [ ] Reintento automático de pagos fallidos
- [ ] Notificaciones por email
- [ ] Exportación de reportes
- [ ] Integración con contabilidad
- [ ] Pagos recurrentes
- [ ] Tokenización de tarjetas
- [ ] Multi-moneda

---

**Desarrollado con ❤️ para FiftyOne**

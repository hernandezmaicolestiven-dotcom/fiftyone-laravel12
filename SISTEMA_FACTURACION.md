# 📄 SISTEMA DE FACTURACIÓN - FIFTYONE

## ✅ IMPLEMENTACIÓN COMPLETA

Sistema de facturación electrónica para Colombia con cálculo automático de IVA (19%).

---

## 🎯 CARACTERÍSTICAS

### ✨ Funcionalidades Principales
- ✅ Generación automática de facturas al completar compra
- ✅ Número de factura único: `FACT-YYYYMMDD-XXXX`
- ✅ Cálculo automático de IVA del 19%
- ✅ Soporte para descuentos por producto
- ✅ Formato de moneda colombiana (COP)
- ✅ Impresión optimizada (botón "Imprimir")
- ✅ Diseño profesional y responsive
- ✅ Almacenamiento en base de datos

---

## 📊 CÁLCULO DE PRECIOS

### Fórmulas Aplicadas

Por cada producto:
```
subtotalProducto = precioUnitario × cantidad
descuentoAplicado = subtotalProducto × (descuento / 100)
baseGravable = subtotalProducto - descuentoAplicado
IVA = baseGravable × 0.19
totalProducto = baseGravable + IVA
```

Totales de la factura:
```
subtotal = Σ baseGravable (de todos los productos)
totalDescuentos = Σ descuentoAplicado
totalIVA = Σ IVA
TOTAL A PAGAR = subtotal + totalIVA
```

---

## 🗂️ ESTRUCTURA DE ARCHIVOS

### Backend (Laravel)
```
database/migrations/
  └── 2026_04_24_150426_create_invoices_table.php

app/Models/
  └── Invoice.php

app/Services/
  └── InvoiceService.php

app/Http/Controllers/
  └── InvoiceController.php

resources/views/invoice/
  └── show.blade.php
```

### Rutas
```php
// Ver factura
GET /factura/{order}

// Descargar/Imprimir factura
GET /factura/{order}/descargar

// API: Obtener datos de factura en JSON
GET /api/factura/{order}
```

---

## 💾 BASE DE DATOS

### Tabla: `invoices`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| invoice_number | string | FACT-YYYYMMDD-XXXX |
| order_id | bigint | Relación con pedido |
| user_id | bigint | Cliente |
| customer_name | string | Nombre del cliente |
| customer_email | string | Email |
| customer_address | text | Dirección de envío |
| customer_document | string | CC/NIT (opcional) |
| subtotal | decimal(12,2) | Antes de IVA |
| total_discounts | decimal(12,2) | Descuentos aplicados |
| total_iva | decimal(12,2) | IVA 19% |
| total | decimal(12,2) | Total a pagar |
| items | json | Productos con cálculos |
| created_at | timestamp | Fecha de emisión |

---

## 🚀 USO

### 1. Generación Automática
Las facturas se crean automáticamente al completar una compra:

```php
// En OrderController@store
$invoiceService = app(\App\Services\InvoiceService::class);
$invoiceService->createInvoiceForOrder($order);
```

### 2. Ver Factura desde Cuenta de Cliente
En "Mi Cuenta" → "Mis Pedidos", cada pedido tiene un botón:
```
🧾 Ver factura
```

### 3. Imprimir Factura
Dentro de la factura, hay un botón "Imprimir factura" que:
- Oculta elementos de navegación
- Optimiza el diseño para impresión
- Usa `window.print()`

### 4. Acceso Programático (API)
```javascript
// Obtener datos de factura en JSON
fetch(`/api/factura/${orderId}`)
  .then(res => res.json())
  .then(data => {
    console.log(data.invoice);
    console.log(data.order);
  });
```

---

## 📋 EJEMPLO DE FACTURA

### Datos de Prueba
```
Cliente: Laura Martínez
Email: laura@email.com
Dirección: Cra 15 #80-20, Bogotá

Productos:
1. Blusa lino blanca | M | $89.000 × 2 | Desc: 10%
2. Jean skinny negro | 28 | $145.000 × 1 | Desc: 0%
3. Vestido floral | S | $120.000 × 1 | Desc: 15%
```

### Cálculos Resultantes
```
Subtotal (antes IVA): $313.300
Descuentos: -$35.700
IVA (19%): $59.527
TOTAL A PAGAR: $372.827
```

---

## 🎨 DISEÑO DE FACTURA

### Secciones
1. **Encabezado**
   - Logo/Nombre de tienda
   - Número de factura
   - Fecha y hora
   - Badge "FACTURA DE VENTA"

2. **Datos del Cliente**
   - Nombre completo
   - Email
   - Dirección
   - Documento (opcional)

3. **Tabla de Productos**
   - Producto | Talla | Precio | Cant. | Desc. | Subtotal | IVA | Total

4. **Resumen Financiero**
   - Subtotal
   - Descuentos
   - IVA (19%)
   - TOTAL A PAGAR

5. **Pie de Factura**
   - Mensaje de agradecimiento
   - Política de devoluciones
   - Datos de contacto

---

## 🔧 MÉTODOS PRINCIPALES

### Invoice Model

```php
// Generar número de factura
Invoice::generateInvoiceNumber()
// Retorna: "FACT-20260424-0001"

// Calcular totales
Invoice::calculateTotals($items)
// Retorna: ['items' => [...], 'subtotal' => ..., 'total_iva' => ..., 'total' => ...]
```

### InvoiceService

```php
// Crear factura para un pedido
$invoiceService->createInvoiceForOrder($order)

// Obtener o crear factura
$invoiceService->getOrCreateInvoice($order)
```

---

## 🔐 SEGURIDAD

- ✅ Solo el cliente dueño del pedido puede ver su factura
- ✅ Los administradores pueden ver todas las facturas
- ✅ Validación de permisos en cada endpoint
- ✅ Números de factura únicos e incrementales

---

## 📱 RESPONSIVE

La factura se adapta a:
- 📱 Móviles (tabla con scroll horizontal)
- 💻 Tablets
- 🖥️ Desktop
- 🖨️ Impresión (optimizada)

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN

- [x] Migración de tabla `invoices`
- [x] Modelo `Invoice` con métodos de cálculo
- [x] Servicio `InvoiceService`
- [x] Controlador `InvoiceController`
- [x] Vista `invoice/show.blade.php`
- [x] Rutas de facturación
- [x] Generación automática al crear pedido
- [x] Botón "Ver factura" en cuenta de cliente
- [x] Formato de moneda colombiana (COP)
- [x] Cálculo de IVA 19%
- [x] Soporte para descuentos
- [x] Diseño responsive
- [x] Optimización para impresión

---

## 🎯 PRÓXIMAS MEJORAS (OPCIONAL)

- [ ] Generación de PDF con DomPDF
- [ ] Envío automático por email
- [ ] Facturación electrónica DIAN
- [ ] Múltiples tasas de IVA
- [ ] Productos exentos de IVA
- [ ] Retención en la fuente
- [ ] Factura en múltiples idiomas

---

## 📞 SOPORTE

Para dudas o problemas con el sistema de facturación:
- Email: soporte@fiftyone.com
- Documentación: Este archivo

---

**Sistema implementado por:** Kiro AI
**Fecha:** 24 de Abril de 2026
**Versión:** 1.0.0

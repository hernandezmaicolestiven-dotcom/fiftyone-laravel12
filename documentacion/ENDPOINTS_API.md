# 📡 Endpoints del Proyecto FiftyOne

**Proyecto:** E-commerce FiftyOne  
**Framework:** Laravel 11  
**Base URL:** http://localhost:8000

---

## 🏠 Endpoints Públicos (Sin autenticación)

### Home y Catálogo

```http
GET /
Descripción: Página principal con productos destacados
Respuesta: Vista HTML con productos paginados (8 por página)
```

```http
GET /catalogo
Descripción: Catálogo completo de productos con filtros
Query Params:
  - category: ID de categoría (opcional)
  - search: Búsqueda por nombre (opcional)
  - sort: Ordenamiento (price_asc, price_desc, newest, oldest)
Respuesta: Vista HTML con productos filtrados
```

### Validación de Cupones

```http
GET /coupon/validate
Descripción: Validar un cupón de descuento
Query Params:
  - code: Código del cupón (requerido)
  - total: Total de la compra (requerido)
Respuesta JSON:
  {
    "valid": true,
    "discount": 10000
  }
```

---

## 🔐 Autenticación de Clientes

### Registro

```http
POST /registro
Descripción: Registrar nuevo cliente
Content-Type: application/x-www-form-urlencoded
Body:
  - name: string (requerido)
  - email: string|email (requerido, único)
  - phone: string (opcional)
  - password: string|min:8 (requerido)
  - password_confirmation: string (requerido)
Respuesta: Redirect a /login con mensaje de éxito
```

### Login

```http
POST /login
Descripción: Iniciar sesión como cliente
Content-Type: application/x-www-form-urlencoded
Body:
  - email: string|email (requerido)
  - password: string (requerido)
  - remember: boolean (opcional)
Respuesta: Redirect a /mi-cuenta o / según el rol
```

### Recuperación de Contraseña

```http
POST /recuperar-contrasena
Descripción: Enviar contraseña temporal por email
Content-Type: application/x-www-form-urlencoded
Body:
  - email: string|email (requerido)
Respuesta: Redirect con mensaje de éxito o error
Nota: Genera contraseña temporal (formato: Temp####!)
```

### Logout

```http
POST /logout
Descripción: Cerrar sesión
Autenticación: Requerida
Respuesta: Redirect a /
```

---

## 👤 Endpoints de Cliente (Requieren autenticación)

### Mi Cuenta

```http
GET /mi-cuenta
Descripción: Ver perfil, órdenes e historial
Autenticación: Requerida (role: customer)
Respuesta: Vista HTML con datos del usuario
```

### Actualizar Perfil

```http
PUT /mi-cuenta/perfil
Descripción: Actualizar información del perfil
Content-Type: multipart/form-data
Body:
  - name: string (requerido)
  - email: string|email (requerido)
  - phone: string (opcional)
  - avatar: file|image (opcional, max: 2MB)
Respuesta: Redirect con mensaje de éxito
```

### Cambiar Contraseña

```http
PUT /mi-cuenta/password
Descripción: Cambiar contraseña del usuario
Body:
  - current_password: string (requerido)
  - password: string|min:8 (requerido)
  - password_confirmation: string (requerido)
Respuesta: Redirect con mensaje de éxito
```

### Cancelar Orden

```http
PATCH /mi-cuenta/orders/{order}/cancel
Descripción: Cancelar una orden pendiente
Autenticación: Requerida
Respuesta: Redirect con mensaje de éxito
```

---

## 🛒 Carrito y Órdenes

### Crear Orden

```http
POST /orders
Descripción: Crear nueva orden desde el carrito
Autenticación: Requerida
Content-Type: application/json
Body:
  {
    "items": [
      {
        "product_id": 1,
        "quantity": 2,
        "size": "M",
        "color": "Negro"
      }
    ],
    "shipping_address": "Calle 123 #45-67",
    "shipping_city": "Bogotá",
    "shipping_phone": "3001234567",
    "coupon_code": "DESCUENTO10" (opcional),
    "payment_method": "wompi"
  }
Respuesta JSON:
  {
    "success": true,
    "order_id": 123,
    "total": 150000,
    "redirect_url": "https://checkout.wompi.co/..."
  }
```

---

## ⭐ Reseñas y Wishlist

### Crear Reseña

```http
POST /reviews
Descripción: Crear reseña de un producto
Autenticación: Requerida
Body:
  - product_id: integer (requerido)
  - rating: integer|1-5 (requerido)
  - comment: string (requerido)
Respuesta: Redirect con mensaje de éxito
```

### Toggle Wishlist

```http
POST /wishlist/toggle
Descripción: Agregar/quitar producto de wishlist
Autenticación: Requerida
Content-Type: application/json
Body:
  {
    "product_id": 1
  }
Respuesta JSON:
  {
    "success": true,
    "action": "added" | "removed"
  }
```

---

## 🧾 Facturas

### Ver Factura

```http
GET /factura/{order}
Descripción: Ver factura de una orden
Autenticación: Requerida
Respuesta: Vista HTML de la factura
```

```http
GET /factura/{order}/descargar
Descripción: Descargar factura en PDF
Autenticación: Requerida
Respuesta: Archivo PDF
```

```http
GET /api/factura/{order}
Descripción: Obtener datos de factura en JSON
Autenticación: Requerida
Respuesta JSON:
  {
    "invoice_number": "INV-2026-001",
    "order_id": 123,
    "total": 150000,
    "items": [...],
    "customer": {...}
  }
```

---

## 💳 Wompi (Pasarela de Pagos)

### Callback de Wompi

```http
GET /wompi/callback
Descripción: Callback después del pago en Wompi
Query Params:
  - id: ID de la transacción en Wompi
Respuesta: Redirect a página de confirmación
Nota: Actualiza el estado de la orden según el resultado del pago
```

---

## 👨‍💼 Panel de Administración

### Login Admin

```http
POST /admin/login
Descripción: Iniciar sesión como administrador
Body:
  - email: string|email (requerido)
  - password: string (requerido)
Respuesta: Redirect a /admin/dashboard
```

### Dashboard

```http
GET /admin/dashboard
Descripción: Panel principal con estadísticas
Autenticación: Requerida (role: admin)
Respuesta: Vista HTML con métricas del negocio
```

---

## 📦 Gestión de Productos (Admin)

### Listar Productos

```http
GET /admin/products
Descripción: Lista de productos con filtros
Query Params:
  - search: Búsqueda por nombre
  - category: Filtrar por categoría
  - stock: Filtrar por stock (low = stock < 5)
Respuesta: Vista HTML con productos paginados
```

### Crear Producto

```http
POST /admin/products
Descripción: Crear nuevo producto
Content-Type: multipart/form-data
Body:
  - name: string (requerido)
  - description: text (opcional)
  - price: numeric (requerido)
  - stock: integer (requerido)
  - category_id: integer (opcional)
  - image: file|image (opcional)
  - sizes: array (opcional, ej: ["S","M","L"])
  - colors_input: string (opcional, separado por comas)
Respuesta: Redirect con mensaje de éxito
Nota: Limpia caché del home automáticamente
```

### Actualizar Producto

```http
PUT /admin/products/{product}
Descripción: Actualizar producto existente
Body: Igual que crear producto
Respuesta: Redirect con mensaje de éxito
Nota: Limpia caché del home automáticamente
```

### Eliminar Producto (Soft Delete)

```http
DELETE /admin/products/{product}
Descripción: Eliminar producto (soft delete)
Respuesta: Redirect con mensaje de éxito
Nota: Limpia caché del home automáticamente
```

### Exportar Productos

```http
GET /admin/products/export/csv
Descripción: Exportar productos a CSV
Respuesta: Archivo CSV con todos los productos
```

```http
GET /admin/products/export/excel
Descripción: Exportar productos a Excel
Respuesta: Archivo XLSX con todos los productos
```

---

## 📊 Reportes (Admin)

### Reporte de Ventas

```http
GET /admin/reports/sales
Descripción: Reporte de ventas con gráficas
Query Params:
  - start_date: Fecha inicio (opcional)
  - end_date: Fecha fin (opcional)
Respuesta: Vista HTML con gráficas y estadísticas
```

### Reporte de Inventario

```http
GET /admin/reports/inventory
Descripción: Reporte de inventario y stock
Respuesta: Vista HTML con productos y stock
```

### Top Productos

```http
GET /admin/reports/top-products
Descripción: Productos más vendidos
Respuesta: Vista HTML con ranking de productos
```

---

## 📋 Gestión de Órdenes (Admin)

### Listar Órdenes

```http
GET /admin/orders
Descripción: Lista de todas las órdenes
Query Params:
  - status: Filtrar por estado (pending, processing, shipped, delivered, cancelled)
  - search: Búsqueda por ID o cliente
Respuesta: Vista HTML con órdenes paginadas
```

### Ver Orden

```http
GET /admin/orders/{order}
Descripción: Ver detalles de una orden
Respuesta: Vista HTML con detalles completos
```

### Actualizar Estado de Orden

```http
PATCH /admin/orders/{order}/status
Descripción: Cambiar estado de una orden
Body:
  - status: string (pending|processing|shipped|delivered|cancelled)
Respuesta: Redirect con mensaje de éxito
Nota: Envía notificación por email al cliente
```

### Exportar Órdenes

```http
GET /admin/orders/export/csv
Descripción: Exportar órdenes a CSV
Respuesta: Archivo CSV
```

---

## 👥 Gestión de Usuarios (Admin)

### Listar Usuarios

```http
GET /admin/users
Descripción: Lista de usuarios
Query Params:
  - role: Filtrar por rol (customer, admin, colaborador)
  - search: Búsqueda por nombre o email
Respuesta: Vista HTML con usuarios paginados
```

### Crear Usuario

```http
POST /admin/users
Descripción: Crear nuevo usuario
Body:
  - name: string (requerido)
  - email: string|email (requerido)
  - password: string|min:8 (requerido)
  - role: string (customer|admin|colaborador)
  - phone: string (opcional)
Respuesta: Redirect con mensaje de éxito
```

---

## 🎟️ Cupones (Admin)

### Listar Cupones

```http
GET /admin/coupons
Descripción: Lista de cupones de descuento
Respuesta: Vista HTML con cupones
```

### Crear Cupón

```http
POST /admin/coupons
Descripción: Crear nuevo cupón
Body:
  - code: string (requerido, único)
  - type: string (percentage|fixed)
  - value: numeric (requerido)
  - min_purchase: numeric (opcional)
  - max_uses: integer (opcional)
  - expires_at: date (opcional)
Respuesta: Redirect con mensaje de éxito
```

### Activar/Desactivar Cupón

```http
PATCH /admin/coupons/{coupon}/toggle
Descripción: Activar o desactivar un cupón
Respuesta: Redirect con mensaje de éxito
```

---

## 📈 Analíticas (Admin)

```http
GET /admin/analytics
Descripción: Dashboard de analíticas avanzadas
Respuesta: Vista HTML con gráficas y métricas
Incluye:
  - Ventas por mes
  - Productos más vendidos
  - Categorías más populares
  - Ingresos totales
  - Órdenes por estado
```

---

## 🧾 Sistema de Facturación (Admin)

### Configuración de Facturación

```http
GET /admin/invoices/settings
Descripción: Ver/editar configuración de facturación
Respuesta: Vista HTML con formulario
```

```http
PUT /admin/invoices/settings
Descripción: Actualizar configuración
Body:
  - company_name: string
  - company_nit: string
  - company_address: string
  - company_phone: string
  - company_email: string
  - invoice_prefix: string
  - invoice_footer: text
Respuesta: Redirect con mensaje de éxito
```

### Listar Facturas

```http
GET /admin/invoices
Descripción: Lista de todas las facturas
Respuesta: Vista HTML con facturas paginadas
```

### Ver Factura (Admin)

```http
GET /admin/invoices/{invoice}
Descripción: Ver detalles de una factura
Respuesta: Vista HTML
```

### Descargar PDF

```http
GET /admin/invoices/{invoice}/download-pdf
Descripción: Descargar factura en PDF
Respuesta: Archivo PDF
```

---

## 📝 Notas Importantes

### Autenticación
- Los endpoints de admin requieren `role = admin` o `role = colaborador`
- Los endpoints de cliente requieren `role = customer`
- La autenticación usa sesiones de Laravel (no tokens)

### Caché
- El home usa caché de 15 minutos
- Se limpia automáticamente al crear/editar/eliminar productos
- Comando manual: `php artisan cache:clear`

### Paginación
- Productos: 8 por página (home), 10 por página (admin)
- Órdenes: 10 por página
- Usuarios: 10 por página

### Validación
- Todos los endpoints validan los datos de entrada
- Respuestas de error incluyen mensajes descriptivos
- Códigos HTTP estándar (200, 201, 400, 401, 403, 404, 500)

---

## 🧪 Ejemplos de Uso con cURL

### Crear Orden
```bash
curl -X POST http://localhost:8000/orders \
  -H "Content-Type: application/json" \
  -H "Cookie: laravel_session=..." \
  -d '{
    "items": [{"product_id": 1, "quantity": 2, "size": "M"}],
    "shipping_address": "Calle 123",
    "shipping_city": "Bogotá",
    "shipping_phone": "3001234567",
    "payment_method": "wompi"
  }'
```

### Validar Cupón
```bash
curl "http://localhost:8000/coupon/validate?code=DESCUENTO10&total=100000"
```

### Toggle Wishlist
```bash
curl -X POST http://localhost:8000/wishlist/toggle \
  -H "Content-Type: application/json" \
  -H "Cookie: laravel_session=..." \
  -d '{"product_id": 1}'
```

---

**Documentación generada:** 1 de junio de 2026  
**Versión del proyecto:** 1.0  
**Framework:** Laravel 11


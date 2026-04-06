# FiftyOne — Documentación de API

Base URL: `http://localhost:8000/api`

## Autenticación

La API usa Laravel Sanctum. Incluye el token en el header:
```
Authorization: Bearer {token}
```

---

## Productos

### GET /api/products
Lista todos los productos con paginación.

**Query params:**
| Param | Tipo | Descripción |
|-------|------|-------------|
| `category` | integer | Filtrar por ID de categoría |
| `page` | integer | Número de página |

**Respuesta 200:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Hoodie Oversize Negro",
      "description": "Algodón premium 320g",
      "price": "89900.00",
      "stock": 45,
      "category_id": 1
    }
  ],
  "links": { "first": "...", "last": "...", "next": "..." },
  "meta": { "current_page": 1, "total": 24 }
}
```

---

### POST /api/products
Crea un nuevo producto.

**Body (JSON):**
```json
{
  "name": "Camiseta Boxy Blanca",
  "description": "100% algodón",
  "price": 59900,
  "stock": 30
}
```

**Respuesta 201:**
```json
{
  "data": { "id": 5, "name": "Camiseta Boxy Blanca", "price": "59900.00" }
}
```

---

### GET /api/products/{id}
Obtiene un producto por ID.

**Respuesta 200:**
```json
{
  "data": { "id": 1, "name": "Hoodie Oversize Negro", "price": "89900.00" }
}
```

---

### PUT /api/products/{id}
Actualiza un producto.

**Body (JSON):**
```json
{ "price": 79900, "stock": 20 }
```

---

### DELETE /api/products/{id}
Elimina un producto.

**Respuesta 204:** Sin contenido.

---

## Pedidos (público)

### POST /orders
Crea un pedido desde la tienda pública.

**Body (JSON):**
```json
{
  "customer_name": "Juan García",
  "customer_email": "juan@email.com",
  "customer_phone": "3001234567",
  "notes": "Entregar en la tarde",
  "items": [
    { "product_id": 1, "quantity": 2 },
    { "product_id": 3, "quantity": 1 }
  ]
}
```

**Respuesta 201:**
```json
{ "success": true, "order_id": 42 }
```

**Errores de validación 422:**
```json
{
  "message": "The customer name field is required.",
  "errors": { "customer_name": ["The customer name field is required."] }
}
```

---

## Códigos de error

| Código | Descripción |
|--------|-------------|
| 200 | OK |
| 201 | Creado |
| 204 | Sin contenido |
| 422 | Error de validación |
| 429 | Rate limit excedido |
| 500 | Error interno del servidor |

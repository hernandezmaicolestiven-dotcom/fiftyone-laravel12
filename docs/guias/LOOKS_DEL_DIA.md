# Sección "Looks del Día" - Guía Técnica

## Descripción
La sección "Looks del Día" muestra 3 conjuntos de ropa (looks) con productos reales de la base de datos. Cada look contiene 3 productos y muestra el precio total del conjunto.

## Ubicación
- **Vista**: `resources/views/welcome.blade.php`
- **Componente React**: `LooksDelMes`
- **Líneas**: ~1390-1480

## Funcionamiento

### 1. Datos de Entrada
Los productos vienen desde el backend a través de la ruta home (`routes/web.php`):

```php
$products = Product::with(['category:id,name'])
    ->withCount('reviews')
    ->select('id', 'name', 'price', 'stock', 'image', 'category_id', 'sizes', 'colors', 'created_at')
    ->latest()
    ->paginate(24);
```

Los productos se pasan a JavaScript mediante:
```javascript
const dbProducts = window.__PRODUCTS__;
```

### 2. Selección de Productos
El componente toma los primeros 9 productos de la página actual y los distribuye en 3 looks:

- **Look 1 (Urbano)**: Productos 0, 1, 2
- **Look 2 (Casual)**: Productos 3, 4, 5
- **Look 3 (Premium)**: Productos 6, 7, 8

Si hay menos de 9 productos, se reutilizan productos desde el inicio usando módulo:
```javascript
const getProduct = (index) => {
  if (index < dbProducts.length) {
    return dbProducts[index];
  }
  return dbProducts[index % dbProducts.length];
};
```

### 3. Estructura de Cada Look
Cada look muestra:
- **Título**: Look Urbano / Casual / Premium
- **Descripción**: Breve descripción del estilo
- **3 Productos**: Con imagen, nombre, precio y botón para agregar al carrito
- **Total**: Suma de los precios de los 3 productos

### 4. Características
- ✅ Productos reales de la base de datos
- ✅ Imágenes y precios correctos
- ✅ Botón para agregar al carrito
- ✅ Fecha actual mostrada dinámicamente
- ✅ Diseño responsive (3 columnas en desktop, 1 en móvil)
- ✅ Gradientes oscuros para cada look
- ✅ Validación de productos disponibles

## Solución de Problemas

### Problema: "No aparecen productos"
**Causa**: `dbProducts` está vacío o undefined

**Solución**:
1. Verificar que hay productos en la base de datos:
   ```bash
   php artisan tinker
   >>> App\Models\Product::count()
   ```

2. Limpiar caché:
   ```bash
   php artisan cache:clear
   ```

3. Verificar en consola del navegador:
   ```javascript
   console.log(window.__PRODUCTS__)
   ```

### Problema: "Muestra $0 en el total"
**Causa**: Los productos no tienen precio o el array está vacío

**Solución**:
1. Verificar que los productos tienen precio:
   ```bash
   php scripts/test-looks-section.php
   ```

2. Revisar la consola del navegador para ver errores de JavaScript

### Problema: "No aparecen imágenes"
**Causa**: Las URLs de las imágenes no son válidas

**Solución**:
1. Verificar que las imágenes existen en `storage/app/public/products/`
2. Ejecutar: `php artisan storage:link`
3. Verificar permisos de la carpeta storage

## Testing

### Test Backend
```bash
php scripts/test-looks-section.php
```

Este script verifica:
- Total de productos en BD
- Productos disponibles en página 1
- Los primeros 9 productos que se usarán en los looks

### Test Frontend
Abrir en el navegador:
```
http://localhost:8000/test-looks.html
```

Este archivo HTML de prueba muestra la sección aislada con datos de ejemplo.

## Personalización

### Cambiar Número de Productos por Look
Modificar en `LooksDelMes`:
```javascript
const looks = [
  {
    title: 'Look Urbano',
    products: [
      getProduct(0),
      getProduct(1),
      getProduct(2),
      getProduct(3), // Agregar más productos
    ].filter(Boolean),
    // ...
  },
];
```

### Cambiar Títulos y Descripciones
```javascript
const looks = [
  {
    title: 'Tu Título Aquí',
    desc: 'Tu descripción aquí',
    // ...
  },
];
```

### Cambiar Colores de Fondo
```javascript
const looks = [
  {
    // ...
    bg: 'from-[#COLOR1] to-[#COLOR2]', // Gradiente Tailwind
  },
];
```

## Mantenimiento

### Actualizar Productos
Los productos se actualizan automáticamente cada vez que se carga la página, tomando los más recientes (ordenados por `created_at DESC`).

### Caché
La página home tiene caché de 10 minutos. Para ver cambios inmediatos:
```bash
php artisan cache:clear
```

## Notas Técnicas
- La sección usa React para renderizado dinámico
- Los productos se cargan desde el backend en cada request
- El caché mejora el rendimiento pero puede retrasar cambios
- La fecha se muestra en formato colombiano (es-CO)
- Los precios se formatean con separador de miles

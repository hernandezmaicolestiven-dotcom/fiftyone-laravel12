# Instrucciones para llenar la base de datos con productos

He actualizado los seeders para crear 65+ productos variados en todas las categorías:

## Categorías creadas:
1. **Hoodies** (15 productos) - Hoodies oversize y sudaderas
2. **Camisetas** (20 productos) - Camisetas boxy y oversize
3. **Pantalones** (15 productos) - Pantalones cargo y joggers
4. **Accesorios** (15 productos) - Gorras, bolsos y mochilas

## Para ejecutar el seeder:

### Opción 1: Resetear toda la base de datos (CUIDADO: Borra todo)
```bash
php artisan migrate:fresh --seed
```

### Opción 2: Solo ejecutar los seeders (sin borrar datos existentes)
```bash
php artisan db:seed
```

### Opción 3: Ejecutar solo el seeder de productos
```bash
php artisan db:seed --class=ProductSeeder
```

### Opción 4: Ejecutar solo el seeder de categorías
```bash
php artisan db:seed --class=CategorySeeder
```

## Características de los productos:

- ✅ Todos tienen imágenes de Unsplash
- ✅ Todos tienen tallas configuradas:
  - Hoodies y Camisetas: S, M, L, XL, XXL
  - Pantalones: 28, 30, 32, 34, 36, 38
  - Accesorios: Talla única
- ✅ Precios variados y realistas
- ✅ Stock aleatorio entre 8-80 unidades
- ✅ Descripciones detalladas
- ✅ Categorías correctamente asignadas

## Notas importantes:

1. Si ya tienes productos en la base de datos y quieres mantenerlos, usa la **Opción 2**
2. Si quieres empezar desde cero con solo estos productos, usa la **Opción 1**
3. El admin user se crea automáticamente:
   - Email: admin@fiftyone.com
   - Password: admin123

## Verificar que funcionó:

Después de ejecutar el seeder, visita:
- http://localhost/catalogo - Deberías ver todos los productos
- http://localhost/ - La página principal también mostrará los productos

¡Listo! Tu catálogo estará lleno de productos variados y listos para vender.

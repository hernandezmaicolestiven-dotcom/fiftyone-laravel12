# 🧪 PRUEBA DEL SISTEMA DE PAGOS

## 📋 INSTRUCCIONES PASO A PASO

### 1️⃣ PREPARACIÓN
```bash
# Asegúrate de que el servidor esté corriendo
php artisan serve

# Asegúrate de que la migración se ejecutó
php artisan migrate:status
```

### 2️⃣ ABRIR LA TIENDA
```
http://localhost:8000
```

### 3️⃣ AGREGAR PRODUCTOS AL CARRITO
- Click en cualquier producto
- Click en "Agregar al carrito"
- Repite con 2-3 productos

### 4️⃣ ABRIR CARRITO
- Click en el icono del carrito (esquina superior derecha)
- Verifica que los productos estén ahí

### 5️⃣ INICIAR CHECKOUT
- Click en "Proceder al pago"
- Si no has iniciado sesión, te pedirá que lo hagas

### 6️⃣ PASO 1: RESUMEN
- Verifica el resumen de productos
- Aplica un cupón si quieres (opcional)
- Click en "Continuar"

### 7️⃣ PASO 2: DATOS
Llena el formulario:
```
Nombre: Test Usuario
Email: test@example.com
Teléfono: 3001234567
Dirección: Calle 123 #45-67
Ciudad: Medellín
Notas: (opcional)
```
- Click en "Método de pago"

### 8️⃣ PASO 3: MÉTODO DE PAGO

Ahora prueba cada método:

#### Prueba 1: NEQUI
1. Click en "Nequi"
2. Verifica que se muestre el campo de teléfono
3. Ingresa: 3001234567
4. Click en "Procesar Pago"
5. ✅ Debe crear la orden exitosamente

#### Prueba 2: DAVIPLATA
1. Click en "Daviplata"
2. Verifica que se muestre el campo de teléfono
3. Ingresa: 3009876543
4. Click en "Procesar Pago"
5. ✅ Debe crear la orden exitosamente

#### Prueba 3: PSE
1. Click en "PSE"
2. Verifica que se muestre el selector de bancos
3. Click en "Bancolombia"
4. Verifica que aparezca "Bancolombia seleccionado"
5. Click en "Procesar Pago"
6. ✅ Debe crear la orden exitosamente

#### Prueba 4: TARJETA
1. Click en "Tarjeta"
2. Verifica que se muestren los campos de tarjeta
3. Llena:
   ```
   Número: 4111 1111 1111 1111
   Nombre: TEST USUARIO
   Fecha: 12/28
   CVV: 123
   ```
4. Click en "Procesar Pago"
5. ✅ Debe crear la orden exitosamente

#### Prueba 5: BANCOLOMBIA
1. Click en "Bancolombia"
2. Verifica que se muestren las instrucciones
3. Lee las instrucciones de transferencia
4. Click en "Procesar Pago"
5. ✅ Debe crear la orden exitosamente

#### Prueba 6: EFECTY
1. Click en "Efecty"
2. Verifica que se muestren las instrucciones
3. Lee las instrucciones de pago en efectivo
4. Click en "Procesar Pago"
5. ✅ Debe crear la orden exitosamente

---

## 🔍 VERIFICACIÓN EN BASE DE DATOS

Después de cada prueba, verifica en la base de datos:

```bash
php artisan tinker
```

```php
// Ver última orden
$order = App\Models\Order::latest()->first();
echo "ID: " . $order->id . "\n";
echo "Método de pago: " . $order->payment_method . "\n";
echo "Estado de pago: " . $order->payment_status . "\n";
echo "Detalles: " . $order->payment_details . "\n";
```

### Resultados Esperados:

**Nequi:**
```
payment_method: nequi
payment_status: pending
payment_details: null
```

**Daviplata:**
```
payment_method: daviplata
payment_status: pending
payment_details: null
```

**PSE:**
```
payment_method: pse
payment_status: pending
payment_details: {"bank":"Bancolombia"}
```

**Tarjeta:**
```
payment_method: tarjeta
payment_status: pending
payment_details: {"card_last4":"1111","card_holder":"TEST USUARIO"}
```

**Bancolombia:**
```
payment_method: bancolombia
payment_status: pending
payment_details: null
```

**Efecty:**
```
payment_method: efecty
payment_status: pending
payment_details: null
```

---

## 📊 VERIFICACIÓN EN PANEL DE ADMIN

1. Ve a http://localhost:8000/admin/login
2. Inicia sesión con:
   ```
   Email: admin@fiftyone.com
   Password: Admin123!
   ```
3. Ve a "Pedidos"
4. Click en el último pedido
5. Verifica que se muestre:
   - ✅ Método de pago
   - ✅ Estado de pago
   - ✅ Detalles del pago (si aplica)

---

## ✅ CHECKLIST DE PRUEBAS

Marca cada prueba cuando la completes:

- [ ] Nequi - Orden creada
- [ ] Nequi - Método guardado en DB
- [ ] Daviplata - Orden creada
- [ ] Daviplata - Método guardado en DB
- [ ] PSE - Orden creada
- [ ] PSE - Banco guardado en detalles
- [ ] Tarjeta - Orden creada
- [ ] Tarjeta - Últimos 4 dígitos guardados
- [ ] Bancolombia - Orden creada
- [ ] Bancolombia - Método guardado en DB
- [ ] Efecty - Orden creada
- [ ] Efecty - Método guardado en DB
- [ ] Panel admin muestra método de pago
- [ ] Panel admin muestra estado de pago

---

## 🐛 TROUBLESHOOTING

### Error: "Selecciona un método de pago"
- Asegúrate de hacer click en uno de los 6 métodos
- Debe aparecer un check verde en el método seleccionado

### Error: "Selecciona tu banco para PSE"
- Debes hacer click en uno de los bancos de la lista
- Debe aparecer "Bancolombia seleccionado" (o el banco que elegiste)

### Error: "Completa los datos de la tarjeta"
- Llena todos los campos: número, nombre, fecha, CVV
- El número debe tener 16 dígitos
- La fecha debe estar en formato MM/AA

### Error: "Error al procesar el pedido"
- Verifica que estés autenticado
- Revisa los logs: `storage/logs/laravel.log`
- Verifica que la migración se haya ejecutado

### No veo el método de pago en el panel admin
- Limpia caché: `php artisan cache:clear`
- Recarga la página del admin

---

## 📸 CAPTURAS ESPERADAS

### Paso 3: Métodos de Pago
```
┌─────────────────────────────────────┐
│  Elige cómo pagar                   │
├─────────────────────────────────────┤
│  [Nequi]  [Daviplata]  [PSE]       │
│  [Banco]  [Efecty]     [Tarjeta]   │
└─────────────────────────────────────┘
```

### PSE Seleccionado
```
┌─────────────────────────────────────┐
│  Selecciona tu banco                │
├─────────────────────────────────────┤
│  [✓ Bancolombia]  [Davivienda]     │
│  [BBVA]           [Banco de Bogotá]│
│  ...                                │
│                                     │
│  ✓ Bancolombia seleccionado        │
└─────────────────────────────────────┘
```

### Tarjeta Seleccionada
```
┌─────────────────────────────────────┐
│  Datos de la tarjeta                │
├─────────────────────────────────────┤
│  Número: [4111 1111 1111 1111]     │
│  Nombre: [TEST USUARIO]             │
│  Fecha:  [12/28]  CVV: [123]       │
└─────────────────────────────────────┘
```

---

## ✅ RESULTADO ESPERADO

Si todas las pruebas pasan:

✅ Los 6 métodos de pago funcionan
✅ Los datos se guardan correctamente en DB
✅ El panel admin muestra la información
✅ Las validaciones funcionan
✅ El sistema está 100% operativo

**El sistema de pagos está completamente funcional.**

# ⚡ Wompi - Guía Rápida de Inicio

## 🚀 Setup en 5 minutos

### 1. Registrarse en Wompi

1. Ve a [https://comercios.wompi.co/](https://comercios.wompi.co/)
2. Crea una cuenta
3. Completa el proceso de verificación

### 2. Obtener llaves de Sandbox

1. Inicia sesión en el panel de Wompi
2. Ve a **Configuración → API Keys**
3. Copia las llaves de **Sandbox**:
   - `pub_test_XXXXXXXX` (Llave pública)
   - `prv_test_XXXXXXXX` (Llave privada)
4. Copia los secretos:
   - Integrity Secret
   - Events Secret

### 3. Configurar el proyecto

Agrega las llaves a tu archivo `.env`:

```bash
WOMPI_PUBLIC_KEY=pub_test_TU_LLAVE_PUBLICA_AQUI
WOMPI_PRIVATE_KEY=prv_test_TU_LLAVE_PRIVADA_AQUI
WOMPI_INTEGRITY_SECRET=test_integrity_TU_SECRET_AQUI
WOMPI_EVENTS_SECRET=test_events_TU_SECRET_AQUI
WOMPI_SANDBOX=true
```

### 4. Ejecutar migraciones

```bash
php artisan migrate
```

### 5. Probar el pago

1. Inicia tu servidor: `php artisan serve`
2. Ve a tu tienda: `http://localhost:8000`
3. Agrega productos al carrito
4. Haz checkout
5. Selecciona **Wompi** como método de pago
6. Usa esta tarjeta de prueba:
   ```
   Número: 4242 4242 4242 4242
   CVV: 123
   Fecha: 12/25
   Nombre: Test User
   ```

¡Listo! 🎉

---

## 🧪 Tarjetas de Prueba

### ✅ Pago Aprobado
```
4242 4242 4242 4242
CVV: 123
Fecha: Cualquier fecha futura
```

### ❌ Pago Rechazado
```
4111 1111 1111 1111
CVV: 123
Fecha: Cualquier fecha futura
```

### ⏳ Pago Pendiente
```
5555 5555 5555 4444
CVV: 123
Fecha: Cualquier fecha futura
```

---

## 🔍 Verificar que funciona

### Ver transacciones en la base de datos:

```bash
php artisan tinker
```

```php
// Ver todas las transacciones
\App\Models\WompiPayment::all();

// Ver última transacción
\App\Models\WompiPayment::latest()->first();

// Ver transacciones aprobadas
\App\Models\WompiPayment::where('status', 'APPROVED')->get();
```

### Ver logs:

```bash
tail -f storage/logs/laravel.log | grep -i wompi
```

---

## 🐛 Problemas comunes

### "WOMPI_PUBLIC_KEY no está configurada"

```bash
php artisan config:clear
```

### "No se crea la transacción"

Verifica que:
1. Las llaves estén en `.env`
2. Hayas ejecutado las migraciones
3. El usuario esté autenticado

### "No redirige a Wompi"

Abre la consola del navegador (F12) y busca errores.

---

## 📞 Soporte

- **Documentación completa:** `docs/INTEGRACION_WOMPI.md`
- **Wompi:** [https://wompi.co/contacto](https://wompi.co/contacto)
- **Docs oficiales:** [https://docs.wompi.co/](https://docs.wompi.co/)

---

**¿Todo funcionando? Pasa a producción siguiendo la guía completa.**

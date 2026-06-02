# 🔐 Credenciales de Acceso - FiftyOne

## 👤 Usuario Cliente (Customer)

**Email:** `hernandezmaicolestiven@gmail.com`  
**Contraseña:** `12345678`  
**Rol:** Cliente

### Acceso:
- URL: `http://localhost:8000/customer/login`
- Puede realizar compras, ver órdenes, gestionar wishlist

---

## 👨‍💼 Usuario Administrador (Admin)

**Email:** `admin@fiftyone.com`  
**Contraseña:** `FiftyOne2026!`  
**Rol:** Administrador

### Acceso:
- URL: `http://localhost:8000/admin/login`
- Panel completo de administración
- Gestión de productos, órdenes, usuarios, cupones, etc.

---

## 🔄 Resetear Credenciales

Si las credenciales no funcionan, ejecuta:

```bash
# Resetear usuario cliente
php artisan db:seed --class=MyUserSeeder

# Resetear usuario admin
php artisan db:seed --class=AdminUserSeeder

# O resetear ambos
php artisan db:seed --class=ResetCredentialsSeeder
```

---

## 🧪 Crear Usuario de Prueba

Si necesitas crear un nuevo usuario de prueba:

```bash
php artisan tinker
```

Luego ejecuta:

```php
User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => Hash::make('password123'),
    'role' => 'customer'
]);
```

---

## ⚠️ Solución de Problemas

### "Credenciales incorrectas"

1. Verifica que estés en la URL correcta:
   - Cliente: `/customer/login`
   - Admin: `/admin/login`

2. Resetea las credenciales:
   ```bash
   php artisan db:seed --class=MyUserSeeder
   ```

3. Limpia la caché:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

### Verificar si el usuario existe

```bash
php artisan tinker --execute="User::where('email', 'hernandezmaicolestiven@gmail.com')->first()"
```

---

## 📝 Notas

- Las contraseñas están hasheadas con bcrypt
- El seeder usa `updateOrCreate` para no duplicar usuarios
- Si cambias la contraseña manualmente, usa `Hash::make('tu_password')`

---

**Última actualización:** 1 de Junio, 2026

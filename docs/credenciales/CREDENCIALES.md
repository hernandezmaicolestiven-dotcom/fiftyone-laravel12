# 🔐 CREDENCIALES DEL SISTEMA - FIFTYONE

## ✅ ESTADO: TODAS LAS CREDENCIALES ACTUALIZADAS Y FUNCIONANDO

---

## 👨‍💼 PANEL DE ADMINISTRACIÓN

### Acceso Admin Principal
```
URL:      http://localhost:8000/admin/login
Email:    admin@fiftyone.com
Password: Admin123!
```

**Permisos:**
- ✅ Acceso completo al panel de administración
- ✅ Gestión de productos, categorías, pedidos
- ✅ Gestión de usuarios y colaboradores
- ✅ Reportes y analytics
- ✅ Configuración de la tienda

---

### Acceso Colaborador
```
URL:      http://localhost:8000/admin/login
Email:    colaborador@fiftyone.com
Password: Colab123!
```

**Permisos:**
- ✅ Acceso al panel de administración
- ✅ Gestión de productos y pedidos
- ⚠️ Sin acceso a gestión de usuarios
- ⚠️ Sin acceso a configuración avanzada

---

## 👤 ÁREA DE CLIENTES

### Cliente de Prueba
```
URL:      http://localhost:8000/login
Email:    cliente@test.com
Password: Cliente123!
```

**Permisos:**
- ✅ Realizar compras en la tienda
- ✅ Ver historial de pedidos
- ✅ Gestionar perfil y contraseña
- ✅ Lista de deseos
- ✅ Dejar reseñas
- ❌ Sin acceso al panel de administración

---

## 🔄 RECUPERACIÓN DE CONTRASEÑA

### Para Clientes:
1. Ir a http://localhost:8000/login
2. Clic en "¿Olvidaste tu contraseña?"
3. Ingresar email
4. Ejecutar `get-reset-link.bat` para obtener el enlace
5. Copiar y pegar el enlace en el navegador

### Para Administradores:
1. Ir a http://localhost:8000/admin/login
2. Clic en "¿Olvidaste tu contraseña?"
3. Seguir el proceso de recuperación

---

## 🔧 RESETEAR CREDENCIALES

Si olvidas alguna contraseña o necesitas resetear todo:

```bash
php artisan db:seed --class=ResetCredentialsSeeder
```

Esto restablecerá todas las credenciales a los valores por defecto mostrados arriba.

---

## ⚠️ IMPORTANTE

### Seguridad:
- ✅ Las contraseñas son CASE SENSITIVE (mayúsculas importan)
- ✅ Incluye el signo de exclamación (!) al final
- ✅ No compartas las credenciales de admin
- ⚠️ Cambia estas contraseñas en producción

### Throttling (Protección contra fuerza bruta):
- Máximo 5 intentos fallidos
- Bloqueo temporal de 60 segundos después de 5 fallos
- Se aplica por IP + email

### Sesiones:
- Las sesiones duran 43,200 minutos (30 días) con "Recordarme"
- Sin "Recordarme": sesión hasta cerrar navegador
- Las sesiones se guardan en archivos (SESSION_DRIVER=file)

---

## 🧪 PROBAR CREDENCIALES

Ejecuta el script de prueba:
```bash
test-login.bat
```

O prueba manualmente:
1. Abre el navegador en modo incógnito
2. Ve a la URL correspondiente
3. Ingresa email y contraseña exactamente como se muestra
4. Haz clic en "Iniciar sesión"

---

## 🐛 TROUBLESHOOTING

### "Credenciales incorrectas"
- ✅ Verifica que estés usando la URL correcta (admin vs cliente)
- ✅ Copia y pega la contraseña para evitar errores de tipeo
- ✅ Asegúrate de incluir el signo de exclamación (!)
- ✅ Verifica que las mayúsculas estén correctas

### "Demasiados intentos fallidos"
- ⏱️ Espera 60 segundos
- 🔄 O ejecuta: `php artisan cache:clear`

### "La página no carga"
- ✅ Verifica que el servidor esté corriendo: `php artisan serve`
- ✅ Verifica la URL: debe ser `localhost:8000` no `127.0.0.1:8000`

### "No puedo acceder al panel de admin"
- ✅ Verifica que el usuario tenga role='admin' o 'colaborador'
- ✅ Cierra sesión y vuelve a iniciar
- ✅ Limpia cookies del navegador

---

## 📊 USUARIOS EN EL SISTEMA

| Email | Contraseña | Rol | Panel |
|-------|-----------|-----|-------|
| admin@fiftyone.com | Admin123! | admin | /admin/login |
| colaborador@fiftyone.com | Colab123! | colaborador | /admin/login |
| cliente@test.com | Cliente123! | customer | /login |

---

## 🔐 CAMBIAR CONTRASEÑAS

### Desde el Panel:
1. Inicia sesión
2. Ve a "Mi Perfil" o "Configuración"
3. Sección "Cambiar Contraseña"
4. Ingresa contraseña actual y nueva contraseña

### Desde la Base de Datos:
```bash
php artisan tinker
```
```php
$user = App\Models\User::where('email', 'admin@fiftyone.com')->first();
$user->password = Hash::make('NuevaContraseña123!');
$user->save();
```

---

## 📝 NOTAS ADICIONALES

- Las contraseñas se hashean con bcrypt (12 rounds)
- Los tokens de "Recordarme" son seguros y únicos
- Las sesiones se regeneran después de login exitoso
- Los intentos fallidos se registran por seguridad

---

**Última actualización:** 28 de Abril, 2026
**Estado:** ✅ Todas las credenciales verificadas y funcionando

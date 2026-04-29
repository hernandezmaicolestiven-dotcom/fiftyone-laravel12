# 🧪 PRUEBA DEL NUEVO FLUJO DE REGISTRO

## 📋 INSTRUCCIONES PASO A PASO

### 1️⃣ ABRIR NAVEGADOR
- Abre tu navegador en **modo incógnito** (Ctrl+Shift+N en Chrome)
- Esto asegura que no haya sesiones previas

### 2️⃣ IR A REGISTRO
```
http://localhost:8000/registro
```

### 3️⃣ LLENAR FORMULARIO
```
Nombre completo: Test Usuario
Email: test_nuevo@example.com
Teléfono: 3001234567
Contraseña: TestPassword123!
Confirmar contraseña: TestPassword123!
```

### 4️⃣ ENVIAR FORMULARIO
- Click en el botón "Crear cuenta"
- Espera unos segundos

### 5️⃣ VERIFICAR MENSAJE DE ÉXITO
Deberías ver:
```
┌────────────────────────────────────────────────────┐
│ ✅ ¡Cuenta creada exitosamente!                    │
│    Ahora puedes iniciar sesión con tus            │
│    credenciales.                                   │
└────────────────────────────────────────────────────┘
```

### 6️⃣ VERIFICAR QUE ESTÁS EN LOGIN
La URL debe ser:
```
http://localhost:8000/login
```

### 7️⃣ INICIAR SESIÓN MANUALMENTE
```
Email: test_nuevo@example.com
Password: TestPassword123!
```
- Click en "Iniciar sesión"

### 8️⃣ VERIFICAR ACCESO
Deberías ser redirigido a:
```
http://localhost:8000/mi-cuenta
```

---

## ✅ RESULTADO ESPERADO

### Lo que DEBE pasar:
1. ✅ Formulario de registro se envía
2. ✅ Aparece mensaje verde de éxito
3. ✅ Estás en la página de login (NO en tu cuenta)
4. ✅ Debes ingresar email y contraseña manualmente
5. ✅ Después de login, accedes a tu cuenta

### Lo que NO debe pasar:
1. ❌ NO debes entrar automáticamente a tu cuenta
2. ❌ NO debes ver el dashboard sin iniciar sesión
3. ❌ NO debes saltarte el paso de login

---

## 🔍 VERIFICACIÓN EN BASE DE DATOS

Después del registro, verifica que el usuario se creó:

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'test_nuevo@example.com')->first();
echo "Usuario encontrado: " . $user->name . "\n";
echo "Email: " . $user->email . "\n";
echo "Role: " . $user->role . "\n";
```

Deberías ver:
```
Usuario encontrado: Test Usuario
Email: test_nuevo@example.com
Role: customer
```

---

## 📸 CAPTURAS ESPERADAS

### Pantalla 1: Registro
```
┌─────────────────────────────────────┐
│         Crea tu cuenta              │
│                                     │
│ Nombre: [Test Usuario          ]   │
│ Email:  [test_nuevo@example.com]   │
│ Tel:    [3001234567            ]   │
│ Pass:   [••••••••••••••        ]   │
│ Conf:   [••••••••••••••        ]   │
│                                     │
│      [Crear cuenta]                 │
└─────────────────────────────────────┘
```

### Pantalla 2: Login con Mensaje
```
┌─────────────────────────────────────┐
│ ✅ ¡Cuenta creada exitosamente!     │
│    Ahora puedes iniciar sesión      │
├─────────────────────────────────────┤
│      Iniciar sesión                 │
│                                     │
│ Email:    [                    ]   │
│ Password: [                    ]   │
│                                     │
│ ☐ Recordarme                        │
│                                     │
│      [Iniciar sesión]               │
│                                     │
│ ¿Olvidaste tu contraseña?           │
└─────────────────────────────────────┘
```

### Pantalla 3: Mi Cuenta
```
┌─────────────────────────────────────┐
│      Mi Cuenta                      │
│                                     │
│ Bienvenido, Test Usuario            │
│                                     │
│ • Mi Perfil                         │
│ • Mis Pedidos                       │
│ • Lista de Deseos                   │
└─────────────────────────────────────┘
```

---

## 🎯 CHECKLIST DE PRUEBA

Marca cada paso cuando lo completes:

- [ ] Abrí navegador en modo incógnito
- [ ] Fui a /registro
- [ ] Llené el formulario completo
- [ ] Envié el formulario
- [ ] Vi mensaje de éxito verde
- [ ] Estoy en la página /login
- [ ] Ingresé email y contraseña manualmente
- [ ] Click en "Iniciar sesión"
- [ ] Accedí a /mi-cuenta exitosamente

---

## 🐛 SI ALGO SALE MAL

### Error: "Email ya existe"
- Usa otro email diferente
- O elimina el usuario anterior

### Error: "Credenciales incorrectas"
- Verifica que escribiste bien la contraseña
- Recuerda que es case-sensitive
- Incluye el signo de exclamación

### No veo el mensaje de éxito
- Limpia caché: `php artisan cache:clear`
- Recarga la página

### Me loguea automáticamente
- Verifica que el código esté actualizado
- Ejecuta: `php artisan config:clear`

---

## ✅ CONFIRMACIÓN FINAL

Si completaste todos los pasos del checklist, el nuevo flujo está funcionando correctamente:

✅ Registro crea la cuenta
✅ Mensaje de éxito aparece
✅ Usuario es redirigido al login
✅ Usuario debe iniciar sesión manualmente
✅ Credenciales funcionan correctamente

**El flujo ahora es paso por paso como solicitaste.**

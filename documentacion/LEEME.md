# 🛍️ FiftyOne - E-commerce Laravel

**Tienda online de ropa oversize** - Sistema completo de comercio electrónico

---

## 🚀 Inicio Rápido

### 1. Iniciar el servidor
```bash
php artisan serve
```

### 2. Acceder al sistema
- **Tienda:** http://localhost:8000
- **Panel Admin:** http://localhost:8000/admin/login

### 3. Credenciales
```
👨‍💼 Admin:        admin@fiftyone.com / admin123
👤 Cliente:       hernandezmaicolestiven@gmail.com / 12345678
👤 Cliente Test:  cliente@test.com / cliente123
🤝 Colaborador:   colaborador@fiftyone.com / colab123
```

---

## 📊 Estado del Sistema

✅ **Base de datos completa:**
- 104 usuarios
- 25 productos con imágenes de calidad
- 499 órdenes (datos históricos de 6 meses)
- Sistema de facturación configurado

✅ **Funcionalidades:**
- Registro e inicio de sesión
- Carrito de compras
- Pasarela de pagos Wompi
- Sistema de facturación
- Reportes y analíticas
- Gestión de productos, categorías, usuarios
- Sistema de reseñas
- Lista de deseos

---

## 🔧 Scripts Útiles

### Resetear base de datos completa
```bash
resetear-todo.bat
```

### Arreglar credenciales
```bash
arreglar-credenciales.bat
```

### Iniciar con HTTPS local
```bash
iniciar-con-https.bat
```

---

## 📁 Estructura de Documentación

```
docs/
├── wompi/              # Documentación de pasarela de pagos
├── emails/             # Configuración de emails y recuperación
├── guias/              # Guías de uso del sistema
├── credenciales/       # Información de acceso
└── guias-antiguas/     # Documentación histórica

scripts/
├── reset-database-complete.php    # Resetear BD
├── fix-credentials.php            # Arreglar credenciales
├── test-product-changes.php       # Probar cambios
└── antiguos/                      # Scripts históricos
```

---

## 📖 Documentación Principal

### Para empezar
- `CREDENCIALES_ACCESO.txt` - Todas las credenciales del sistema
- `TODO_FUNCIONANDO.txt` - Resumen de funcionalidades
- `RESUMEN_FINAL.md` - Guía completa del proyecto

### Configuración
- `BASE_DE_DATOS_LISTA.md` - Info de la base de datos
- `CAMBIOS_EN_TIEMPO_REAL.md` - Cómo funcionan los cambios
- `docs/wompi/` - Configuración de pagos
- `docs/emails/` - Configuración de emails

### Para el instructor
- `PARA_EL_INSTRUCTOR.txt` - Información para evaluación
- `CHECKLIST_PROYECTO_COMPLETO.md` - Criterios cumplidos
- `LISTO_PARA_DEMOSTRACION.md` - Guía de demostración

---

## 🎯 Características Principales

### Para Clientes
- ✅ Catálogo de productos con filtros
- ✅ Carrito de compras
- ✅ Checkout con Wompi
- ✅ Historial de órdenes
- ✅ Sistema de reseñas
- ✅ Lista de deseos
- ✅ Recuperación de contraseña

### Para Administradores
- ✅ Dashboard con analíticas
- ✅ Gestión de productos (CRUD completo)
- ✅ Gestión de categorías
- ✅ Gestión de órdenes
- ✅ Gestión de usuarios
- ✅ Sistema de cupones
- ✅ Reportes y gráficas
- ✅ Sistema de facturación
- ✅ Soft deletes (papelera)

---

## 🛠️ Tecnologías

- **Backend:** Laravel 11
- **Frontend:** Blade, Alpine.js, Tailwind CSS
- **Base de datos:** MySQL
- **Pagos:** Wompi (Colombia)
- **Emails:** SMTP (Gmail/Mailtrap)

---

## 📞 Soporte

Si tienes problemas:

1. **Credenciales no funcionan:**
   ```bash
   arreglar-credenciales.bat
   ```

2. **Base de datos vacía:**
   ```bash
   resetear-todo.bat
   ```

3. **Cambios no se ven:**
   - Los cambios en productos se ven inmediatamente
   - El caché se limpia automáticamente

---

## ✅ Verificación

Todo está funcionando si puedes:
- ✅ Iniciar sesión con las credenciales
- ✅ Ver productos en el home
- ✅ Agregar productos al carrito
- ✅ Acceder al panel de administración
- ✅ Ver reportes con datos

---

## 🎉 ¡Listo para usar!

El sistema está completamente configurado y listo para demostración.

**Siguiente paso:** Ejecuta `php artisan serve` y abre http://localhost:8000


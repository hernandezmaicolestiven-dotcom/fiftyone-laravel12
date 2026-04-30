# 🎓 Acceso para el Instructor

## 📱 Información de Acceso

### URLs Disponibles

Prueba estas URLs en orden hasta que una funcione:

1. **Opción 1 (Red principal):**
   ```
   http://10.6.64.193:8000
   ```

2. **Opción 2 (Red alternativa):**
   ```
   http://192.168.137.1:8000
   ```

3. **Opción 3 (Red local):**
   ```
   http://172.30.144.1:8000
   ```

### 🔐 Credenciales de Acceso

#### 👨‍💼 Panel de Administración
```
URL: http://[IP]:8000/admin/login

Email: admin@fiftyone.com
Password: Admin123!
```
⚠️ **Importante:** La contraseña incluye mayúsculas y el signo de exclamación (!)

#### 👤 Cliente (Tienda)
```
URL: http://[IP]:8000/login

Email: cliente@test.com
Password: Cliente123!
```

#### 🤝 Colaborador (Acceso limitado al admin)
```
URL: http://[IP]:8000/admin/login

Email: colaborador@fiftyone.com
Password: Colab123!
```

## ✅ Verificación Rápida

### Desde el PC del Instructor:

1. **Verificar conectividad:**
   ```
   ping 10.6.64.193
   ```
   Si responde, la conexión es buena.

2. **Abrir navegador:**
   - Chrome, Firefox, Edge, etc.
   - Ir a: `http://10.6.64.193:8000`

3. **Si no funciona la primera IP, probar las otras:**
   - `http://192.168.137.1:8000`
   - `http://172.30.144.1:8000`

## 🎯 Funcionalidades para Demostrar

### 1. Página Principal
- Landing page con productos destacados
- Catálogo de productos
- Carrito de compras

### 2. Panel de Administración
- Dashboard con estadísticas
- Gestión de productos
- Gestión de pedidos
- Reportes y análisis
- Sistema de facturación

### 3. Área de Cliente
- Registro e inicio de sesión
- Perfil de usuario
- Historial de pedidos
- Sistema de reseñas

## 🔧 Solución de Problemas

### Si no puede acceder:

1. **Verificar que ambos equipos estén en la misma red**
   - Conectados al mismo Wi-Fi o red

2. **Probar todas las IPs disponibles**
   - 10.6.64.193
   - 192.168.137.1
   - 172.30.144.1

3. **Verificar el firewall**
   - Ya está configurado en el host
   - Si usa VPN, puede bloquear el acceso

4. **Usar el navegador en modo incógnito**
   - Evita problemas de caché

## 📊 Estado del Sistema

- ✅ Docker corriendo
- ✅ Contenedores activos (app + db)
- ✅ Firewall configurado
- ✅ Puerto 8000 abierto
- ✅ Base de datos con migraciones
- ✅ Aplicación lista para usar

## 🎨 Características Destacadas

### Sistema Completo de E-commerce
- Catálogo de productos con categorías
- Carrito de compras funcional
- Sistema de pagos (6 métodos)
- Gestión de pedidos
- Sistema de facturación PDF
- Notificaciones por email
- Panel de administración completo
- Reportes y estadísticas
- Sistema de reseñas
- Cupones de descuento

### Tecnologías Utilizadas
- **Backend:** Laravel 12 + PHP 8.2
- **Frontend:** Blade + Tailwind CSS + Vite
- **Base de Datos:** MySQL 8.0
- **Contenedores:** Docker + Docker Compose
- **Servidor Web:** Nginx
- **Gestión de Procesos:** Supervisor

## 📞 Contacto

Si hay algún problema durante la demostración, avísame inmediatamente.

---

**Fecha:** 30 de Abril de 2026  
**Proyecto:** FiftyOne - Sistema de E-commerce  
**Dockerizado:** ✅ Completamente funcional


# 🔐 Sistema de Recuperación de Contraseña y Control de Sesiones

## ✅ Lo que YA está implementado:

### 1. Recuperación de Contraseña
- ✅ Vista de "Olvidé mi contraseña" (`forgot-password.blade.php`)
- ✅ Vista de "Restablecer contraseña" (`reset-password.blade.php`)
- ✅ Envío de contraseña temporal por email
- ✅ Clase `TemporaryPasswordMail` para el email
- ✅ Rutas configuradas (`/recuperar-contrasena`, `/restablecer-contrasena`)

### 2. Control de Sesiones (NUEVO)
- ✅ Migración `add_password_reset_and_sessions_columns_to_users_table`
  - Columnas: `last_login_at`, `last_login_ip`, `failed_login_attempts`, `locked_until`, `password_changed_at`, `force_password_change`
- ✅ Migración `create_user_sessions_table`
  - Tabla completa para trackear sesiones por dispositivo
- ✅ Modelo `UserSession` con detección de dispositivos
- ✅ Modelo `User` actualizado con métodos de seguridad
- ✅ Controlador actualizado con tracking de login
- ✅ Middleware `TrackUserSession` (creado pero no registrado)

## ⚠️ Lo que falta configurar:

### 1. Registrar el Middleware

Edita `bootstrap/app.php` y agrega el middleware en la sección de middleware web:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\TrackUserSession::class,
    ]);
})
```

### 2. Agregar Sección de Sesiones Activas en Mi Cuenta

En `resources/views/customer/account.blade.php`, agrega esta sección después de la sección de pedidos:

```html
{{-- Sesiones activas --}}
<div class="card-dark border border-white/10 rounded-2xl p-6 fade-up" style="animation-delay: 0.2s">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-xl font-black flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-indigo-500"></i> Sesiones activas
            </h2>
            <p class="text-sm text-gray-500 mt-1">Dispositivos donde has iniciado sesión</p>
        </div>
        <form method="POST" action="{{ route('customer.logout-other-sessions') }}" 
              onsubmit="return confirm('¿Cerrar todas las demás sesiones?')">
            @csrf
            <button type="submit" class="text-xs px-3 py-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition border border-red-500/20">
                <i class="fa-solid fa-power-off mr-1"></i> Cerrar otras sesiones
            </button>
        </form>
    </div>

    @php
        $sessions = $user->sessions()->latest('last_activity')->get();
    @endphp

    @if($sessions->isEmpty())
        <div class="text-center py-8 text-gray-500">
            <i class="fa-solid fa-shield text-4xl mb-3 opacity-20"></i>
            <p class="text-sm">No hay sesiones activas</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($sessions as $session)
            <div class="flex items-center gap-4 p-4 rounded-xl bg-white/3 border border-white/5 hover:border-white/10 transition">
                {{-- Icono del dispositivo --}}
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:{{ $session->is_current ? 'linear-gradient(135deg,#3B59FF,#7B2FBE)' : 'rgba(255,255,255,.05)' }}">
                    <i class="fa-solid {{ 
                        $session->device_type === 'mobile' ? 'fa-mobile-screen' : 
                        ($session->device_type === 'tablet' ? 'fa-tablet-screen-button' : 'fa-desktop') 
                    }} text-{{ $session->is_current ? 'white' : 'gray-400' }}"></i>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-sm">{{ $session->browser }} en {{ $session->platform }}</p>
                        @if($session->is_current)
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-green-500/20 text-green-400 font-bold border border-green-500/30">
                                Actual
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fa-solid fa-location-dot mr-1"></i>{{ $session->ip_address }} · 
                        <i class="fa-solid fa-clock mr-1"></i>{{ $session->last_activity->diffForHumans() }}
                    </p>
                </div>

                <div class="text-xs text-gray-600">
                    {{ ucfirst($session->device_type) }}
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
```

### 3. Actualizar el Controlador de Cuenta

En `CustomerAuthController@account`, pasa las sesiones a la vista:

```php
public function account()
{
    $user = auth()->user();
    $orders = $user->orders()->with('items')->latest()->get();
    $wishlist = \App\Models\Wishlist::with('product.category')
        ->where('user_id', $user->id)->latest()->get();
    $sessions = $user->sessions()->latest('last_activity')->get(); // AGREGAR ESTA LÍNEA
    
    return view('customer.account', compact('user', 'orders', 'wishlist', 'sessions'));
}
```

## 🎯 Características Implementadas:

### Seguridad de Login:
- ✅ **Intentos fallidos**: Después de 5 intentos, la cuenta se bloquea por 30 minutos
- ✅ **Tracking de IP**: Se registra la IP del último login
- ✅ **Timestamp de login**: Se guarda la fecha del último acceso
- ✅ **Desbloqueo automático**: Después de 30 minutos la cuenta se desbloquea

### Gestión de Sesiones:
- ✅ **Múltiples dispositivos**: Permite login desde varios dispositivos
- ✅ **Detección de dispositivo**: Identifica móvil, tablet o desktop
- ✅ **Detección de navegador**: Chrome, Firefox, Safari, Edge, etc.
- ✅ **Detección de plataforma**: Windows, Mac, Linux, Android, iOS
- ✅ **Sesión actual marcada**: La sesión activa aparece destacada
- ✅ **Cerrar otras sesiones**: Botón para cerrar todas menos la actual
- ✅ **Auto-limpieza**: Sesiones antiguas (>30 días) se eliminan automáticamente

### Recuperación de Contraseña:
- ✅ **Contraseña temporal**: Se genera automáticamente
- ✅ **Envío por email**: Notificación con la nueva contraseña
- ✅ **Forzar cambio**: Flag para obligar cambio en próximo login

## 📋 Cómo Probar:

### 1. Recuperación de Contraseña:
1. Ve a `/login`
2. Click en "¿Olvidaste tu contraseña?"
3. Ingresa tu email
4. Revisa tu correo electrónico
5. Usa la contraseña temporal para iniciar sesión
6. Cambia tu contraseña desde "Mi Cuenta"

### 2. Control de Sesiones:
1. Inicia sesión en un dispositivo/navegador
2. Abre otra pestaña o navegador diferente
3. Inicia sesión nuevamente con la misma cuenta
4. Ve a "Mi Cuenta" → Sesiones activas
5. Verás ambas sesiones listadas
6. Usa "Cerrar otras sesiones" para cerrar todas excepto la actual

### 3. Bloqueo por Intentos Fallidos:
1. Intenta iniciar sesión con contraseña incorrecta
2. Repite 5 veces
3. La cuenta se bloqueará por 30 minutos
4. Mensaje: "Tu cuenta está bloqueada temporalmente. Intenta de nuevo en X minutos"

## 🔒 Mejoras de Seguridad:

1. **Throttling**: Ya implementado en rutas (5 intentos por minuto)
2. **Session Regeneration**: Se regenera el ID de sesión en cada login
3. **CSRF Protection**: Tokens CSRF en todos los formularios
4. **Password Hashing**: Bcrypt automático con Laravel
5. **Input Validation**: Validación en todos los endpoints
6. **SQL Injection Protection**: Eloquent ORM previene inyección SQL

## 📝 Notas Importantes:

- Las migraciones ya están ejecutadas ✅
- El modelo User ya está actualizado ✅
- Las rutas ya están configuradas ✅
- Solo falta registrar el middleware y agregar la vista de sesiones

## 🚀 Próximas Mejoras Sugeridas:

1. **2FA (Autenticación de dos factores)**: Agregar Google Authenticator
2. **Email de alerta**: Notificar al usuario de nuevos logins
3. **Geolocalización**: Mostrar ubicación aproximada de cada sesión
4. **Dispositivos confiables**: Marcar dispositivos como confiables
5. **Historial de acceso**: Log completo de todos los accesos

---

**Fecha de implementación**: 2 de Junio, 2026  
**Versión**: 1.0.0  
**Estado**: Implementado parcialmente (falta configurar middleware y vista)

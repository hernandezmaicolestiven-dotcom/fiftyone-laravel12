<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordFacade;
use Illuminate\Validation\Rules\Password;

/**
 * CustomerAuthController — Registro y login de clientes en la tienda pública.
 * Separado del AuthController del admin para mantener responsabilidades claras.
 */
class CustomerAuthController extends Controller
{
    public function showRegister()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        // NO hacer login automático, redirigir al login con mensaje de éxito
        return redirect()->route('customer.login')
            ->with('success', '¡Cuenta creada exitosamente! Ahora puedes iniciar sesión con tus credenciales.');
    }

    public function showLogin()
    {
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Si es admin → panel de administración
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Si es colaborador → panel también
            if (Auth::user()->role === 'colaborador') {
                return redirect()->route('admin.dashboard');
            }

            // Si viene del carrito → regresar al home con checkout abierto
            if ($request->input('redirect') === 'checkout' || $request->query('redirect') === 'checkout') {
                return redirect('/?checkout=1');
            }

            return redirect()->route('customer.account');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sesión cerrada correctamente.');
    }

    /** Área de cuenta del cliente — historial de pedidos */
    public function account()
    {
        $user    = auth()->user();
        $orders  = $user->orders()->with('items')->latest()->get();
        $wishlist = \App\Models\Wishlist::with('product.category')
            ->where('user_id', $user->id)->latest()->get();
        return view('customer.account', compact('user', 'orders', 'wishlist'));
    }

    public function cancelOrder(\App\Models\Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        if ($order->status !== 'pending') {
            return back()->with('error', 'Solo puedes cancelar pedidos pendientes.');
        }
        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Pedido #'.$order->id.' cancelado.');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->only('name', 'email', 'phone');
        
        // Manejar subida de avatar
        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            
            // Guardar nuevo avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }
        
        $user->update($data);
        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:8',
        ]);
        if (!\Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }
        auth()->user()->update(['password' => \Hash::make($request->password)]);
        return back()->with('success', 'Contraseña actualizada.');
    }

    /** Mostrar formulario de recuperación de contraseña */
    public function showForgotPassword()
    {
        return view('customer.auth.forgot-password');
    }

    /** Enviar enlace de recuperación */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = PasswordFacade::sendResetLink(
            $request->only('email')
        );

        return $status === PasswordFacade::RESET_LINK_SENT
            ? back()->with('status', 'Te hemos enviado un enlace de recuperación a tu correo.')
            : back()->withErrors(['email' => 'No encontramos una cuenta con ese correo.']);
    }

    /** Mostrar formulario de restablecimiento */
    public function showResetPassword($token)
    {
        return view('customer.auth.reset-password', ['token' => $token]);
    }

    /** Restablecer contraseña */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = PasswordFacade::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === PasswordFacade::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('success', 'Tu contraseña ha sido restablecida. Ya puedes iniciar sesión.')
            : back()->withErrors(['email' => 'El enlace de recuperación es inválido o ha expirado.']);
    }
}

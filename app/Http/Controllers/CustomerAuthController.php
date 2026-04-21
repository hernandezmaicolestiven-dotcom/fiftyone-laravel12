<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        Auth::login($user);

        return redirect()->route('customer.account')
            ->with('success', '¡Bienvenido, '.$user->name.'!');
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

        return redirect('/');
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
        ]);
        $user->update($request->only('name', 'email', 'phone'));
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
}

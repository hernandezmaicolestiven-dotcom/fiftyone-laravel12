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

            // Si es admin, no dejarlo entrar por aquí
            if (Auth::user()->role === 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Usa el panel de administración para iniciar sesión.'])->onlyInput('email');
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
        $user = auth()->user();
        $orders = $user->orders()->with('items')->latest()->get();

        return view('customer.account', compact('user', 'orders'));
    }
}

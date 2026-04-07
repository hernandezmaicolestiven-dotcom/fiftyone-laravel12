<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ColaboradorController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $colaboradores = User::where('role', 'colaborador')->latest()->get();
        return view('admin.colaboradores.index', compact('colaboradores'));
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'colaborador',
        ]);

        return back()->with('success', 'Colaborador agregado correctamente.');
    }

    public function destroy(User $user)
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        if ($user->role !== 'colaborador') {
            return back()->with('error', 'Solo puedes eliminar colaboradores.');
        }

        $user->delete();
        return back()->with('success', 'Colaborador eliminado.');
    }
}

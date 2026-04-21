<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminManagerController extends Controller
{
    private function checkSuperAdmin()
    {
        abort_if(auth()->user()->role !== 'superadmin', 403);
    }

    public function index()
    {
        $this->checkSuperAdmin();
        $admins = User::whereIn('role', ['admin', 'colaborador'])->latest()->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $this->checkSuperAdmin();
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,colaborador',
            'password' => ['required', Password::min(8)],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return back()->with('success', ucfirst($request->role).' creado correctamente.');
    }

    public function destroy(User $user)
    {
        $this->checkSuperAdmin();
        if ($user->role === 'superadmin') {
            return back()->with('error', 'No puedes eliminar al superadmin.');
        }
        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->checkSuperAdmin();
        $request->validate(['password' => ['required', Password::min(8)]]);
        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Contraseña restablecida.');
    }
}

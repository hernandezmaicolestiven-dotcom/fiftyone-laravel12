@extends('admin.layouts.app')
@section('title','Administradores')
@section('page-title','Gestión de Administradores')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Formulario crear admin --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center gap-2 mb-1">
            <i class="fa-solid fa-crown text-amber-500"></i>
            <h2 class="text-base font-bold text-gray-800">Crear administrador</h2>
        </div>
        <p class="text-xs text-gray-400 mb-5">Solo el superadmin puede crear admins</p>

        <form method="POST" action="{{ route('admin.admins.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre</label>
                <input type="text" name="name" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50"
                       placeholder="Nombre completo">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50"
                       placeholder="correo@ejemplo.com">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Rol</label>
                <select name="role" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
                    <option value="admin">Admin</option>
                    <option value="colaborador">Colaborador</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Contraseña</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50"
                       placeholder="Mínimo 8 caracteres">
            </div>
            @if($errors->any())
            <p class="text-xs text-red-500">{{ $errors->first() }}</p>
            @endif
            <button type="submit" class="w-full py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition"
                    style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
                <i class="fa-solid fa-plus mr-1.5"></i> Crear administrador
            </button>
        </form>
    </div>

    {{-- Lista admins --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-800">Administradores activos</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $admins->count() }} usuario(s) con acceso al panel</p>
        </div>

        @forelse($admins as $a)
        <div class="px-6 py-4 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                         style="background:linear-gradient(135deg,{{ $a->role==='admin' ? '#3B59FF,#7B2FBE' : '#d97706,#f59e0b' }})">
                        {{ strtoupper(substr($a->name,0,1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $a->name }}</p>
                        <p class="text-xs text-gray-400">{{ $a->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ $a->role==='admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ ucfirst($a->role) }}
                    </span>
                    {{-- Reset contraseña --}}
                    <form method="POST" action="{{ route('admin.admins.reset', $a) }}" class="flex gap-1"
                          onsubmit="return confirm('¿Restablecer contraseña a admin123?')">
                        @csrf @method('PATCH')
                        <input type="hidden" name="password" value="admin123">
                        <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                            Reset pass
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.admins.destroy', $a) }}"
                          onsubmit="return confirm('¿Eliminar a {{ $a->name }}?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="px-6 py-16 text-center text-gray-400">
            <i class="fa-solid fa-users text-4xl mb-3 block opacity-20"></i>
            <p>No hay administradores creados.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

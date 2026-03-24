@extends('admin.layouts.app')

@section('title', 'Usuarios')
@section('page-title', 'Usuarios')

@section('content')

@if(session('success'))
    <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
    </div>
@endif

{{-- Stats rápidas --}}
<div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background: linear-gradient(135deg, rgba(59,89,255,0.15), rgba(123,47,190,0.15))">
            <i class="fa-solid fa-users" style="background: linear-gradient(135deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400">Total usuarios</p>
            <p class="text-xl font-bold text-gray-800">{{ $users->total() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-user-check text-emerald-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400">Este mes</p>
            <p class="text-xl font-bold text-gray-800">
                {{ \App\Models\User::whereMonth('created_at', now()->month)->count() }}
            </p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 col-span-2 sm:col-span-1">
        <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-user-shield text-amber-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400">Administradores</p>
            <p class="text-xl font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
        </div>
    </div>
</div>

{{-- Tabla principal --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-5 border-b border-gray-100">
        <div>
            <h3 class="text-base font-semibold text-gray-800">Lista de usuarios</h3>
            <p class="text-xs text-gray-400 mt-0.5">Gestiona los administradores del panel</p>
        </div>
        <div class="flex gap-2">
            <form method="GET" class="flex gap-2">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Buscar..."
                           class="pl-8 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 w-48 bg-gray-50 focus:bg-white transition">
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}"
                       class="px-3 py-2 rounded-xl border border-gray-200 text-gray-400 hover:bg-gray-50 text-sm transition">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold whitespace-nowrap shadow-sm hover:opacity-90 transition"
               style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                <i class="fa-solid fa-plus"></i>
                <span class="hidden sm:inline">Nuevo usuario</span>
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wide">
                    <th class="px-6 py-3 text-left font-semibold">Usuario</th>
                    <th class="px-6 py-3 text-left font-semibold hidden md:table-cell">Email</th>
                    <th class="px-6 py-3 text-left font-semibold hidden lg:table-cell">Registrado</th>
                    <th class="px-6 py-3 text-right font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/70 transition group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm"
                                 style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 flex items-center gap-2">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                              style="background: linear-gradient(135deg, rgba(59,89,255,0.1), rgba(123,47,190,0.1)); color: #3B59FF">
                                            Tú
                                        </span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400 md:hidden">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 hidden md:table-cell">{{ $user->email }}</td>
                    <td class="px-6 py-4 hidden lg:table-cell">
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fa-regular fa-calendar"></i>
                            {{ $user->created_at->format('d/m/Y') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 text-xs font-medium transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span class="hidden sm:inline">Editar</span>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('¿Seguro que quieres eliminar a {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-400 hover:border-red-300 hover:text-red-500 hover:bg-red-50 text-xs font-medium transition">
                                    <i class="fa-solid fa-trash"></i>
                                    <span class="hidden sm:inline">Eliminar</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center"
                             style="background: linear-gradient(135deg, rgba(59,89,255,0.1), rgba(123,47,190,0.1))">
                            <i class="fa-solid fa-users text-2xl" style="background: linear-gradient(135deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        </div>
                        <p class="text-gray-500 font-medium">No hay usuarios registrados</p>
                        <p class="text-gray-400 text-sm mt-1">Crea el primer usuario con el botón de arriba.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">
            Mostrando {{ $users->firstItem() }}–{{ $users->lastItem() }} de {{ $users->total() }} usuarios
        </p>
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection

@extends('admin.layouts.app')
@section('title','Papelera de usuarios')
@section('page-title','Papelera — Usuarios')
@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Papelera de usuarios</h2>
            <p class="text-sm text-gray-400 mt-0.5">{{ $users->total() }} usuario(s) eliminado(s)</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i> Volver
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                <th class="px-6 py-3 text-left">Nombre</th>
                <th class="px-6 py-3 text-left">Email</th>
                <th class="px-6 py-3 text-left">Rol</th>
                <th class="px-6 py-3 text-left">Eliminado</th>
                <th class="px-6 py-3 text-right">Acciones</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 opacity-75">
                    <td class="px-6 py-4 font-medium text-gray-500 line-through">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $user->role }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $user->deleted_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                        <form method="POST" action="{{ route('admin.users.restore', $user->id) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Restaurar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.force-delete', $user->id) }}"
                              onsubmit="return confirm('¿Eliminar permanentemente?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                <i class="fa-solid fa-trash mr-1"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-16 text-center text-gray-400">
                    <i class="fa-solid fa-trash text-4xl mb-3 block opacity-20"></i> La papelera está vacía.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $users->links('vendor.pagination.tailwind') }}</div>
    @endif
</div>
@endsection

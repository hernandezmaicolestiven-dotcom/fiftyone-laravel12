@extends('admin.layouts.app')

@section('title', 'Categorías')
@section('page-title', 'Categorías')

@section('content')

<div class="bg-white rounded-xl shadow-sm">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Lista de categorías</h2>
            <p class="text-sm text-gray-500">{{ $categories->total() }} categorías en total</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
            <i class="fa-solid fa-plus"></i> Nueva categoría
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" class="flex gap-3 p-4 border-b border-gray-100">
        <div class="relative flex-1 max-w-sm">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i class="fa-solid fa-search text-sm"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar categoría..."
                   class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-sm px-4 py-2 rounded-lg transition">
            Filtrar
        </button>
        @if(request('search'))
            <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-500 hover:text-red-500 flex items-center gap-1 px-2">
                <i class="fa-solid fa-xmark"></i> Limpiar
            </a>
        @endif
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Slug</th>
                    <th class="px-6 py-3">Descripción</th>
                    <th class="px-6 py-3">Productos</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $category->name }}</td>
                    <td class="px-6 py-4">
                        <code class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $category->slug }}</code>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs max-w-xs truncate">
                        {{ $category->description ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                            {{ $category->products_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="inline-flex items-center gap-1 text-xs bg-amber-50 hover:bg-amber-100 text-amber-700 px-3 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                  onsubmit="return confirm('¿Eliminar la categoría «{{ addslashes($category->name) }}»? Los productos asociados quedarán sin categoría.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 text-xs bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded-lg transition">
                                    <i class="fa-solid fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-tags text-4xl mb-3 block"></i>
                        No hay categorías que mostrar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $categories->links() }}
    </div>
    @endif

</div>

@endsection

@extends('admin.layouts.app')

@section('title', 'Productos')
@section('page-title', 'Productos')

@section('content')

<div class="bg-white rounded-xl shadow-sm">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Lista de productos</h2>
            <p class="text-sm text-gray-500">{{ $products->total() }} productos en total</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition"
           style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
            <i class="fa-solid fa-plus"></i> Nuevo producto
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-3 p-4 border-b border-gray-100">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i class="fa-solid fa-search text-sm"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar producto..."
                   class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <select name="category"
                class="border border-gray-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                class="text-white text-sm px-4 py-2 rounded-lg transition"
                style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
            Filtrar
        </button>
        @if(request()->hasAny(['search','category']))
            <a href="{{ route('admin.products.index') }}"
               class="text-sm text-gray-500 hover:text-red-500 flex items-center gap-1 px-2">
                <i class="fa-solid fa-xmark"></i> Limpiar
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">Producto</th>
                    <th class="px-6 py-3">Categoría</th>
                    <th class="px-6 py-3">Precio</th>
                    <th class="px-6 py-3">Stock</th>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if($product->image)
                                    <img src="{{ str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image) }}" class="w-full h-full object-cover" alt="">
                                @else
                                    <i class="fa-solid fa-image text-gray-300"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->category)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white" style="background: linear-gradient(90deg, rgba(59,89,255,0.15), rgba(123,47,190,0.15)); color: #7B2FBE; background: linear-gradient(90deg, #ede9fe, #ddd6fe)">
                                <span style="background: linear-gradient(90deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight:600">{{ $product->category->name }}</span>
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">Sin categoría</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">COP ${{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $product->stock < 5 ? 'bg-red-100 text-red-700' : ($product->stock < 20 ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                            {{ $product->stock }} uds.
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $product->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="inline-flex items-center gap-1 text-xs bg-amber-50 hover:bg-amber-100 text-amber-700 px-3 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                  onsubmit="return confirm('¿Eliminar el producto «{{ addslashes($product->name) }}»? Esta acción no se puede deshacer.')">
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
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                        No hay productos que mostrar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $products->links() }}
    </div>
    @endif

</div>

@endsection

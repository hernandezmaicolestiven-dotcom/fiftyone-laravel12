@extends('admin.layouts.app')

@section('title', 'Productos')
@section('page-title', 'Productos')

@section('content')

@if(session('success'))
<div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Lista de productos</h2>
            <p class="text-sm text-gray-500">{{ $products->total() }} productos en total</p>
        </div>
        <div class="flex flex-wrap gap-2">

            {{-- Dropdown Exportar --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                        class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                    <i class="fa-solid fa-arrow-up-from-bracket text-indigo-500"></i>
                    Exportar
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform" :class="open && 'rotate-180'"></i>
                </button>
                <div x-show="open" x-transition
                     class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-20">
                    <a href="{{ route('admin.products.export.csv', request()->query()) }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <span class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-file-csv text-emerald-600 text-xs"></i>
                        </span>
                        Exportar CSV
                    </a>
                    <a href="{{ route('admin.products.export.excel', request()->query()) }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <span class="w-7 h-7 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-file-excel text-green-600 text-xs"></i>
                        </span>
                        Exportar Excel
                    </a>
                </div>
            </div>

            {{-- Botón Importar --}}
            <button type="button" @click="$dispatch('open-import')"
                    class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-arrow-down-to-bracket text-violet-500"></i>
                Importar
            </button>

            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center gap-2 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition shadow-sm hover:opacity-90"
               style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                <i class="fa-solid fa-plus"></i> Nuevo producto
            </a>
        </div>
    </div>

    {{-- Modal Importar --}}
    <div x-data="{ show: false }" @open-import.window="show = true" x-cloak>
        <div x-show="show" x-transition.opacity
             class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/50 backdrop-blur-sm p-4"
             @click.self="show = false">
            <div x-show="show" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">

                {{-- Modal header --}}
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between"
                     style="background: linear-gradient(135deg, #f8f7ff, #fff)">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                             style="background: linear-gradient(135deg, rgba(59,89,255,0.12), rgba(123,47,190,0.12))">
                            <i class="fa-solid fa-file-import" style="background: linear-gradient(135deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Importar productos</h3>
                            <p class="text-xs text-gray-400">CSV o Excel (.xlsx)</p>
                        </div>
                    </div>
                    <button @click="show = false" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Modal body --}}
                <form method="POST" action="{{ route('admin.products.import.csv') }}" enctype="multipart/form-data" class="p-6">
                    @csrf

                    {{-- Formato esperado --}}
                    <div class="mb-5 p-4 rounded-xl bg-indigo-50 border border-indigo-100">
                        <p class="text-xs font-semibold text-indigo-700 mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info"></i> Formato esperado
                        </p>
                        <div class="overflow-x-auto">
                            <table class="text-xs w-full text-indigo-600">
                                <thead>
                                    <tr class="border-b border-indigo-200">
                                        <th class="pb-1 pr-3 font-semibold text-left">ID</th>
                                        <th class="pb-1 pr-3 font-semibold text-left">Nombre</th>
                                        <th class="pb-1 pr-3 font-semibold text-left">Descripción</th>
                                        <th class="pb-1 pr-3 font-semibold text-left">Precio</th>
                                        <th class="pb-1 pr-3 font-semibold text-left">Stock</th>
                                        <th class="pb-1 font-semibold text-left">Categoría</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-indigo-400">
                                        <td class="pt-1 pr-3">—</td>
                                        <td class="pt-1 pr-3">Camiseta</td>
                                        <td class="pt-1 pr-3">Algodón</td>
                                        <td class="pt-1 pr-3">29900</td>
                                        <td class="pt-1 pr-3">50</td>
                                        <td class="pt-1">Ropa</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-xs text-indigo-400 mt-2">La primera fila se omite como encabezado.</p>
                    </div>

                    {{-- Drop zone --}}
                    <label for="importFile"
                           class="flex flex-col items-center justify-center gap-3 w-full h-36 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/50 transition group">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 group-hover:bg-indigo-100 flex items-center justify-center transition">
                            <i class="fa-solid fa-cloud-arrow-up text-gray-400 group-hover:text-indigo-500 text-xl transition"></i>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-600 group-hover:text-indigo-600 transition">
                                Arrastra tu archivo o <span class="underline">haz clic aquí</span>
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">.csv, .xlsx — máx. 5 MB</p>
                        </div>
                        <input id="importFile" type="file" name="file" accept=".csv,.txt,.xlsx,.xls" required class="hidden"
                               onchange="document.getElementById('importFileName').textContent = this.files[0]?.name ?? ''">
                    </label>
                    <p id="importFileName" class="text-xs text-indigo-600 text-center mt-2 font-medium"></p>

                    {{-- Acciones --}}
                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="show = false"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition font-medium">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 rounded-xl text-white text-sm font-semibold shadow-sm hover:opacity-90 transition"
                                style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                            <i class="fa-solid fa-upload mr-1.5"></i> Importar
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
        {{ $products->links('vendor.pagination.tailwind') }}
    </div>
    @endif

</div>

@endsection

@extends('admin.layouts.app')
@section('title','Papelera de productos')
@section('page-title','Papelera')

@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Papelera de productos</h2>
            <p class="text-sm text-gray-400 mt-0.5">{{ $products->total() }} producto(s) eliminado(s)</p>
        </div>
        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition">
            <i class="fa-solid fa-arrow-left text-xs"></i> Volver a productos
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                    <th class="px-6 py-3 text-left">Producto</th>
                    <th class="px-6 py-3 text-left">Categoría</th>
                    <th class="px-6 py-3 text-left">Precio</th>
                    <th class="px-6 py-3 text-left">Eliminado</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition opacity-75">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($product->image)
                            <img src="{{ str_starts_with($product->image,'http') ? $product->image : Storage::url($product->image) }}"
                                 class="w-10 h-10 rounded-xl object-cover grayscale" alt="{{ $product->name }}">
                            @endif
                            <span class="font-medium text-gray-600 line-through">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-400">{{ $product->category?->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-400">$ {{ number_format($product->price,0,',','.') }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form method="POST" action="{{ route('admin.products.restore', $product->id) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition">
                                    <i class="fa-solid fa-rotate-left mr-1"></i> Restaurar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.products.force-delete', $product->id) }}"
                                  onsubmit="return confirm('¿Eliminar permanentemente? Esta acción no se puede deshacer.')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                    <i class="fa-solid fa-trash mr-1"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                        <i class="fa-solid fa-trash text-4xl mb-3 block opacity-20"></i>
                        La papelera está vacía.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $products->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>
@endsection

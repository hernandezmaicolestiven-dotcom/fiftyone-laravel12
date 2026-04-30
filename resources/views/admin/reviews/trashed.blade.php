@extends('admin.layouts.app')
@section('title','Papelera de reseñas')
@section('page-title','Papelera — Reseñas')
@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Papelera de reseñas</h2>
            <p class="text-sm text-gray-400 mt-0.5">{{ $reviews->total() }} reseña(s) eliminada(s)</p>
        </div>
        <a href="{{ route('admin.reviews.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i> Volver
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                <th class="px-6 py-3 text-left">Producto</th>
                <th class="px-6 py-3 text-left">Usuario</th>
                <th class="px-6 py-3 text-left">Rating</th>
                <th class="px-6 py-3 text-left">Comentario</th>
                <th class="px-6 py-3 text-left">Eliminada</th>
                <th class="px-6 py-3 text-right">Acciones</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50 opacity-75">
                    <td class="px-6 py-4 text-gray-500">{{ $review->product->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $review->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-star text-xs {{ $i <= $review->rating ? 'fa-solid text-amber-400' : 'fa-regular text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-400 max-w-xs truncate">{{ $review->comment }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $review->deleted_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                        <form method="POST" action="{{ route('admin.reviews.restore', $review->id) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Restaurar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.reviews.force-delete', $review->id) }}"
                              onsubmit="return confirm('¿Eliminar permanentemente?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                <i class="fa-solid fa-trash mr-1"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-16 text-center text-gray-400">
                    <i class="fa-solid fa-trash text-4xl mb-3 block opacity-20"></i> La papelera está vacía.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $reviews->links('vendor.pagination.tailwind') }}</div>
    @endif
</div>
@endsection

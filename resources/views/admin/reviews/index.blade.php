@extends('admin.layouts.app')
@section('title', 'Reseñas')
@section('page-title', 'Reseñas')

@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-800">Reseñas de clientes</h2>
        <p class="text-sm text-gray-400 mt-0.5">{{ $reviews->total() }} reseña{{ $reviews->total() !== 1 ? 's' : '' }} en total</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                    <th class="px-6 py-3 text-left">Producto</th>
                    <th class="px-6 py-3 text-left">Cliente</th>
                    <th class="px-6 py-3 text-left">Calificación</th>
                    <th class="px-6 py-3 text-left">Comentario</th>
                    <th class="px-6 py-3 text-left">Fecha</th>
                    <th class="px-6 py-3 text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $review->product->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $review->user->name ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fa-star text-xs {{ $i <= $review->rating ? 'fa-solid text-amber-400' : 'fa-regular text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $review->comment ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $review->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                              onsubmit="return confirm('¿Eliminar esta reseña?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <i class="fa-solid fa-star text-4xl mb-3 block opacity-20"></i>
                        No hay reseñas todavía.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reviews->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $reviews->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>
@endsection

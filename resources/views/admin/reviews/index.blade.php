@extends('admin.layouts.app')
@section('title', 'Reseñas')
@section('page-title', 'Moderación de Reseñas')

@section('content')

<div class="bg-white rounded-xl shadow-sm">
    <!-- Header con estadísticas -->
    <div class="p-6 border-b border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-amber-600 uppercase">Pendientes</p>
                        <p class="text-2xl font-black text-amber-900 mt-1">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center">
                        <i class="fa-solid fa-clock text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-emerald-600 uppercase">Aprobadas</p>
                        <p class="text-2xl font-black text-emerald-900 mt-1">{{ $stats['approved'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center">
                        <i class="fa-solid fa-check text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-red-600 uppercase">Rechazadas</p>
                        <p class="text-2xl font-black text-red-900 mt-1">{{ $stats['rejected'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-500 flex items-center justify-center">
                        <i class="fa-solid fa-ban text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-blue-600 uppercase">Total</p>
                        <p class="text-2xl font-black text-blue-900 mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center">
                        <i class="fa-solid fa-star text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Reseñas de clientes</h2>
                <p class="text-sm text-gray-500">{{ $reviews->total() }} reseñas en total</p>
            </div>
            <a href="{{ route('admin.reviews.trashed') }}"
               class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                <i class="fa-solid fa-trash text-red-400"></i> Papelera
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" action="{{ route('admin.reviews.index') }}" class="p-4 border-b border-gray-100 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <select name="status" onchange="this.form.submit()"
                    class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="">Todos los estados</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>⏳ Pendientes</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>✅ Aprobadas</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>❌ Rechazadas</option>
            </select>

            <select name="product_id" onchange="this.form.submit()"
                    class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="">Todos los productos</option>
                @foreach($products as $p)
                <option value="{{ $p->id }}" {{ $productId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        @if($productId || $status)
        <div class="mt-3">
            <a href="{{ route('admin.reviews.index') }}" class="text-sm text-gray-500 hover:text-red-500 flex items-center gap-1">
                <i class="fa-solid fa-xmark"></i> Limpiar filtros
            </a>
        </div>
        @endif
    </form>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Producto</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Calificación</th>
                    <th class="px-6 py-3">Comentario</th>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        @if($review->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                <i class="fa-solid fa-clock mr-1"></i> Pendiente
                            </span>
                        @elseif($review->status === 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <i class="fa-solid fa-check mr-1"></i> Aprobada
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <i class="fa-solid fa-ban mr-1"></i> Rechazada
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $review->product->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $review->user->name ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fa-star text-xs {{ $i <= $review->rating ? 'fa-solid text-amber-400' : 'fa-regular text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs">
                        <p class="line-clamp-2">{{ $review->comment ?? '—' }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $review->created_at->format('d/m/Y H:i') }}
                        @if($review->approved_at)
                            <p class="text-emerald-600 mt-1">
                                <i class="fa-solid fa-check-circle"></i> {{ $review->approved_at->format('d/m/Y') }}
                            </p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            @if($review->status === 'pending')
                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg transition">
                                        <i class="fa-solid fa-check"></i> Aprobar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 text-xs bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded-lg transition">
                                        <i class="fa-solid fa-ban"></i> Rechazar
                                    </button>
                                </form>
                            @elseif($review->status === 'rejected')
                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg transition">
                                        <i class="fa-solid fa-check"></i> Aprobar
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="inline"
                                  onsubmit="return confirm('¿Mover esta reseña a la papelera?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 text-xs bg-gray-50 hover:bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg transition">
                                    <i class="fa-solid fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-star text-4xl mb-3 block"></i>
                        No hay reseñas que mostrar.
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

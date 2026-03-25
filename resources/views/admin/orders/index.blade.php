@extends('admin.layouts.app')
@section('title', 'Pedidos')
@section('page-title', 'Pedidos')

@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Todos los pedidos</h2>
            <p class="text-sm text-gray-400">{{ $orders->total() }} pedidos en total</p>
        </div>
        <form method="GET" class="flex gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar cliente..."
                   class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 w-48">
            <select name="status" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <option value="">Todos los estados</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                <option value="shipped"   {{ request('status') === 'shipped'   ? 'selected' : '' }}>Enviado</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-xl text-white text-sm font-semibold"
                    style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">Filtrar</button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50">Limpiar</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                    <th class="px-6 py-3 text-left">#</th>
                    <th class="px-6 py-3 text-left">Cliente</th>
                    <th class="px-6 py-3 text-left">Contacto</th>
                    <th class="px-6 py-3 text-left">Items</th>
                    <th class="px-6 py-3 text-left">Total</th>
                    <th class="px-6 py-3 text-left">Estado</th>
                    <th class="px-6 py-3 text-left">Fecha</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                @php
                    $colors = [
                        'pending'   => 'bg-amber-100 text-amber-700',
                        'confirmed' => 'bg-blue-100 text-blue-700',
                        'shipped'   => 'bg-indigo-100 text-indigo-700',
                        'delivered' => 'bg-emerald-100 text-emerald-700',
                        'cancelled' => 'bg-red-100 text-red-600',
                    ];
                    $color = $colors[$order->status] ?? 'bg-gray-100 text-gray-600';
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-mono text-gray-400">#{{ $order->id }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-gray-500">
                        <div>{{ $order->customer_email ?? '—' }}</div>
                        <div class="text-xs">{{ $order->customer_phone ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $order->items->count() }} producto(s)</td>
                    <td class="px-6 py-4 font-bold text-gray-800">${{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $color }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                                Ver
                            </a>
                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                                  onsubmit="return confirm('¿Eliminar este pedido?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center text-gray-400">
                        <i class="fa-solid fa-bag-shopping text-4xl mb-3 block opacity-30"></i>
                        No hay pedidos todavía.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection

@extends('admin.layouts.app')
@section('title','Papelera de pedidos')
@section('page-title','Papelera — Pedidos')
@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Papelera de pedidos</h2>
            <p class="text-sm text-gray-400 mt-0.5">{{ $orders->total() }} pedido(s) eliminado(s)</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i> Volver
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                <th class="px-6 py-3 text-left">#</th>
                <th class="px-6 py-3 text-left">Cliente</th>
                <th class="px-6 py-3 text-left">Total</th>
                <th class="px-6 py-3 text-left">Estado</th>
                <th class="px-6 py-3 text-left">Eliminado</th>
                <th class="px-6 py-3 text-right">Acciones</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 opacity-75">
                    <td class="px-6 py-4 font-mono text-gray-500 line-through">#{{ $order->id }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-gray-500 font-semibold">${{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $order->status_label }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $order->deleted_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                        <form method="POST" action="{{ route('admin.orders.restore', $order->id) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Restaurar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.orders.force-delete', $order->id) }}"
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
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $orders->links('vendor.pagination.tailwind') }}</div>
    @endif
</div>
@endsection

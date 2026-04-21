@extends('admin.layouts.app')
@section('title', 'Pedido #' . $order->id)
@section('page-title', 'Pedido #' . $order->id)

@section('content')
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Order items --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Productos del pedido</h2>
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">{{ $order->status_label }}</span>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                    <th class="px-6 py-3 text-left">Producto</th>
                    <th class="px-6 py-3 text-center">Cantidad</th>
                    <th class="px-6 py-3 text-right">Precio</th>
                    <th class="px-6 py-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                <tr>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $item->product_name }}</div>
                        @if($item->product)
                            <div class="text-xs text-gray-400">{{ $item->product->category->name ?? '' }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center text-gray-600">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 text-right text-gray-600">${{ number_format($item->price, 2) }}</td>
                    <td class="px-6 py-4 text-right font-semibold text-gray-800">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-gray-200">
                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700">Total</td>
                    <td class="px-6 py-4 text-right font-black text-xl text-gray-900">${{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Sidebar info --}}
    <div class="space-y-4">

        {{-- Customer info --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">Información del cliente</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-user text-gray-400 mt-0.5 w-4"></i>
                    <span class="text-gray-700 font-medium">{{ $order->customer_name }}</span>
                </div>
                @if($order->customer_email)
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-envelope text-gray-400 mt-0.5 w-4"></i>
                    <span class="text-gray-600">{{ $order->customer_email }}</span>
                </div>
                @endif
                @if($order->customer_phone)
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-phone text-gray-400 mt-0.5 w-4"></i>
                    <span class="text-gray-600">{{ $order->customer_phone }}</span>
                </div>
                @endif
                @if($order->shipping_address)
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-location-dot text-indigo-400 mt-0.5 w-4"></i>
                    <div>
                        <span class="text-gray-800 font-semibold block">{{ $order->shipping_address }}</span>
                        @if($order->city)<span class="text-gray-500 text-sm">{{ $order->city }}</span>@endif
                    </div>
                </div>
                @endif
                @if($order->notes)
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-note-sticky text-gray-400 mt-0.5 w-4"></i>
                    <span class="text-gray-600 italic">{{ $order->notes }}</span>
                </div>
                @endif
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-clock text-gray-400 mt-0.5 w-4"></i>
                    <span class="text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Update status --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">Actualizar estado</h3>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf @method('PATCH')
                <select name="status" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @foreach(['pending' => 'Pendiente', 'confirmed' => 'Confirmado', 'shipped' => 'Enviado', 'delivered' => 'Entregado', 'cancelled' => 'Cancelado'] as $val => $label)
                        <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full py-2 rounded-xl text-white text-sm font-semibold"
                        style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                    Guardar cambio
                </button>
            </form>
        </div>

        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition px-2">
            <i class="fa-solid fa-arrow-left"></i> Volver a pedidos
        </a>
        <div class="flex gap-2 mt-3">
            <a href="{{ route('admin.generators.invoice', $order) }}" target="_blank"
               class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-file-invoice text-indigo-500"></i> Factura
            </a>
            <a href="{{ route('admin.generators.label', $order) }}" target="_blank"
               class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-tag text-purple-500"></i> Etiqueta
            </a>
        </div>
    </div>
</div>
@endsection

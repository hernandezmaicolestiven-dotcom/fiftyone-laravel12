@extends('admin.layouts.app')
@section('title', 'Factura ' . $invoice->invoice_number)
@section('page-title', 'Detalle de Factura')

@section('content')

<div class="mb-6 flex gap-3">
    <a href="{{ route('admin.invoices.index') }}" 
       class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
        <i class="fa-solid fa-arrow-left"></i> Volver a facturas
    </a>
    <a href="{{ route('admin.invoices.download-pdf', $invoice) }}" target="_blank"
       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
        <i class="fa-solid fa-download"></i> Descargar PDF
    </a>
    @if($invoice->isActive())
    <button onclick="document.getElementById('resend-form').submit()"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
        <i class="fa-solid fa-envelope"></i> Reenviar por email
    </button>
    <button onclick="document.getElementById('cancel-modal').classList.remove('hidden')"
            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
        <i class="fa-solid fa-ban"></i> Anular factura
    </button>
    @endif
</div>

<!-- Factura -->
<div class="bg-white rounded-xl shadow-lg p-8 md:p-12">
    <!-- Encabezado -->
    <div class="flex justify-between items-start mb-8 pb-6 border-b-2 border-gray-200">
        <div>
            <h1 class="text-3xl font-black text-gray-900">FIFTYONE</h1>
            <p class="text-sm text-gray-500 mt-1">Ropa Oversize Colombia</p>
        </div>
        <div class="text-right">
            <div class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg mb-2">
                <p class="text-xs font-semibold">FACTURA DE VENTA</p>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $invoice->invoice_number }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $invoice->created_at->format('d/m/Y H:i') }}</p>
            @if($invoice->status === 'cancelled')
                <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                    <i class="fa-solid fa-ban mr-1"></i> ANULADA
                </div>
            @else
                <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                    <i class="fa-solid fa-circle-check mr-1"></i> ACTIVA
                </div>
            @endif
        </div>
    </div>

    <!-- Datos del cliente -->
    <div class="mb-8">
        <h3 class="text-sm font-bold text-gray-500 uppercase mb-3">Facturado a:</h3>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="font-bold text-gray-900">{{ $invoice->customer_name }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ $invoice->customer_email }}</p>
            @if($invoice->customer_address)
                <p class="text-sm text-gray-600 mt-1">{{ $invoice->customer_address }}</p>
            @endif
            @if($invoice->customer_document)
                <p class="text-sm text-gray-600 mt-1">CC/NIT: {{ $invoice->customer_document }}</p>
            @endif
        </div>
    </div>

    @if($invoice->status === 'cancelled')
    <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <div class="flex items-start gap-3">
            <i class="fa-solid fa-ban text-red-500 text-xl mt-0.5"></i>
            <div>
                <p class="font-bold text-red-900">Factura Anulada</p>
                <p class="text-sm text-red-700 mt-1">{{ $invoice->cancellation_reason }}</p>
                <p class="text-xs text-red-600 mt-1">Anulada el {{ $invoice->cancelled_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de productos -->
    <div class="mb-8">
        <h3 class="text-sm font-bold text-gray-500 uppercase mb-3">Detalle de productos:</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-100 border-b-2 border-gray-200">
                        <th class="text-left py-3 px-4 font-bold text-gray-700">Producto</th>
                        <th class="text-center py-3 px-3 font-bold text-gray-700">Talla</th>
                        <th class="text-right py-3 px-3 font-bold text-gray-700">Precio</th>
                        <th class="text-center py-3 px-3 font-bold text-gray-700">Cant.</th>
                        <th class="text-right py-3 px-3 font-bold text-gray-700">Desc.</th>
                        <th class="text-right py-3 px-3 font-bold text-gray-700">Subtotal</th>
                        <th class="text-right py-3 px-3 font-bold text-gray-700">IVA ({{ $invoice->iva_percentage ?? 19 }}%)</th>
                        <th class="text-right py-3 px-4 font-bold text-gray-700">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4">
                            <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                            @if(isset($item['color']) && $item['color'])
                                <p class="text-xs text-gray-500">Color: {{ $item['color'] }}</p>
                            @endif
                        </td>
                        <td class="text-center py-3 px-3 text-gray-600">{{ $item['size'] ?? '-' }}</td>
                        <td class="text-right py-3 px-3 text-gray-600">${{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td class="text-center py-3 px-3 text-gray-600">{{ $item['quantity'] }}</td>
                        <td class="text-right py-3 px-3 text-gray-600">{{ $item['discount'] ?? 0 }}%</td>
                        <td class="text-right py-3 px-3 text-gray-600">${{ number_format($item['base_gravable'], 0, ',', '.') }}</td>
                        <td class="text-right py-3 px-3 text-gray-600">${{ number_format($item['iva'], 0, ',', '.') }}</td>
                        <td class="text-right py-3 px-4 font-semibold text-gray-900">${{ number_format($item['total'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Resumen financiero -->
    <div class="flex justify-end mb-8">
        <div class="w-full md:w-80">
            <div class="bg-gray-50 rounded-lg p-5 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal (antes de IVA):</span>
                    <span class="font-semibold text-gray-900">${{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($invoice->total_discounts > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Descuentos aplicados:</span>
                    <span class="font-semibold text-red-600">-${{ number_format($invoice->total_discounts, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">IVA ({{ $invoice->iva_percentage ?? 19 }}%):</span>
                    <span class="font-semibold text-gray-900">${{ number_format($invoice->total_iva, 0, ',', '.') }}</span>
                </div>
                <div class="border-t-2 border-gray-200 pt-3 flex justify-between">
                    <span class="font-bold text-gray-900">TOTAL:</span>
                    <span class="font-bold text-2xl text-indigo-600">${{ number_format($invoice->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del pedido -->
    @if($invoice->order)
    <div class="border-t-2 border-gray-200 pt-6">
        <h3 class="text-sm font-bold text-gray-500 uppercase mb-3">Información del pedido:</h3>
        <div class="grid md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-600">Pedido: 
                    <a href="{{ route('admin.orders.show', $invoice->order) }}" 
                       class="font-semibold text-indigo-600 hover:underline">
                        #{{ $invoice->order->id }}
                    </a>
                </p>
                <p class="text-gray-600 mt-1">Estado: 
                    <span class="font-semibold">{{ ucfirst($invoice->order->status) }}</span>
                </p>
                <p class="text-gray-600 mt-1">Método de pago: 
                    <span class="font-semibold">{{ $invoice->order->payment_method ?? 'No especificado' }}</span>
                </p>
            </div>
            <div>
                @if($invoice->order->tracking_number)
                <p class="text-gray-600">Tracking: 
                    <code class="font-semibold bg-gray-100 px-2 py-0.5 rounded">{{ $invoice->order->tracking_number }}</code>
                </p>
                @endif
                @if($invoice->order->shipping_address)
                <p class="text-gray-600 mt-1">Dirección de envío:</p>
                <p class="text-gray-800 font-medium">{{ $invoice->order->shipping_address }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal anular factura -->
<div id="cancel-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" 
     style="background:rgba(0,0,0,.55);backdrop-filter:blur(6px)">
    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full">
        <div class="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-ban text-red-500 text-2xl"></i>
        </div>
        <h3 class="text-lg font-black text-gray-900 mb-2 text-center">Anular Factura</h3>
        <p class="text-sm text-gray-500 mb-4 text-center">Esta acción no se puede deshacer. La factura quedará marcada como anulada.</p>
        
        <form method="POST" action="{{ route('admin.invoices.cancel', $invoice) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Motivo de anulación *</label>
                <textarea name="reason" rows="3" required
                          class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-400"
                          placeholder="Ej: Error en facturación, devolución de pedido, etc."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('cancel-modal').classList.add('hidden')"
                        class="flex-1 py-3 rounded-2xl border-2 border-gray-100 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-3 rounded-2xl text-white text-sm font-bold transition hover:opacity-90"
                        style="background:linear-gradient(90deg,#ef4444,#dc2626);box-shadow:0 4px 15px rgba(239,68,68,.3)">
                    Anular factura
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Form reenviar (oculto) -->
<form id="resend-form" method="POST" action="{{ route('admin.invoices.resend', $invoice) }}" class="hidden">
    @csrf
</form>

@endsection

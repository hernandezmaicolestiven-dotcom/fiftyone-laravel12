<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $invoice->invoice_number }} - FiftyOne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .invoice-container { box-shadow: none !important; margin: 0 !important; }
        }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Botones de acción -->
        <div class="no-print mb-6 flex gap-3 justify-end">
            <a href="{{ route('customer.orders') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700 font-medium transition">
                <i class="fa-solid fa-arrow-left mr-2"></i>Volver a pedidos
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                <i class="fa-solid fa-print mr-2"></i>Imprimir factura
            </button>
        </div>

        <!-- Factura -->
        <div class="invoice-container bg-white rounded-xl shadow-lg p-8 md:p-12">
            <!-- Encabezado -->
            <div class="flex justify-between items-start mb-8 pb-6 border-b-2 border-gray-200">
                <div>
                    <h1 class="text-3xl font-black text-gray-900">FIFTYONE</h1>
                    <p class="text-sm text-gray-500 mt-1">Ropa Oversize Colombia</p>
                </div>
                <div class="text-right">
                    <div class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg mb-2">
                        <p class="text-xs font-semibold">FACTURA DE VENTA</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $invoice->invoice_number }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $invoice->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-xs text-gray-400 mt-1">Colombia</p>
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
                                <th class="text-right py-3 px-3 font-bold text-gray-700">IVA (19%)</th>
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
                                <td class="text-right py-3 px-3 text-gray-600">{{ $item['discount'] }}%</td>
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
                            <span class="text-gray-600">IVA (19%):</span>
                            <span class="font-semibold text-gray-900">${{ number_format($invoice->total_iva, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t-2 border-gray-200 pt-3 flex justify-between">
                            <span class="font-bold text-gray-900">TOTAL A PAGAR:</span>
                            <span class="font-bold text-2xl text-blue-600">${{ number_format($invoice->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie de factura -->
            <div class="border-t-2 border-gray-200 pt-6 space-y-4">
                <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded">
                    <p class="text-sm font-semibold text-blue-900">¡Gracias por tu compra!</p>
                    <p class="text-xs text-blue-700 mt-1">Apreciamos tu confianza en FiftyOne. Esperamos verte pronto.</p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-4 text-xs text-gray-600">
                    <div>
                        <p class="font-bold text-gray-700 mb-1">Política de devoluciones:</p>
                        <p>Tienes 30 días para devolver productos sin usar con etiquetas originales.</p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700 mb-1">Contacto:</p>
                        <p>Email: contacto@fiftyone.com</p>
                        <p>WhatsApp: +57 300 123 4567</p>
                        <p>Web: www.fiftyone.com</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nota legal -->
        <p class="text-center text-xs text-gray-400 mt-6 no-print">
            Este documento es una factura electrónica válida en Colombia
        </p>
    </div>
</body>
</html>

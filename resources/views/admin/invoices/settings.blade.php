@extends('admin.layouts.app')
@section('title', 'Configuración de Facturación')
@section('page-title', 'Configuración de Facturación')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.invoices.index') }}" 
       class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
        <i class="fa-solid fa-arrow-left"></i> Volver a facturas
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Formulario -->
    <div class="lg:col-span-2">
        <form method="POST" action="{{ route('admin.invoices.update-settings') }}" class="bg-white rounded-xl shadow-sm">
            @csrf
            @method('PUT')

            <!-- Configuración de IVA -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i class="fa-solid fa-percent text-emerald-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Configuración de IVA</h3>
                        <p class="text-sm text-gray-500">Porcentaje de IVA aplicado a las facturas</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Porcentaje de IVA (%) *
                    </label>
                    <div class="relative">
                        <input type="number" name="iva_percentage" step="0.01" min="0" max="100" required
                               value="{{ old('iva_percentage', $settings['iva_percentage']) }}"
                               class="w-full px-4 py-2.5 pr-10 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        En Colombia el IVA estándar es 19%. Puedes ajustarlo según tu necesidad.
                    </p>
                </div>
            </div>

            <!-- Numeración de facturas -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="fa-solid fa-hashtag text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Numeración de Facturas</h3>
                        <p class="text-sm text-gray-500">Formato de numeración consecutiva</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Prefijo *
                        </label>
                        <input type="text" name="invoice_prefix" maxlength="10" required
                               value="{{ old('invoice_prefix', $settings['invoice_prefix']) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                               placeholder="FV">
                        <p class="text-xs text-gray-500 mt-1">Ej: FV, FACT, INV</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Próximo número
                        </label>
                        <input type="number" value="{{ $settings['next_invoice_number'] }}" disabled
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-500">
                        <p class="text-xs text-gray-500 mt-1">Se incrementa automáticamente</p>
                    </div>
                </div>

                <div class="mt-4 bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded">
                    <p class="text-sm font-semibold text-indigo-900">Vista previa:</p>
                    <code class="text-lg font-bold text-indigo-700 mt-1 block">
                        {{ $settings['invoice_prefix'] }}-{{ date('Y') }}-{{ str_pad($settings['next_invoice_number'], 4, '0', STR_PAD_LEFT) }}
                    </code>
                </div>
            </div>

            <!-- Datos de la empresa -->
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i class="fa-solid fa-building text-amber-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Datos de la Empresa</h3>
                        <p class="text-sm text-gray-500">Información que aparecerá en las facturas</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre de la empresa *
                        </label>
                        <input type="text" name="company_name" maxlength="255" required
                               value="{{ old('company_name', $settings['company_name']) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                               placeholder="FiftyOne S.A.S.">
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                NIT
                            </label>
                            <input type="text" name="company_nit" maxlength="50"
                                   value="{{ old('company_nit', $settings['company_nit']) }}"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                   placeholder="900.123.456-7">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text" name="company_phone" maxlength="50"
                                   value="{{ old('company_phone', $settings['company_phone']) }}"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                   placeholder="+57 300 123 4567">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Dirección
                        </label>
                        <input type="text" name="company_address" maxlength="500"
                               value="{{ old('company_address', $settings['company_address']) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                               placeholder="Calle 123 #45-67, Bogotá, Colombia">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email de contacto
                        </label>
                        <input type="email" name="company_email" maxlength="255"
                               value="{{ old('company_email', $settings['company_email']) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                               placeholder="facturacion@fiftyone.com">
                    </div>
                </div>
            </div>

            <!-- Botón guardar -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition">
                    <i class="fa-solid fa-save mr-2"></i>
                    Guardar configuración
                </button>
            </div>
        </form>
    </div>

    <!-- Panel informativo -->
    <div class="space-y-6">
        <!-- Info legal -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mb-4">
                <i class="fa-solid fa-scale-balanced text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Cumplimiento Legal</h3>
            <p class="text-sm text-gray-600 mb-4">
                La numeración consecutiva de facturas es un requisito legal en Colombia según la DIAN.
            </p>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                    <span>Numeración consecutiva automática</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                    <span>Sin saltos en la numeración</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                    <span>Formato año incluido</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                    <span>Registro de anulaciones</span>
                </li>
            </ul>
        </div>

        <!-- Ayuda IVA -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center mb-4">
                <i class="fa-solid fa-lightbulb text-amber-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Sobre el IVA</h3>
            <p class="text-sm text-gray-600 mb-3">
                El IVA (Impuesto al Valor Agregado) en Colombia tiene diferentes tarifas:
            </p>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start gap-2">
                    <span class="font-semibold text-gray-700">19%</span>
                    <span>Tarifa general</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="font-semibold text-gray-700">5%</span>
                    <span>Tarifa reducida</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="font-semibold text-gray-700">0%</span>
                    <span>Productos exentos</span>
                </li>
            </ul>
        </div>

        <!-- Estadísticas -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center gap-3 mb-4">
                <i class="fa-solid fa-chart-line text-2xl"></i>
                <h3 class="text-lg font-bold">Estadísticas</h3>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-sm opacity-90">Facturas emitidas</p>
                    <p class="text-2xl font-black">{{ \App\Models\Invoice::count() }}</p>
                </div>
                <div>
                    <p class="text-sm opacity-90">Próxima factura</p>
                    <code class="text-lg font-bold">
                        {{ $settings['invoice_prefix'] }}-{{ date('Y') }}-{{ str_pad($settings['next_invoice_number'], 4, '0', STR_PAD_LEFT) }}
                    </code>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

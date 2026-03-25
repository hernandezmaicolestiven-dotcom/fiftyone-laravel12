@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php use Illuminate\Support\Facades\Storage; @endphp

{{-- Bienvenida --}}
<div class="rounded-2xl p-6 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
     style="background: linear-gradient(135deg, #0d0d1a 0%, #0a0e2e 55%, #1a0a2e 100%)">
    <div>
        <p class="text-gray-400 text-sm mb-1">Bienvenido de nuevo,</p>
        <h2 class="text-2xl font-bold text-white">{{ auth()->user()->name }} 👋</h2>
        <p class="text-gray-400 text-sm mt-1">{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-medium transition border border-white/10">
            <i class="fa-solid fa-plus"></i> Nuevo producto
        </a>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition hover:opacity-90"
           style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
            <i class="fa-solid fa-tags"></i> Nueva categoría
        </a>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 xl:grid-cols-5 gap-4 mb-6">

    <a href="{{ route('admin.products.index') }}"
       class="group bg-white rounded-2xl p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 overflow-hidden relative">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-10"
             style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)"></div>
        <div class="w-11 h-11 rounded-xl mb-4 flex items-center justify-center"
             style="background: linear-gradient(135deg, rgba(59,89,255,0.12), rgba(123,47,190,0.12))">
            <i class="fa-solid fa-box" style="background: linear-gradient(135deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <p class="text-3xl font-black text-gray-800 mb-1">{{ $stats['products'] }}</p>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Productos</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stats['total_stock'] }} uds. en stock</p>
    </a>

    <a href="{{ route('admin.categories.index') }}"
       class="group bg-white rounded-2xl p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 overflow-hidden relative">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-10 bg-emerald-400"></div>
        <div class="w-11 h-11 rounded-xl bg-emerald-100 mb-4 flex items-center justify-center">
            <i class="fa-solid fa-tags text-emerald-600"></i>
        </div>
        <p class="text-3xl font-black text-gray-800 mb-1">{{ $stats['categories'] }}</p>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Categorías</p>
        <p class="text-xs text-gray-400 mt-1">Activas en tienda</p>
    </a>

    <a href="{{ route('admin.users.index') }}"
       class="group bg-white rounded-2xl p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 overflow-hidden relative">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-10 bg-sky-400"></div>
        <div class="w-11 h-11 rounded-xl bg-sky-100 mb-4 flex items-center justify-center">
            <i class="fa-solid fa-users text-sky-600"></i>
        </div>
        <p class="text-3xl font-black text-gray-800 mb-1">{{ $stats['users'] }}</p>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Usuarios</p>
        <p class="text-xs text-gray-400 mt-1">Administradores</p>
    </a>

    <a href="{{ route('admin.orders.index') }}"
       class="group bg-white rounded-2xl p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 overflow-hidden relative">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-10 bg-violet-400"></div>
        <div class="w-11 h-11 rounded-xl bg-violet-100 mb-4 flex items-center justify-center">
            <i class="fa-solid fa-bag-shopping text-violet-600"></i>
        </div>
        <p class="text-3xl font-black text-gray-800 mb-1">{{ $stats['orders'] }}</p>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Pedidos</p>
        <p class="text-xs mt-1 {{ $stats['orders_pending'] > 0 ? 'text-amber-500 font-medium' : 'text-gray-400' }}">
            {{ $stats['orders_pending'] }} pendientes
        </p>
    </a>

    <a href="{{ route('admin.products.index', ['stock' => 'low']) }}"
       class="group rounded-2xl p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 overflow-hidden relative
              {{ $stats['low_stock'] > 0 ? '' : 'bg-white' }}"
       style="{{ $stats['low_stock'] > 0 ? 'background: linear-gradient(135deg, #fff7ed, #fff)' : '' }}">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-10 bg-amber-400"></div>
        <div class="w-11 h-11 rounded-xl bg-amber-100 mb-4 flex items-center justify-center">
            <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
        </div>
        <p class="text-3xl font-black mb-1 {{ $stats['low_stock'] > 0 ? 'text-amber-600' : 'text-gray-800' }}">
            {{ $stats['low_stock'] }}
        </p>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Stock bajo</p>
        <p class="text-xs mt-1 {{ $stats['low_stock'] > 0 ? 'text-amber-500 font-medium' : 'text-gray-400' }}">
            {{ $stats['out_stock'] }} sin stock
        </p>
    </a>

</div>

{{-- Fila principal --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

    {{-- Gráfica --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-800">Productos agregados</h3>
                <p class="text-xs text-gray-400 mt-0.5">Últimos 7 meses</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <span class="w-3 h-3 rounded-full inline-block" style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)"></span>
                Productos
            </div>
        </div>
        <canvas id="salesChart" height="110"></canvas>
    </div>

    {{-- Top categorías --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-gray-800">Top categorías</h3>
                <p class="text-xs text-gray-400 mt-0.5">Por cantidad de productos</p>
            </div>
            <a href="{{ route('admin.categories.index') }}"
               class="text-xs font-semibold hover:underline"
               style="background: linear-gradient(90deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Ver todas
            </a>
        </div>
        <div class="space-y-4">
            @forelse($topCategories as $i => $cat)
            @php
                $max = $topCategories->first()->products_count ?: 1;
                $pct = round(($cat->products_count / $max) * 100);
                $colors = ['#3B59FF','#7B2FBE','#06b6d4','#10b981','#f59e0b'];
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">{{ $cat->name }}</span>
                    <span class="text-xs font-bold text-gray-500">{{ $cat->products_count }}</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700"
                         style="width: {{ $pct }}%; background: {{ $colors[$i] ?? '#3B59FF' }}"></div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Sin categorías aún.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- Fila inferior --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

    {{-- Últimos productos --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-base font-bold text-gray-800">Últimos productos</h3>
                <p class="text-xs text-gray-400 mt-0.5">Recién agregados al catálogo</p>
            </div>
            <a href="{{ route('admin.products.index') }}"
               class="text-xs font-semibold hover:underline"
               style="background: linear-gradient(90deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Ver todos
            </a>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($recentProducts as $product)
            <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50 transition">
                <div class="w-11 h-11 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden">
                    @if($product->image)
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image) }}"
                             class="w-full h-full object-cover" alt="">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fa-solid fa-image text-gray-300"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->category?->name ?? 'Sin categoría' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold" style="background: linear-gradient(90deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        ${{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    <p class="text-xs {{ $product->stock < 5 ? 'text-amber-500 font-semibold' : 'text-gray-400' }}">
                        {{ $product->stock }} uds.
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Stock bajo --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-base font-bold text-gray-800">Alertas de stock</h3>
                <p class="text-xs text-gray-400 mt-0.5">Productos con menos de 5 unidades</p>
            </div>
            @if($stats['low_stock'] > 0)
            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-600">
                {{ $stats['low_stock'] }} alertas
            </span>
            @endif
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($lowStockProducts as $product)
            <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50 transition">
                <div class="w-11 h-11 rounded-xl flex-shrink-0 overflow-hidden bg-gray-100">
                    @if($product->image)
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image) }}"
                             class="w-full h-full object-cover" alt="">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fa-solid fa-image text-gray-300"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->name }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $product->stock === 0 ? 'bg-red-500' : 'bg-amber-400' }}"
                                 style="width: {{ min($product->stock * 20, 100) }}%"></div>
                        </div>
                        <span class="text-xs font-bold {{ $product->stock === 0 ? 'text-red-500' : 'text-amber-500' }}">
                            {{ $product->stock }} uds.
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="flex-shrink-0 text-xs px-3 py-1.5 rounded-lg border border-gray-200 text-gray-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition">
                    Editar
                </a>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 mx-auto mb-3 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                </div>
                <p class="text-sm font-semibold text-gray-700">Todo en orden</p>
                <p class="text-xs text-gray-400 mt-1">No hay productos con stock bajo.</p>
            </div>
            @endforelse
        </div>
        @if($stats['low_stock'] > 4)
        <div class="px-6 py-3 border-t border-gray-100">
            <a href="{{ route('admin.products.index', ['stock' => 'low']) }}"
               class="text-xs font-semibold hover:underline"
               style="background: linear-gradient(90deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Ver todos los productos con stock bajo →
            </a>
        </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const gradLine = ctx.createLinearGradient(0, 0, ctx.canvas.width, 0);
gradLine.addColorStop(0, '#3B59FF');
gradLine.addColorStop(1, '#7B2FBE');
const gradFill = ctx.createLinearGradient(0, 0, 0, 280);
gradFill.addColorStop(0, 'rgba(123,47,190,0.18)');
gradFill.addColorStop(1, 'rgba(59,89,255,0.01)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! $chartLabels !!},
        datasets: [{
            label: 'Productos',
            data: {!! $chartData !!},
            borderColor: gradLine,
            backgroundColor: gradFill,
            borderWidth: 2.5,
            fill: true,
            tension: 0.45,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#7B2FBE',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0a0e2e',
                titleColor: '#a5b4fc',
                bodyColor: '#fff',
                padding: 10,
                cornerRadius: 10,
                displayColors: false,
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, color: '#9ca3af', font: { size: 11 } },
                grid: { color: '#f3f4f6' }
            },
            x: {
                ticks: { color: '#9ca3af', font: { size: 11 } },
                grid: { display: false }
            }
        }
    }
});
</script>
@endpush

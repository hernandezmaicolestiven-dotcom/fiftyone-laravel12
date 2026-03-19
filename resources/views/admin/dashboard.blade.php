@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(59,89,255,0.15), rgba(123,47,190,0.15))">
            <i class="fa-solid fa-box text-xl" style="background: linear-gradient(135deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Productos</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['products'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-tags text-emerald-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Categorías</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['categories'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-users text-sky-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Usuarios</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['users'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Stock bajo</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['low_stock'] }}</p>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Chart --}}
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Actividad de ventas (últimos 7 meses)</h2>
        <canvas id="salesChart" height="100"></canvas>
    </div>

    {{-- Recent Products --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-700">Últimos productos</h2>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold hover:underline" style="background: linear-gradient(90deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Ver todos</a>
        </div>
        <div class="space-y-3">
            @foreach($recentProducts as $product)
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if($product->image)
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image) }}" class="w-full h-full object-cover" alt="">
                    @else
                        <i class="fa-solid fa-image text-gray-400 text-sm"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->category?->name ?? 'Sin categoría' }}</p>
                </div>
                <span class="text-sm font-semibold" style="background: linear-gradient(90deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">COP ${{ number_format($product->price, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, ctx.canvas.width, 0);
gradient.addColorStop(0, '#3B59FF');
gradient.addColorStop(1, '#7B2FBE');
const gradientFill = ctx.createLinearGradient(0, 0, 0, 300);
gradientFill.addColorStop(0, 'rgba(123,47,190,0.2)');
gradientFill.addColorStop(1, 'rgba(59,89,255,0.02)');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! $chartLabels !!},
        datasets: [{
            label: 'Ventas',
            data: {!! $chartData !!},
            borderColor: gradient,
            backgroundColor: gradientFill,
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#7B2FBE',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush

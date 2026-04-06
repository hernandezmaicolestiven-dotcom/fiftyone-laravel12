@extends('admin.layouts.app')
@section('title', 'Analytics')
@section('page-title', 'Analytics')

@section('content')

{{-- Selector de año --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-black text-gray-800">Estadísticas históricas</h2>
        <p class="text-sm text-gray-400 mt-0.5">Análisis de rendimiento y predicción IA</p>
    </div>
    <form method="GET" class="flex items-center gap-2">
        <label class="text-xs font-semibold text-gray-500">Año:</label>
        <select name="year" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
            @foreach($availableYears as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>
</div>

{{-- KPIs --}}
<div class="grid grid-cols-2 xl:grid-cols-6 gap-4 mb-6">
    @foreach([
        ['Pedidos',       number_format($kpis['total_orders'],0,',','.'),   'fa-bag-shopping',  '#3B59FF','#7B2FBE','rgba(59,89,255,.12)'],
        ['Ingresos',      '$'.number_format($kpis['total_revenue'],0,',','.'), 'fa-dollar-sign','#059669','#10b981','rgba(5,150,105,.12)'],
        ['Ticket prom.',  '$'.number_format($kpis['avg_order'],0,',','.'),  'fa-receipt',       '#0891b2','#06b6d4','rgba(8,145,178,.12)'],
        ['Entregados',    $kpis['delivered'],                               'fa-circle-check',  '#059669','#10b981','rgba(5,150,105,.12)'],
        ['Cancelados',    $kpis['cancelled'],                               'fa-circle-xmark',  '#dc2626','#ef4444','rgba(220,38,38,.1)'],
        ['Conversión',    $kpis['conversion'].'%',                          'fa-chart-pie',     '#7c3aed','#a855f7','rgba(124,58,237,.12)'],
    ] as [$label,$value,$icon,$c1,$c2,$bg])
    <div class="bg-white rounded-2xl shadow-sm p-5 relative overflow-hidden">
        <div class="absolute -right-3 -top-3 w-14 h-14 rounded-full opacity-10" style="background:linear-gradient(135deg,{{$c1}},{{$c2}})"></div>
        <div class="w-9 h-9 rounded-xl mb-3 flex items-center justify-center" style="background:{{$bg}}">
            <i class="fa-solid {{$icon}} text-xs" style="background:linear-gradient(135deg,{{$c1}},{{$c2}});-webkit-background-clip:text;-webkit-text-fill-color:transparent"></i>
        </div>
        <p class="text-xl font-black text-gray-800">{{ $value }}</p>
        <p class="text-xs text-gray-400 mt-0.5 uppercase tracking-wide font-semibold">{{ $label }}</p>
    </div>
    @endforeach
</div>

{{-- Comparativa año anterior --}}
@if($growthRate !== null)
<div class="mb-6 p-4 rounded-2xl flex items-center gap-4 {{ $growthRate >= 0 ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200' }}">
    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $growthRate >= 0 ? 'bg-emerald-100' : 'bg-red-100' }}">
        <i class="fa-solid {{ $growthRate >= 0 ? 'fa-arrow-trend-up text-emerald-600' : 'fa-arrow-trend-down text-red-500' }}"></i>
    </div>
    <div>
        <p class="text-sm font-bold {{ $growthRate >= 0 ? 'text-emerald-800' : 'text-red-700' }}">
            {{ $growthRate >= 0 ? '+' : '' }}{{ $growthRate }}% vs {{ $year - 1 }}
        </p>
        <p class="text-xs {{ $growthRate >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
            Año anterior: ${{ number_format($prevYearRevenue, 0, ',', '.') }}
        </p>
    </div>
</div>
@endif

{{-- Gráficas --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

    {{-- Línea ingresos + predicción --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Ingresos mensuales {{ $year }}</h3>
                <p class="text-xs text-gray-400">Con predicción IA para los próximos 3 meses</p>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full font-semibold text-white"
                  style="background:linear-gradient(90deg,#7c3aed,#a855f7)">
                <i class="fa-solid fa-brain mr-1"></i> IA activa
            </span>
        </div>
        <canvas id="revenueChart" height="130"></canvas>
    </div>

    {{-- Barras pedidos por mes --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Pedidos por mes</h3>
        <p class="text-xs text-gray-400 mb-5">{{ $year }}</p>
        <canvas id="ordersBarChart" height="220"></canvas>
    </div>
</div>

{{-- Top productos + dona estados --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Top 5 productos — {{ $year }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                    <th class="px-6 py-3 text-left">#</th>
                    <th class="px-6 py-3 text-left">Producto</th>
                    <th class="px-6 py-3 text-center">Uds.</th>
                    <th class="px-6 py-3 text-right">Ingresos</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($topProducts as $i => $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-gray-400 font-mono text-xs">{{ $i+1 }}</td>
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $p['name'] }}</td>
                        <td class="px-6 py-3 text-center font-bold text-gray-700">{{ number_format($p['qty'],0,',','.') }}</td>
                        <td class="px-6 py-3 text-right font-bold" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
                            ${{ number_format($p['revenue'],0,',','.') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Sin datos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Predicción IA --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                 style="background:linear-gradient(135deg,rgba(124,58,237,.15),rgba(168,85,247,.15))">
                <i class="fa-solid fa-brain" style="background:linear-gradient(135deg,#7c3aed,#a855f7);-webkit-background-clip:text;-webkit-text-fill-color:transparent"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-800">Predicción de ingresos</h3>
                <p class="text-xs text-gray-400">Regresión lineal sobre datos históricos</p>
            </div>
        </div>
        @php
            $predMonths = ['Mes +1', 'Mes +2', 'Mes +3'];
        @endphp
        <div class="space-y-4">
            @foreach($prediction as $i => $val)
            <div class="p-4 rounded-2xl" style="background:linear-gradient(135deg,rgba(124,58,237,.06),rgba(168,85,247,.06));border:1px solid rgba(124,58,237,.15)">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-purple-700">{{ $predMonths[$i] }}</span>
                    <span class="text-xs text-purple-400">Predicción IA</span>
                </div>
                <p class="text-2xl font-black" style="background:linear-gradient(90deg,#7c3aed,#a855f7);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
                    ${{ number_format($val, 0, ',', '.') }}
                </p>
                <div class="mt-2 h-1.5 bg-purple-100 rounded-full overflow-hidden">
                    @php $maxPred = max($prediction) ?: 1; @endphp
                    <div class="h-full rounded-full" style="width:{{ round($val/$maxPred*100) }}%;background:linear-gradient(90deg,#7c3aed,#a855f7)"></div>
                </div>
            </div>
            @endforeach
        </div>
        <p class="text-xs text-gray-400 mt-4 text-center">
            <i class="fa-solid fa-circle-info mr-1"></i>
            Basado en tendencia de {{ count($months->where('revenue', '>', 0)) }} meses con datos
        </p>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function(){
    const months  = {!! $months->pluck('month') !!};
    const revenue = {!! $months->pluck('revenue') !!};
    const orders  = {!! $months->pluck('orders') !!};
    const pred    = {!! json_encode($prediction) !!};

    // Línea ingresos + predicción
    (function(){
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const gBlue = ctx.createLinearGradient(0,0,0,250);
        gBlue.addColorStop(0,'rgba(59,89,255,.15)'); gBlue.addColorStop(1,'rgba(59,89,255,0)');
        const gPurple = ctx.createLinearGradient(0,0,0,250);
        gPurple.addColorStop(0,'rgba(124,58,237,.15)'); gPurple.addColorStop(1,'rgba(124,58,237,0)');

        const predLabels = ['Mes +1','Mes +2','Mes +3'];
        const allLabels  = [...months, ...predLabels];
        const realData   = [...revenue, null, null, null];
        const predData   = [...Array(12).fill(null), ...pred];

        new Chart(ctx, {
            type: 'line',
            data: { labels: allLabels, datasets: [
                { label:'Ingresos reales', data: realData, borderColor:'#3B59FF', backgroundColor: gBlue, borderWidth:2.5, fill:true, tension:0.4, pointRadius:4, pointHoverRadius:7 },
                { label:'Predicción IA',   data: predData, borderColor:'#a855f7', backgroundColor: gPurple, borderWidth:2, borderDash:[6,4], fill:true, tension:0.4, pointRadius:5, pointStyle:'triangle', pointHoverRadius:8 },
            ]},
            options: { responsive:true, interaction:{mode:'index',intersect:false},
                plugins:{ legend:{display:true,labels:{font:{size:11},usePointStyle:true}},
                    tooltip:{backgroundColor:'#0a0e2e',titleColor:'#a5b4fc',bodyColor:'#e2e8f0',padding:10,cornerRadius:10,
                        callbacks:{label:c=>'  $'+c.parsed.y?.toLocaleString('es-CO')}}},
                scales:{ y:{beginAtZero:true,ticks:{color:'#9ca3af',font:{size:10}},grid:{color:'#f3f4f6'},border:{dash:[4,4]}},
                         x:{ticks:{color:'#9ca3af',font:{size:10}},grid:{display:false}} }}
        });
    })();

    // Barras pedidos
    (function(){
        const ctx = document.getElementById('ordersBarChart').getContext('2d');
        const grad = ctx.createLinearGradient(0,0,ctx.canvas.width,0);
        grad.addColorStop(0,'#3B59FF'); grad.addColorStop(1,'#7B2FBE');
        new Chart(ctx, {
            type:'bar',
            data:{ labels:months, datasets:[{label:'Pedidos',data:orders,backgroundColor:grad,borderRadius:6,borderSkipped:false}]},
            options:{ responsive:true, plugins:{legend:{display:false},tooltip:{backgroundColor:'#0a0e2e',titleColor:'#a5b4fc',bodyColor:'#fff',padding:10,cornerRadius:10}},
                scales:{ y:{beginAtZero:true,ticks:{stepSize:1,color:'#9ca3af',font:{size:10}},grid:{color:'#f3f4f6'},border:{dash:[4,4]}},
                         x:{ticks:{color:'#9ca3af',font:{size:10}},grid:{display:false}} }}
        });
    })();
})();
</script>
@endpush

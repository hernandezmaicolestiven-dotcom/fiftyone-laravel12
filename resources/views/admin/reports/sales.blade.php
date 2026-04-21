@extends('admin.layouts.app')
@section('title', 'Reporte de Ventas')
@section('page-title', 'Reportes')

@section('content')
@php $isPrint = request('print'); @endphp

{{-- Sub-nav reportes --}}
@include('admin.reports._nav', ['active' => 'sales'])

{{-- Filtros --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-5 @if($isPrint) hidden @endif">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5"><i class="fa-regular fa-calendar-plus mr-1 text-indigo-400"></i>Desde</label>
            <input type="date" name="date_from" value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}"
                   class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50 focus:bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5"><i class="fa-regular fa-calendar-minus mr-1 text-indigo-400"></i>Hasta</label>
            <input type="date" name="date_to" value="{{ request('date_to', $dateTo->format('Y-m-d')) }}"
                   class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50 focus:bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5"><i class="fa-solid fa-circle-half-stroke mr-1 text-indigo-400"></i>Estado</label>
            <select name="status" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
                <option value="">Todos</option>
                @foreach(['pending'=>'Pendiente','confirmed'=>'Confirmado','shipped'=>'Enviado','delivered'=>'Entregado','cancelled'=>'Cancelado'] as $val=>$lbl)
                    <option value="{{ $val }}" {{ $status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-5 py-2 rounded-xl text-white text-sm font-semibold shadow-sm hover:opacity-90 transition"
                style="background: linear-gradient(90deg,#3B59FF,#7B2FBE)">
            <i class="fa-solid fa-filter mr-1.5 text-xs"></i>Aplicar
        </button>
        <a href="{{ route('admin.reports.sales') }}" class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition">
            <i class="fa-solid fa-xmark text-xs"></i> Limpiar
        </a>
        <div class="ml-auto flex gap-2">
            <button type="button" onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-print text-indigo-500"></i> Imprimir / PDF
            </button>
        </div>
    </form>
</div>

{{-- KPIs --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
    @foreach([
        ['Ingresos totales', '$'.number_format($totalRevenue,0,',','.'), 'fa-dollar-sign', '#3B59FF','#7B2FBE', 'rgba(59,89,255,0.12)'],
        ['Pedidos',          $totalOrders,                               'fa-bag-shopping','#7c3aed','#a855f7','rgba(124,58,237,0.12)'],
        ['Ticket promedio',  '$'.number_format($avgOrder,0,',','.'),     'fa-receipt',     '#0891b2','#06b6d4','rgba(8,145,178,0.12)'],
        ['Entregados',       $delivered,                                 'fa-circle-check','#059669','#10b981','rgba(5,150,105,0.12)'],
    ] as [$label,$value,$icon,$c1,$c2,$bg])
    <div class="bg-white rounded-2xl shadow-sm p-5 relative overflow-hidden">
        <div class="absolute -right-3 -top-3 w-16 h-16 rounded-full opacity-10" style="background:linear-gradient(135deg,{{$c1}},{{$c2}})"></div>
        <div class="w-10 h-10 rounded-xl mb-3 flex items-center justify-center" style="background:{{$bg}}">
            <i class="fa-solid {{$icon}} text-sm" style="background:linear-gradient(135deg,{{$c1}},{{$c2}});-webkit-background-clip:text;-webkit-text-fill-color:transparent"></i>
        </div>
        <p class="text-2xl font-black text-gray-800">{{ $value }}</p>
        <p class="text-xs text-gray-400 mt-0.5 uppercase tracking-wide font-semibold">{{ $label }}</p>
    </div>
    @endforeach
</div>

{{-- Gráficas --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

    {{-- Línea ventas diarias --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Ventas diarias</h3>
                <p class="text-xs text-gray-400">{{ $dateFrom->format('d/m/Y') }} — {{ $dateTo->format('d/m/Y') }}</p>
            </div>
            <div class="flex gap-3 text-xs text-gray-400">
                <span class="flex items-center gap-1.5"><span class="w-3 h-1.5 rounded-full inline-block" style="background:#3B59FF"></span>Pedidos</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-1.5 rounded-full inline-block" style="background:#10b981"></span>Ingresos</span>
            </div>
        </div>
        <canvas id="salesLineChart" height="120"></canvas>
    </div>

    {{-- Dona estados --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Pedidos por estado</h3>
        <p class="text-xs text-gray-400 mb-5">Distribución del período</p>
        <canvas id="statusDonut" height="180"></canvas>
        <div class="mt-4 space-y-1.5">
            @php $donutColors = ['#f59e0b','#3b82f6','#6366f1','#10b981','#ef4444']; $di=0; @endphp
            @foreach($statusCounts as $lbl => $cnt)
            <div class="flex items-center justify-between text-xs">
                <span class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $donutColors[$di++] }}"></span>
                    {{ $lbl }}
                </span>
                <span class="font-semibold text-gray-700">{{ $cnt }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Top clientes --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-gray-800">Top clientes</h3>
            <p class="text-xs text-gray-400">{{ $totalCustomers }} clientes · Por ingresos generados en el período</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                <th class="px-6 py-3 text-left">#</th>
                <th class="px-6 py-3 text-left">Cliente</th>
                <th class="px-6 py-3 text-left">Email</th>
                <th class="px-6 py-3 text-center">Pedidos</th>
                <th class="px-6 py-3 text-right">Total</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($topCustomers as $i => $c)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-gray-400 font-mono">{{ ($page - 1) * 10 + $i + 1 }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $c['name'] }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $c['email'] }}</td>
                    <td class="px-6 py-3 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold">{{ $c['count'] }}</span>
                    </td>
                    <td class="px-6 py-3 text-right font-bold text-gray-800">${{ number_format($c['total'],0,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">Sin datos en el período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- Paginación --}}
    @if($customersPages > 1)
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">Página {{ $page }} de {{ $customersPages }}</p>
        <div class="flex gap-2">
            @if($page > 1)
            <a href="{{ request()->fullUrlWithQuery(['customers_page' => $page - 1]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                ← Anterior
            </a>
            @endif
            @if($page < $customersPages)
            <a href="{{ request()->fullUrlWithQuery(['customers_page' => $page + 1]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                Siguiente →
            </a>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
// Línea diaria
(function(){
    const ctx = document.getElementById('salesLineChart').getContext('2d');
    const gBlue = ctx.createLinearGradient(0,0,0,250);
    gBlue.addColorStop(0,'rgba(59,89,255,0.15)'); gBlue.addColorStop(1,'rgba(59,89,255,0)');
    const gGreen = ctx.createLinearGradient(0,0,0,250);
    gGreen.addColorStop(0,'rgba(16,185,129,0.15)'); gGreen.addColorStop(1,'rgba(16,185,129,0)');
    new Chart(ctx,{
        type:'line',
        data:{
            labels:{!! $chartLabels !!},
            datasets:[
                {label:'Pedidos',data:{!! $chartOrders !!},borderColor:'#3B59FF',backgroundColor:gBlue,borderWidth:2,fill:true,tension:0.4,pointRadius:3,pointHoverRadius:6,yAxisID:'y'},
                {label:'Ingresos',data:{!! $chartRevenue !!},borderColor:'#10b981',backgroundColor:gGreen,borderWidth:2,fill:true,tension:0.4,pointRadius:3,pointHoverRadius:6,yAxisID:'y1'},
            ]
        },
        options:{
            responsive:true,interaction:{mode:'index',intersect:false},
            plugins:{legend:{display:false},tooltip:{backgroundColor:'#0a0e2e',titleColor:'#a5b4fc',bodyColor:'#e2e8f0',padding:10,cornerRadius:10,
                callbacks:{label:c=>c.dataset.label==='Ingresos'?'  $'+c.parsed.y.toLocaleString('es-CO'):'  '+c.parsed.y+' pedidos'}}},
            scales:{
                y:{beginAtZero:true,position:'left',ticks:{stepSize:1,color:'#9ca3af',font:{size:10}},grid:{color:'#f3f4f6'},border:{dash:[4,4]}},
                y1:{beginAtZero:true,position:'right',ticks:{color:'#9ca3af',font:{size:10}},grid:{drawOnChartArea:false}},
                x:{ticks:{color:'#9ca3af',font:{size:10},maxTicksLimit:10},grid:{display:false}}
            }
        }
    });
})();

// Dona estados
(function(){
    const ctx = document.getElementById('statusDonut').getContext('2d');
    new Chart(ctx,{
        type:'doughnut',
        data:{
            labels:{!! json_encode(array_keys($statusCounts)) !!},
            datasets:[{data:{!! json_encode(array_values($statusCounts)) !!},
                backgroundColor:['#f59e0b','#3b82f6','#6366f1','#10b981','#ef4444'],
                borderWidth:0,hoverOffset:6}]
        },
        options:{responsive:true,cutout:'72%',plugins:{legend:{display:false},tooltip:{backgroundColor:'#0a0e2e',titleColor:'#a5b4fc',bodyColor:'#fff',padding:10,cornerRadius:10}}}
    });
})();
</script>
@endpush

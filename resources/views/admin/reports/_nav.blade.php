<div class="flex flex-wrap gap-2 mb-5">
    @foreach([
        ['sales',        'fa-chart-line',   'Ventas',              'admin.reports.sales'],
        ['inventory',    'fa-boxes-stacked','Inventario',          'admin.reports.inventory'],
        ['top-products', 'fa-trophy',       'Productos vendidos',  'admin.reports.top-products'],
    ] as [$key, $icon, $label, $route])
    <a href="{{ route($route) }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm"
       @if($active === $key)
           style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);color:#fff"
       @else
           style="background:#fff;color:#6b7280;border:1px solid #e5e7eb"
           onmouseover="this.style.background='#f9fafb'"
           onmouseout="this.style.background='#fff'"
       @endif>
        <i class="fa-solid {{ $icon }} text-xs"></i> {{ $label }}
    </a>
    @endforeach
</div>

@push('styles')
<style>
@media print {
    aside, nav, form, .no-print { display: none !important; }
    body { background: white !important; }
    .bg-white { box-shadow: none !important; }
    canvas { max-width: 100% !important; }
}
</style>
@endpush

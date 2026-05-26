<?php $__env->startSection('title', 'Productos más vendidos'); ?>
<?php $__env->startSection('page-title', 'Reportes'); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('admin.reports._nav', ['active' => 'top-products'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div class="bg-white rounded-2xl shadow-sm p-5 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5"><i class="fa-regular fa-calendar-plus mr-1 text-indigo-400"></i>Desde</label>
            <input type="date" name="date_from" value="<?php echo e(request('date_from', $dateFrom->format('Y-m-d'))); ?>"
                   class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50 focus:bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5"><i class="fa-regular fa-calendar-minus mr-1 text-indigo-400"></i>Hasta</label>
            <input type="date" name="date_to" value="<?php echo e(request('date_to', $dateTo->format('Y-m-d'))); ?>"
                   class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50 focus:bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5"><i class="fa-solid fa-tags mr-1 text-indigo-400"></i>Categoría</label>
            <select name="category" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
                <option value="">Todas</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e($categoryId == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button type="submit" class="px-5 py-2 rounded-xl text-white text-sm font-semibold shadow-sm hover:opacity-90 transition"
                style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
            <i class="fa-solid fa-filter mr-1.5 text-xs"></i>Aplicar
        </button>
        <a href="<?php echo e(route('admin.reports.top-products')); ?>" class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition">
            <i class="fa-solid fa-xmark text-xs"></i> Limpiar
        </a>
        <div class="ml-auto">
            <button type="button" onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-print text-indigo-500"></i> Imprimir / PDF
            </button>
        </div>
    </form>
</div>


<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
    <?php $__currentLoopData = [
        ['Unidades vendidas', number_format($totalQty,0,',','.'),    'fa-boxes-stacked','#3B59FF','#7B2FBE','rgba(59,89,255,0.12)'],
        ['Ingresos',         '$'.number_format($totalRevenue,0,',','.'), 'fa-dollar-sign','#059669','#10b981','rgba(5,150,105,0.12)'],
        ['Productos únicos', $uniqueProds,                           'fa-box',          '#0891b2','#06b6d4','rgba(8,145,178,0.12)'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$value,$icon,$c1,$c2,$bg]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white rounded-2xl shadow-sm p-5 relative overflow-hidden">
        <div class="absolute -right-3 -top-3 w-16 h-16 rounded-full opacity-10" style="background:linear-gradient(135deg,<?php echo e($c1); ?>,<?php echo e($c2); ?>)"></div>
        <div class="w-10 h-10 rounded-xl mb-3 flex items-center justify-center" style="background:<?php echo e($bg); ?>">
            <i class="fa-solid <?php echo e($icon); ?> text-sm" style="background:linear-gradient(135deg,<?php echo e($c1); ?>,<?php echo e($c2); ?>);-webkit-background-clip:text;-webkit-text-fill-color:transparent"></i>
        </div>
        <p class="text-2xl font-black text-gray-800"><?php echo e($value); ?></p>
        <p class="text-xs text-gray-400 mt-0.5 uppercase tracking-wide font-semibold"><?php echo e($label); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-5">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Top 10 — Unidades vendidas</h3>
        <p class="text-xs text-gray-400 mb-5"><?php echo e($dateFrom->format('d/m/Y')); ?> — <?php echo e($dateTo->format('d/m/Y')); ?></p>
        <canvas id="qtyChart" height="220"></canvas>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Top 10 — Ingresos generados</h3>
        <p class="text-xs text-gray-400 mb-5"><?php echo e($dateFrom->format('d/m/Y')); ?> — <?php echo e($dateTo->format('d/m/Y')); ?></p>
        <canvas id="revenueChart" height="220"></canvas>
    </div>
</div>


<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-sm font-bold text-gray-800">Ranking completo</h3>
        <p class="text-xs text-gray-400">Top 10 productos del período</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                <th class="px-6 py-3 text-left">#</th>
                <th class="px-6 py-3 text-left">Producto</th>
                <th class="px-6 py-3 text-left">Categoría</th>
                <th class="px-6 py-3 text-center">Pedidos</th>
                <th class="px-6 py-3 text-center">Unidades</th>
                <th class="px-6 py-3 text-right">Ingresos</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3">
                        <?php if($i < 3): ?>
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black text-white inline-flex"
                                  style="background:<?php echo e(['linear-gradient(135deg,#f59e0b,#fbbf24)','linear-gradient(135deg,#9ca3af,#d1d5db)','linear-gradient(135deg,#b45309,#d97706)'][$i]); ?>">
                                <?php echo e($i+1); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-gray-400 font-mono text-xs"><?php echo e($i+1); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-3 font-semibold text-gray-800"><?php echo e($p['name']); ?></td>
                    <td class="px-6 py-3 text-gray-500"><?php echo e($p['category']); ?></td>
                    <td class="px-6 py-3 text-center">
                        <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold"><?php echo e($p['orders']); ?></span>
                    </td>
                    <td class="px-6 py-3 text-center font-bold text-gray-800"><?php echo e(number_format($p['qty'],0,',','.')); ?></td>
                    <td class="px-6 py-3 text-right font-bold" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
                        $<?php echo e(number_format($p['revenue'],0,',','.')); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">Sin datos en el período.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const labels = <?php echo $barLabels; ?>;
const gradH = (ctx, c1, c2) => { const g = ctx.createLinearGradient(0,0,ctx.canvas.width,0); g.addColorStop(0,c1); g.addColorStop(1,c2); return g; };

(function(){
    const ctx = document.getElementById('qtyChart').getContext('2d');
    new Chart(ctx,{type:'bar',
        data:{labels,datasets:[{label:'Unidades',data:<?php echo $barQty; ?>,backgroundColor:gradH(ctx,'#3B59FF','#7B2FBE'),borderRadius:6,borderSkipped:false}]},
        options:{indexAxis:'y',responsive:true,plugins:{legend:{display:false},tooltip:{backgroundColor:'#0a0e2e',titleColor:'#a5b4fc',bodyColor:'#fff',padding:10,cornerRadius:10}},
            scales:{x:{beginAtZero:true,ticks:{color:'#9ca3af',font:{size:10}},grid:{color:'#f3f4f6'},border:{dash:[4,4]}},
                    y:{ticks:{color:'#374151',font:{size:11}},grid:{display:false}}}}
    });
})();
(function(){
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx,{type:'bar',
        data:{labels,datasets:[{label:'Ingresos',data:<?php echo $barRevenue; ?>,backgroundColor:gradH(ctx,'#059669','#10b981'),borderRadius:6,borderSkipped:false}]},
        options:{indexAxis:'y',responsive:true,plugins:{legend:{display:false},tooltip:{backgroundColor:'#0a0e2e',titleColor:'#a5b4fc',bodyColor:'#fff',padding:10,cornerRadius:10,
            callbacks:{label:c=>'  $'+c.parsed.x.toLocaleString('es-CO')}}},
            scales:{x:{beginAtZero:true,ticks:{color:'#9ca3af',font:{size:10}},grid:{color:'#f3f4f6'},border:{dash:[4,4]}},
                    y:{ticks:{color:'#374151',font:{size:11}},grid:{display:false}}}}
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/admin/reports/top-products.blade.php ENDPATH**/ ?>
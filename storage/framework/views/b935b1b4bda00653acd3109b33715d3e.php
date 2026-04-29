<?php $__env->startSection('title', 'Reporte de Inventario'); ?>
<?php $__env->startSection('page-title', 'Reportes'); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('admin.reports._nav', ['active' => 'inventory'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div class="bg-white rounded-2xl shadow-sm p-5 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5"><i class="fa-solid fa-tags mr-1 text-indigo-400"></i>Categoría</label>
            <select name="category" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
                <option value="">Todas</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e($categoryId == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1.5"><i class="fa-solid fa-cubes-stacked mr-1 text-indigo-400"></i>Stock</label>
            <select name="stock" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
                <option value="">Todos</option>
                <option value="out"  <?php echo e($stockFilter==='out'  ? 'selected':''); ?>>Sin stock</option>
                <option value="low"  <?php echo e($stockFilter==='low'  ? 'selected':''); ?>>Stock bajo (&lt;5)</option>
                <option value="ok"   <?php echo e($stockFilter==='ok'   ? 'selected':''); ?>>Normal (≥5)</option>
            </select>
        </div>
        <button type="submit" class="px-5 py-2 rounded-xl text-white text-sm font-semibold shadow-sm hover:opacity-90 transition"
                style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
            <i class="fa-solid fa-filter mr-1.5 text-xs"></i>Aplicar
        </button>
        <a href="<?php echo e(route('admin.reports.inventory')); ?>" class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition">
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


<div class="grid grid-cols-2 xl:grid-cols-5 gap-4 mb-5">
    <?php $__currentLoopData = [
        ['Productos',     $totalProducts, 'fa-box',                '#3B59FF','#7B2FBE','rgba(59,89,255,0.12)'],
        ['Unidades',      $totalStock,    'fa-cubes-stacked',      '#0891b2','#06b6d4','rgba(8,145,178,0.12)'],
        ['Sin stock',     $outOfStock,    'fa-circle-xmark',       '#dc2626','#ef4444','rgba(220,38,38,0.1)'],
        ['Stock bajo',    $lowStock,      'fa-triangle-exclamation','#d97706','#f59e0b','rgba(217,119,6,0.1)'],
        ['Valor total',   '$'.number_format($totalValue,0,',','.'), 'fa-dollar-sign','#059669','#10b981','rgba(5,150,105,0.12)'],
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


<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">
    
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Stock por categoría</h3>
        <p class="text-xs text-gray-400 mb-5">Unidades disponibles</p>
        <canvas id="catBarChart" height="130"></canvas>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Estado del stock</h3>
        <p class="text-xs text-gray-400 mb-5">Distribución de productos</p>
        <canvas id="stockDonut" height="180"></canvas>
        <div class="mt-4 space-y-1.5">
            <?php $dc=['#ef4444','#f59e0b','#3b82f6','#10b981']; $di=0; ?>
            <?php $__currentLoopData = $stockStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lbl=>$cnt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center justify-between text-xs">
                <span class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full" style="background:<?php echo e($dc[$di++]); ?>"></span><?php echo e($lbl); ?>

                </span>
                <span class="font-semibold text-gray-700"><?php echo e($cnt); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-sm font-bold text-gray-800">Detalle de inventario</h3>
        <p class="text-xs text-gray-400"><?php echo e($totalProducts); ?> productos</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                <th class="px-6 py-3 text-left">Producto</th>
                <th class="px-6 py-3 text-left">Categoría</th>
                <th class="px-6 py-3 text-right">Precio</th>
                <th class="px-6 py-3 text-center">Stock</th>
                <th class="px-6 py-3 text-right">Valor</th>
                <th class="px-6 py-3 text-center">Estado</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $st = $p->stock === 0 ? ['Sin stock','bg-red-100 text-red-700'] : ($p->stock < 5 ? ['Bajo','bg-amber-100 text-amber-700'] : ['Normal','bg-emerald-100 text-emerald-700']);
                ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3 font-medium text-gray-800"><?php echo e($p->name); ?></td>
                    <td class="px-6 py-3 text-gray-500"><?php echo e($p->category?->name ?? '—'); ?></td>
                    <td class="px-6 py-3 text-right font-semibold text-gray-700">$<?php echo e(number_format($p->price,0,',','.')); ?></td>
                    <td class="px-6 py-3 text-center font-bold <?php echo e($p->stock < 5 ? 'text-red-600' : 'text-gray-800'); ?>"><?php echo e($p->stock); ?></td>
                    <td class="px-6 py-3 text-right text-gray-600">$<?php echo e(number_format($p->price*$p->stock,0,',','.')); ?></td>
                    <td class="px-6 py-3 text-center">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo e($st[1]); ?>"><?php echo e($st[0]); ?></span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">Sin productos.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
    const ctx = document.getElementById('catBarChart').getContext('2d');
    const grad = ctx.createLinearGradient(0,0,ctx.canvas.width,0);
    grad.addColorStop(0,'#3B59FF'); grad.addColorStop(1,'#7B2FBE');
    new Chart(ctx,{
        type:'bar',
        data:{labels:<?php echo $catNames; ?>,datasets:[{label:'Unidades en stock',data:<?php echo $catStock; ?>,backgroundColor:grad,borderRadius:8,borderSkipped:false}]},
        options:{responsive:true,plugins:{legend:{display:false},tooltip:{backgroundColor:'#0a0e2e',titleColor:'#a5b4fc',bodyColor:'#fff',padding:10,cornerRadius:10}},
            scales:{y:{beginAtZero:true,ticks:{color:'#9ca3af',font:{size:10}},grid:{color:'#f3f4f6'},border:{dash:[4,4]}},
                    x:{ticks:{color:'#9ca3af',font:{size:10}},grid:{display:false}}}}
    });
})();
(function(){
    const ctx = document.getElementById('stockDonut').getContext('2d');
    new Chart(ctx,{type:'doughnut',
        data:{labels:<?php echo json_encode(array_keys($stockStatus)); ?>,
              datasets:[{data:<?php echo json_encode(array_values($stockStatus)); ?>,backgroundColor:['#ef4444','#f59e0b','#3b82f6','#10b981'],borderWidth:0,hoverOffset:6}]},
        options:{responsive:true,cutout:'72%',plugins:{legend:{display:false},tooltip:{backgroundColor:'#0a0e2e',titleColor:'#a5b4fc',bodyColor:'#fff',padding:10,cornerRadius:10}}}
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/admin/reports/inventory.blade.php ENDPATH**/ ?>
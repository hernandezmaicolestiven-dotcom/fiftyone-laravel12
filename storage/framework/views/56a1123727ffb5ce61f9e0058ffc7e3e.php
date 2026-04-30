<div class="flex flex-wrap gap-2 mb-5">
    <?php $__currentLoopData = [
        ['sales',        'fa-chart-line',   'Ventas',              'admin.reports.sales'],
        ['inventory',    'fa-boxes-stacked','Inventario',          'admin.reports.inventory'],
        ['top-products', 'fa-trophy',       'Productos vendidos',  'admin.reports.top-products'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $icon, $label, $route]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route($route)); ?>"
       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm"
       <?php if($active === $key): ?>
           style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);color:#fff"
       <?php else: ?>
           style="background:#fff;color:#6b7280;border:1px solid #e5e7eb"
           onmouseover="this.style.background='#f9fafb'"
           onmouseout="this.style.background='#fff'"
       <?php endif; ?>>
        <i class="fa-solid <?php echo e($icon); ?> text-xs"></i> <?php echo e($label); ?>

    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->startPush('styles'); ?>
<style>
@media print {
    aside, nav, form, .no-print { display: none !important; }
    body { background: white !important; }
    .bg-white { box-shadow: none !important; }
    canvas { max-width: 100% !important; }
}
</style>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\MAI\fiftyone-laravel12-main\resources\views/admin/reports/_nav.blade.php ENDPATH**/ ?>

<?php $__env->startSection('title', 'Blog'); ?>
<?php $__env->startSection('content'); ?>
<div class="text-center mb-12">
    <span class="text-indigo-600 text-sm font-semibold uppercase tracking-widest">Contenido</span>
    <h1 class="text-4xl font-black text-gray-900 mt-2">Blog FiftyOne</h1>
    <p class="text-gray-500 mt-3">Tendencias, tips de moda y novedades de la marca</p>
</div>
<div class="grid md:grid-cols-2 gap-6">
    <?php $__currentLoopData = [
        ['Cómo combinar un hoodie oversize','Guía completa para crear outfits con hoodies oversize para cualquier ocasión.','fa-shirt','Abr 2026'],
        ['Los mejores colores para esta temporada','Descubre qué tonos dominan el streetwear esta temporada y cómo usarlos.','fa-palette','Mar 2026'],
        ['Cuidado de telas premium','Tips para mantener tus prendas FiftyOne en perfecto estado por más tiempo.','fa-heart','Mar 2026'],
        ['Streetwear colombiano en auge','Cómo la moda urbana colombiana está ganando reconocimiento internacional.','fa-globe','Feb 2026'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$title,$desc,$icon,$date]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md transition">
        <div class="h-40 flex items-center justify-center" style="background:linear-gradient(135deg,#0d0d1a,#0a0e2e)">
            <i class="fa-solid <?php echo e($icon); ?> text-5xl text-indigo-400 opacity-50"></i>
        </div>
        <div class="p-5">
            <p class="text-xs text-indigo-500 font-semibold mb-2"><?php echo e($date); ?></p>
            <h3 class="font-bold text-gray-800 mb-2"><?php echo e($title); ?></h3>
            <p class="text-gray-500 text-sm"><?php echo e($desc); ?></p>
            <span class="inline-block mt-3 text-xs text-indigo-600 font-semibold">Próximamente →</span>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pages.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/pages/blog.blade.php ENDPATH**/ ?>
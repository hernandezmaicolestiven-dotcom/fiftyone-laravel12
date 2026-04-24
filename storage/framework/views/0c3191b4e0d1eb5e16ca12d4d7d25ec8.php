
<?php $__env->startSection('title', 'Sobre Nosotros'); ?>
<?php $__env->startSection('content'); ?>
<div class="text-center mb-12">
    <span class="text-indigo-600 text-sm font-semibold uppercase tracking-widest">Nuestra historia</span>
    <h1 class="text-4xl font-black text-gray-900 mt-2">Sobre FiftyOne</h1>
</div>
<div class="prose prose-lg max-w-none text-gray-600 space-y-6">
    <p>FiftyOne nació de la pasión por el streetwear y la moda oversize. Somos una marca colombiana dedicada a crear prendas de alta calidad con un estilo urbano y contemporáneo.</p>
    <p>Nuestro nombre hace referencia al punto de equilibrio perfecto — ni demasiado holgado, ni demasiado ajustado. El fit oversize que todos buscan.</p>
    <p>Trabajamos con telas premium, costuras reforzadas y diseños que combinan comodidad con estilo. Cada prenda es pensada para durar y para que te sientas bien en ella.</p>
    <div class="grid grid-cols-3 gap-6 my-10">
        <?php $__currentLoopData = [['2022','Año de fundación'],['500+','Clientes satisfechos'],['25+','Productos únicos']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$val,$lab]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="text-center p-6 rounded-2xl bg-gray-50 border border-gray-100">
            <p class="text-3xl font-black text-indigo-600"><?php echo e($val); ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo e($lab); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <p>Estamos ubicados en Colombia y enviamos a todo el país. Nuestra misión es hacer que el streetwear de calidad sea accesible para todos.</p>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pages.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/pages/sobre-nosotros.blade.php ENDPATH**/ ?>

<?php $__env->startSection('title', 'Envíos'); ?>
<?php $__env->startSection('content'); ?>
<div class="text-center mb-12">
    <span class="text-indigo-600 text-sm font-semibold uppercase tracking-widest">Logística</span>
    <h1 class="text-4xl font-black text-gray-900 mt-2">Información de Envíos</h1>
</div>
<div class="space-y-6">
    <?php $__currentLoopData = [
        ['fa-truck','Envío estándar','3 a 5 días hábiles. Disponible para todo Colombia. Costo calculado al finalizar la compra.'],
        ['fa-bolt','Envío express','1 a 2 días hábiles. Disponible en ciudades principales (Bogotá, Medellín, Cali, Barranquilla).'],
        ['fa-box','Empaque','Todas las prendas se envían en bolsa sellada con etiqueta FiftyOne. Empaque seguro y discreto.'],
        ['fa-rotate-left','Seguimiento','Recibirás un número de guía por email para rastrear tu pedido en tiempo real.'],
        ['fa-shield-halved','Garantía de entrega','Si tu pedido no llega en el tiempo estimado, te contactamos y gestionamos la solución.'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon,$title,$desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="flex gap-4 p-6 rounded-2xl bg-gray-50 border border-gray-100">
        <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid <?php echo e($icon); ?> text-indigo-600 text-lg"></i>
        </div>
        <div>
            <h3 class="font-bold text-gray-800 mb-1"><?php echo e($title); ?></h3>
            <p class="text-gray-500 text-sm"><?php echo e($desc); ?></p>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pages.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/pages/envios.blade.php ENDPATH**/ ?>
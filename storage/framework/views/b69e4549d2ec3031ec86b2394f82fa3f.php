
<?php $__env->startSection('title', 'Devoluciones'); ?>
<?php $__env->startSection('content'); ?>
<div class="text-center mb-12">
    <span class="text-indigo-600 text-sm font-semibold uppercase tracking-widest">Política</span>
    <h1 class="text-4xl font-black text-gray-900 mt-2">Devoluciones y Cambios</h1>
</div>
<div class="space-y-6 text-gray-600">
    <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200">
        <h3 class="font-bold text-emerald-800 mb-2"><i class="fa-solid fa-circle-check mr-2"></i>30 días para cambios</h3>
        <p class="text-emerald-700 text-sm">Tienes 30 días desde la recepción para solicitar un cambio o devolución sin costo adicional.</p>
    </div>
    <?php $__currentLoopData = [['¿Cuándo aplica?','La prenda debe estar sin usar, con etiquetas originales y en su empaque original.'],['¿Cómo solicitar?','Escríbenos a contacto@fiftyone.com con tu número de pedido y el motivo del cambio.'],['Tiempo de proceso','Una vez recibida la prenda, procesamos el cambio o reembolso en 5 días hábiles.'],['Reembolsos','Los reembolsos se realizan por el mismo medio de pago utilizado en la compra.']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$title,$desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-1"><?php echo e($title); ?></h3>
        <p class="text-gray-500 text-sm"><?php echo e($desc); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>
<div class="text-center mb-12">
    <span class="text-indigo-600 text-sm font-semibold uppercase tracking-widest">Política</span>
    <h1 class="text-4xl font-black text-gray-900 mt-2">Devoluciones y Cambios</h1>
</div>
<div class="space-y-6 text-gray-600">
    <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200">
        <h3 class="font-bold text-emerald-800 mb-2"><i class="fa-solid fa-circle-check mr-2"></i>30 días para cambios</h3>
        <p class="text-emerald-700 text-sm">Tienes 30 días desde la recepción para solicitar un cambio o devolución sin costo adicional.</p>
    </div>
    <?php $__currentLoopData = [
        ['¿Cuándo aplica?','La prenda debe estar sin usar, con etiquetas originales y en su empaque original.'],
        ['¿Cómo solicitar?','Escríbenos a contacto@fiftyone.com con tu número de pedido y el motivo del cambio.'],
        ['Tiempo de proceso','Una vez recibida la prenda, procesamos el cambio o reembolso en 5 días hábiles.'],
        ['Reembolsos','Los reembolsos se realizan por el mismo medio de pago utilizado en la compra.'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$title,$desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-1"><?php echo e($title); ?></h3>
        <p class="text-gray-500 text-sm"><?php echo e($desc); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pages.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/pages/devoluciones.blade.php ENDPATH**/ ?>
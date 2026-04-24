
<?php $__env->startSection('title', 'Contacto'); ?>
<?php $__env->startSection('content'); ?>
<div class="text-center mb-12">
    <span class="text-indigo-600 text-sm font-semibold uppercase tracking-widest">Estamos aquí</span>
    <h1 class="text-4xl font-black text-gray-900 mt-2">Contáctanos</h1>
    <p class="text-gray-500 mt-3">Responderemos en menos de 24 horas</p>
</div>
<div class="grid md:grid-cols-2 gap-10">
    <div class="space-y-6">
        <?php $__currentLoopData = [
            ['fa-envelope','Email','contacto@fiftyone.com'],
            ['fa-phone','WhatsApp','+57 300 123 4567'],
            ['fa-location-dot','Ciudad','Medellín, Colombia'],
            ['fa-clock','Horario','Lun–Vie 9am–6pm'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon,$label,$val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid <?php echo e($icon); ?> text-indigo-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase"><?php echo e($label); ?></p>
                <p class="text-gray-800 font-medium"><?php echo e($val); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4">Envíanos un mensaje</h3>
        <div class="space-y-3">
            <input type="text" placeholder="Tu nombre" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <input type="email" placeholder="Tu email" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <textarea rows="4" placeholder="Tu mensaje..." class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"></textarea>
            <button class="w-full py-3 rounded-xl text-white font-semibold text-sm" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">Enviar mensaje</button>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pages.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/pages/contacto.blade.php ENDPATH**/ ?>

<?php $__env->startSection('title', 'Trabaja con Nosotros'); ?>
<?php $__env->startSection('content'); ?>
<div class="text-center mb-12">
    <span class="text-indigo-600 text-sm font-semibold uppercase tracking-widest">Únete</span>
    <h1 class="text-4xl font-black text-gray-900 mt-2">Trabaja con Nosotros</h1>
    <p class="text-gray-500 mt-3">Sé parte del equipo FiftyOne</p>
</div>
<div class="grid md:grid-cols-3 gap-6 mb-12">
    <?php $__currentLoopData = [['fa-heart','Cultura','Ambiente joven, creativo y apasionado por la moda urbana.'],['fa-chart-line','Crecimiento','Oportunidades reales de desarrollo profesional.'],['fa-users','Equipo','Trabajamos en equipo, nos apoyamos y celebramos juntos.']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon,$title,$desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="text-center p-6 rounded-2xl bg-gray-50 border border-gray-100">
        <i class="fa-solid <?php echo e($icon); ?> text-2xl text-indigo-500 mb-3 block"></i>
        <h3 class="font-bold text-gray-800 mb-2"><?php echo e($title); ?></h3>
        <p class="text-gray-500 text-sm"><?php echo e($desc); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 text-center">
    <h3 class="font-bold text-gray-800 text-xl mb-2">¿Interesado?</h3>
    <p class="text-gray-500 text-sm mb-4">Envíanos tu hoja de vida a <strong>empleos@fiftyone.com</strong> con el cargo al que aplicas.</p>
    <a href="/contacto" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white font-semibold text-sm" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
        <i class="fa-solid fa-envelope text-xs"></i> Contáctanos
    </a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pages.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/pages/trabaja.blade.php ENDPATH**/ ?>
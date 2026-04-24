
<?php $__env->startSection('title', 'Preguntas Frecuentes'); ?>
<?php $__env->startSection('content'); ?>
<div class="text-center mb-12">
    <span class="text-indigo-600 text-sm font-semibold uppercase tracking-widest">FAQ</span>
    <h1 class="text-4xl font-black text-gray-900 mt-2">Preguntas Frecuentes</h1>
</div>
<div class="space-y-4">
    <?php $__currentLoopData = [
        ['¿Cómo sé qué talla elegir?','Nuestras prendas son oversize por diseño. Si usas talla M en ropa regular, con una S de FiftyOne tendrás el fit perfecto. Revisa la guía de tallas en cada producto.'],
        ['¿Cuánto tarda el envío?','Envío estándar: 3 a 5 días hábiles. Express: 1 a 2 días en ciudades principales.'],
        ['¿Puedo pagar contra entrega?','Sí, aceptamos pago contra entrega en la mayoría de ciudades de Colombia.'],
        ['¿Las telas se deforman con el lavado?','No. Usamos algodón de alta densidad (220g–380g) que mantiene su forma. Recomendamos lavar a mano o en ciclo delicado.'],
        ['¿Hacen envíos internacionales?','Por ahora solo enviamos dentro de Colombia. Próximamente expandiremos a más países.'],
        ['¿Puedo cambiar mi pedido después de confirmarlo?','Puedes modificar tu pedido dentro de las primeras 2 horas. Escríbenos de inmediato a contacto@fiftyone.com.'],
        ['¿Cómo rastreo mi pedido?','Una vez despachado, recibirás un número de guía por email para rastrear en tiempo real.'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$q,$a]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <details class="group p-6 rounded-2xl bg-gray-50 border border-gray-100 cursor-pointer">
        <summary class="font-semibold text-gray-800 flex items-center justify-between list-none">
            <?php echo e($q); ?>

            <i class="fa-solid fa-chevron-down text-gray-400 text-xs group-open:rotate-180 transition-transform"></i>
        </summary>
        <p class="text-gray-500 text-sm mt-3"><?php echo e($a); ?></p>
    </details>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pages.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/pages/faq.blade.php ENDPATH**/ ?>
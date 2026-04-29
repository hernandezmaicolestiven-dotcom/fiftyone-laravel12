<!DOCTYPE html>
<html lang="es">
<?php use Illuminate\Support\Facades\Storage; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Catálogo</title>
</head>
<body>
    <h1>Test de Datos</h1>
    
    <h2>Productos (<?php echo e($products->count()); ?>)</h2>
    <ul>
        <?php $__currentLoopData = $products->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($p->name); ?> - $<?php echo e(number_format($p->price, 0)); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    
    <h2>Categorías (<?php echo e($categories->count()); ?>)</h2>
    <ul>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($c->name); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    
    <hr>
    <a href="/catalogo">Ir al catálogo real</a>
</body>
</html>
<?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\catalogo-test.blade.php ENDPATH**/ ?>
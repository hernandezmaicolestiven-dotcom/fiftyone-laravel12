<?php $__env->startSection('title', 'Nuevo Producto'); ?>
<?php $__env->startSection('page-title', 'Nuevo Producto'); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="flex items-center gap-3 mb-6">
            <a href="<?php echo e(route('admin.products.index')); ?>" class="text-gray-400 hover:text-indigo-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-lg font-semibold text-gray-800">Crear nuevo producto</h2>
        </div>

        <?php if($errors->any()): ?>
            <div class="mb-5 bg-red-50 border border-red-200 rounded-lg p-4">
                <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.products.store')); ?>" enctype="multipart/form-data" class="space-y-5">
            <?php echo csrf_field(); ?>

            <?php echo $__env->make('admin.products._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="text-white text-sm font-medium px-6 py-2.5 rounded-lg transition"
                        style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Guardar producto
                </button>
                <a href="<?php echo e(route('admin.products.index')); ?>"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-200 hover:border-gray-300 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\admin\products\create.blade.php ENDPATH**/ ?>
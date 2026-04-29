<?php $__env->startSection('title', 'Nueva Categoría'); ?>
<?php $__env->startSection('page-title', 'Nueva Categoría'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">

    
    <div class="relative rounded-3xl overflow-hidden mb-6 p-6 flex items-center gap-5"
         style="background: linear-gradient(135deg, #0d0d1a 0%, #0a0e2e 55%, #1a0a2e 100%)">
        <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full opacity-20"
             style="background: radial-gradient(circle, #3B59FF, transparent)"></div>
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 bg-white/10">
            <i class="fa-solid fa-tags text-white text-xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-black text-white">Nueva categoría</h2>
            <p class="text-gray-400 text-sm mt-0.5">Organiza tus productos con categorías claras</p>
        </div>
        <a href="<?php echo e(route('admin.categories.index')); ?>"
           class="ml-auto flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-medium transition border border-white/10 flex-shrink-0">
            <i class="fa-solid fa-arrow-left text-xs"></i> Volver
        </a>
    </div>

    <?php if($errors->any()): ?>
        <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">
            <i class="fa-solid fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
            <ul class="space-y-0.5">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)"></div>
        <div class="p-6">
            <form method="POST" action="<?php echo e(route('admin.categories.store')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>
                <?php echo $__env->make('admin.categories._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold shadow hover:opacity-90 transition"
                            style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar categoría
                    </button>
                    <a href="<?php echo e(route('admin.categories.index')); ?>"
                       class="px-5 py-3 rounded-xl border-2 border-gray-100 text-gray-500 text-sm font-semibold hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    
    <div class="mt-4 flex items-start gap-3 bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-3">
        <i class="fa-solid fa-lightbulb text-indigo-400 mt-0.5 flex-shrink-0"></i>
        <p class="text-xs text-indigo-600">
            El <span class="font-bold">slug</span> se genera automáticamente a partir del nombre.
            Úsalo para identificar la categoría en la URL de tu tienda.
        </p>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\admin\categories\create.blade.php ENDPATH**/ ?>
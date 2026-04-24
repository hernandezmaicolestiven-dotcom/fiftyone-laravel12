
<?php $__env->startSection('title','Papelera de categorías'); ?>
<?php $__env->startSection('page-title','Papelera — Categorías'); ?>
<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Papelera de categorías</h2>
            <p class="text-sm text-gray-400 mt-0.5"><?php echo e($categories->total()); ?> categoría(s) eliminada(s)</p>
        </div>
        <a href="<?php echo e(route('admin.categories.index')); ?>" class="text-sm text-gray-500 hover:text-indigo-600 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i> Volver
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                <th class="px-6 py-3 text-left">Nombre</th>
                <th class="px-6 py-3 text-left">Eliminada</th>
                <th class="px-6 py-3 text-right">Acciones</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 opacity-75">
                    <td class="px-6 py-4 font-medium text-gray-500 line-through"><?php echo e($cat->name); ?></td>
                    <td class="px-6 py-4 text-gray-400 text-xs"><?php echo e($cat->deleted_at->format('d/m/Y H:i')); ?></td>
                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                        <form method="POST" action="<?php echo e(route('admin.categories.restore', $cat->id)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Restaurar
                            </button>
                        </form>
                        <form method="POST" action="<?php echo e(route('admin.categories.force-delete', $cat->id)); ?>"
                              onsubmit="return confirm('¿Eliminar permanentemente?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                <i class="fa-solid fa-trash mr-1"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="3" class="px-6 py-16 text-center text-gray-400">
                    <i class="fa-solid fa-trash text-4xl mb-3 block opacity-20"></i> La papelera está vacía.
                </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($categories->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-100"><?php echo e($categories->links('vendor.pagination.tailwind')); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/admin/categories/trashed.blade.php ENDPATH**/ ?>
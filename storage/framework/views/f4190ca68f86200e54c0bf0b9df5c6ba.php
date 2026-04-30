
<?php $__env->startSection('title', 'Reseñas'); ?>
<?php $__env->startSection('page-title', 'Reseñas'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Reseñas de clientes</h2>
                <p class="text-sm text-gray-400 mt-0.5"><?php echo e($reviews->total()); ?> reseña(s) en total</p>
            </div>
            <form method="GET" class="flex gap-2">
                <select name="product_id" onchange="this.form.submit()"
                        class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
                    <option value="">Todos los productos</option>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>" <?php echo e($productId == $p->id ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php if($productId): ?>
                <a href="<?php echo e(route('admin.reviews.index')); ?>" class="px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition">
                    Limpiar
                </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                    <th class="px-6 py-3 text-left">Producto</th>
                    <th class="px-6 py-3 text-left">Cliente</th>
                    <th class="px-6 py-3 text-left">Calificación</th>
                    <th class="px-6 py-3 text-left">Comentario</th>
                    <th class="px-6 py-3 text-left">Fecha</th>
                    <th class="px-6 py-3 text-right">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800"><?php echo e($review->product->name ?? '—'); ?></td>
                    <td class="px-6 py-4 text-gray-600"><?php echo e($review->user->name ?? '—'); ?></td>
                    <td class="px-6 py-4">
                        <div class="flex gap-0.5">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-star text-xs <?php echo e($i <= $review->rating ? 'fa-solid text-amber-400' : 'fa-regular text-gray-300'); ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate"><?php echo e($review->comment ?? '—'); ?></td>
                    <td class="px-6 py-4 text-gray-400 text-xs"><?php echo e($review->created_at->format('d/m/Y')); ?></td>
                    <td class="px-6 py-4 text-right">
                        <form method="POST" action="<?php echo e(route('admin.reviews.destroy', $review)); ?>"
                              onsubmit="return confirm('¿Eliminar esta reseña?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <i class="fa-solid fa-star text-4xl mb-3 block opacity-20"></i>
                        No hay reseñas todavía.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($reviews->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-100">
        <?php echo e($reviews->links('vendor.pagination.tailwind')); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAI\fiftyone-laravel12-main\resources\views/admin/reviews/index.blade.php ENDPATH**/ ?>
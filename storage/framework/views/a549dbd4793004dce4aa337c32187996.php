<?php $__env->startSection('title', 'Categorias'); ?>
<?php $__env->startSection('page-title', 'Categorias'); ?>

<?php $__env->startSection('content'); ?>


<div x-data="{ open:false, form:null, msg:'' }" x-cloak
     @confirm-delete.window="open=true; form=$event.detail.form; msg=$event.detail.msg">
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.55);backdrop-filter:blur(6px)">
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full text-center">
            <div class="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-trash-can text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-black text-gray-900 mb-1">Mover a papelera</h3>
            <p class="text-sm text-gray-500 mb-4" x-text="msg"></p>
            <div class="flex items-center gap-2 bg-indigo-50 rounded-2xl px-4 py-2.5 mb-6 text-left">
                <i class="fa-solid fa-rotate-left text-indigo-500 flex-shrink-0"></i>
                <p class="text-xs text-indigo-700 font-medium">Puedes restaurarlo desde la papelera cuando quieras</p>
            </div>
            <div class="flex gap-3">
                <button @click="open=false"
                        class="flex-1 py-3 rounded-2xl border-2 border-gray-100 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">
                    Cancelar
                </button>
                <button @click="form.submit(); open=false"
                        class="flex-1 py-3 rounded-2xl text-white text-sm font-bold transition hover:opacity-90"
                        style="background:linear-gradient(90deg,#ef4444,#dc2626);box-shadow:0 4px 15px rgba(239,68,68,.3)">
                    Mover a papelera
                </button>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Lista de categorias</h2>
            <p class="text-sm text-gray-500"><?php echo e($categories->total()); ?> categorias en total</p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.categories.trashed')); ?>"
               class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                <i class="fa-solid fa-trash text-red-400"></i> Papelera
            </a>
            <a href="<?php echo e(route('admin.categories.create')); ?>"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-plus"></i> Nueva categoria
            </a>
        </div>
    </div>

    <form method="GET" action="<?php echo e(route('admin.categories.index')); ?>" class="flex gap-3 p-4 border-b border-gray-100">
        <div class="relative flex-1 max-w-sm">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i class="fa-solid fa-search text-sm"></i>
            </span>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                   placeholder="Buscar categoria..."
                   class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-sm px-4 py-2 rounded-lg transition">
            Filtrar
        </button>
        <?php if(request('search')): ?>
        <a href="<?php echo e(route('admin.categories.index')); ?>" class="text-sm text-gray-500 hover:text-red-500 flex items-center gap-1 px-2">
            <i class="fa-solid fa-xmark"></i> Limpiar
        </a>
        <?php endif; ?>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Slug</th>
                    <th class="px-6 py-3">Descripcion</th>
                    <th class="px-6 py-3">Productos</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800"><?php echo e($category->name); ?></td>
                    <td class="px-6 py-4">
                        <code class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded"><?php echo e($category->slug); ?></code>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs max-w-xs truncate"><?php echo e($category->description ?? '-'); ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                            <?php echo e($category->products_count); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo e(route('admin.categories.edit', $category)); ?>"
                               class="inline-flex items-center gap-1 text-xs bg-amber-50 hover:bg-amber-100 text-amber-700 px-3 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </a>
                            <form id="del-cat-<?php echo e($category->id); ?>" method="POST"
                                  action="<?php echo e(route('admin.categories.destroy', $category)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="button"
                                        @click="$dispatch('confirm-delete', { form: document.getElementById('del-cat-<?php echo e($category->id); ?>'), msg: 'La categoria <?php echo e(addslashes($category->name)); ?> se movera a la papelera. Los productos quedaran sin categoria.' })"
                                        class="inline-flex items-center gap-1 text-xs bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded-lg transition">
                                    <i class="fa-solid fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-tags text-4xl mb-3 block"></i>
                        No hay categorias que mostrar.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($categories->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-100">
        <?php echo e($categories->links('vendor.pagination.tailwind')); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/categories/index.blade.php ENDPATH**/ ?>
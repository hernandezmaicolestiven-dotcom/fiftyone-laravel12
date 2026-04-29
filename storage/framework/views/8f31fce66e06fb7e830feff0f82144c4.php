<?php $__env->startSection('title', 'Pedidos'); ?>
<?php $__env->startSection('page-title', 'Pedidos'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">

    
    <div class="px-6 py-5 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Todos los pedidos</h2>
                <p class="text-sm text-gray-400">
                    <?php echo e($orders->total()); ?> pedido<?php echo e($orders->total() !== 1 ? 's' : ''); ?> en total
                    <?php if(request()->hasAny(['search','status','date_from','date_to'])): ?>
                        <span class="ml-1 text-indigo-500 font-medium">(filtrado)</span>
                    <?php endif; ?>
                </p>
            </div>

            
            <div class="relative self-start sm:self-auto" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                        class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition whitespace-nowrap">
                    <i class="fa-solid fa-arrow-up-from-bracket text-indigo-500"></i>
                    Exportar
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                </button>
                <div x-show="open" x-transition
                     class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-20">
                    <a href="<?php echo e(route('admin.orders.export.csv', request()->query())); ?>"
                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <span class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-file-csv text-emerald-600 text-xs"></i>
                        </span>
                        Exportar CSV
                    </a>
                    <a href="<?php echo e(route('admin.orders.export.pdf', request()->query())); ?>"
                       target="_blank"
                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <span class="w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-file-pdf text-red-500 text-xs"></i>
                        </span>
                        Exportar PDF
                        <span class="ml-auto text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded-md">Imprimir</span>
                    </a>
                </div>
            </div>
        </div>

        
        <form method="GET" x-data="{ showDates: <?php echo e(request()->hasAny(['date_from','date_to']) ? 'true' : 'false'); ?> }">
            <div class="flex flex-wrap gap-2 items-center">

                
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                           placeholder="Buscar cliente..."
                           class="pl-8 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 w-48 bg-gray-50 focus:bg-white transition">
                </div>

                
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-circle-half-stroke text-xs"></i>
                    </span>
                    <select name="status"
                            class="pl-8 pr-8 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50 focus:bg-white transition appearance-none">
                        <option value="">Todos los estados</option>
                        <option value="pending"   <?php echo e(request('status') === 'pending'   ? 'selected' : ''); ?>>Pendiente</option>
                        <option value="confirmed" <?php echo e(request('status') === 'confirmed' ? 'selected' : ''); ?>>Confirmado</option>
                        <option value="shipped"   <?php echo e(request('status') === 'shipped'   ? 'selected' : ''); ?>>Enviado</option>
                        <option value="delivered" <?php echo e(request('status') === 'delivered' ? 'selected' : ''); ?>>Entregado</option>
                        <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Cancelado</option>
                    </select>
                </div>

                
                <button type="button" @click="showDates = !showDates"
                        class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border text-sm transition"
                        :class="showDates
                            ? 'border-indigo-300 bg-indigo-50 text-indigo-600'
                            : 'border-gray-200 bg-gray-50 text-gray-500 hover:bg-gray-100'">
                    <i class="fa-regular fa-calendar text-xs"></i>
                    Fechas
                    <?php if(request()->hasAny(['date_from','date_to'])): ?>
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    <?php endif; ?>
                </button>

                
                <button type="submit"
                        class="px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-sm hover:opacity-90 transition"
                        style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                    <i class="fa-solid fa-filter mr-1.5 text-xs"></i>Filtrar
                </button>

                
                <?php if(request()->hasAny(['search','status','date_from','date_to'])): ?>
                <a href="<?php echo e(route('admin.orders.index')); ?>"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition">
                    <i class="fa-solid fa-xmark text-xs"></i> Limpiar
                </a>
                <?php endif; ?>
            </div>

            
            <div x-show="showDates" x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-3 p-4 rounded-2xl bg-gray-50 border border-gray-100 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                        <i class="fa-regular fa-calendar-plus mr-1 text-indigo-400"></i>Desde
                    </label>
                    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                           class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                        <i class="fa-regular fa-calendar-minus mr-1 text-indigo-400"></i>Hasta
                    </label>
                    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                           class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white">
                </div>
                <?php if(request()->hasAny(['date_from','date_to'])): ?>
                <div class="flex items-end">
                    <p class="text-xs text-indigo-500 font-medium flex items-center gap-1">
                        <i class="fa-solid fa-circle-info"></i>
                        Filtro de fecha activo
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

    
    <?php if(request()->hasAny(['search','status','date_from','date_to'])): ?>
    <?php
        $statusLabels = ['pending'=>'Pendiente','confirmed'=>'Confirmado','shipped'=>'Enviado','delivered'=>'Entregado','cancelled'=>'Cancelado'];
    ?>
    <div class="px-6 py-2.5 bg-indigo-50/60 border-b border-indigo-100 flex flex-wrap gap-2 items-center">
        <span class="text-xs text-indigo-400 font-medium">Filtros:</span>
        <?php if(request('search')): ?>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-indigo-200 text-xs text-indigo-700 font-medium">
                <i class="fa-solid fa-magnifying-glass text-indigo-400"></i> <?php echo e(request('search')); ?>

            </span>
        <?php endif; ?>
        <?php if(request('status')): ?>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-indigo-200 text-xs text-indigo-700 font-medium">
                <i class="fa-solid fa-circle-half-stroke text-indigo-400"></i> <?php echo e($statusLabels[request('status')] ?? request('status')); ?>

            </span>
        <?php endif; ?>
        <?php if(request('date_from')): ?>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-indigo-200 text-xs text-indigo-700 font-medium">
                <i class="fa-regular fa-calendar text-indigo-400"></i> Desde <?php echo e(\Carbon\Carbon::parse(request('date_from'))->format('d/m/Y')); ?>

            </span>
        <?php endif; ?>
        <?php if(request('date_to')): ?>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-indigo-200 text-xs text-indigo-700 font-medium">
                <i class="fa-regular fa-calendar text-indigo-400"></i> Hasta <?php echo e(\Carbon\Carbon::parse(request('date_to'))->format('d/m/Y')); ?>

            </span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                    <th class="px-6 py-3 text-left">#</th>
                    <th class="px-6 py-3 text-left">Cliente</th>
                    <th class="px-6 py-3 text-left">Contacto</th>
                    <th class="px-6 py-3 text-left">Items</th>
                    <th class="px-6 py-3 text-left">Total</th>
                    <th class="px-6 py-3 text-left">Estado</th>
                    <th class="px-6 py-3 text-left">Fecha</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $colors = [
                        'pending'   => 'bg-amber-100 text-amber-700',
                        'confirmed' => 'bg-blue-100 text-blue-700',
                        'shipped'   => 'bg-indigo-100 text-indigo-700',
                        'delivered' => 'bg-emerald-100 text-emerald-700',
                        'cancelled' => 'bg-red-100 text-red-600',
                    ];
                    $color = $colors[$order->status] ?? 'bg-gray-100 text-gray-600';
                ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-mono text-gray-400">#<?php echo e($order->id); ?></td>
                    <td class="px-6 py-4 font-semibold text-gray-800"><?php echo e($order->customer_name); ?></td>
                    <td class="px-6 py-4 text-gray-500">
                        <div><?php echo e($order->customer_email ?? '—'); ?></div>
                        <div class="text-xs"><?php echo e($order->customer_phone ?? ''); ?></div>
                    </td>
                    <td class="px-6 py-4 text-gray-600"><?php echo e($order->items->count()); ?> producto(s)</td>
                    <td class="px-6 py-4 font-bold text-gray-800">$<?php echo e(number_format($order->total, 2)); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($color); ?>">
                            <?php echo e($order->status_label); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs"><?php echo e($order->created_at->format('d/m/Y H:i')); ?></td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>"
                               class="px-3 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                                Ver
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.orders.destroy', $order)); ?>"
                                  onsubmit="return confirm('¿Eliminar este pedido?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center text-gray-400">
                        <i class="fa-solid fa-bag-shopping text-4xl mb-3 block opacity-30"></i>
                        No hay pedidos todavía.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($orders->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-100">
        <?php echo e($orders->links('vendor.pagination.tailwind')); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAI\fiftyone-laravel12-main\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>
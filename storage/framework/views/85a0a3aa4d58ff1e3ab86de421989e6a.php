
<?php $__env->startSection('title', 'Facturas'); ?>
<?php $__env->startSection('page-title', 'Gestión de Facturas'); ?>

<?php $__env->startSection('content'); ?>

<div class="bg-white rounded-xl shadow-sm">
    <!-- Header con estadísticas -->
    <div class="p-6 border-b border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-emerald-600 uppercase">Total Facturado</p>
                        <p class="text-2xl font-black text-emerald-900 mt-1">$<?php echo e(number_format($stats['total'], 0, ',', '.')); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center">
                        <i class="fa-solid fa-dollar-sign text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-blue-600 uppercase">Facturas Activas</p>
                        <p class="text-2xl font-black text-blue-900 mt-1"><?php echo e($stats['count']); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center">
                        <i class="fa-solid fa-file-invoice text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-amber-600 uppercase">Este Mes</p>
                        <p class="text-2xl font-black text-amber-900 mt-1">$<?php echo e(number_format($stats['this_month'], 0, ',', '.')); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center">
                        <i class="fa-solid fa-calendar text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-red-600 uppercase">Anuladas</p>
                        <p class="text-2xl font-black text-red-900 mt-1"><?php echo e($stats['cancelled']); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-500 flex items-center justify-center">
                        <i class="fa-solid fa-ban text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Lista de Facturas</h2>
                <p class="text-sm text-gray-500"><?php echo e($invoices->total()); ?> facturas en total</p>
            </div>
            <div class="flex gap-2">
                <a href="<?php echo e(route('admin.invoices.settings')); ?>"
                   class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                    <i class="fa-solid fa-gear"></i> Configuración
                </a>
                <form method="GET" action="<?php echo e(route('admin.invoices.export-csv')); ?>" class="inline">
                    <?php $__currentLoopData = request()->except('_token'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                        <i class="fa-solid fa-download"></i> Exportar CSV
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" action="<?php echo e(route('admin.invoices.index')); ?>" class="p-4 border-b border-gray-100 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <i class="fa-solid fa-search text-sm"></i>
                </span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Buscar por número, cliente..."
                       class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="">Todos los estados</option>
                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Activas</option>
                <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Anuladas</option>
            </select>

            <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                   placeholder="Desde"
                   class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">

            <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                   placeholder="Hasta"
                   class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div class="flex gap-2 mt-3">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-sm px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-filter mr-1"></i> Filtrar
            </button>
            <?php if(request()->hasAny(['search', 'status', 'date_from', 'date_to'])): ?>
            <a href="<?php echo e(route('admin.invoices.index')); ?>" class="text-sm text-gray-500 hover:text-red-500 flex items-center gap-1 px-3 py-2">
                <i class="fa-solid fa-xmark"></i> Limpiar filtros
            </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">Número</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Pedido</th>
                    <th class="px-6 py-3">Subtotal</th>
                    <th class="px-6 py-3">IVA</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <code class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded font-semibold">
                            <?php echo e($invoice->invoice_number); ?>

                        </code>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800"><?php echo e($invoice->customer_name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($invoice->customer_email); ?></p>
                    </td>
                    <td class="px-6 py-4">
                        <?php if($invoice->order): ?>
                            <a href="<?php echo e(route('admin.orders.show', $invoice->order)); ?>" 
                               class="text-indigo-600 hover:underline text-xs">
                                #<?php echo e($invoice->order->id); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-gray-400 text-xs">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-gray-600">$<?php echo e(number_format($invoice->subtotal, 0, ',', '.')); ?></td>
                    <td class="px-6 py-4 text-gray-600">$<?php echo e(number_format($invoice->total_iva, 0, ',', '.')); ?></td>
                    <td class="px-6 py-4 font-semibold text-gray-900">$<?php echo e(number_format($invoice->total, 0, ',', '.')); ?></td>
                    <td class="px-6 py-4">
                        <?php if($invoice->status === 'active'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <i class="fa-solid fa-circle-check mr-1"></i> Activa
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <i class="fa-solid fa-ban mr-1"></i> Anulada
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        <?php echo e($invoice->created_at->format('d/m/Y H:i')); ?>

                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>"
                               class="inline-flex items-center gap-1 text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-eye"></i> Ver
                            </a>
                            <a href="<?php echo e(route('admin.invoices.download-pdf', $invoice)); ?>" target="_blank"
                               class="inline-flex items-center gap-1 text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-download"></i> PDF
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-file-invoice text-4xl mb-3 block"></i>
                        No hay facturas que mostrar.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($invoices->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-100">
        <?php echo e($invoices->links('vendor.pagination.tailwind')); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/admin/invoices/index.blade.php ENDPATH**/ ?>

<?php $__env->startSection('title','Cupones'); ?>
<?php $__env->startSection('page-title','Cupones de descuento'); ?>
<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-base font-bold text-gray-800 mb-1">Generar cupón</h2>
        <p class="text-xs text-gray-400 mb-5">El código se genera automáticamente</p>
        <form method="POST" action="<?php echo e(route('admin.coupons.store')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo de descuento</label>
                <select name="type" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
                    <option value="percent">Porcentaje (%)</option>
                    <option value="fixed">Valor fijo (COP)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Valor</label>
                <input type="number" name="value" min="1" required placeholder="Ej: 15 para 15%"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Usos máximos <span class="text-gray-400 font-normal">(vacío = ilimitado)</span></label>
                <input type="number" name="uses_left" min="1" placeholder="Ej: 100"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha de expiración <span class="text-gray-400 font-normal">(opcional)</span></label>
                <input type="date" name="expires_at"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
            </div>
            <?php if($errors->any()): ?>
            <p class="text-xs text-red-500"><?php echo e($errors->first()); ?></p>
            <?php endif; ?>
            <button type="submit" class="w-full py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition"
                    style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
                <i class="fa-solid fa-wand-magic-sparkles mr-1.5"></i> Generar cupón
            </button>
        </form>
    </div>

    
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-800">Cupones activos</h2>
            <p class="text-xs text-gray-400 mt-0.5"><?php echo e($coupons->count()); ?> cupón(es) en total</p>
        </div>
        <?php $__empty_1 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="px-6 py-4 border-b border-gray-50 last:border-0 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
            <div class="flex items-center gap-3">
                <div class="px-3 py-1.5 rounded-xl font-mono font-black text-sm tracking-widest"
                     style="background:linear-gradient(135deg,rgba(59,89,255,.1),rgba(123,47,190,.1));color:#3B59FF">
                    <?php echo e($c->code); ?>

                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">
                        <?php echo e($c->type === 'percent' ? $c->value.'%' : '$ '.number_format($c->value,0,',','.')); ?> de descuento
                    </p>
                    <p class="text-xs text-gray-400">
                        <?php echo e($c->uses_left !== null ? $c->uses_left.' usos restantes' : 'Usos ilimitados'); ?>

                        <?php if($c->expires_at): ?> · Vence <?php echo e($c->expires_at->format('d/m/Y')); ?> <?php endif; ?>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold <?php echo e($c->isValid() ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600'); ?>">
                    <?php echo e($c->isValid() ? 'Activo' : 'Inactivo'); ?>

                </span>
                <form method="POST" action="<?php echo e(route('admin.coupons.toggle', $c)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        <?php echo e($c->active ? 'Desactivar' : 'Activar'); ?>

                    </button>
                </form>
                <form method="POST" action="<?php echo e(route('admin.coupons.destroy', $c)); ?>" onsubmit="return confirm('¿Eliminar cupón?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="px-6 py-16 text-center text-gray-400">
            <i class="fa-solid fa-ticket text-4xl mb-3 block opacity-20"></i>
            <p>No hay cupones. Genera el primero.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\admin\coupons\index.blade.php ENDPATH**/ ?>
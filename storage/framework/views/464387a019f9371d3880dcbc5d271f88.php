
<?php $__env->startSection('title','Administradores'); ?>
<?php $__env->startSection('page-title','Gestión de Administradores'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center gap-2 mb-1">
            <i class="fa-solid fa-crown text-amber-500"></i>
            <h2 class="text-base font-bold text-gray-800">Crear administrador</h2>
        </div>
        <p class="text-xs text-gray-400 mb-5">Solo el superadmin puede crear admins</p>

        <form method="POST" action="<?php echo e(route('admin.admins.store')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre</label>
                <input type="text" name="name" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50"
                       placeholder="Nombre completo">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50"
                       placeholder="correo@ejemplo.com">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Rol</label>
                <select name="role" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
                    <option value="admin">Admin</option>
                    <option value="colaborador">Colaborador</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Contraseña</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50"
                       placeholder="Mínimo 8 caracteres">
            </div>
            <?php if($errors->any()): ?>
            <p class="text-xs text-red-500"><?php echo e($errors->first()); ?></p>
            <?php endif; ?>
            <button type="submit" class="w-full py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition"
                    style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
                <i class="fa-solid fa-plus mr-1.5"></i> Crear administrador
            </button>
        </form>
    </div>

    
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-800">Administradores activos</h2>
            <p class="text-xs text-gray-400 mt-0.5"><?php echo e($admins->count()); ?> usuario(s) con acceso al panel</p>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="px-6 py-4 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                         style="background:linear-gradient(135deg,<?php echo e($a->role==='admin' ? '#3B59FF,#7B2FBE' : '#d97706,#f59e0b'); ?>)">
                        <?php echo e(strtoupper(substr($a->name,0,1))); ?>

                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800"><?php echo e($a->name); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e($a->email); ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold <?php echo e($a->role==='admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700'); ?>">
                        <?php echo e(ucfirst($a->role)); ?>

                    </span>
                    
                    <form method="POST" action="<?php echo e(route('admin.admins.reset', $a)); ?>" class="flex gap-1"
                          onsubmit="return confirm('¿Restablecer contraseña a admin123?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <input type="hidden" name="password" value="admin123">
                        <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                            Reset pass
                        </button>
                    </form>
                    <form method="POST" action="<?php echo e(route('admin.admins.destroy', $a)); ?>"
                          onsubmit="return confirm('¿Eliminar a <?php echo e($a->name); ?>?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="px-6 py-16 text-center text-gray-400">
            <i class="fa-solid fa-users text-4xl mb-3 block opacity-20"></i>
            <p>No hay administradores creados.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\admin\admins\index.blade.php ENDPATH**/ ?>
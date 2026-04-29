<?php $__env->startSection('title', 'Nuevo usuario'); ?>
<?php $__env->startSection('page-title', 'Nuevo usuario'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">

    
    <div class="rounded-2xl p-6 mb-6 flex items-center gap-5"
         style="background: linear-gradient(135deg, #0d0d1a 0%, #0a0e2e 50%, #1a0a2e 100%)">
        <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-user-plus text-white text-2xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Crear nuevo usuario</h2>
            <p class="text-sm text-gray-400 mt-0.5">Completa los datos para agregar un administrador al panel.</p>
        </div>
    </div>

    <form method="POST" action="<?php echo e(route('admin.users.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

            
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                     style="background: linear-gradient(135deg, rgba(59,89,255,0.15), rgba(123,47,190,0.15))">
                    <i class="fa-solid fa-circle-info text-xs" style="background: linear-gradient(135deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700">Información personal</span>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Nombre completo <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-user text-sm"></i>
                        </span>
                        <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                               placeholder="Ej: Juan García"
                               class="w-full pl-10 pr-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition
                                      <?php echo e($errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white'); ?>">
                    </div>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Correo electrónico <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </span>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                               placeholder="correo@ejemplo.com"
                               class="w-full pl-10 pr-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition
                                      <?php echo e($errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white'); ?>">
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="px-6 py-4 border-t border-b border-gray-100 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                     style="background: linear-gradient(135deg, rgba(59,89,255,0.15), rgba(123,47,190,0.15))">
                    <i class="fa-solid fa-lock text-xs" style="background: linear-gradient(135deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700">Seguridad</span>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Contraseña <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" id="password" required
                               placeholder="Mínimo 8 caracteres"
                               class="w-full pl-10 pr-10 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition
                                      <?php echo e($errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white'); ?>">
                        <button type="button" onclick="togglePass('password', this)"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($message); ?>

                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Confirmar contraseña <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               placeholder="Repite la contraseña"
                               class="w-full pl-10 pr-10 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition border-gray-200 bg-gray-50 focus:bg-white">
                        <button type="button" onclick="togglePass('password_confirmation', this)"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                
                <div class="sm:col-span-2">
                    <div class="flex gap-1.5 mb-1" id="strength-bars">
                        <div class="h-1.5 flex-1 rounded-full bg-gray-200" id="bar1"></div>
                        <div class="h-1.5 flex-1 rounded-full bg-gray-200" id="bar2"></div>
                        <div class="h-1.5 flex-1 rounded-full bg-gray-200" id="bar3"></div>
                        <div class="h-1.5 flex-1 rounded-full bg-gray-200" id="bar4"></div>
                    </div>
                    <p class="text-xs text-gray-400" id="strength-label">Ingresa una contraseña</p>
                </div>
            </div>

            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <a href="<?php echo e(route('admin.users.index')); ?>"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-white transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Volver
                </a>
                <button type="submit"
                        class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-sm hover:opacity-90 transition"
                        style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                    <i class="fa-solid fa-user-plus"></i> Crear usuario
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

document.getElementById('password').addEventListener('input', function () {
    const val = this.value;
    const bars = [document.getElementById('bar1'), document.getElementById('bar2'),
                  document.getElementById('bar3'), document.getElementById('bar4')];
    const label = document.getElementById('strength-label');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
    const labels = ['Muy débil', 'Débil', 'Buena', 'Fuerte'];
    bars.forEach((b, i) => b.style.background = i < score ? colors[score - 1] : '#e5e7eb');
    label.textContent = val.length ? labels[score - 1] ?? 'Muy débil' : 'Ingresa una contraseña';
    label.style.color = val.length ? colors[score - 1] : '#9ca3af';
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\admin\users\create.blade.php ENDPATH**/ ?>
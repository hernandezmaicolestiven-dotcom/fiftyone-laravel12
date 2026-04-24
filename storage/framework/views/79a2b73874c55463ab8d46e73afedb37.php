<?php $__env->startSection('title', 'Configuración'); ?>
<?php $__env->startSection('page-title', 'Configuración'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{ tab: '<?php echo e(session('tab', 'profile')); ?>' }">


<div class="relative rounded-3xl overflow-hidden mb-8 h-44"
     style="background: linear-gradient(135deg, #0d0d1a 0%, #0a0e2e 50%, #1a0a2e 100%)">
    
    <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full opacity-20"
         style="background: radial-gradient(circle, #3B59FF, transparent)"></div>
    <div class="absolute -bottom-16 -left-8 w-64 h-64 rounded-full opacity-10"
         style="background: radial-gradient(circle, #7B2FBE, transparent)"></div>
    <div class="absolute top-6 right-32 w-2 h-2 rounded-full bg-indigo-400 opacity-60"></div>
    <div class="absolute top-16 right-48 w-1 h-1 rounded-full bg-purple-400 opacity-40"></div>
    <div class="absolute bottom-8 right-24 w-3 h-3 rounded-full bg-blue-400 opacity-30"></div>

    <div class="relative z-10 flex items-center gap-6 h-full px-8">
        
        <div class="relative flex-shrink-0 group">
            <?php if($user->avatar): ?>
                <img src="<?php echo e(Storage::url($user->avatar)); ?>" alt="<?php echo e($user->name); ?>" 
                     class="w-20 h-20 rounded-2xl object-cover shadow-2xl border-2 border-white/20">
            <?php else: ?>
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-3xl font-black text-white shadow-2xl border-2 border-white/20"
                     style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>
            <?php endif; ?>
            <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-400 border-2 border-white flex items-center justify-center">
                <i class="fa-solid fa-check text-white" style="font-size:8px"></i>
            </div>
            
            <button onclick="document.getElementById('adminAvatarInput').click()" 
                    class="absolute inset-0 bg-black/60 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                <i class="fa-solid fa-camera text-white text-xl"></i>
            </button>
            <form id="adminAvatarForm" method="POST" action="<?php echo e(route('admin.settings.profile')); ?>" enctype="multipart/form-data" class="hidden">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <input type="hidden" name="name" value="<?php echo e($user->name); ?>">
                <input type="hidden" name="email" value="<?php echo e($user->email); ?>">
                <input type="file" id="adminAvatarInput" name="avatar" accept="image/*" onchange="document.getElementById('adminAvatarForm').submit()">
            </form>
        </div>
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h2 class="text-2xl font-black text-white"><?php echo e($user->name); ?></h2>
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-white/10 text-indigo-300 border border-white/10">
                    Admin
                </span>
            </div>
            <p class="text-gray-400 text-sm"><?php echo e($user->email); ?></p>
            <div class="flex items-center gap-4 mt-2">
                <span class="text-xs text-gray-500 flex items-center gap-1.5">
                    <i class="fa-solid fa-calendar-days text-indigo-400"></i>
                    Desde <?php echo e($user->created_at->format('d/m/Y')); ?>

                </span>
                <span class="text-xs text-gray-500 flex items-center gap-1.5">
                    <i class="fa-solid fa-clock text-purple-400"></i>
                    Actualizado <?php echo e($user->updated_at->diffForHumans()); ?>

                </span>
            </div>
        </div>
    </div>
</div>


<div class="flex gap-1 bg-white rounded-2xl shadow-sm p-1.5 mb-6 overflow-x-auto">
    <?php $__currentLoopData = [
        ['profile',  'fa-user',              'Perfil',          'text-indigo-600'],
        ['security', 'fa-shield-halved',     'Seguridad',       'text-purple-600'],
        ['store',    'fa-store',             'Tienda',          'text-sky-600'],
        ['danger',   'fa-triangle-exclamation','Zona peligrosa','text-red-500'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $icon, $label, $activeText]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <button @click="tab='<?php echo e($key); ?>'"
            :class="tab==='<?php echo e($key); ?>'
                ? '<?php echo e($key === 'danger' ? 'bg-red-500 text-white shadow-sm' : 'text-white shadow-sm'); ?>'
                : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50'"
            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all whitespace-nowrap flex-1 justify-center"
            :style="tab==='<?php echo e($key); ?>' && '<?php echo e($key); ?>' !== 'danger'
                ? 'background: linear-gradient(90deg, #3B59FF, #7B2FBE)'
                : ''">
        <i class="fa-solid <?php echo e($icon); ?> text-xs"></i>
        <?php echo e($label); ?>

    </button>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
<div class="lg:col-span-2 space-y-5">


<div x-show="tab==='profile'" x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

    <?php if(session('success_profile')): ?>
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm mb-4">
        <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
        </div>
        <?php echo e(session('success_profile')); ?>

    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)"></div>
        <div class="p-6">
            <h3 class="text-base font-bold text-gray-800 mb-5 flex items-center gap-2">
                <i class="fa-solid fa-user text-indigo-500"></i> Información personal
            </h3>
            <form method="POST" action="<?php echo e(route('admin.settings.profile')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nombre completo</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-4 flex items-center text-gray-300 group-focus-within:text-indigo-500 transition">
                                <i class="fa-solid fa-user text-sm"></i>
                            </span>
                            <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required
                                   class="w-full pl-11 pr-4 py-3.5 border-2 rounded-xl text-sm font-medium focus:outline-none transition
                                          <?php echo e($errors->has('name') ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50 focus:border-indigo-400 focus:bg-white'); ?>">
                        </div>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Correo electrónico</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-4 flex items-center text-gray-300 group-focus-within:text-indigo-500 transition">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </span>
                            <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required
                                   class="w-full pl-11 pr-4 py-3.5 border-2 rounded-xl text-sm font-medium focus:outline-none transition
                                          <?php echo e($errors->has('email') ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50 focus:border-indigo-400 focus:bg-white'); ?>">
                        </div>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold shadow hover:opacity-90 transition"
                            style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div x-show="tab==='security'" x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

    <?php if(session('success_password')): ?>
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm mb-4">
        <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
        </div>
        <?php echo e(session('success_password')); ?>

    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #7B2FBE, #3B59FF)"></div>
        <div class="p-6">
            <h3 class="text-base font-bold text-gray-800 mb-5 flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-purple-500"></i> Cambiar contraseña
            </h3>
            <form method="POST" action="<?php echo e(route('admin.settings.password')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Contraseña actual</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-300 group-focus-within:text-purple-500 transition">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="current_password" id="cur_pass"
                               class="w-full pl-11 pr-12 py-3.5 border-2 rounded-xl text-sm font-medium focus:outline-none transition
                                      <?php echo e($errors->has('current_password') ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50 focus:border-purple-400 focus:bg-white'); ?>">
                        <button type="button" onclick="togglePass('cur_pass',this)"
                                class="absolute inset-y-0 right-4 flex items-center text-gray-300 hover:text-gray-500 transition">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nueva contraseña</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-4 flex items-center text-gray-300 group-focus-within:text-purple-500 transition">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input type="password" name="password" id="new_pass"
                                   class="w-full pl-11 pr-12 py-3.5 border-2 rounded-xl text-sm font-medium focus:outline-none transition
                                          <?php echo e($errors->has('password') ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50 focus:border-purple-400 focus:bg-white'); ?>">
                            <button type="button" onclick="togglePass('new_pass',this)"
                                    class="absolute inset-y-0 right-4 flex items-center text-gray-300 hover:text-gray-500 transition">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Confirmar contraseña</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-4 flex items-center text-gray-300 group-focus-within:text-purple-500 transition">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="conf_pass"
                                   class="w-full pl-11 pr-12 py-3.5 border-2 border-gray-100 bg-gray-50 focus:border-purple-400 focus:bg-white rounded-xl text-sm font-medium focus:outline-none transition">
                            <button type="button" onclick="togglePass('conf_pass',this)"
                                    class="absolute inset-y-0 right-4 flex items-center text-gray-300 hover:text-gray-500 transition">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="pt-1">
                    <div class="flex gap-1.5 mb-1.5">
                        <div class="h-2 flex-1 rounded-full bg-gray-100 transition-all" id="sb1"></div>
                        <div class="h-2 flex-1 rounded-full bg-gray-100 transition-all" id="sb2"></div>
                        <div class="h-2 flex-1 rounded-full bg-gray-100 transition-all" id="sb3"></div>
                        <div class="h-2 flex-1 rounded-full bg-gray-100 transition-all" id="sb4"></div>
                    </div>
                    <p class="text-xs text-gray-400" id="slabel">Ingresa una contraseña</p>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold shadow hover:opacity-90 transition"
                            style="background: linear-gradient(90deg, #7B2FBE, #3B59FF)">
                        <i class="fa-solid fa-key"></i> Actualizar contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div x-show="tab==='store'" x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="h-1.5 w-full bg-sky-400"></div>
        <div class="p-6 space-y-5">
            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-store text-sky-500"></i> Información de la tienda
            </h3>
            <?php $__currentLoopData = [
                ['fa-store','Nombre','text-sky-500', config('app.name'), 'APP_NAME'],
                ['fa-globe','URL','text-indigo-500', config('app.url'), 'APP_URL'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $label, $color, $val, $key]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2"><?php echo e($label); ?></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center <?php echo e($color); ?>">
                        <i class="fa-solid <?php echo e($icon); ?> text-sm"></i>
                    </span>
                    <input type="text" value="<?php echo e($val); ?>" disabled
                           class="w-full pl-11 pr-4 py-3.5 border-2 border-gray-100 bg-gray-50 rounded-xl text-sm font-medium text-gray-500 cursor-not-allowed">
                    <span class="absolute inset-y-0 right-4 flex items-center">
                        <code class="text-xs bg-gray-200 text-gray-500 px-2 py-0.5 rounded-lg"><?php echo e($key); ?></code>
                    </span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-3 flex-wrap">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold
                    <?php echo e(config('app.env') === 'production' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'); ?>">
                    <span class="w-2 h-2 rounded-full <?php echo e(config('app.env') === 'production' ? 'bg-emerald-500' : 'bg-amber-500'); ?> animate-pulse"></span>
                    <?php echo e(ucfirst(config('app.env'))); ?>

                </span>
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold
                    <?php echo e(config('app.debug') ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600'); ?>">
                    <i class="fa-solid fa-bug text-xs"></i>
                    Debug <?php echo e(config('app.debug') ? 'ON' : 'OFF'); ?>

                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mt-5">
        <?php $__currentLoopData = [
            [route('admin.products.index'),   'fa-box',   'bg-indigo-50 border-indigo-100 text-indigo-600 hover:border-indigo-300', 'Productos'],
            [route('admin.categories.index'), 'fa-tags',  'bg-emerald-50 border-emerald-100 text-emerald-600 hover:border-emerald-300', 'Categorías'],
            [route('admin.users.index'),      'fa-users', 'bg-sky-50 border-sky-100 text-sky-600 hover:border-sky-300', 'Usuarios'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$url, $icon, $cls, $lbl]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e($url); ?>"
           class="flex flex-col items-center gap-3 p-5 rounded-2xl border-2 transition group <?php echo e($cls); ?>">
            <i class="fa-solid <?php echo e($icon); ?> text-2xl opacity-60 group-hover:opacity-100 transition"></i>
            <span class="text-xs font-bold"><?php echo e($lbl); ?></span>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<div x-show="tab==='danger'" x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border-2 border-red-100">
        <div class="h-1.5 w-full bg-gradient-to-r from-red-400 to-rose-500"></div>
        <div class="p-6">
            <h3 class="text-base font-bold text-red-600 mb-2 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i> Zona peligrosa
            </h3>
            <p class="text-sm text-gray-500 mb-6">Las acciones en esta sección son irreversibles. Procede con cuidado.</p>

            <div class="rounded-2xl border-2 border-red-100 bg-red-50 p-5">
                <div class="flex items-start gap-4 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-user-xmark text-red-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-red-700">Eliminar mi cuenta</p>
                        <p class="text-xs text-red-400 mt-1">Se eliminará permanentemente tu cuenta de administrador. Esta acción no se puede deshacer.</p>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('admin.settings.delete')); ?>"
                      onsubmit="return confirm('¿Estás completamente seguro? Esta acción NO se puede deshacer.')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <label class="block text-xs font-bold text-red-400 uppercase tracking-widest mb-2">
                        Escribe <span class="text-red-600">ELIMINAR</span> para confirmar
                    </label>
                    <div class="flex gap-3">
                        <input type="text" name="confirm_delete" placeholder="ELIMINAR"
                               class="flex-1 px-4 py-3 border-2 border-red-200 bg-white rounded-xl text-sm font-medium focus:outline-none focus:border-red-400 transition">
                        <button type="submit"
                                class="flex items-center gap-2 px-5 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-bold transition shadow">
                            <i class="fa-solid fa-trash"></i> Eliminar
                        </button>
                    </div>
                    <?php $__errorArgs = ['confirm_delete'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-2"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </form>
            </div>
        </div>
    </div>
</div>

</div>


<div class="space-y-5">

    
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="h-1.5" style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)"></div>
        <div class="p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Resumen de cuenta</p>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-xs text-gray-400 flex items-center gap-2">
                        <i class="fa-solid fa-hashtag text-indigo-400 w-4 text-center"></i> ID
                    </span>
                    <span class="text-xs font-bold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-lg">#<?php echo e($user->id); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-xs text-gray-400 flex items-center gap-2">
                        <i class="fa-solid fa-circle-dot text-emerald-400 w-4 text-center"></i> Estado
                    </span>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                    </span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-xs text-gray-400 flex items-center gap-2">
                        <i class="fa-solid fa-calendar text-purple-400 w-4 text-center"></i> Registro
                    </span>
                    <span class="text-xs font-bold text-gray-700"><?php echo e($user->created_at->format('d/m/Y')); ?></span>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-xs text-gray-400 flex items-center gap-2">
                        <i class="fa-solid fa-pen text-sky-400 w-4 text-center"></i> Editado
                    </span>
                    <span class="text-xs font-bold text-gray-700"><?php echo e($user->updated_at->diffForHumans()); ?></span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm p-5" x-show="tab==='security'">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Consejos</p>
        <ul class="space-y-3">
            <?php $__currentLoopData = [
                ['fa-check-circle','text-emerald-500','Mínimo 8 caracteres'],
                ['fa-check-circle','text-emerald-500','Letras, números y símbolos'],
                ['fa-check-circle','text-emerald-500','No reutilices contraseñas'],
                ['fa-rotate','text-indigo-400','Cámbiala cada 3 meses'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $color, $tip]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="flex items-center gap-2.5 text-xs text-gray-500">
                <i class="fa-solid <?php echo e($icon); ?> <?php echo e($color); ?> flex-shrink-0"></i> <?php echo e($tip); ?>

            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm p-5" x-show="tab==='profile' || tab==='store'">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Accesos rápidos</p>
        <div class="space-y-2">
            <?php $__currentLoopData = [
                [route('admin.dashboard'),         'fa-gauge-high', 'Dashboard'],
                [route('admin.products.index'),    'fa-box',        'Productos'],
                [route('admin.categories.index'),  'fa-tags',       'Categorías'],
                [route('admin.users.index'),       'fa-users',      'Usuarios'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$url, $icon, $lbl]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($url); ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-500 hover:bg-gray-50 hover:text-indigo-600 transition group">
                <i class="fa-solid <?php echo e($icon); ?> w-4 text-center text-gray-300 group-hover:text-indigo-500 transition"></i>
                <?php echo e($lbl); ?>

                <i class="fa-solid fa-arrow-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition"></i>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

</div>
</div>
</div>

<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}
const np = document.getElementById('new_pass');
if (np) np.addEventListener('input', function() {
    const v = this.value, bars = [1,2,3,4].map(i => document.getElementById('sb'+i)), lbl = document.getElementById('slabel');
    let s = 0;
    if (v.length >= 8) s++; if (/[A-Z]/.test(v)) s++; if (/[0-9]/.test(v)) s++; if (/[^A-Za-z0-9]/.test(v)) s++;
    const c = ['#ef4444','#f97316','#eab308','#22c55e'], l = ['Muy débil','Débil','Buena','Fuerte'];
    bars.forEach((b,i) => b.style.background = i < s ? c[s-1] : '#f3f4f6');
    lbl.textContent = v.length ? l[s-1]??'Muy débil' : 'Ingresa una contraseña';
    lbl.style.color = v.length ? c[s-1] : '#9ca3af';
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/admin/settings.blade.php ENDPATH**/ ?>
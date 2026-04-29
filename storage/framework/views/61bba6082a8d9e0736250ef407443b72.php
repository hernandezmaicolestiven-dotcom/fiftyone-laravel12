<?php $__env->startSection('title', 'Usuarios'); ?>
<?php $__env->startSection('page-title', 'Usuarios'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation"></i> <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>


<div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background: linear-gradient(135deg, rgba(59,89,255,0.15), rgba(123,47,190,0.15))">
            <i class="fa-solid fa-users" style="background: linear-gradient(135deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400">Total usuarios</p>
            <p class="text-xl font-bold text-gray-800"><?php echo e($users->total()); ?></p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-user-check text-emerald-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400">Este mes</p>
            <p class="text-xl font-bold text-gray-800">
                <?php echo e(\App\Models\User::whereMonth('created_at', now()->month)->count()); ?>

            </p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 col-span-2 sm:col-span-1">
        <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-user-shield text-amber-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400">Administradores</p>
            <p class="text-xl font-bold text-gray-800"><?php echo e(\App\Models\User::count()); ?></p>
        </div>
    </div>
</div>


<div class="bg-white rounded-2xl shadow-sm overflow-hidden">

    
    <div class="px-6 py-5 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
            <div>
                <h3 class="text-base font-bold text-gray-800">Lista de usuarios</h3>
                <p class="text-xs text-gray-400 mt-0.5">
                    <?php echo e($users->total()); ?> usuario<?php echo e($users->total() !== 1 ? 's' : ''); ?> registrado<?php echo e($users->total() !== 1 ? 's' : ''); ?>

                    <?php if(request()->hasAny(['search','date_from','date_to'])): ?>
                        <span class="ml-1 text-indigo-500 font-medium">(filtrado)</span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="flex gap-2" x-data>
                
                <button @click="$dispatch('open-import-users')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">
                    <i class="fa-solid fa-arrow-down-to-bracket text-violet-500"></i>
                    <span class="hidden sm:inline">Importar CSV</span>
                </button>
                <a href="<?php echo e(route('admin.users.export.csv')); ?>"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition">
                    <i class="fa-solid fa-file-csv text-emerald-500"></i>
                    <span class="hidden sm:inline">Exportar CSV</span>
                </a>
                <a href="<?php echo e(route('admin.users.create')); ?>"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold whitespace-nowrap shadow-sm hover:opacity-90 transition"
                   style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                    <i class="fa-solid fa-plus"></i>
                    <span class="hidden sm:inline">Nuevo usuario</span>
                </a>
            </div>
        </div>

        
        <form method="GET" x-data="{ showDates: <?php echo e(request()->hasAny(['date_from','date_to']) ? 'true' : 'false'); ?> }">
            <div class="flex flex-wrap gap-2 items-center">

                
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                           placeholder="Buscar nombre o email..."
                           class="pl-8 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 w-52 bg-gray-50 focus:bg-white transition">
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

                
                <?php if(request()->hasAny(['search','date_from','date_to'])): ?>
                <a href="<?php echo e(route('admin.users.index')); ?>"
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
                        <i class="fa-regular fa-calendar-plus mr-1 text-indigo-400"></i>Registrado desde
                    </label>
                    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                           class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                        <i class="fa-regular fa-calendar-minus mr-1 text-indigo-400"></i>Registrado hasta
                    </label>
                    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                           class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white">
                </div>
                <?php if(request()->hasAny(['date_from','date_to'])): ?>
                <div class="flex items-end">
                    <p class="text-xs text-indigo-500 font-medium flex items-center gap-1">
                        <i class="fa-solid fa-circle-info"></i> Filtro de fecha activo
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

    
    <?php if(request()->hasAny(['search','date_from','date_to'])): ?>
    <div class="px-6 py-2.5 bg-indigo-50/60 border-b border-indigo-100 flex flex-wrap gap-2 items-center">
        <span class="text-xs text-indigo-400 font-medium">Filtros:</span>
        <?php if(request('search')): ?>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-indigo-200 text-xs text-indigo-700 font-medium">
                <i class="fa-solid fa-magnifying-glass text-indigo-400"></i> <?php echo e(request('search')); ?>

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

    
    <div x-data="{ show: false }" @open-import-users.window="show = true">
        <div x-show="show" x-transition.opacity
             class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/50 backdrop-blur-sm p-4"
             @click.self="show = false">
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">

                
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between"
                     style="background: linear-gradient(135deg, #f8f7ff, #fff)">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                             style="background: linear-gradient(135deg, rgba(59,89,255,0.12), rgba(123,47,190,0.12))">
                            <i class="fa-solid fa-users" style="background: linear-gradient(135deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Importar usuarios</h3>
                            <p class="text-xs text-gray-400">Archivo CSV</p>
                        </div>
                    </div>
                    <button @click="show = false"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                
                <form method="POST" action="<?php echo e(route('admin.users.import.csv')); ?>" enctype="multipart/form-data" class="p-6">
                    <?php echo csrf_field(); ?>

                    
                    <div class="mb-5 p-4 rounded-xl bg-indigo-50 border border-indigo-100">
                        <p class="text-xs font-semibold text-indigo-700 mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info"></i> Formato esperado
                        </p>
                        <div class="overflow-x-auto">
                            <table class="text-xs w-full text-indigo-600">
                                <thead>
                                    <tr class="border-b border-indigo-200">
                                        <th class="pb-1 pr-4 font-semibold text-left">Nombre</th>
                                        <th class="pb-1 pr-4 font-semibold text-left">Email</th>
                                        <th class="pb-1 font-semibold text-left">Contraseña <span class="font-normal text-indigo-400">(opcional)</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-indigo-400">
                                        <td class="pt-1 pr-4">Juan García</td>
                                        <td class="pt-1 pr-4">juan@email.com</td>
                                        <td class="pt-1">miClave123</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-xs text-indigo-400 mt-2">La primera fila se omite. Sin contraseña se usa <code class="bg-indigo-100 px-1 rounded">password123</code>.</p>
                    </div>

                    
                    <label for="importUsersFile"
                           class="flex flex-col items-center justify-center gap-3 w-full h-36 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/50 transition group">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 group-hover:bg-indigo-100 flex items-center justify-center transition">
                            <i class="fa-solid fa-cloud-arrow-up text-gray-400 group-hover:text-indigo-500 text-xl transition"></i>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-600 group-hover:text-indigo-600 transition">
                                Arrastra tu archivo o <span class="underline">haz clic aquí</span>
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">.csv — máx. 2 MB</p>
                        </div>
                        <input id="importUsersFile" type="file" name="file" accept=".csv,.txt" required class="hidden"
                               onchange="document.getElementById('importUsersFileName').textContent = this.files[0]?.name ?? ''">
                    </label>
                    <p id="importUsersFileName" class="text-xs text-indigo-600 text-center mt-2 font-medium"></p>

                    
                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="show = false"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition font-medium">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 rounded-xl text-white text-sm font-semibold shadow-sm hover:opacity-90 transition"
                                style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                            <i class="fa-solid fa-upload mr-1.5"></i> Importar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wide">
                    <th class="px-6 py-3 text-left font-semibold">Usuario</th>
                    <th class="px-6 py-3 text-left font-semibold hidden md:table-cell">Email</th>
                    <th class="px-6 py-3 text-left font-semibold hidden lg:table-cell">Registrado</th>
                    <th class="px-6 py-3 text-right font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50/70 transition group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm"
                                 style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 flex items-center gap-2">
                                    <?php echo e($user->name); ?>

                                    <?php if($user->id === auth()->id()): ?>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                              style="background: linear-gradient(135deg, rgba(59,89,255,0.1), rgba(123,47,190,0.1)); color: #3B59FF">
                                            Tú
                                        </span>
                                    <?php endif; ?>
                                </p>
                                <p class="text-xs text-gray-400 md:hidden"><?php echo e($user->email); ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 hidden md:table-cell"><?php echo e($user->email); ?></td>
                    <td class="px-6 py-4 hidden lg:table-cell">
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fa-regular fa-calendar"></i>
                            <?php echo e($user->created_at->format('d/m/Y')); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 text-xs font-medium transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span class="hidden sm:inline">Editar</span>
                            </a>
                            <?php if($user->id !== auth()->id()): ?>
                            <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>"
                                  onsubmit="return confirm('¿Seguro que quieres eliminar a <?php echo e($user->name); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-400 hover:border-red-300 hover:text-red-500 hover:bg-red-50 text-xs font-medium transition">
                                    <i class="fa-solid fa-trash"></i>
                                    <span class="hidden sm:inline">Eliminar</span>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center"
                             style="background: linear-gradient(135deg, rgba(59,89,255,0.1), rgba(123,47,190,0.1))">
                            <i class="fa-solid fa-users text-2xl" style="background: linear-gradient(135deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        </div>
                        <p class="text-gray-500 font-medium">No hay usuarios registrados</p>
                        <p class="text-gray-400 text-sm mt-1">Crea el primer usuario con el botón de arriba.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($users->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-100">
        <?php echo e($users->links('vendor.pagination.tailwind')); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\admin\users\index.blade.php ENDPATH**/ ?>
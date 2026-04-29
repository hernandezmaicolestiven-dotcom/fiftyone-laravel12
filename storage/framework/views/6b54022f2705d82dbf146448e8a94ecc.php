<div>
    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
        Nombre <span class="text-red-400">*</span>
    </label>
    <div class="relative group">
        <span class="absolute inset-y-0 left-4 flex items-center text-gray-300 group-focus-within:text-indigo-500 transition">
            <i class="fa-solid fa-tag text-sm"></i>
        </span>
        <input type="text" name="name" value="<?php echo e(old('name', $category->name ?? '')); ?>" required
               placeholder="Ej: Ropa Deportiva"
               class="w-full pl-11 pr-4 py-3.5 border-2 rounded-xl text-sm font-medium focus:outline-none transition
                      <?php echo e($errors->has('name') ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50 focus:border-indigo-400 focus:bg-white'); ?>">
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

<div>
    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
        Descripción <span class="text-gray-300 font-normal normal-case tracking-normal">— opcional</span>
    </label>
    <div class="relative group">
        <span class="absolute top-3.5 left-4 text-gray-300 group-focus-within:text-indigo-500 transition">
            <i class="fa-solid fa-align-left text-sm"></i>
        </span>
        <textarea name="description" rows="4"
                  placeholder="Describe brevemente esta categoría..."
                  class="w-full pl-11 pr-4 py-3.5 border-2 rounded-xl text-sm font-medium focus:outline-none transition resize-none
                         border-gray-100 bg-gray-50 focus:border-indigo-400 focus:bg-white"><?php echo e(old('description', $category->description ?? '')); ?></textarea>
    </div>
    <?php $__errorArgs = ['description'];
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
<?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\admin\categories\_form.blade.php ENDPATH**/ ?>
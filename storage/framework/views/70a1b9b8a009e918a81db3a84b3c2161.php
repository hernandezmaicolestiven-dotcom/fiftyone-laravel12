

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
    <input type="text" name="name" value="<?php echo e(old('name', $product->name ?? '')); ?>" required
           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
           placeholder="Ej: Camiseta Dry-Fit Pro">
    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
    <textarea name="description" rows="3"
              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none"
              placeholder="Descripción del producto..."><?php echo e(old('description', $product->description ?? '')); ?></textarea>
    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Precio <span class="text-red-500">*</span></label>
        <div class="relative">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">$</span>
            <input type="number" name="price" value="<?php echo e(old('price', $product->price ?? '')); ?>"
                   step="0.01" min="0" required
                   class="w-full pl-7 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="0.00">
        </div>
        <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Stock <span class="text-red-500">*</span></label>
        <input type="number" name="stock" value="<?php echo e(old('stock', $product->stock ?? 0)); ?>"
               min="0" required
               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 <?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
    <select name="category_id"
            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        <option value="">Sin categoría</option>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($cat->id); ?>"
                <?php echo e(old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : ''); ?>>
                <?php echo e($cat->name); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Tallas disponibles</label>
    <div class="flex flex-wrap gap-2">
        <?php $__currentLoopData = ['XS','S','M','L','XL','XXL','Talla única']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $selected = in_array($size, old('sizes', isset($product) ? ($product->sizes ?? []) : [])); ?>
        <label class="flex items-center gap-1.5 cursor-pointer">
            <input type="checkbox" name="sizes[]" value="<?php echo e($size); ?>" <?php echo e($selected ? 'checked' : ''); ?>

                   class="rounded border-gray-300 text-indigo-600">
            <span class="text-sm text-gray-700"><?php echo e($size); ?></span>
        </label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Colores disponibles <span class="text-gray-400 text-xs font-normal">(separados por coma)</span></label>
    <input type="text" name="colors_input" value="<?php echo e(old('colors_input', isset($product) ? implode(', ', $product->colors ?? []) : '')); ?>"
           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
           placeholder="Negro, Blanco, Gris, Azul Marino">
</div>

<div x-data="{ preview: '<?php echo e(isset($product) && $product->image ? (str_starts_with($product->image, "http") ? $product->image : Storage::url($product->image)) : ""); ?>' }">
    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>

    <div x-show="preview" class="mb-3">
        <img :src="preview" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
    </div>

    <label class="flex items-center gap-3 cursor-pointer border-2 border-dashed border-gray-200 hover:border-indigo-400 rounded-lg p-4 transition">
        <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-xl"></i>
        <span class="text-sm text-gray-500">Haz clic para subir una imagen (JPG, PNG, WEBP — máx. 2MB)</span>
        <input type="file" name="image" accept="image/*" class="hidden"
               @change="preview = URL.createObjectURL($event.target.files[0])">
    </label>
    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\admin\products\_form.blade.php ENDPATH**/ ?>
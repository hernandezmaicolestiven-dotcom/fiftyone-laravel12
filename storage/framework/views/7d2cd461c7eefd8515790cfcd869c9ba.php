
<?php $__env->startSection('title','Configuracion de la tienda'); ?>
<?php $__env->startSection('page-title','Configuracion de la tienda'); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('admin.store-settings.update')); ?>">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
                    <i class="fa-solid fa-store text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-800">Informacion general</h2>
                    <p class="text-xs text-gray-400">Datos de la tienda</p>
                </div>
            </div>
            <div class="space-y-4">
                <?php $__currentLoopData = [
                    ['store_name',      'Nombre de la tienda',  'text',  'FiftyOne',              'fa-store'],
                    ['store_phone',     'Telefono',             'tel',   '3118422192',            'fa-phone'],
                    ['store_email',     'Email de contacto',    'email', 'contacto@fiftyone.com', 'fa-envelope'],
                    ['store_address',   'Direccion',            'text',  'Medellin, Colombia',    'fa-location-dot'],
                    ['store_instagram', 'Instagram',            'text',  '@fiftyone.co',          'fa-instagram'],
                    ['store_whatsapp',  'WhatsApp (con codigo)', 'text', '573118422192',          'fa-whatsapp'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $type, $placeholder, $icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1"><?php echo e($label); ?></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="fa-brands <?php echo e($icon); ?> text-sm" style="<?php echo e(in_array($icon,['fa-instagram','fa-whatsapp']) ? '' : 'display:none'); ?>"></i>
                            <i class="fa-solid <?php echo e($icon); ?> text-sm" style="<?php echo e(!in_array($icon,['fa-instagram','fa-whatsapp']) ? '' : 'display:none'); ?>"></i>
                        </span>
                        <input type="<?php echo e($type); ?>" name="<?php echo e($key); ?>"
                               value="<?php echo e($settings[$key] ?? ''); ?>"
                               placeholder="<?php echo e($placeholder); ?>"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#059669,#10b981)">
                    <i class="fa-solid fa-truck text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-800">Configuracion de envios</h2>
                    <p class="text-xs text-gray-400">Precios y condiciones</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Precio de envio (COP)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">$</span>
                        <input type="number" name="shipping_price"
                               value="<?php echo e($settings['shipping_price'] ?? 15000); ?>"
                               class="w-full pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Costo de envio estandar</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Envio gratis desde (COP)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">$</span>
                        <input type="number" name="free_shipping_min"
                               value="<?php echo e($settings['free_shipping_min'] ?? 200000); ?>"
                               class="w-full pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Compras mayores a este valor tienen envio gratis</p>
                </div>
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200">
                    <p class="text-xs text-emerald-700 font-semibold">
                        <i class="fa-solid fa-circle-check mr-1"></i>
                        El banner del home se actualiza automaticamente con estos valores
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button type="submit"
                class="px-8 py-3 rounded-2xl text-white font-bold text-sm transition hover:opacity-90"
                style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);box-shadow:0 8px 25px rgba(59,89,255,.3)">
            <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar configuracion
        </button>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/admin/store-settings/index.blade.php ENDPATH**/ ?>
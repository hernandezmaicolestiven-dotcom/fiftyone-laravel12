<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña — FiftyOne Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-900 via-indigo-950 to-gray-900 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md px-6">
    <div class="bg-white rounded-2xl shadow-2xl p-8">

        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
                 style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)">
                <i class="fa-solid fa-lock-open text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Nueva contraseña</h1>
            <p class="text-gray-500 text-sm mt-1">Elige una contraseña segura</p>
        </div>

        
        <?php if($errors->any()): ?>
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.password.update')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="token" value="<?php echo e($token); ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-envelope text-sm"></i>
                    </span>
                    <input type="email" name="email" value="<?php echo e(old('email', $email)); ?>" required
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="admin@fiftyone.com">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" name="password" required
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Mínimo 8 caracteres">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" name="password_confirmation" required
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Repite la contraseña">
                </div>
            </div>

            <button type="submit"
                    class="w-full text-white font-semibold py-2.5 rounded-xl transition text-sm hover:opacity-90"
                    style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                <i class="fa-solid fa-check mr-2"></i> Restablecer contraseña
            </button>
        </form>
    </div>
</div>

</body>
</html>
<?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\admin\auth\reset-password.blade.php ENDPATH**/ ?>
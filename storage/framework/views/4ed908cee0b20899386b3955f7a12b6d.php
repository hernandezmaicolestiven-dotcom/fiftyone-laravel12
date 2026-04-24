<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> — FiftyOne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-white text-gray-900">


<nav class="bg-black border-b border-white/10 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-shirt text-white text-sm"></i>
            </div>
            <span class="text-white font-black text-xl">Fifty<span class="text-indigo-400">One</span></span>
        </a>
        <a href="/" class="text-sm text-gray-400 hover:text-white transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i> Volver a la tienda
        </a>
    </div>
</nav>


<main class="max-w-4xl mx-auto px-4 py-16">
    <?php echo $__env->yieldContent('content'); ?>
</main>


<footer class="bg-black text-gray-500 py-8 mt-16">
    <div class="max-w-7xl mx-auto px-4 text-center text-xs">
        © 2026 FiftyOne. Todos los derechos reservados.
    </div>
</footer>

</body>
</html>
<?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/pages/layout.blade.php ENDPATH**/ ?>
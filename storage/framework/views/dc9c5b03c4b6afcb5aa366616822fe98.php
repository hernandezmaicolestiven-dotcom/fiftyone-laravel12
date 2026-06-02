<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo FiftyOne - v2.0</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @keyframes slideIn { from{opacity:0;transform:translateX(100px)} to{opacity:1;transform:translateX(0)} }
        @keyframes fadeOut { from{opacity:1} to{opacity:0} }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        html { scroll-behavior: smooth; }
        #productos { scroll-margin-top: 100px; }
        
        /* Animación de entrada para productos */
        .product-card {
            animation: fadeInUp 0.4s ease-out forwards;
            opacity: 0;
        }
        .product-card:nth-child(1) { animation-delay: 0.05s; }
        .product-card:nth-child(2) { animation-delay: 0.1s; }
        .product-card:nth-child(3) { animation-delay: 0.15s; }
        .product-card:nth-child(4) { animation-delay: 0.2s; }
        .product-card:nth-child(5) { animation-delay: 0.25s; }
        .product-card:nth-child(6) { animation-delay: 0.3s; }
        .product-card:nth-child(7) { animation-delay: 0.35s; }
        .product-card:nth-child(8) { animation-delay: 0.4s; }
        .product-card:nth-child(9) { animation-delay: 0.45s; }
        .product-card:nth-child(10) { animation-delay: 0.5s; }
        .product-card:nth-child(11) { animation-delay: 0.55s; }
        .product-card:nth-child(12) { animation-delay: 0.6s; }
    </style>
    
    <!-- Script de scroll ANTES de renderizar -->
    <script>
        // Detectar si hay paginación y preparar el scroll
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('page') || window.location.hash === '#productos') {
                // Guardar que necesitamos hacer scroll
                sessionStorage.setItem('scrollToProducts', 'true');
            }
        })();
    </script>
</head>
<body class="bg-gray-50" style="font-family: 'Inter', sans-serif;">

<!-- ✅ VERSIÓN FUNCIONAL SIN REACT - CARGADA CORRECTAMENTE -->

<!-- Navbar -->
<nav class="fixed top-0 left-0 right-0 z-50 bg-black border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center">
                    <i class="fa-solid fa-shirt text-white text-sm"></i>
                </div>
                <span class="text-white font-black text-xl">FiftyOne</span>
            </a>
            <a href="/" class="text-gray-300 hover:text-white text-sm">← Volver</a>
        </div>
    </div>
</nav>

<!-- Contenido -->
<div class="pt-24 pb-16 max-w-7xl mx-auto px-4">
    
    <!-- Header -->
    <div class="mb-8">
        <span class="text-sm font-bold uppercase text-purple-600">Catálogo</span>
        <h1 class="text-4xl font-black text-gray-900 mt-1">Todos los productos</h1>
        <p class="text-gray-500 mt-2"><?php echo e($products->total()); ?> productos disponibles</p>
    </div>

    <!-- Filtros -->
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="/catalogo" class="px-4 py-2 rounded-full text-sm font-semibold <?php echo e(!request('category') ? 'bg-purple-600 text-white' : 'bg-white text-gray-600 border'); ?>">
            Todos
        </a>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="/catalogo?category=<?php echo e($cat->id); ?>" class="px-4 py-2 rounded-full text-sm font-semibold <?php echo e(request('category') == $cat->id ? 'bg-purple-600 text-white' : 'bg-white text-gray-600 border'); ?>">
            <?php echo e($cat->name); ?>

        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Grid de productos -->
    <div id="productos" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm border hover:shadow-lg transition-all">
            <div class="relative aspect-square bg-gray-100">
                <img src="<?php echo e($product->image ? (str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image)) : 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400'); ?>" 
                     alt="<?php echo e($product->name); ?>"
                     class="w-full h-full object-cover">
                <?php if($product->stock < 5): ?>
                <span class="absolute top-3 left-3 text-xs font-bold px-2 py-1 rounded-full bg-red-500 text-white">Oferta</span>
                <?php endif; ?>
            </div>
            <div class="p-4">
                <?php if($product->category): ?>
                <span class="text-xs font-medium text-purple-600"><?php echo e($product->category->name); ?></span>
                <?php endif; ?>
                <h3 class="font-semibold text-gray-900 mt-1 text-sm"><?php echo e($product->name); ?></h3>
                <p class="text-lg font-black mt-2 text-purple-600">
                    $<?php echo e(number_format($product->price, 0, ',', '.')); ?>

                </p>
                <button onclick="agregarAlCarrito(<?php echo e($product->id); ?>, '<?php echo e($product->name); ?>', <?php echo e($product->price); ?>)" 
                        class="mt-3 w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-cart-plus mr-1"></i> Agregar
                </button>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    
    <!-- Script inline para scroll inmediato después del grid -->
    <script>
        if (sessionStorage.getItem('scrollToProducts') === 'true') {
            sessionStorage.removeItem('scrollToProducts');
            const productos = document.getElementById('productos');
            if (productos) {
                const yOffset = -90;
                const y = productos.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo(0, y);
            }
        }
    </script>

    <!-- Paginación -->
    <?php if($products->hasPages()): ?>
    <div class="mt-12 flex justify-center">
        <div class="flex items-center gap-2">
            
            <?php if($products->onFirstPage()): ?>
                <span class="w-10 h-10 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </span>
            <?php else: ?>
                <a href="<?php echo e($products->previousPageUrl()); ?>#productos" class="w-10 h-10 rounded-lg bg-white border hover:bg-purple-50 hover:border-purple-600 text-gray-700 flex items-center justify-center transition">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </a>
            <?php endif; ?>

            
            <?php $__currentLoopData = $products->getUrlRange(1, $products->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page == $products->currentPage()): ?>
                    <span class="w-10 h-10 rounded-lg bg-purple-600 text-white font-bold flex items-center justify-center">
                        <?php echo e($page); ?>

                    </span>
                <?php else: ?>
                    <a href="<?php echo e($url); ?>#productos" class="w-10 h-10 rounded-lg bg-white border hover:bg-purple-50 hover:border-purple-600 text-gray-700 font-semibold flex items-center justify-center transition">
                        <?php echo e($page); ?>

                    </a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($products->hasMorePages()): ?>
                <a href="<?php echo e($products->nextPageUrl()); ?>#productos" class="w-10 h-10 rounded-lg bg-white border hover:bg-purple-50 hover:border-purple-600 text-gray-700 flex items-center justify-center transition">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </a>
            <?php else: ?>
                <span class="w-10 h-10 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- Botón flotante del carrito -->
<button onclick="verCarrito()" class="fixed bottom-6 right-6 w-14 h-14 bg-purple-600 hover:bg-purple-700 text-white rounded-full shadow-lg flex items-center justify-center z-50">
    <i class="fa-solid fa-shopping-cart"></i>
    <span id="cart-count" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full text-xs font-bold flex items-center justify-center hidden">0</span>
</button>

<script>
// Carrito simple
let cart = JSON.parse(localStorage.getItem('fiftyone_cart') || '[]');

function agregarAlCarrito(id, name, price) {
    const existing = cart.find(i => i.id === id);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({ id, name, price, qty: 1, cartKey: id + '-nosize' });
    }
    localStorage.setItem('fiftyone_cart', JSON.stringify(cart));
    actualizarContador();
    mostrarToast(name);
}

function mostrarToast(productName) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-20 right-4 z-[9999]';
    toast.style.animation = 'slideIn 0.3s ease-out';
    toast.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl p-4 flex items-center gap-3 border border-gray-200 min-w-[300px] max-w-md">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
                <i class="fa-solid fa-check text-white text-lg"></i>
            </div>
            <div class="flex-1">
                <p class="font-bold text-gray-900 text-sm">¡Agregado al carrito!</p>
                <p class="text-xs text-gray-600 mt-0.5">${productName}</p>
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function verCarrito() {
    if (cart.length === 0) {
        alert('El carrito está vacío');
        return;
    }
    window.location.href = '/?checkout=1';
}

function actualizarContador() {
    const count = cart.reduce((s, i) => s + i.qty, 0);
    const badge = document.getElementById('cart-count');
    if (count > 0) {
        badge.textContent = count;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

actualizarContador();
</script>

</body>
</html>
<?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views/catalogo.blade.php ENDPATH**/ ?>
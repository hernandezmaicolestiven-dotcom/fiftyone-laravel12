<?php
use Illuminate\Support\Facades\Storage;

// Obtener productos reales de la base de datos
$productosDisponibles = \App\Models\Product::latest()->take(9)->get();

// Asegurar que tengamos al menos 9 productos
if ($productosDisponibles->count() < 9) {
    $productosDisponibles = \App\Models\Product::inRandomOrder()->take(9)->get();
}

// Crear 3 looks con productos reales
$looks = [
    [
        'id' => 1,
        'nombre' => 'Look Urbano',
        'descripcion' => 'Perfecto para la ciudad',
        'estilo' => 'Streetwear',
        'ocasion' => 'Casual',
        'imagen' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&q=80',
        'color' => 'from-purple-900 via-purple-800 to-indigo-900',
        'productos' => $productosDisponibles->slice(0, 3)
    ],
    [
        'id' => 2,
        'nombre' => 'Look Casual',
        'descripcion' => 'Comodidad todo el día',
        'estilo' => 'Relajado',
        'ocasion' => 'Diario',
        'imagen' => 'https://images.unsplash.com/photo-1490114538077-0a7f8cb49891?w=800&q=80',
        'color' => 'from-blue-900 via-indigo-800 to-purple-900',
        'productos' => $productosDisponibles->slice(3, 3)
    ],
    [
        'id' => 3,
        'nombre' => 'Look Premium',
        'descripcion' => 'Elegancia oversize',
        'estilo' => 'Sofisticado',
        'ocasion' => 'Especial',
        'imagen' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800&q=80',
        'color' => 'from-pink-900 via-purple-800 to-indigo-900',
        'productos' => $productosDisponibles->slice(6, 3)
    ]
];

// Fecha y hora actual
$ahora = now()->locale('es');
$fecha = $ahora->isoFormat('dddd, D [de] MMMM [de] YYYY');
$hora = $ahora->format('h:i A');
?>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}
@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}
.look-card {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.look-card:hover {
    transform: translateY(-16px) scale(1.02);
}
.look-image {
    transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}
.look-card:hover .look-image {
    transform: scale(1.15) rotate(2deg);
}
.shimmer {
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
}
</style>

<section id="inspiracion" class="relative py-24 overflow-hidden" style="background: linear-gradient(135deg, #0a0e27 0%, #1a1a2e 50%, #16213e 100%);">
    
    <div class="absolute inset-0 opacity-30">
        <div class="absolute top-20 left-10 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-20 left-1/2 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full mb-6 backdrop-blur-xl" style="background: rgba(59, 89, 255, 0.15); border: 1px solid rgba(59, 89, 255, 0.3);">
                <i class="fa-solid fa-sparkles text-yellow-400 text-lg"></i>
                <span class="text-white font-bold uppercase tracking-widest text-sm">Inspiración del día</span>
            </div>
            
            <h2 class="text-6xl md:text-7xl font-black text-white mb-4" style="text-shadow: 0 0 40px rgba(59, 89, 255, 0.5);">
                Looks del día
            </h2>
            
            <div class="flex items-center justify-center gap-6 text-gray-300">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-day text-purple-400"></i>
                    <span class="capitalize font-medium"><?php echo e($fecha); ?></span>
                </div>
                <div class="w-1 h-1 rounded-full bg-purple-400"></div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-clock text-blue-400"></i>
                    <span class="font-medium"><?php echo e($hora); ?></span>
                </div>
            </div>
        </div>

        
        <div class="grid md:grid-cols-3 gap-8">
            <?php $__currentLoopData = $looks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $look): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $total = $look['productos']->sum('price');
            ?>
            
            <div class="look-card group relative">
                
                <div class="relative rounded-3xl overflow-hidden shadow-2xl" style="background: linear-gradient(135deg, rgba(15, 15, 35, 0.95), rgba(25, 25, 45, 0.95));">
                    
                    
                    <div class="relative h-64 overflow-hidden">
                        <img src="<?php echo e($look['imagen']); ?>" 
                             alt="<?php echo e($look['nombre']); ?>" 
                             class="look-image w-full h-full object-cover">
                        
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                        
                        
                        <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
                            <span class="px-4 py-2 rounded-full text-white text-xs font-bold uppercase tracking-wider backdrop-blur-xl" 
                                  style="background: rgba(59, 89, 255, 0.9); box-shadow: 0 8px 32px rgba(59, 89, 255, 0.4);">
                                <?php echo e($look['estilo']); ?>

                            </span>
                            <div class="w-10 h-10 rounded-full backdrop-blur-xl flex items-center justify-center" 
                                 style="background: rgba(255, 255, 255, 0.1);">
                                <i class="fa-solid fa-heart text-white text-sm"></i>
                            </div>
                        </div>

                        
                        <div class="absolute bottom-4 left-4 right-4">
                            <h3 class="text-2xl font-black text-white mb-1"><?php echo e($look['nombre']); ?></h3>
                            <p class="text-sm text-gray-300"><?php echo e($look['descripcion']); ?></p>
                        </div>
                    </div>

                    
                    <div class="p-6">
                        
                        <div class="flex items-center gap-2 mb-5">
                            <i class="fa-solid fa-tag text-purple-400 text-sm"></i>
                            <span class="text-sm text-gray-400">Para: <span class="text-white font-semibold"><?php echo e($look['ocasion']); ?></span></span>
                        </div>

                        
                        <div class="space-y-3 mb-6">
                            <?php $__currentLoopData = $look['productos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $imagen = $producto->image 
                                    ? (str_starts_with($producto->image, 'http') 
                                        ? $producto->image 
                                        : Storage::url($producto->image)) 
                                    : 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80';
                            ?>
                            
                            <div class="flex items-center gap-3 p-3 rounded-xl transition-all hover:bg-white/5" style="background: rgba(255, 255, 255, 0.02);">
                                
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 font-bold text-sm" 
                                     style="background: linear-gradient(135deg, #3B59FF, #7B2FBE);">
                                    <?php echo e($index + 1); ?>

                                </div>
                                
                                
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-white truncate"><?php echo e($producto->name); ?></p>
                                    <p class="text-xs text-purple-400 font-bold">$ <?php echo e(number_format($producto->price, 0, ',', '.')); ?></p>
                                </div>
                                
                                
                                <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 ring-2 ring-purple-500/30">
                                    <img src="<?php echo e($imagen); ?>" 
                                         class="w-full h-full object-cover" 
                                         alt="<?php echo e($producto->name); ?>">
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        
                        <div class="relative h-px mb-6 shimmer" style="background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.5), transparent);"></div>

                        
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total del look</p>
                                <p class="text-3xl font-black text-white">$ <?php echo e(number_format($total, 0, ',', '.')); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400 mb-1">Ahorro</p>
                                <p class="text-lg font-bold text-green-400">-15%</p>
                            </div>
                        </div>

                        
                        <button onclick="agregarLookCompleto(<?php echo e($look['id']); ?>)" 
                                class="w-full py-4 px-6 rounded-xl font-bold text-white text-sm uppercase tracking-wider transition-all hover:scale-105 hover:shadow-2xl relative overflow-hidden group"
                                style="background: linear-gradient(135deg, #3B59FF, #7B2FBE);">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-bag-shopping"></i>
                                Agregar look completo
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="text-center mt-16">
            <p class="text-gray-400 text-lg">
                <i class="fa-solid fa-lightbulb text-yellow-400 mr-2"></i>
                Combina estos looks y crea tu propio estilo único
            </p>
        </div>
    </div>
</section>

<script>
// Datos de productos por look
const looksProductos = {
    <?php $__currentLoopData = $looks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $look): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo e($look['id']); ?>: [
        <?php $__currentLoopData = $look['productos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        {
            id: <?php echo e($p->id); ?>,
            name: "<?php echo e(addslashes($p->name)); ?>",
            price: <?php echo e($p->price); ?>

        },
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ],
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
};

// Función para agregar look completo al carrito
function agregarLookCompleto(lookId) {
    const productos = looksProductos[lookId];
    if (!productos) return;
    
    let carrito = JSON.parse(localStorage.getItem('fiftyone_cart') || '[]');
    let agregados = 0;
    
    productos.forEach(function(prod) {
        const productoCompleto = window.__PRODUCTS__ ? window.__PRODUCTS__.find(function(p) { return p.id === prod.id; }) : null;
        
        if (productoCompleto) {
            const existe = carrito.find(function(item) { return item.id === prod.id; });
            
            if (existe) {
                existe.qty = (existe.qty || 1) + 1;
            } else {
                carrito.push({
                    id: productoCompleto.id,
                    name: productoCompleto.name,
                    price: productoCompleto.price,
                    img: productoCompleto.img,
                    qty: 1,
                    size: productoCompleto.sizes && productoCompleto.sizes[0] ? productoCompleto.sizes[0] : 'M'
                });
            }
            agregados++;
        }
    });
    
    if (agregados > 0) {
        localStorage.setItem('fiftyone_cart', JSON.stringify(carrito));
        window.dispatchEvent(new Event('storage'));
        
        // Notificación moderna
        mostrarNotificacion(agregados);
    }
}

function mostrarNotificacion(cantidad) {
    const notif = document.createElement('div');
    notif.className = 'fixed top-24 right-4 z-[9999] px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-xl animate-bounce';
    notif.style.background = 'linear-gradient(135deg, rgba(59, 89, 255, 0.95), rgba(123, 47, 190, 0.95))';
    notif.style.border = '1px solid rgba(255, 255, 255, 0.2)';
    notif.innerHTML = `
        <div class="flex items-center gap-4 text-white">
            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-check text-2xl"></i>
            </div>
            <div>
                <p class="font-black text-lg">¡Look agregado!</p>
                <p class="text-sm opacity-90">${cantidad} productos en tu carrito</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(notif);
    
    setTimeout(function() {
        notif.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(function() { notif.remove(); }, 300);
    }, 3000);
}
</script>
<?php /**PATH /var/www/html/resources/views/partials/looks-inspiracion.blade.php ENDPATH**/ ?>
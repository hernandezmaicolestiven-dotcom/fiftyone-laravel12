<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta — FiftyOne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family:'Inter',sans-serif; }
        .dark-bg { background:linear-gradient(135deg,#000 0%,#0d0d0d 40%,#0a0e2e 70%,#1a0a2e 100%); }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        .float { animation:float 3.5s ease-in-out infinite; }
    </style>
    <script>
    var loaded = Date.now();
    window.addEventListener('focus', function() {
        if (Date.now() - loaded > 300000) { window.location.reload(); }
    });
    </script>
</head>
<body class="min-h-screen flex">


<div class="hidden lg:flex w-1/2 dark-bg flex-col items-center justify-center p-16 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full blur-3xl" style="background:rgba(59,89,255,.15)"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full blur-3xl" style="background:rgba(123,47,190,.15)"></div>
    </div>
    <div class="relative z-10 text-center">
        <div class="float inline-flex items-center justify-center w-24 h-24 rounded-3xl mb-8 shadow-2xl"
             style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
            <i class="fa-solid fa-shirt text-white text-4xl"></i>
        </div>
        <h1 class="text-5xl font-black text-white mb-4">Fifty<span style="background:linear-gradient(90deg,#3B59FF,#a78bfa);-webkit-background-clip:text;-webkit-text-fill-color:transparent">One</span></h1>
        <p class="text-gray-400 text-lg max-w-xs mx-auto leading-relaxed mb-10">Únete a la comunidad oversize más grande de Colombia.</p>
        <div class="flex justify-center gap-8">
            <div class="text-center"><p class="text-2xl font-black text-white">25+</p><p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Productos</p></div>
            <div class="text-center"><p class="text-2xl font-black text-white">500+</p><p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Clientes</p></div>
            <div class="text-center"><p class="text-2xl font-black text-white">4.9★</p><p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Valoración</p></div>
        </div>
    </div>
</div>


<div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 p-6 overflow-y-auto">
    <div class="w-full max-w-md py-6">

        
        <div class="lg:hidden text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-3"
                 style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
                <i class="fa-solid fa-shirt text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-black text-gray-900">Fifty<span class="text-indigo-600">One</span></h1>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            <div class="mb-7">
                <h2 class="text-2xl font-black text-gray-900">Crea tu cuenta</h2>
                <p class="text-gray-400 text-sm mt-1">Regístrate para hacer tus pedidos</p>
            </div>

            <?php if($errors->any()): ?>
            <div class="mb-5 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo e($errors->first()); ?>

            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('customer.register.post')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Nombre completo</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-300"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                               class="w-full pl-11 pr-4 py-3 border-2 border-gray-100 rounded-2xl text-sm focus:outline-none focus:border-indigo-400 bg-gray-50 focus:bg-white transition font-medium"
                               placeholder="Tu nombre completo">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-300"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                               class="w-full pl-11 pr-4 py-3 border-2 border-gray-100 rounded-2xl text-sm focus:outline-none focus:border-indigo-400 bg-gray-50 focus:bg-white transition font-medium"
                               placeholder="tu@email.com">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Teléfono <span class="text-gray-300 font-normal normal-case">(opcional)</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-300"><i class="fa-solid fa-phone"></i></span>
                        <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>"
                               class="w-full pl-11 pr-4 py-3 border-2 border-gray-100 rounded-2xl text-sm focus:outline-none focus:border-indigo-400 bg-gray-50 focus:bg-white transition font-medium"
                               placeholder="300 000 0000">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Contraseña</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-300"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" id="reg_password" required
                               class="w-full pl-11 pr-12 py-3 border-2 border-gray-100 rounded-2xl text-sm focus:outline-none focus:border-indigo-400 bg-gray-50 focus:bg-white transition font-medium"
                               placeholder="Mínimo 8 caracteres">
                        <button type="button" onclick="togglePass('reg_password','eye1')"
                                class="absolute inset-y-0 right-4 flex items-center text-gray-300 hover:text-indigo-500 transition">
                            <i id="eye1" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Confirmar contraseña</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-300"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password_confirmation" id="reg_confirm" required
                               class="w-full pl-11 pr-12 py-3 border-2 border-gray-100 rounded-2xl text-sm focus:outline-none focus:border-indigo-400 bg-gray-50 focus:bg-white transition font-medium"
                               placeholder="Repite la contraseña">
                        <button type="button" onclick="togglePass('reg_confirm','eye2')"
                                class="absolute inset-y-0 right-4 flex items-center text-gray-300 hover:text-indigo-500 transition">
                            <i id="eye2" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit"
                        class="w-full py-4 rounded-2xl text-white font-black text-sm transition hover:opacity-90 flex items-center justify-center gap-2"
                        style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);box-shadow:0 8px 30px rgba(59,89,255,.35)">
                    <i class="fa-solid fa-user-plus text-xs"></i> Crear cuenta
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-400">
                    ¿Ya tienes cuenta?
                    <a href="<?php echo e(route('customer.login')); ?>" class="text-indigo-600 font-bold hover:underline">Inicia sesión</a>
                </p>
            </div>
        </div>

        <p class="text-center mt-5">
            <a href="/" class="text-sm text-gray-400 hover:text-gray-600 transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i> Volver a la tienda
            </a>
        </p>
    </div>
</div>

<script>
function togglePass(id, eyeId) {
    var i = document.getElementById(id);
    var e = document.getElementById(eyeId);
    if (i.type === 'password') { i.type = 'text'; e.classList.replace('fa-eye','fa-eye-slash'); }
    else { i.type = 'password'; e.classList.replace('fa-eye-slash','fa-eye'); }
}
</script>
</body>
</html>
<?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\customer\auth\register.blade.php ENDPATH**/ ?>
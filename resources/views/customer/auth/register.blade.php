<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta — FiftyOne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4">

<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <a href="/" class="inline-flex items-center gap-2">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
                <i class="fa-solid fa-shirt text-white text-sm"></i>
            </div>
            <span class="text-2xl font-black text-gray-900">Fifty<span style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;-webkit-text-fill-color:transparent">One</span></span>
        </a>
        <p class="text-gray-500 text-sm mt-2">Crea tu cuenta para seguir tus pedidos</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h1 class="text-xl font-bold text-gray-900 mb-6">Crear cuenta</h1>

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('customer.register.post') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400"><i class="fa-solid fa-user text-sm"></i></span>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-gray-50 focus:bg-white transition"
                           placeholder="Tu nombre">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400"><i class="fa-solid fa-envelope text-sm"></i></span>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-gray-50 focus:bg-white transition"
                           placeholder="tu@email.com">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono <span class="text-gray-400 font-normal">(opcional)</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400"><i class="fa-solid fa-phone text-sm"></i></span>
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-gray-50 focus:bg-white transition"
                           placeholder="300 000 0000">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400"><i class="fa-solid fa-lock text-sm"></i></span>
                    <input type="password" name="password" required
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-gray-50 focus:bg-white transition"
                           placeholder="Mínimo 8 caracteres">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400"><i class="fa-solid fa-lock text-sm"></i></span>
                    <input type="password" name="password_confirmation" required
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-gray-50 focus:bg-white transition"
                           placeholder="Repite la contraseña">
                </div>
            </div>

            <button type="submit"
                    class="w-full text-white font-semibold py-3 rounded-xl transition hover:opacity-90 mt-2"
                    style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
                Crear cuenta
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            ¿Ya tienes cuenta?
            <a href="{{ route('customer.login') }}" class="text-indigo-600 font-semibold hover:underline">Inicia sesión</a>
        </p>
    </div>

    <p class="text-center mt-4">
        <a href="/" class="text-sm text-gray-400 hover:text-gray-600 transition">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Volver a la tienda
        </a>
    </p>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion &mdash; FiftyOne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4">

<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <a href="/"><div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-2xl mb-4"><i class="fa-solid fa-store text-white text-2xl"></i></div></a>
        <h1 class="text-2xl font-bold text-gray-900">FiftyOne</h1>
        <p class="text-gray-500 text-sm mt-1">Inicia sesión la tu cuenta</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Inicjiar sesión</h2>

        @if(session('success'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('customer.login.post') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400"><i class="fa-solid fa-envelope text-sm"></i></span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-gray-50 focus:bg-white transition"
                           placeholder="tu@email.com">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400"><i class="fa-solid fa-lock text-sm"></i></span>
                    <input type="password" name="password" id="password" required
                           class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-gray-50 focus:bg-white transition"
                           placeholder="••••••••">
                    <button type="button" onclick="togglePass()"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-indigo-500 transition">
                        <i id="eye" class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-gray-300"> Recordarme
            </label>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                Iniciar sesión
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">
                ¿Sin cuenta? <a href="{{ route('customer.register') }}" class="text-indigo-600 font-semibold hover:underline">Registrate gratis</a>
            </p>
            <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl border border-gray-200 text-gray-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition">
                <i class="fa-solid fa-gauge-high text-xs"></i> Admin
            </a>
        </div>
    </div>

    <p class="text-center mt-4">
        <a href="/" class="text-sm text-gray-400 hover:text-gray-600 transition">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Volver a la tienda
        </a>
    </p>
</div>

<script>
function togglePass() {
    var i = document.getElementById('password');
    var e = document.getElementById('eye');
    if (i.type === 'password') { i.type = 'text'; e.classList.replace('fa-eye','fa-eye-slash'); }
    else { i.type = 'password'; e.classList.replace('fa-eye-slash','fa-eye'); }
}
</script>
</body>
</html>
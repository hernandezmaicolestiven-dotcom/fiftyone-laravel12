<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña &mdash; FiftyOne</title>
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
        <p class="text-gray-500 text-sm mt-1">Crea una nueva contraseña</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Restablecer contraseña</h2>

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('customer.password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ request('email') }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400"><i class="fa-solid fa-lock text-sm"></i></span>
                    <input type="password" name="password" id="password" required
                           class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-gray-50 focus:bg-white transition"
                           placeholder="••••••••">
                    <button type="button" onclick="togglePass('password', 'eye1')"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-indigo-500 transition">
                        <i id="eye1" class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400"><i class="fa-solid fa-lock text-sm"></i></span>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-gray-50 focus:bg-white transition"
                           placeholder="••••••••">
                    <button type="button" onclick="togglePass('password_confirmation', 'eye2')"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-indigo-500 transition">
                        <i id="eye2" class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                Restablecer contraseña
            </button>
        </form>
    </div>

    <p class="text-center mt-4">
        <a href="/" class="text-sm text-gray-400 hover:text-gray-600 transition">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Volver a la tienda
        </a>
    </p>
</div>

<script>
function togglePass(inputId, eyeId) {
    var i = document.getElementById(inputId);
    var e = document.getElementById(eyeId);
    if (i.type === 'password') { i.type = 'text'; e.classList.replace('fa-eye','fa-eye-slash'); }
    else { i.type = 'password'; e.classList.replace('fa-eye-slash','fa-eye'); }
}
</script>
</body>
</html>

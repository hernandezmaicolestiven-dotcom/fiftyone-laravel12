<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvidé mi contraseña — FiftyOne Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-900 via-indigo-950 to-gray-900 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md px-6">
    <div class="bg-white rounded-2xl shadow-2xl p-8">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
                 style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)">
                <i class="fa-solid fa-key text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">¿Olvidaste tu contraseña?</h1>
            <p class="text-gray-500 text-sm mt-1">Te enviaremos un enlace para restablecerla</p>
        </div>

        {{-- Success --}}
        @if(session('success'))
            <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Error --}}
        @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email de tu cuenta</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-envelope text-sm"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="admin@fiftyone.com">
                </div>
            </div>

            <button type="submit"
                    class="w-full text-white font-semibold py-2.5 rounded-xl transition text-sm hover:opacity-90"
                    style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                <i class="fa-solid fa-paper-plane mr-2"></i> Enviar enlace de recuperación
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('admin.login') }}"
               class="text-sm text-indigo-600 hover:underline flex items-center justify-center gap-1">
                <i class="fa-solid fa-arrow-left text-xs"></i> Volver al inicio de sesión
            </a>
        </div>
    </div>
</div>

</body>
</html>

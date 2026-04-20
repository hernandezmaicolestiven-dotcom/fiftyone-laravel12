<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada — FiftyOne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family:'Inter',sans-serif; background:linear-gradient(135deg,#000 0%,#0d0d0d 40%,#0a0e2e 70%,#1a0a2e 100%); min-height:100vh; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-15px)} }
        .float { animation:float 3s ease-in-out infinite; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4">
    <div class="text-center">
        <div class="float inline-flex items-center justify-center w-32 h-32 rounded-3xl mb-8 shadow-2xl"
             style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
            <i class="fa-solid fa-shirt text-white text-5xl"></i>
        </div>
        <h1 class="text-8xl font-black text-white mb-4"
            style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
            404
        </h1>
        <h2 class="text-2xl font-bold text-white mb-3">Página no encontrada</h2>
        <p class="text-gray-400 mb-8 max-w-sm mx-auto">Esta prenda no existe en nuestro catálogo. Vuelve al inicio y encuentra lo que buscas.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="/"
               class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl text-white font-bold text-sm transition hover:opacity-90"
               style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);box-shadow:0 8px 30px rgba(59,89,255,.4)">
                <i class="fa-solid fa-house text-xs"></i> Volver al inicio
            </a>
            <a href="/catalogo"
               class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl font-bold text-sm transition"
               style="background:rgba(255,255,255,.08);color:white;border:1px solid rgba(255,255,255,.15)">
                <i class="fa-solid fa-shirt text-xs"></i> Ver catálogo
            </a>
        </div>
    </div>
</body>
</html>

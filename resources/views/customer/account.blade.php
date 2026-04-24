<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi cuenta — FiftyOne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-bg { background: linear-gradient(135deg,#000 0%,#0d0d0d 40%,#0a0e2e 70%,#1a0a2e 100%); }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .fade-up { animation: fadeUp .5s ease forwards; }
        /* Light mode */
        body.light { background:#f1f5f9; color:#1e293b; }
        body.light .hero-bg { background: linear-gradient(135deg,#0d0d1a 0%,#0a0e2e 55%,#1a0a2e 100%) !important; }
        body.light .card-dark { background:white !important; border-color:#e2e8f0 !important; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        body.light .text-gray-400 { color:#64748b !important; }
        body.light .text-gray-500 { color:#64748b !important; }
        body.light .text-gray-600 { color:#475569 !important; }
        body.light .border-white\/10 { border-color:#e2e8f0 !important; }
        body.light .border-white\/5 { border-color:#f1f5f9 !important; }
        body.light .bg-gray-950 { background:#f8fafc !important; }
        body.light .bg-white\/5 { background:white !important; border-color:#e2e8f0 !important; }
        body.light .bg-white\/3 { background:white !important; }
        body.light .bg-white\/4 { background:white !important; }
        body.light input, body.light textarea { color:#1e293b !important; }
        body.light .text-white { color:#1e293b !important; }
        /* Excepciones: mantener blanco en hero y navbar */
        body.light nav .text-white { color:white !important; }
        body.light .hero-section .text-white { color:white !important; }
        body.light .hero-section h1 { color:white !important; -webkit-text-fill-color:white !important; }
        /* Nombre del usuario siempre visible */
        nav .text-gray-400 { color:rgba(255,255,255,.7) !important; }
        /* Nombre en el hero siempre blanco */
        .hero-section h1 { color: white !important; -webkit-text-fill-color: white !important; }
    </style>
</head>
<body class="bg-gray-950 text-white min-h-screen">

{{-- NAVBAR --}}
<nav class="hero-bg border-b border-white/10 sticky top-0 z-40 backdrop-blur-sm">
    <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
                <i class="fa-solid fa-shirt text-white text-sm"></i>
            </div>
            <span class="text-white font-black text-xl">Fifty<span style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;-webkit-text-fill-color:transparent">One</span></span>
        </a>
        <div class="flex items-center gap-4">
            {{-- Avatar y nombre --}}
            <div class="flex items-center gap-2">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" 
                         class="w-8 h-8 rounded-full object-cover border border-white/20">
                @else
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white border border-white/20"
                         style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <span class="text-gray-400 text-sm hidden sm:block">{{ $user->name }}</span>
            </div>
            {{-- Toggle tema --}}
            <button id="themeToggle" onclick="toggleTheme()"
                    class="w-12 h-6 rounded-full relative transition-all duration-300 flex-shrink-0"
                    style="background:rgba(255,255,255,.15)">
                <span id="themeThumb"
                      class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow flex items-center justify-center transition-all duration-300">
                    <i id="themeIcon" class="fa-solid fa-moon text-indigo-600 text-xs"></i>
                </span>
            </button>
            <form method="POST" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-1.5 text-sm text-gray-400 hover:text-red-400 transition px-3 py-1.5 rounded-xl hover:bg-white/5">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i> Salir
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="max-w-5xl mx-auto px-4 py-10">

    @if(session('success'))
    <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-2xl text-sm flex items-center gap-2 fade-up">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Hero header --}}
    <div class="hero-section relative rounded-3xl overflow-hidden p-8 mb-8 fade-up"
         style="background:linear-gradient(135deg,#0d0d1a 0%,#0a0e2e 55%,#1a0a2e 100%)">
        <div class="absolute inset-0 opacity-20"
             style="background-image:radial-gradient(circle at 20% 50%,#3B59FF 0%,transparent 50%),radial-gradient(circle at 80% 20%,#7B2FBE 0%,transparent 50%)"></div>
        <div class="relative flex items-center gap-5">
            {{-- Avatar con opción de cambiar --}}
            <div class="relative group">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" 
                         class="w-20 h-20 rounded-2xl object-cover shadow-2xl border-2 border-white/20">
                @else
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white text-3xl font-black flex-shrink-0 shadow-2xl border-2 border-white/20"
                         style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                {{-- Botón para cambiar foto --}}
                <button onclick="document.getElementById('avatarInput').click()" 
                        class="absolute inset-0 bg-black/60 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                    <i class="fa-solid fa-camera text-white text-xl"></i>
                </button>
                <form id="avatarForm" method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data" class="hidden">
                    @csrf @method('PUT')
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="document.getElementById('avatarForm').submit()">
                </form>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Bienvenido de nuevo,</p>
                <h1 class="text-2xl sm:text-3xl font-black text-white" style="color:white!important;-webkit-text-fill-color:white!important">{{ $user->name }}</h1>
                <p class="text-gray-400 text-sm mt-1">
                    {{ $user->email }}
                    @if($user->phone) · {{ $user->phone }} @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    @php
        $pending = $orders->whereIn('status', ['pending','confirmed','shipped'])->count();
        $total   = $orders->sum('total');
    @endphp
    <div class="grid grid-cols-3 gap-4 mb-8 fade-up" style="animation-delay:.1s">
        @foreach([
            ['Pedidos totales', $orders->count(),                          'fa-bag-shopping', '#3B59FF','#7B2FBE'],
            ['En proceso',      $pending,                                  'fa-clock',        '#d97706','#f59e0b'],
            ['Total comprado',  '$ '.number_format($total,0,',','.'),      'fa-dollar-sign',  '#059669','#10b981'],
        ] as [$label,$value,$icon,$c1,$c2])
        <div class="rounded-2xl p-5 border border-white/10 hover:border-white/20 transition" style="background:rgba(255,255,255,.04)">
            <div class="w-9 h-9 rounded-xl mb-3 flex items-center justify-center" style="background:linear-gradient(135deg,{{ $c1 }}22,{{ $c2 }}22)">
                <i class="fa-solid {{ $icon }} text-sm" style="background:linear-gradient(135deg,{{ $c1 }},{{ $c2 }});-webkit-background-clip:text;-webkit-text-fill-color:transparent"></i>
            </div>
            <p class="text-2xl font-black text-white">{{ $value }}</p>
            <p class="text-xs text-gray-500 mt-0.5 uppercase tracking-wide font-semibold">{{ $label }}</p>
        </div>
        @endforeach
    </div>

    {{-- Perfil editable --}}
    <div class="rounded-2xl overflow-hidden border border-white/10 fade-up mb-6" style="background:rgba(255,255,255,.03)">
        <div class="px-6 py-4 border-b border-white/10">
            <h2 class="text-base font-bold text-white">Mi perfil</h2>
        </div>
        <div class="p-6 grid md:grid-cols-2 gap-6">
            <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Nombre</label>
                    <input type="text" name="name" value="{{ $user->name }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Teléfono</label>
                    <input type="tel" name="phone" value="{{ $user->phone }}"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition"
                        style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">Guardar cambios</button>
            </form>
            <form method="POST" action="{{ route('customer.password.update') }}" class="space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Contraseña actual</label>
                    <input type="password" name="current_password" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    @error('current_password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Nueva contraseña</label>
                    <input type="password" name="password" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition"
                        style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15)">Cambiar contraseña</button>
            </form>
        </div>
    </div>

    {{-- Wishlist --}}
    @if($wishlist->count() > 0)
    <div class="rounded-2xl overflow-hidden border border-white/10 fade-up mt-6" style="background:rgba(255,255,255,.03)">
        <div class="px-6 py-4 border-b border-white/10">
            <h2 class="text-base font-bold text-white">Mi lista de deseos</h2>
            <p class="text-xs text-gray-500 mt-0.5">{{ $wishlist->count() }} producto(s) guardado(s)</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 p-4">
            @foreach($wishlist as $w)
            @php $p = $w->product; @endphp
            <div class="rounded-2xl overflow-hidden border border-white/10 hover:border-white/20 transition" style="background:rgba(255,255,255,.04)">
                <div class="aspect-square overflow-hidden bg-gray-800">
                    @if($p->image)
                    <img src="{{ str_starts_with($p->image,'http') ? $p->image : Storage::url($p->image) }}"
                         alt="{{ $p->name }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="p-3">
                    <p class="text-white text-xs font-semibold truncate">{{ $p->name }}</p>
                    <p class="font-black text-sm mt-1" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
                        $ {{ number_format($p->price,0,',','.') }}
                    </p>
                    <a href="/" class="mt-2 block text-center text-xs py-1.5 rounded-xl text-white font-semibold transition hover:opacity-80"
                       style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">Ver producto</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Historial de pedidos --}}
    <div class="rounded-2xl overflow-hidden border border-white/10 fade-up" style="background:rgba(255,255,255,.03)">
        <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-white">Mis pedidos</h2>
                <p class="text-xs text-gray-500 mt-0.5">Historial completo de compras</p>
            </div>
            <a href="/" class="text-xs font-semibold px-3 py-1.5 rounded-xl border border-white/10 text-gray-400 hover:text-white hover:border-white/30 transition">
                <i class="fa-solid fa-plus text-xs mr-1"></i> Nueva compra
            </a>
        </div>

        @forelse($orders as $order)
        @php
            $statusStyles = [
                'pending'   => ['bg-amber-500/15 text-amber-400 border-amber-500/30',   'Pendiente'],
                'confirmed' => ['bg-blue-500/15 text-blue-400 border-blue-500/30',       'Confirmado'],
                'shipped'   => ['bg-indigo-500/15 text-indigo-400 border-indigo-500/30', 'Enviado'],
                'delivered' => ['bg-emerald-500/15 text-emerald-400 border-emerald-500/30','Entregado'],
                'cancelled' => ['bg-red-500/15 text-red-400 border-red-500/30',          'Cancelado'],
            ];
            [$badgeClass, $badgeLabel] = $statusStyles[$order->status] ?? ['bg-gray-500/15 text-gray-400 border-gray-500/30', $order->status];

            $steps = [
                ['key'=>'pending',   'label'=>'Recibido',   'icon'=>'fa-circle-check'],
                ['key'=>'confirmed', 'label'=>'Confirmado', 'icon'=>'fa-box'],
                ['key'=>'shipped',   'label'=>'Enviado',    'icon'=>'fa-truck'],
                ['key'=>'delivered', 'label'=>'Entregado',  'icon'=>'fa-house'],
            ];
            $stepOrder = ['pending'=>0,'confirmed'=>1,'shipped'=>2,'delivered'=>3];
            $currentStep = $stepOrder[$order->status] ?? -1;
        @endphp
        <div class="px-6 py-5 border-b border-white/5 last:border-0">
            {{-- Header del pedido --}}
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="font-mono text-xs text-gray-500">#{{ $order->id }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        <span class="text-xs text-gray-600">{{ $order->created_at->format('d/m/Y · H:i') }}</span>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($order->items as $item)
                        <span class="text-xs px-2 py-0.5 rounded-lg text-gray-400 border border-white/10" style="background:rgba(255,255,255,.04)">
                            {{ $item->product_name }} ×{{ $item->quantity }}
                        </span>
                        @endforeach
                    </div>
                    @if($order->tracking_number)
                    <div class="flex items-center gap-2 mt-2 p-2 rounded-xl" style="background:rgba(59,89,255,.1)">
                        <i class="fa-solid fa-truck text-indigo-400 text-xs"></i>
                        <span class="text-xs text-indigo-300 font-semibold">Guia: {{ $order->tracking_number }}</span>
                    </div>
                    @endif
                    @if($order->shipping_address)
                    <div class="flex items-center gap-1.5 mt-2">
                        <i class="fa-solid fa-location-dot text-indigo-400 text-xs"></i>
                        <span class="text-xs text-gray-400">{{ $order->shipping_address }}{{ $order->city ? ', '.$order->city : '' }}</span>
                    </div>
                    @endif
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="font-black text-lg" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
                        ${{ number_format($order->total, 0, ',', '.') }}
                    </p>
                    <div class="mt-2 flex flex-col gap-1.5">
                        <a href="{{ route('invoice.show', $order) }}" target="_blank" 
                           class="text-xs text-blue-400 hover:text-blue-300 transition font-semibold inline-flex items-center justify-end gap-1">
                            <i class="fa-solid fa-file-invoice text-xs"></i> Ver factura
                        </a>
                        @if($order->status === 'pending')
                        <form method="POST" action="{{ route('customer.order.cancel', $order) }}"
                              onsubmit="return confirm('¿Cancelar este pedido?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-300 transition font-semibold">
                                Cancelar pedido
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Línea de tiempo (solo si no está cancelado) --}}
            @if($order->status !== 'cancelled')
            <div class="flex items-center gap-0 mt-3">
                @foreach($steps as $i => $step)
                @php
                    $done    = $currentStep >= $i;
                    $current = $currentStep === $i;
                @endphp
                {{-- Paso --}}
                <div class="flex flex-col items-center flex-shrink-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition-all"
                         style="{{ $done ? 'background:linear-gradient(135deg,#3B59FF,#7B2FBE);color:white' : 'background:rgba(255,255,255,.07);color:#4b5563' }}">
                        <i class="fa-solid {{ $step['icon'] }} text-xs"></i>
                    </div>
                    <span class="text-[10px] mt-1 font-medium {{ $done ? 'text-indigo-400' : 'text-gray-600' }} whitespace-nowrap">
                        {{ $step['label'] }}
                    </span>
                </div>
                {{-- Línea conectora --}}
                @if(!$loop->last)
                <div class="flex-1 h-0.5 mx-1 mb-4 rounded-full"
                     style="{{ $currentStep > $i ? 'background:linear-gradient(90deg,#3B59FF,#7B2FBE)' : 'background:rgba(255,255,255,.07)' }}"></div>
                @endif
                @endforeach
            </div>
            @else
            <div class="mt-3 flex items-center gap-2 text-xs text-red-400">
                <i class="fa-solid fa-circle-xmark"></i>
                <span>Este pedido fue cancelado</span>
            </div>
            @endif
        </div>
        @empty
        <div class="px-6 py-20 text-center">
            <div class="w-20 h-20 rounded-3xl mx-auto mb-5 flex items-center justify-center"
                 style="background:linear-gradient(135deg,rgba(59,89,255,.15),rgba(123,47,190,.15));border:1px solid rgba(59,89,255,.2)">
                <i class="fa-solid fa-bag-shopping text-3xl" style="background:linear-gradient(135deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;-webkit-text-fill-color:transparent"></i>
            </div>
            <p class="text-white font-bold text-lg">Aún no tienes pedidos</p>
            <p class="text-gray-500 text-sm mt-1">Explora nuestra colección y haz tu primera compra</p>
            <a href="/"
               class="inline-flex items-center gap-2 mt-6 px-6 py-3 rounded-2xl text-white font-semibold text-sm transition hover:opacity-90"
               style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);box-shadow:0 8px 30px rgba(59,89,255,.3)">
                <i class="fa-solid fa-shirt text-xs"></i> Ir a la tienda
            </a>
        </div>
        @endforelse
    </div>

</div>

<script>
function toggleTheme() {
    const body  = document.body;
    const thumb = document.getElementById('themeThumb');
    const icon  = document.getElementById('themeIcon');
    const isLight = body.classList.toggle('light');
    localStorage.setItem('customerTheme', isLight ? 'light' : 'dark');
    thumb.style.transform = isLight ? 'translateX(24px)' : 'translateX(0)';
    icon.className = isLight
        ? 'fa-solid fa-sun text-amber-500 text-xs'
        : 'fa-solid fa-moon text-indigo-600 text-xs';
}
// Aplicar tema guardado
(function(){
    const saved = localStorage.getItem('customerTheme');
    if (saved === 'light') {
        document.body.classList.add('light');
        const thumb = document.getElementById('themeThumb');
        const icon  = document.getElementById('themeIcon');
        if (thumb) thumb.style.transform = 'translateX(24px)';
        if (icon)  icon.className = 'fa-solid fa-sun text-amber-500 text-xs';
    }
})();
</script>
</body>
</html>

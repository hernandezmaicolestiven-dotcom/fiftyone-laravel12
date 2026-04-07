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
            <span class="text-gray-400 text-sm hidden sm:block">{{ $user->name }}</span>
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
    <div class="relative rounded-3xl overflow-hidden p-8 mb-8 fade-up"
         style="background:linear-gradient(135deg,#0d0d1a 0%,#0a0e2e 55%,#1a0a2e 100%)">
        <div class="absolute inset-0 opacity-20"
             style="background-image:radial-gradient(circle at 20% 50%,#3B59FF 0%,transparent 50%),radial-gradient(circle at 80% 20%,#7B2FBE 0%,transparent 50%)"></div>
        <div class="relative flex items-center gap-5">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white text-3xl font-black flex-shrink-0 shadow-2xl"
                 style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Bienvenido de nuevo,</p>
                <h1 class="text-2xl sm:text-3xl font-black text-white">{{ $user->name }} 👋</h1>
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
        @endphp
        <div class="px-6 py-4 border-b border-white/5 last:border-0 hover:bg-white/[0.02] transition">
            <div class="flex items-start justify-between gap-4">
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
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="font-black text-lg" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
                        ${{ number_format($order->total, 0, ',', '.') }}
                    </p>
                </div>
            </div>
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
</body>
</html>

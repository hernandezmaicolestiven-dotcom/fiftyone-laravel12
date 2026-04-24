<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin') — FiftyOne</title>
    <script>
        // Aplicar tema ANTES de renderizar para evitar flash
        (function() {
            const theme = localStorage.getItem('adminTheme') || 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: {} }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        * { transition: background-color 0.2s ease, border-color 0.2s ease, color 0.15s ease; }

        /* ── DARK MODE GLOBAL ── */
        /* Cards / panels blancos */
        .dark .bg-white { background-color: #1e1e2e !important; }
        /* Textos */
        .dark .text-gray-800 { color: #e2e8f0 !important; }
        .dark .text-gray-700 { color: #cbd5e1 !important; }
        .dark .text-gray-600 { color: #94a3b8 !important; }
        .dark .text-gray-500 { color: #64748b !important; }
        /* Fondos grises claros */
        .dark .bg-gray-50  { background-color: #16162a !important; }
        .dark .bg-gray-100 { background-color: #12121f !important; }
        /* Bordes */
        .dark .border-gray-100 { border-color: #2d2d44 !important; }
        .dark .border-gray-200 { border-color: #2d2d44 !important; }
        /* Inputs */
        .dark input:not([type=submit]):not([type=button]),
        .dark select,
        .dark textarea {
            background-color: #12121f !important;
            border-color: #2d2d44 !important;
            color: #e2e8f0 !important;
        }
        .dark input::placeholder { color: #475569 !important; }
        /* Tablas */
        .dark table thead tr { background-color: #16162a !important; }
        .dark table tbody tr:hover { background-color: #16162a !important; }
        .dark table tbody { border-color: #2d2d44 !important; }
        .dark td, .dark th { color: #cbd5e1 !important; }
        /* Hover links */
        .dark .hover\:bg-gray-50:hover { background-color: #16162a !important; }
        /* Badges claros */
        .dark .bg-emerald-100 { background-color: rgba(16,185,129,0.15) !important; }
        .dark .bg-sky-100     { background-color: rgba(14,165,233,0.15) !important; }
        .dark .bg-amber-100   { background-color: rgba(245,158,11,0.15) !important; }
        .dark .bg-red-50      { background-color: rgba(239,68,68,0.1)  !important; }
        .dark .bg-indigo-100  { background-color: rgba(99,102,241,0.15) !important; }
        /* Scrollbar */
        .dark ::-webkit-scrollbar { width: 6px; }
        .dark ::-webkit-scrollbar-track { background: #12121f; }
        .dark ::-webkit-scrollbar-thumb { background: #3B59FF55; border-radius: 3px; }
        /* Sidebar scrollbar siempre oscuro */
        aside ::-webkit-scrollbar { width: 4px; }
        aside ::-webkit-scrollbar-track { background: #0a0e2e; }
        aside ::-webkit-scrollbar-thumb { background: rgba(59,89,255,.4); border-radius: 3px; }
        aside { scrollbar-color: rgba(59,89,255,.4) #0a0e2e; scrollbar-width: thin; }
        /* Code tags */
        .dark code { background-color: #2d2d44 !important; color: #a5b4fc !important; }
        /* Dividers */
        .dark .divide-y > * { border-color: #2d2d44 !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-950 font-sans">

{{-- Skip to main content (accesibilidad) --}}
<a href="#main-content"
   class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[999] focus:px-4 focus:py-2 focus:rounded-xl focus:text-white focus:text-sm focus:font-semibold focus:shadow-lg"
   style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
    Saltar al contenido principal
</a>

<div class="flex h-screen overflow-hidden" x-data="{
          sidebarOpen: true,
          dark: localStorage.getItem('adminTheme') === 'dark',
          toggleTheme() {
              this.dark = !this.dark;
              const theme = this.dark ? 'dark' : 'light';
              localStorage.setItem('adminTheme', theme);
              document.documentElement.classList.toggle('dark', this.dark);
          }
      }">

    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-16'"
           class="text-white flex flex-col transition-all duration-300 ease-in-out flex-shrink-0 z-20"
           style="background: linear-gradient(180deg, #0d0d1a 0%, #0a0e2e 50%, #1a0a2e 100%)"
           role="navigation" aria-label="Menú principal">

        {{-- Logo --}}
        <div class="flex items-center justify-between px-4 py-5 border-b border-white/10">
            <span x-show="sidebarOpen" class="text-xl font-bold tracking-wide"
                  style="background: linear-gradient(90deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                FiftyOne
            </span>
            <span x-show="!sidebarOpen" class="text-xl font-bold"
                  style="background: linear-gradient(90deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                F1
            </span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
            @foreach([
                ['admin.dashboard',         'fa-gauge-high',       'Dashboard',          'admin.dashboard'],
                ['admin.products.index',    'fa-box',              'Productos',          'admin.products*'],
                ['admin.categories.index',  'fa-tags',             'Categorias',         'admin.categories*'],
                ['admin.orders.index',      'fa-bag-shopping',     'Pedidos',            'admin.orders*'],
                ['admin.users.index',       'fa-users',            'Usuarios',           'admin.users*'],
                ['admin.reviews.index',     'fa-star',             'Resenas',            'admin.reviews*'],
                ['admin.coupons.index',     'fa-ticket',           'Cupones',            'admin.coupons*'],
                ['admin.reports.sales',     'fa-chart-line',       'Reportes',           'admin.reports*'],
                ['admin.analytics',         'fa-brain',            'Analytics',          'admin.analytics*'],
                ['admin.store-settings.index','fa-sliders',        'Tienda',             'admin.store-settings*'],
                ['admin.settings',          'fa-gear',             'Configuracion',      'admin.settings*'],
            ] as [$route, $icon, $label, $pattern])
            @php
                $active = request()->routeIs($pattern);
                $unread = ($route === 'admin.messages.index')
                    ? cache()->remember('admin_unread_msgs_'.auth()->id(), 30, fn() => \App\Models\Message::where('is_read', false)->where('user_id', '!=', auth()->id())->count())
                    : 0;
            @endphp
            <a href="{{ route($route) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition-all {{ $active ? 'text-white' : 'hover:text-white' }}"
               style="{{ $active ? 'background: linear-gradient(90deg, #3B59FF, #7B2FBE);' : '' }}"
               onmouseover="{{ !$active ? "this.style.background='linear-gradient(90deg,rgba(59,89,255,0.3),rgba(123,47,190,0.3))'" : '' }}"
               onmouseout="{{ !$active ? "this.style.background=''" : '' }}">
                <i class="fa-solid {{ $icon }} w-5 text-center"></i>
                <span x-show="sidebarOpen" class="text-sm font-medium flex-1">{{ $label }}</span>
                @if($unread > 0)
                <span x-show="sidebarOpen" class="text-[10px] font-black px-1.5 py-0.5 rounded-full text-white" style="background:#ef4444">{{ $unread }}</span>
                @endif
            </a>
            @endforeach

            {{-- Colaboradores — solo admin y superadmin --}}
            @if(in_array(auth()->user()->role, ['admin','superadmin']))
            @php $activeColab = request()->routeIs('admin.colaboradores*'); @endphp
            <a href="{{ route('admin.colaboradores.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition-all {{ $activeColab ? 'text-white' : 'hover:text-white' }}"
               style="{{ $activeColab ? 'background: linear-gradient(90deg, #3B59FF, #7B2FBE);' : '' }}"
               onmouseover="{{ !$activeColab ? "this.style.background='linear-gradient(90deg,rgba(59,89,255,0.3),rgba(123,47,190,0.3))'" : '' }}"
               onmouseout="{{ !$activeColab ? "this.style.background=''" : '' }}">
                <i class="fa-solid fa-user-shield w-5 text-center"></i>
                <span x-show="sidebarOpen" class="text-sm font-medium flex-1">Colaboradores</span>
            </a>
            @endif

            {{-- Gestión de Admins — solo superadmin --}}
            @if(auth()->user()->role === 'superadmin')
            @php $activeAdmins = request()->routeIs('admin.admins*'); @endphp
            <a href="{{ route('admin.admins.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition-all {{ $activeAdmins ? 'text-white' : 'hover:text-white' }}"
               style="{{ $activeAdmins ? 'background: linear-gradient(90deg, #3B59FF, #7B2FBE);' : '' }}"
               onmouseover="{{ !$activeAdmins ? "this.style.background='linear-gradient(90deg,rgba(59,89,255,0.3),rgba(123,47,190,0.3))'" : '' }}"
               onmouseout="{{ !$activeAdmins ? "this.style.background=''" : '' }}">
                <i class="fa-solid fa-crown w-5 text-center"></i>
                <span x-show="sidebarOpen" class="text-sm font-medium flex-1">Administradores</span>
            </a>
            @endif
        </nav>

        {{-- User footer --}}
        <div class="border-t border-white/10 px-4 py-4">
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 rounded-lg p-1 hover:bg-white/5 transition">
                @if(auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" 
                         class="w-8 h-8 rounded-full object-cover flex-shrink-0 border border-white/20">
                @else
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 text-white border border-white/20"
                         style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div x-show="sidebarOpen" class="overflow-hidden">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </a>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- NAVBAR --}}
        <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 z-10 flex items-center justify-between px-6 py-3 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                        :aria-label="sidebarOpen ? 'Colapsar menú' : 'Expandir menú'"
                        aria-controls="sidebar">
                    <i class="fa-solid fa-bars text-lg" aria-hidden="true"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-700 dark:text-gray-200">@yield('page-title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-400 dark:text-gray-500 hidden sm:block">{{ now()->format('d M Y') }}</span>

                {{-- CAMPANA DE NOTIFICACIONES --}}
                @php
                    $pendingOrders = cache()->remember('admin_pending_orders', 60, fn() => \App\Models\Order::where('status','pending')->count());
                    $lowStock      = cache()->remember('admin_low_stock', 60, fn() => \App\Models\Product::where('stock','<',5)->where('stock','>',0)->count());
                    $outStock      = cache()->remember('admin_out_stock', 60, fn() => \App\Models\Product::where('stock',0)->count());
                    $notifCount    = ($pendingOrders > 0 ? 1 : 0) + ($lowStock > 0 ? 1 : 0) + ($outStock > 0 ? 1 : 0);
                @endphp
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                            class="relative w-9 h-9 rounded-xl flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition">
                        <i class="fa-solid fa-bell text-base"></i>
                        @if($notifCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 w-4 h-4 rounded-full text-white text-[10px] font-bold flex items-center justify-center"
                              style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">{{ $notifCount }}</span>
                        @endif
                    </button>

                    <div x-show="open" x-cloak x-transition
                         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-800">Notificaciones</h3>
                            @if($notifCount > 0)
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold text-white"
                                  style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">{{ $notifCount }} nuevas</span>
                            @endif
                        </div>
                        <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                            @if($pendingOrders > 0)
                            <a href="{{ route('admin.orders.index', ['status'=>'pending']) }}"
                               class="flex items-start gap-3 px-4 py-3.5 hover:bg-amber-50 transition">
                                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-bag-shopping text-amber-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $pendingOrders }} pedido{{ $pendingOrders>1?'s':'' }} pendiente{{ $pendingOrders>1?'s':'' }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Requieren atención</p>
                                </div>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-300 ml-auto mt-1.5"></i>
                            </a>
                            @endif
                            @if($lowStock > 0)
                            <a href="{{ route('admin.products.index', ['stock'=>'low']) }}"
                               class="flex items-start gap-3 px-4 py-3.5 hover:bg-orange-50 transition">
                                <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-triangle-exclamation text-orange-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $lowStock }} producto{{ $lowStock>1?'s':'' }} con stock bajo</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Menos de 5 unidades</p>
                                </div>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-300 ml-auto mt-1.5"></i>
                            </a>
                            @endif
                            @if($outStock > 0)
                            <a href="{{ route('admin.products.index', ['stock'=>'low']) }}"
                               class="flex items-start gap-3 px-4 py-3.5 hover:bg-red-50 transition">
                                <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-circle-xmark text-red-500 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $outStock }} producto{{ $outStock>1?'s':'' }} sin stock</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Agotados — requieren reposición</p>
                                </div>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-300 ml-auto mt-1.5"></i>
                            </a>
                            @endif
                            @if($notifCount === 0)
                            <div class="px-4 py-8 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-100 mx-auto mb-3 flex items-center justify-center">
                                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                                </div>
                                <p class="text-sm font-semibold text-gray-700">Todo en orden</p>
                                <p class="text-xs text-gray-400 mt-1">Sin alertas pendientes</p>
                            </div>
                            @endif
                        </div>
                        @if($notifCount > 0)
                        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                            <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                                Ver dashboard completo →
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- TOGGLE DARK MODE --}}
                <button @click="toggleTheme()"
                        class="relative w-14 h-7 rounded-full transition-all duration-300 focus:outline-none flex-shrink-0"
                        :style="dark
                            ? 'background: linear-gradient(90deg, #3B59FF, #7B2FBE)'
                            : 'background: #e5e7eb'">
                    <span class="absolute top-0.5 left-0.5 w-6 h-6 rounded-full bg-white shadow-md transition-all duration-300 flex items-center justify-center"
                          :class="dark ? 'translate-x-7' : 'translate-x-0'">
                        <i class="fa-solid text-xs transition-all duration-200"
                           :class="dark ? 'fa-moon text-indigo-600' : 'fa-sun text-amber-500'"></i>
                    </span>
                </button>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition px-3 py-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="hidden sm:inline">Salir</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="flex-1 overflow-y-auto p-6 bg-gray-100 dark:bg-gray-950" role="main" id="main-content">

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mb-5 flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500 flex-shrink-0"></i>
                    <span class="text-sm flex-1">{{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 flex-shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mb-5 flex items-center gap-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl shadow-sm">
                    <i class="fa-solid fa-circle-xmark text-red-500 flex-shrink-0"></i>
                    <span class="text-sm flex-1">{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-400 hover:text-red-600 flex-shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mb-5 flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500 flex-shrink-0"></i>
                    <span class="text-sm flex-1">{{ session('warning') }}</span>
                    <button @click="show = false" class="text-amber-400 hover:text-amber-600 flex-shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('info'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mb-5 flex items-center gap-3 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl shadow-sm">
                    <i class="fa-solid fa-circle-info text-blue-500 flex-shrink-0"></i>
                    <span class="text-sm flex-1">{{ session('info') }}</span>
                    <button @click="show = false" class="text-blue-400 hover:text-blue-600 flex-shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')

{{-- Global page loader removido para mejor performance --}}
<script>
// Sin spinner - navegación directa
</script>
</body>
</html>

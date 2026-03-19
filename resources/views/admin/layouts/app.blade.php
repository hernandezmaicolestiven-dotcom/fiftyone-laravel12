<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin') — FiftyOne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link.active { @apply bg-indigo-700 text-white; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans" x-data="{ sidebarOpen: true }">

<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-16'"
           class="text-white flex flex-col transition-all duration-300 ease-in-out flex-shrink-0"
           style="background: linear-gradient(180deg, #0d0d1a 0%, #0a0e2e 50%, #1a0a2e 100%)">

        {{-- Logo --}}
        <div class="flex items-center justify-between px-4 py-5 border-b border-white/10">
            <span x-show="sidebarOpen" class="text-xl font-bold tracking-wide" style="background: linear-gradient(90deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">FiftyOne</span>
            <span x-show="!sidebarOpen" class="text-xl font-bold" style="background: linear-gradient(90deg, #3B59FF, #7B2FBE); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">F1</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'hover:text-white' }}"
               style="{{ request()->routeIs('admin.dashboard') ? 'background: linear-gradient(90deg, #3B59FF, #7B2FBE);' : '' }}"
               onmouseover="{{ !request()->routeIs('admin.dashboard') ? "this.style.background='linear-gradient(90deg,rgba(59,89,255,0.3),rgba(123,47,190,0.3))'" : '' }}"
               onmouseout="{{ !request()->routeIs('admin.dashboard') ? "this.style.background=''" : '' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                <span x-show="sidebarOpen" class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition {{ request()->routeIs('admin.products*') ? 'text-white' : 'hover:text-white' }}"
               style="{{ request()->routeIs('admin.products*') ? 'background: linear-gradient(90deg, #3B59FF, #7B2FBE);' : '' }}"
               onmouseover="{{ !request()->routeIs('admin.products*') ? "this.style.background='linear-gradient(90deg,rgba(59,89,255,0.3),rgba(123,47,190,0.3))'" : '' }}"
               onmouseout="{{ !request()->routeIs('admin.products*') ? "this.style.background=''" : '' }}">
                <i class="fa-solid fa-box w-5 text-center"></i>
                <span x-show="sidebarOpen" class="text-sm font-medium">Productos</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition {{ request()->routeIs('admin.categories*') ? 'text-white' : 'hover:text-white' }}"
               style="{{ request()->routeIs('admin.categories*') ? 'background: linear-gradient(90deg, #3B59FF, #7B2FBE);' : '' }}"
               onmouseover="{{ !request()->routeIs('admin.categories*') ? "this.style.background='linear-gradient(90deg,rgba(59,89,255,0.3),rgba(123,47,190,0.3))'" : '' }}"
               onmouseout="{{ !request()->routeIs('admin.categories*') ? "this.style.background=''" : '' }}">
                <i class="fa-solid fa-tags w-5 text-center"></i>
                <span x-show="sidebarOpen" class="text-sm font-medium">Categorías</span>
            </a>
        </nav>

        {{-- User --}}
        <div class="border-t border-white/10 px-4 py-4">
            <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 rounded-lg p-1 hover:bg-white/5 transition">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 text-white" style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
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
        <header class="bg-white shadow-sm z-10 flex items-center justify-between px-6 py-3">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-indigo-600 transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-700">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 text-sm text-gray-600 hover:text-red-500 transition">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Salir</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="flex-1 overflow-y-auto p-6">

            {{-- Alerts --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    <span class="text-sm">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <i class="fa-solid fa-circle-xmark text-red-500"></i>
                    <span class="text-sm">{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>

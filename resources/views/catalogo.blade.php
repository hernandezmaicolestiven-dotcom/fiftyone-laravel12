<!DOCTYPE html>
<html lang="es">
@php use Illuminate\Support\Facades\Storage; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo — FiftyOne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .card-hover { transition: transform .25s ease, box-shadow .25s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(123,47,190,.12); }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

{{-- NAVBAR --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-black/95 backdrop-blur-sm border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
                    <i class="fa-solid fa-shirt text-white text-sm"></i>
                </div>
                <span class="text-white font-black text-xl">Fifty<span style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent">One</span></span>
            </a>
            <div class="flex items-center gap-4">
                <a href="/" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">← Volver al inicio</a>
            </div>
        </div>
    </div>
</nav>

<div class="pt-24 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8">
        <span class="text-sm font-semibold uppercase tracking-widest" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent">Catálogo completo</span>
        <h1 class="text-4xl font-black text-gray-900 mt-1">Todos los productos</h1>
        <p class="text-gray-500 mt-2">{{ $products->total() }} productos disponibles</p>
    </div>

    {{-- Filtro por categoría --}}
    <form method="GET" class="flex flex-wrap gap-2 mb-8">
        @if(!request('category'))
        <a href="{{ route('products.index') }}"
           class="px-4 py-2 rounded-full text-sm font-semibold transition-all text-white"
           style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
            Todos
        </a>
        @else
        <a href="{{ route('products.index') }}"
           class="px-4 py-2 rounded-full text-sm font-semibold transition-all bg-white text-gray-600 border border-gray-200 hover:border-gray-400">
            Todos
        </a>
        @endif
        @foreach($categories as $cat)
            @if(request('category') == $cat->id)
            <a href="{{ route('products.index', ['category' => $cat->id]) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-all text-white"
               style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
                {{ $cat->name }}
            </a>
            @else
            <a href="{{ route('products.index', ['category' => $cat->id]) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-all bg-white text-gray-600 border border-gray-200 hover:border-gray-400">
                {{ $cat->name }}
            </a>
            @endif
        @endforeach
    </form>

    {{-- Grid de productos --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($products as $product)
        <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 group">
            <div class="relative aspect-square overflow-hidden bg-gray-100">
                @php
                    $img = $product->image
                        ? (str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image))
                        : 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80';
                @endphp
                <img src="{{ $img }}" alt="{{ $product->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @if($product->stock < 5)
                    <span class="absolute top-3 left-3 text-xs font-bold px-2.5 py-1 rounded-full bg-red-500 text-white">Oferta</span>
                @elseif($product->created_at->diffInDays() < 30)
                    <span class="absolute top-3 left-3 text-xs font-bold px-2.5 py-1 rounded-full text-white" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">Nuevo</span>
                @endif
            </div>
            <div class="p-4">
                @if($product->category)
                    <span class="text-xs font-medium text-purple-600">{{ $product->category->name }}</span>
                @endif
                <h3 class="font-semibold text-gray-900 mt-1 text-sm leading-tight">{{ $product->name }}</h3>
                @if($product->description)
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $product->description }}</p>
                @endif
                <p class="text-lg font-black mt-2" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent">
                    COP ${{ number_format($product->price, 0, ',', '.') }}
                </p>
                <button class="mt-3 w-full text-white text-sm font-semibold py-2.5 rounded-xl transition-all"
                        style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
                    <i class="fa-solid fa-cart-plus text-xs mr-1"></i> Agregar al carrito
                </button>
            </div>
        </div>
        @empty
            <div class="col-span-4 text-center py-20 text-gray-400">
                <i class="fa-solid fa-box-open text-5xl mb-4 block"></i>
                No hay productos disponibles.
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    @if($products->hasPages())
        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @endif

</div>

</body>
</html>

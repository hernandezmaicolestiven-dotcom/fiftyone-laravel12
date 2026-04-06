@extends('admin.layouts.app')
@section('title', 'Editar Categoría')
@section('page-title', 'Editar Categoría')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="relative rounded-3xl overflow-hidden mb-6 p-6 flex items-center gap-5"
         style="background: linear-gradient(135deg, #0d0d1a 0%, #0a0e2e 55%, #1a0a2e 100%)">
        <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full opacity-20"
             style="background: radial-gradient(circle, #7B2FBE, transparent)"></div>
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 text-2xl font-black text-white"
             style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)">
            {{ strtoupper(substr($category->name, 0, 1)) }}
        </div>
        <div>
            <h2 class="text-xl font-black text-white">{{ $category->name }}</h2>
            <div class="flex items-center gap-3 mt-1">
                <code class="text-xs bg-white/10 text-indigo-300 px-2 py-0.5 rounded-lg">/{{ $category->slug }}</code>
                <span class="text-xs text-gray-400">
                    <i class="fa-solid fa-box mr-1"></i>{{ $category->products_count ?? $category->products()->count() }} productos
                </span>
            </div>
        </div>
        <a href="{{ route('admin.categories.index') }}"
           class="ml-auto flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-medium transition border border-white/10 flex-shrink-0">
            <i class="fa-solid fa-arrow-left text-xs"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm">
            <i class="fa-solid fa-circle-check flex-shrink-0"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">
            <i class="fa-solid fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
            <ul class="space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #7B2FBE, #3B59FF)"></div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-5">
                @csrf @method('PUT')
                @include('admin.categories._form')
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold shadow hover:opacity-90 transition"
                            style="background: linear-gradient(90deg, #7B2FBE, #3B59FF)">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
                    </button>
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-5 py-3 rounded-xl border-2 border-gray-100 text-gray-500 text-sm font-semibold hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Zona peligrosa --}}
    <div class="mt-5 bg-white rounded-2xl shadow-sm overflow-hidden border-2 border-red-100">
        <div class="h-1.5 bg-gradient-to-r from-red-400 to-rose-500"></div>
        <div class="p-5 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-bold text-red-600 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i> Eliminar categoría
                </p>
                <p class="text-xs text-gray-400 mt-0.5">Los productos asociados quedarán sin categoría.</p>
            </div>
            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                  onsubmit="return confirm('¿Eliminar la categoría «{{ addslashes($category->name) }}»?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-xs font-bold transition">
                    <i class="fa-solid fa-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

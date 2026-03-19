@extends('admin.layouts.app')

@section('title', 'Editar Categoría')
@section('page-title', 'Editar Categoría')

@section('content')

<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-indigo-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-lg font-semibold text-gray-800">Editar: {{ $category->name }}</h2>
        </div>

        @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-lg p-4">
                <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-5">
            @csrf @method('PUT')
            @include('admin.categories._form')
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Actualizar
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-200 hover:border-gray-300 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

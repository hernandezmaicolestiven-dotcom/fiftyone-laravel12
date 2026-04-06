@extends('admin.layouts.app')

@section('title', 'Mi Perfil')
@section('page-title', 'Mi Perfil')

@section('content')

<div class="max-w-2xl space-y-6">

    {{-- Datos personales --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-5 flex items-center gap-2">
            <i class="fa-solid fa-user text-sm" style="background: linear-gradient(90deg,#3B59FF,#7B2FBE); -webkit-background-clip:text; -webkit-text-fill-color:transparent;"></i>
            Información de la cuenta
        </h2>

        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('email') border-red-400 @enderror">
                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition"
                    style="background: linear-gradient(90deg,#3B59FF,#7B2FBE)">
                Guardar cambios
            </button>
        </form>
    </div>

    {{-- Cambiar contraseña --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-5 flex items-center gap-2">
            <i class="fa-solid fa-lock text-sm" style="background: linear-gradient(90deg,#3B59FF,#7B2FBE); -webkit-background-clip:text; -webkit-text-fill-color:transparent;"></i>
            Cambiar contraseña
        </h2>

        <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña actual</label>
                <input type="password" name="current_password" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('current_password') border-red-400 @enderror">
                @error('current_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('password') border-red-400 @enderror">
                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <button type="submit" class="text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition"
                    style="background: linear-gradient(90deg,#3B59FF,#7B2FBE)">
                Actualizar contraseña
            </button>
        </form>
    </div>

</div>

@endsection

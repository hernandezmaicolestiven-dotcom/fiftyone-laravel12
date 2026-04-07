@extends('admin.layouts.app')
@section('title', 'Colaboradores')
@section('page-title', 'Colaboradores')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Formulario agregar --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-base font-bold text-gray-800 mb-1">Agregar colaborador</h2>
        <p class="text-xs text-gray-400 mb-5">Puede gestionar productos, categorías y pedidos.</p>

        <form method="POST" action="{{ route('admin.colaboradores.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50"
                       placeholder="Nombre completo">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50"
                       placeholder="correo@ejemplo.com">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Contraseña</label>
                <div class="relative">
                    <input type="password" name="password" id="col_pass" required
                           class="w-full px-3 py-2.5 pr-10 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50"
                           placeholder="Mínimo 8 caracteres">
                    <button type="button" onclick="toggleColPass()"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-indigo-500">
                        <i id="eye-col" class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            @if($errors->any())
            <p class="text-xs text-red-500">{{ $errors->first() }}</p>
            @endif

            <button type="submit"
                    class="w-full py-2.5 rounded-xl text-white text-sm font-semibold transition hover:opacity-90"
                    style="background:linear-gradient(90deg,#3B59FF,#7B2FBE)">
                <i class="fa-solid fa-user-plus mr-1.5"></i> Agregar colaborador
            </button>
        </form>
    </div>

    {{-- Lista colaboradores --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-800">Colaboradores activos</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $colaboradores->count() }} colaborador{{ $colaboradores->count() !== 1 ? 'es' : '' }}</p>
        </div>

        @forelse($colaboradores as $col)
        <div class="px-6 py-4 border-b border-gray-50 last:border-0 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                     style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">
                    {{ strtoupper(substr($col->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $col->name }}</p>
                    <p class="text-xs text-gray-400">{{ $col->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-indigo-100 text-indigo-700">
                    <i class="fa-solid fa-user-shield mr-1"></i>Colaborador
                </span>
                <span class="text-xs text-gray-400">{{ $col->created_at->format('d/m/Y') }}</span>
                <form method="POST" action="{{ route('admin.colaboradores.destroy', $col) }}"
                      onsubmit="return confirm('¿Eliminar a {{ $col->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="px-6 py-16 text-center">
            <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center"
                 style="background:linear-gradient(135deg,rgba(59,89,255,.1),rgba(123,47,190,.1))">
                <i class="fa-solid fa-user-shield text-2xl text-indigo-400"></i>
            </div>
            <p class="text-gray-600 font-semibold">Sin colaboradores aún</p>
            <p class="text-gray-400 text-sm mt-1">Agrega el primero con el formulario.</p>
        </div>
        @endforelse
    </div>
</div>

<script>
function toggleColPass() {
    const input = document.getElementById('col_pass');
    const eye   = document.getElementById('eye-col');
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection

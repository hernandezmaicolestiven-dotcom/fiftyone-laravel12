@extends('admin.layouts.app')
@section('title','Papelera de cupones')
@section('page-title','Papelera — Cupones')
@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Papelera de cupones</h2>
            <p class="text-sm text-gray-400 mt-0.5">{{ $coupons->count() }} cupón(es) eliminado(s)</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i> Volver
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-400">
                <th class="px-6 py-3 text-left">Código</th>
                <th class="px-6 py-3 text-left">Tipo</th>
                <th class="px-6 py-3 text-left">Valor</th>
                <th class="px-6 py-3 text-left">Eliminado</th>
                <th class="px-6 py-3 text-right">Acciones</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($coupons as $coupon)
                <tr class="hover:bg-gray-50 opacity-75">
                    <td class="px-6 py-4 font-mono text-gray-500 line-through">{{ $coupon->code }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $coupon->type === 'percent' ? 'Porcentaje' : 'Fijo' }}</td>
                    <td class="px-6 py-4 text-gray-500 font-semibold">
                        {{ $coupon->type === 'percent' ? $coupon->value . '%' : '$' . number_format($coupon->value, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $coupon->deleted_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                        <form method="POST" action="{{ route('admin.coupons.restore', $coupon->id) }}">
                            @csrf @method('PATCH')
                            <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Restaurar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.coupons.force-delete', $coupon->id) }}"
                              onsubmit="return confirm('¿Eliminar permanentemente?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                <i class="fa-solid fa-trash mr-1"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-16 text-center text-gray-400">
                    <i class="fa-solid fa-trash text-4xl mb-3 block opacity-20"></i> La papelera está vacía.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

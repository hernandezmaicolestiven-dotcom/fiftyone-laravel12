@extends('pages.layout')
@section('title', 'Envíos')
@section('content')
<div class="text-center mb-12">
    <span class="text-indigo-600 text-sm font-semibold uppercase tracking-widest">Logística</span>
    <h1 class="text-4xl font-black text-gray-900 mt-2">Información de Envíos</h1>
</div>
<div class="space-y-6">
    @foreach([
        ['fa-truck','Envío estándar','3 a 5 días hábiles. Disponible para todo Colombia. Costo calculado al finalizar la compra.'],
        ['fa-bolt','Envío express','1 a 2 días hábiles. Disponible en ciudades principales (Bogotá, Medellín, Cali, Barranquilla).'],
        ['fa-box','Empaque','Todas las prendas se envían en bolsa sellada con etiqueta FiftyOne. Empaque seguro y discreto.'],
        ['fa-rotate-left','Seguimiento','Recibirás un número de guía por email para rastrear tu pedido en tiempo real.'],
        ['fa-shield-halved','Garantía de entrega','Si tu pedido no llega en el tiempo estimado, te contactamos y gestionamos la solución.'],
    ] as [$icon,$title,$desc])
    <div class="flex gap-4 p-6 rounded-2xl bg-gray-50 border border-gray-100">
        <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid {{ $icon }} text-indigo-600 text-lg"></i>
        </div>
        <div>
            <h3 class="font-bold text-gray-800 mb-1">{{ $title }}</h3>
            <p class="text-gray-500 text-sm">{{ $desc }}</p>
        </div>
    </div>
    @endforeach
</div>
@endsection

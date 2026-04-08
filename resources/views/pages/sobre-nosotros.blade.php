@extends('pages.layout')
@section('title', 'Sobre Nosotros')
@section('content')
<div class="text-center mb-12">
    <span class="text-indigo-600 text-sm font-semibold uppercase tracking-widest">Nuestra historia</span>
    <h1 class="text-4xl font-black text-gray-900 mt-2">Sobre FiftyOne</h1>
</div>
<div class="prose prose-lg max-w-none text-gray-600 space-y-6">
    <p>FiftyOne nació de la pasión por el streetwear y la moda oversize. Somos una marca colombiana dedicada a crear prendas de alta calidad con un estilo urbano y contemporáneo.</p>
    <p>Nuestro nombre hace referencia al punto de equilibrio perfecto — ni demasiado holgado, ni demasiado ajustado. El fit oversize que todos buscan.</p>
    <p>Trabajamos con telas premium, costuras reforzadas y diseños que combinan comodidad con estilo. Cada prenda es pensada para durar y para que te sientas bien en ella.</p>
    <div class="grid grid-cols-3 gap-6 my-10">
        @foreach([['2022','Año de fundación'],['500+','Clientes satisfechos'],['25+','Productos únicos']] as [$val,$lab])
        <div class="text-center p-6 rounded-2xl bg-gray-50 border border-gray-100">
            <p class="text-3xl font-black text-indigo-600">{{ $val }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $lab }}</p>
        </div>
        @endforeach
    </div>
    <p>Estamos ubicados en Colombia y enviamos a todo el país. Nuestra misión es hacer que el streetwear de calidad sea accesible para todos.</p>
</div>
@endsection

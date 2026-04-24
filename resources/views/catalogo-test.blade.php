<!DOCTYPE html>
<html lang="es">
@php use Illuminate\Support\Facades\Storage; @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Catálogo</title>
</head>
<body>
    <h1>Test de Datos</h1>
    
    <h2>Productos ({{ $products->count() }})</h2>
    <ul>
        @foreach($products->take(5) as $p)
            <li>{{ $p->name }} - ${{ number_format($p->price, 0) }}</li>
        @endforeach
    </ul>
    
    <h2>Categorías ({{ $categories->count() }})</h2>
    <ul>
        @foreach($categories as $c)
            <li>{{ $c->name }}</li>
        @endforeach
    </ul>
    
    <hr>
    <a href="/catalogo">Ir al catálogo real</a>
</body>
</html>

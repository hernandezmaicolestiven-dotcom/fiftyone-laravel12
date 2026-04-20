<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Etiqueta #{{ $order->id }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial,sans-serif; background:#fff; display:flex; align-items:center; justify-content:center; min-height:100vh; }
.label { width:400px; border:2px solid #1f2937; border-radius:12px; overflow:hidden; }
.label-header { background:linear-gradient(135deg,#0d0d1a,#0a0e2e); color:white; padding:14px 20px; display:flex; justify-content:space-between; align-items:center; }
.label-header .brand { font-size:18px; font-weight:900; }
.label-header .brand span { color:#3B59FF; }
.label-header .order { font-size:11px; color:#94a3b8; text-align:right; }
.label-header .order strong { font-size:16px; color:white; display:block; }
.section { padding:14px 20px; border-bottom:1px dashed #e5e7eb; }
.section h4 { font-size:9px; text-transform:uppercase; letter-spacing:1px; color:#9ca3af; font-weight:700; margin-bottom:6px; }
.section p { font-size:14px; font-weight:700; color:#1f2937; line-height:1.5; }
.section .sub { font-size:12px; font-weight:400; color:#6b7280; }
.items { padding:14px 20px; }
.items h4 { font-size:9px; text-transform:uppercase; letter-spacing:1px; color:#9ca3af; font-weight:700; margin-bottom:8px; }
.item { display:flex; justify-content:space-between; font-size:12px; padding:3px 0; border-bottom:1px solid #f3f4f6; }
.barcode { padding:14px 20px; text-align:center; background:#f8fafc; }
.barcode .num { font-family:monospace; font-size:20px; font-weight:900; letter-spacing:4px; color:#1f2937; }
.barcode .bars { display:flex; gap:2px; justify-content:center; margin:8px 0 4px; }
.barcode .bars span { display:inline-block; width:{{ rand(2,4) }}px; height:40px; background:#1f2937; }
@media print { .no-print { display:none; } body { display:block; } }
</style>
</head>
<body>
<div>
<div class="no-print" style="text-align:center;margin-bottom:16px;display:flex;gap:8px;justify-content:center">
    <button onclick="window.print()" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);color:white;border:none;padding:8px 20px;border-radius:8px;font-weight:700;cursor:pointer">
        🖨️ Imprimir etiqueta
    </button>
    <button onclick="window.close()" style="background:#f1f5f9;color:#374151;border:1px solid #e5e7eb;padding:8px 20px;border-radius:8px;font-weight:600;cursor:pointer">
        Cerrar
    </button>
</div>

<div class="label">
    <div class="label-header">
        <div class="brand">Fifty<span>One</span></div>
        <div class="order">
            Pedido
            <strong>#{{ str_pad($order->id,6,'0',STR_PAD_LEFT) }}</strong>
            {{ $order->created_at->format('d/m/Y') }}
        </div>
    </div>

    <div class="section">
        <h4>📦 Destinatario</h4>
        <p>{{ $order->customer_name }}</p>
        @if($order->customer_phone)<p class="sub">📞 {{ $order->customer_phone }}</p>@endif
        @if($order->customer_email)<p class="sub">✉️ {{ $order->customer_email }}</p>@endif
    </div>

    <div class="section">
        <h4>🏪 Remitente</h4>
        <p>FiftyOne</p>
        <p class="sub">contacto@fiftyone.com · Colombia</p>
    </div>

    <div class="items">
        <h4>Contenido del paquete</h4>
        @foreach($order->items as $item)
        <div class="item">
            <span>{{ $item->product_name }}</span>
            <span>×{{ $item->quantity }}</span>
        </div>
        @endforeach
    </div>

    <div class="barcode">
        <div class="bars">
            @for($i=0;$i<40;$i++)
            <span style="width:{{ rand(1,4) }}px;height:{{ rand(30,45) }}px"></span>
            @endfor
        </div>
        <div class="num">{{ str_pad($order->id,6,'0',STR_PAD_LEFT) }}-FO</div>
    </div>
</div>
</div>
</body>
</html>

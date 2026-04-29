<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Factura #<?php echo e($order->id); ?></title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial,sans-serif; font-size:13px; color:#1f2937; background:#fff; }
.header { background:linear-gradient(135deg,#0d0d1a,#0a0e2e); color:white; padding:28px 32px; display:flex; justify-content:space-between; align-items:center; }
.logo { font-size:24px; font-weight:900; }
.logo span { color:#3B59FF; }
.invoice-num { text-align:right; }
.invoice-num h2 { font-size:20px; font-weight:800; }
.invoice-num p { font-size:11px; color:#94a3b8; margin-top:2px; }
.body { padding:28px 32px; }
.grid2 { display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px; }
.box { background:#f8fafc; border-radius:12px; padding:16px; }
.box h4 { font-size:10px; text-transform:uppercase; letter-spacing:.8px; color:#6b7280; font-weight:700; margin-bottom:8px; }
.box p { font-size:13px; color:#1f2937; line-height:1.6; }
table { width:100%; border-collapse:collapse; margin-bottom:20px; }
thead tr { background:#f1f5f9; }
thead th { padding:10px 14px; text-align:left; font-size:10px; text-transform:uppercase; letter-spacing:.8px; color:#6b7280; font-weight:700; }
tbody td { padding:10px 14px; border-bottom:1px solid #f3f4f6; }
.total-box { background:linear-gradient(135deg,#0d0d1a,#0a0e2e); color:white; border-radius:12px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; }
.total-box .label { font-size:12px; color:#94a3b8; }
.total-box .amount { font-size:22px; font-weight:900; }
.status { display:inline-block; padding:4px 12px; border-radius:999px; font-size:11px; font-weight:700; }
.footer { padding:16px 32px; text-align:center; font-size:10px; color:#9ca3af; border-top:1px solid #f3f4f6; margin-top:8px; }
@media print { .no-print { display:none; } }
</style>
</head>
<body>
<div class="no-print" style="padding:12px 32px;background:#f8fafc;border-bottom:1px solid #e5e7eb;display:flex;gap:8px">
    <button onclick="window.print()" style="background:linear-gradient(90deg,#3B59FF,#7B2FBE);color:white;border:none;padding:8px 20px;border-radius:8px;font-weight:700;cursor:pointer;font-size:13px">
        🖨️ Imprimir / Guardar PDF
    </button>
    <button onclick="window.close()" style="background:#f1f5f9;color:#374151;border:1px solid #e5e7eb;padding:8px 20px;border-radius:8px;font-weight:600;cursor:pointer;font-size:13px">
        Cerrar
    </button>
</div>

<div class="header">
    <div class="logo">Fifty<span>One</span></div>
    <div class="invoice-num">
        <h2>FACTURA</h2>
        <p>#<?php echo e(str_pad($order->id, 6, '0', STR_PAD_LEFT)); ?></p>
        <p><?php echo e($order->created_at->format('d/m/Y')); ?></p>
    </div>
</div>

<div class="body">
    <div class="grid2">
        <div class="box">
            <h4>Cliente</h4>
            <p><strong><?php echo e($order->customer_name); ?></strong></p>
            <?php if($order->customer_email): ?><p><?php echo e($order->customer_email); ?></p><?php endif; ?>
            <?php if($order->customer_phone): ?><p><?php echo e($order->customer_phone); ?></p><?php endif; ?>
        </div>
        <div class="box">
            <h4>Estado del pedido</h4>
            <p style="margin-bottom:6px"><strong>Pedido #<?php echo e($order->id); ?></strong></p>
            <span class="status" style="background:<?php echo e(match($order->status){ 'delivered'=>'#d1fae5','shipped'=>'#e0e7ff','confirmed'=>'#dbeafe','cancelled'=>'#fee2e2',default=>'#fef3c7'}); ?>;color:<?php echo e(match($order->status){'delivered'=>'#065f46','shipped'=>'#3730a3','confirmed'=>'#1e40af','cancelled'=>'#991b1b',default=>'#92400e'}); ?>">
                <?php echo e($order->status_label); ?>

            </span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th style="text-align:center">Cant.</th>
                <th style="text-align:right">Precio unit.</th>
                <th style="text-align:right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($item->product_name); ?></td>
                <td style="text-align:center"><?php echo e($item->quantity); ?></td>
                <td style="text-align:right">$ <?php echo e(number_format($item->price,0,',','.')); ?></td>
                <td style="text-align:right;font-weight:700">$ <?php echo e(number_format($item->subtotal,0,',','.')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="total-box">
        <div>
            <div class="label">Total a pagar</div>
            <?php if($order->notes): ?><div style="font-size:11px;color:#94a3b8;margin-top:4px">Nota: <?php echo e($order->notes); ?></div><?php endif; ?>
        </div>
        <div class="amount">$ <?php echo e(number_format($order->total,0,',','.')); ?></div>
    </div>
</div>

<div class="footer">
    FiftyOne — Ropa Oversize Premium · contacto@fiftyone.com · Colombia
</div>
</body>
</html>
<?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\admin\generators\invoice.blade.php ENDPATH**/ ?>
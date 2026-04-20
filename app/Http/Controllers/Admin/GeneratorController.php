<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class GeneratorController extends Controller
{
    // ── Factura HTML imprimible ───────────────────────────────────────────
    public function invoice(Order $order)
    {
        $order->load('items');
        $html = view('admin.generators.invoice', compact('order'))->render();
        return response($html)->header('Content-Type', 'text/html');
    }

    // ── Etiqueta de envío ─────────────────────────────────────────────────
    public function label(Order $order)
    {
        $html = view('admin.generators.label', compact('order'))->render();
        return response($html)->header('Content-Type', 'text/html');
    }
}

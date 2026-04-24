<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Muestra la factura de un pedido
     */
    public function show(Order $order)
    {
        // Verificar que el usuario tenga acceso al pedido
        if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para ver esta factura');
        }

        // Obtener o crear factura
        $invoice = $this->invoiceService->getOrCreateInvoice($order);

        return view('invoice.show', compact('invoice', 'order'));
    }

    /**
     * Descarga la factura en PDF (placeholder)
     */
    public function download(Order $order)
    {
        // Verificar permisos
        if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $invoice = $this->invoiceService->getOrCreateInvoice($order);

        // Aquí puedes implementar generación de PDF con DomPDF o similar
        // Por ahora retornamos la vista para imprimir
        return view('invoice.print', compact('invoice', 'order'));
    }

    /**
     * API: Obtiene datos de factura en JSON
     */
    public function getInvoiceData(Order $order)
    {
        if (auth()->id() !== $order->user_id && !auth()->user()->isAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $invoice = $this->invoiceService->getOrCreateInvoice($order);

        return response()->json([
            'invoice' => $invoice,
            'order' => $order->load('items.product'),
        ]);
    }
}

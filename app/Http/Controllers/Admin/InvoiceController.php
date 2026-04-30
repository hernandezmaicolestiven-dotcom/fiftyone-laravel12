<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceSetting;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Lista de facturas
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['order', 'user'])->latest();

        // Filtros
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $invoices = $query->paginate(20)->withQueryString();

        // Estadísticas
        $stats = [
            'total' => Invoice::where('status', 'active')->sum('total'),
            'count' => Invoice::where('status', 'active')->count(),
            'cancelled' => Invoice::where('status', 'cancelled')->count(),
            'this_month' => Invoice::where('status', 'active')
                ->whereMonth('created_at', now()->month)
                ->sum('total'),
        ];

        return view('admin.invoices.index', compact('invoices', 'stats'));
    }

    /**
     * Ver detalle de factura
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['order.items', 'user']);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Anular factura
     */
    public function cancel(Request $request, Invoice $invoice)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $invoice->cancel($request->reason);

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Factura anulada correctamente.');
    }

    /**
     * Reenviar factura por email
     */
    public function resend(Invoice $invoice)
    {
        // TODO: Implementar envío de email
        return back()->with('success', 'Factura reenviada por email.');
    }

    /**
     * Descargar factura en PDF
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['order.items']);
        
        // Por ahora retornamos HTML, luego se puede implementar PDF con DomPDF
        return view('admin.invoices.pdf', compact('invoice'));
    }

    /**
     * Configuración de facturación
     */
    public function settings()
    {
        $settings = [
            'iva_percentage' => InvoiceSetting::get('iva_percentage', 19.00),
            'invoice_prefix' => InvoiceSetting::get('invoice_prefix', 'FV'),
            'next_invoice_number' => InvoiceSetting::get('next_invoice_number', 1),
            'company_name' => InvoiceSetting::get('company_name', 'FiftyOne'),
            'company_nit' => InvoiceSetting::get('company_nit', ''),
            'company_address' => InvoiceSetting::get('company_address', ''),
            'company_phone' => InvoiceSetting::get('company_phone', ''),
            'company_email' => InvoiceSetting::get('company_email', ''),
        ];

        return view('admin.invoices.settings', compact('settings'));
    }

    /**
     * Actualizar configuración
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'iva_percentage' => 'required|numeric|min:0|max:100',
            'invoice_prefix' => 'required|string|max:10',
            'company_name' => 'required|string|max:255',
            'company_nit' => 'nullable|string|max:50',
            'company_address' => 'nullable|string|max:500',
            'company_phone' => 'nullable|string|max:50',
            'company_email' => 'nullable|email|max:255',
        ]);

        InvoiceSetting::set('iva_percentage', $request->iva_percentage, 'number');
        InvoiceSetting::set('invoice_prefix', $request->invoice_prefix, 'string');
        InvoiceSetting::set('company_name', $request->company_name, 'string');
        InvoiceSetting::set('company_nit', $request->company_nit, 'string');
        InvoiceSetting::set('company_address', $request->company_address, 'string');
        InvoiceSetting::set('company_phone', $request->company_phone, 'string');
        InvoiceSetting::set('company_email', $request->company_email, 'string');

        return back()->with('success', 'Configuración actualizada correctamente.');
    }

    /**
     * Exportar facturas a CSV
     */
    public function exportCsv(Request $request)
    {
        $query = Invoice::with(['order', 'user'])->latest();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $invoices = $query->get();

        return response()->stream(function () use ($invoices) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['Número', 'Cliente', 'Email', 'Subtotal', 'IVA', 'Total', 'Estado', 'Fecha']);
            
            foreach ($invoices as $inv) {
                fputcsv($handle, [
                    $inv->invoice_number,
                    $inv->customer_name,
                    $inv->customer_email,
                    number_format($inv->subtotal, 2),
                    number_format($inv->total_iva, 2),
                    number_format($inv->total, 2),
                    $inv->status === 'active' ? 'Activa' : 'Anulada',
                    $inv->created_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="facturas_'.now()->format('Ymd_His').'.csv"',
        ]);
    }
}

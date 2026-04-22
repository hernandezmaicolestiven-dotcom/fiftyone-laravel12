<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_name', 'like', '%'.$request->search.'%')
                    ->orWhere('customer_email', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $orders = $this->filteredQuery($request)->get();

        return response()->stream(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['ID', 'Cliente', 'Email', 'Teléfono', 'Items', 'Total', 'Estado', 'Fecha']);
            foreach ($orders as $o) {
                fputcsv($handle, [
                    $o->id, $o->customer_name, $o->customer_email ?? '',
                    $o->customer_phone ?? '', $o->items->count(),
                    number_format($o->total, 2), $o->status_label,
                    $o->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="pedidos_'.now()->format('Ymd_His').'.csv"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $orders = $this->filteredQuery($request)->get();

        $statusColors = [
            'pending' => '#d97706',
            'confirmed' => '#2563eb',
            'shipped' => '#4f46e5',
            'delivered' => '#059669',
            'cancelled' => '#dc2626',
        ];
        $statusBg = [
            'pending' => '#fef3c7',
            'confirmed' => '#dbeafe',
            'shipped' => '#e0e7ff',
            'delivered' => '#d1fae5',
            'cancelled' => '#fee2e2',
        ];

        $rows = '';
        foreach ($orders as $o) {
            $sc = $statusColors[$o->status] ?? '#6b7280';
            $sbg = $statusBg[$o->status] ?? '#f3f4f6';
            $rows .= "
            <tr>
                <td style='padding:10px 14px;color:#6b7280;font-family:monospace;font-size:12px'>#".$o->id."</td>
                <td style='padding:10px 14px;font-weight:600;color:#1f2937'>".htmlspecialchars($o->customer_name)."</td>
                <td style='padding:10px 14px;color:#6b7280;font-size:12px'>".htmlspecialchars($o->customer_email ?? '—')."<br><span style='font-size:11px'>".htmlspecialchars($o->customer_phone ?? '')."</span></td>
                <td style='padding:10px 14px;color:#374151;text-align:center'>".$o->items->count()."</td>
                <td style='padding:10px 14px;font-weight:700;color:#1f2937'>\$".number_format($o->total, 2)."</td>
                <td style='padding:10px 14px'>
                    <span style='background:{$sbg};color:{$sc};padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600'>".$o->status_label."</span>
                </td>
                <td style='padding:10px 14px;color:#9ca3af;font-size:11px'>".$o->created_at->format('d/m/Y H:i')."</td>
            </tr>
            <tr><td colspan='7' style='padding:0;border-bottom:1px solid #f3f4f6'></td></tr>";
        }

        $total = $orders->sum('total');
        $count = $orders->count();
        $date = now()->format('d/m/Y H:i');
        $filters = [];
        if ($request->filled('search')) {
            $filters[] = 'Cliente: '.$request->search;
        }
        if ($request->filled('status')) {
            $filters[] = 'Estado: '.($orders->first()?->status_label ?? $request->status);
        }
        if ($request->filled('date_from')) {
            $filters[] = 'Desde: '.Carbon::parse($request->date_from)->format('d/m/Y');
        }
        if ($request->filled('date_to')) {
            $filters[] = 'Hasta: '.Carbon::parse($request->date_to)->format('d/m/Y');
        }
        $filterStr = $filters ? implode(' &nbsp;·&nbsp; ', $filters) : 'Sin filtros aplicados';

        $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'>
        <style>
            * { margin:0; padding:0; box-sizing:border-box; }
            body { font-family: Arial, sans-serif; font-size: 13px; color: #1f2937; background: #fff; }
            .header { background: linear-gradient(135deg, #0d0d1a, #0a0e2e); color: white; padding: 28px 32px; }
            .header h1 { font-size: 22px; font-weight: 800; letter-spacing: -0.5px; }
            .header p  { font-size: 12px; color: #94a3b8; margin-top: 4px; }
            .meta { padding: 16px 32px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
            .meta .filters { font-size: 11px; color: #6b7280; }
            .meta .stats { display: flex; gap: 24px; }
            .meta .stat { text-align: right; }
            .meta .stat .val { font-size: 18px; font-weight: 800; color: #1f2937; }
            .meta .stat .lbl { font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; }
            table { width: 100%; border-collapse: collapse; }
            thead tr { background: #f1f5f9; }
            thead th { padding: 10px 14px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.8px; color: #6b7280; font-weight: 700; }
            tbody tr:hover { background: #fafafa; }
            .footer { padding: 16px 32px; text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #f3f4f6; margin-top: 8px; }
        </style></head><body>
        <div class='header'>
            <h1>📦 Reporte de Pedidos</h1>
            <p>Generado el {$date}</p>
        </div>
        <div class='meta'>
            <div class='filters'><strong>Filtros:</strong> {$filterStr}</div>
            <div class='stats'>
                <div class='stat'><div class='val'>{$count}</div><div class='lbl'>Pedidos</div></div>
                <div class='stat'><div class='val'>\$".number_format($total, 2)."</div><div class='lbl'>Total</div></div>
            </div>
        </div>
        <table>
            <thead><tr>
                <th>#</th><th>Cliente</th><th>Contacto</th><th style='text-align:center'>Items</th><th>Total</th><th>Estado</th><th>Fecha</th>
            </tr></thead>
            <tbody>{$rows}</tbody>
        </table>
        <div class='footer'>FiftyOne Admin &nbsp;·&nbsp; {$date} &nbsp;·&nbsp; {$count} pedidos exportados</div>
        </body></html>";

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="pedidos_'.now()->format('Ymd_His').'.html"',
        ]);
    }

    private function filteredQuery(Request $request)
    {
        $query = Order::with('items')->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_name', 'like', '%'.$request->search.'%')
                    ->orWhere('customer_email', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    public function show(Order $order)
    {
        $order->load('items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'          => 'required|in:pending,confirmed,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        try {
            // Guardar número de guía si se proporcionó
            if ($request->filled('tracking_number')) {
                $order->update(['tracking_number' => $request->tracking_number]);
            }

            $this->orderService->updateStatus($order, $request->status);

            return back()->with('success', 'Estado actualizado a "'.$order->fresh()->status_label.'".');
        } catch (\Exception $e) {
            Log::error('Error actualizando estado de pedido', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'No se pudo actualizar el estado. Intenta de nuevo.');
        }
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Pedido eliminado.');
    }
}

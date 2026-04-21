<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ── Reporte 1: Ventas ────────────────────────────────────────────────────
    public function sales(Request $request)
    {
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : now()->subDays(29)->startOfDay();
        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : now()->endOfDay();

        $status = $request->get('status', '');

        $query = Order::query()
            ->whereBetween('created_at', [$dateFrom, $dateTo]);
        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->get();

        // KPIs
        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();
        $avgOrder = $totalOrders ? $totalRevenue / $totalOrders : 0;
        $delivered = $orders->where('status', 'delivered')->count();

        // Línea diaria (últimos 30 días o rango)
        $days = collect();
        $current = $dateFrom->copy();
        while ($current <= $dateTo) {
            $days->push($current->copy());
            $current->addDay();
        }
        // Limitar a 60 días para no saturar el chart
        if ($days->count() > 60) {
            $days = collect(range(29, 0))->map(fn ($i) => now()->subDays($i));
            $dateFrom = $days->first()->startOfDay();
            $dateTo = $days->last()->endOfDay();
            $orders = Order::whereBetween('created_at', [$dateFrom, $dateTo])
                ->when($status, fn ($q) => $q->where('status', $status))->get();
        }

        $chartLabels = $days->map(fn ($d) => $d->format('d/m'))->values();
        $chartOrders = $days->map(fn ($d) => $orders->filter(fn ($o) => Carbon::parse($o->created_at)->isSameDay($d))->count())->values();
        $chartRevenue = $days->map(fn ($d) => (float) $orders->filter(fn ($o) => Carbon::parse($o->created_at)->isSameDay($d))->sum('total'))->values();

        // Dona por estado
        $statusCounts = [
            'Pendiente' => $orders->where('status', 'pending')->count(),
            'Confirmado' => $orders->where('status', 'confirmed')->count(),
            'Enviado' => $orders->where('status', 'shipped')->count(),
            'Entregado' => $orders->where('status', 'delivered')->count(),
            'Cancelado' => $orders->where('status', 'cancelled')->count(),
        ];

        // Top clientes con paginación manual
        $perPage = 10;
        $page    = (int) request('customers_page', 1);
        $allCustomers = $orders->groupBy('customer_email')
            ->map(fn ($g) => ['name' => $g->first()->customer_name, 'email' => $g->first()->customer_email, 'total' => $g->sum('total'), 'count' => $g->count()])
            ->sortByDesc('total')->values();
        $totalCustomers = $allCustomers->count();
        $topCustomers   = $allCustomers->slice(($page - 1) * $perPage, $perPage)->values();
        $customersPages = (int) ceil($totalCustomers / $perPage);

        return view('admin.reports.sales', compact(
            'totalRevenue', 'totalOrders', 'avgOrder', 'delivered',
            'chartLabels', 'chartOrders', 'chartRevenue',
            'statusCounts', 'topCustomers', 'totalCustomers', 'customersPages', 'page',
            'dateFrom', 'dateTo', 'status'
        ));
    }

    // ── Reporte 2: Inventario ────────────────────────────────────────────────
    public function inventory(Request $request)
    {
        $categoryId = $request->get('category', '');
        $stockFilter = $request->get('stock', '');

        $query = Product::with('category');
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($stockFilter === 'out') {
            $query->where('stock', 0);
        } elseif ($stockFilter === 'low') {
            $query->where('stock', '>', 0)->where('stock', '<', 5);
        } elseif ($stockFilter === 'ok') {
            $query->where('stock', '>=', 5);
        }

        $products = $query->orderBy('stock')->get();

        // KPIs
        $totalProducts = $products->count();
        $totalStock = $products->sum('stock');
        $outOfStock = $products->where('stock', 0)->count();
        $lowStock = $products->where('stock', '>', 0)->where('stock', '<', 5)->count();
        $totalValue = $products->sum(fn ($p) => $p->price * $p->stock);

        // Barras por categoría
        $byCategory = $products->groupBy(fn ($p) => $p->category?->name ?? 'Sin categoría')
            ->map(fn ($g) => ['count' => $g->count(), 'stock' => $g->sum('stock'), 'value' => $g->sum(fn ($p) => $p->price * $p->stock)])
            ->sortByDesc('stock')->values();

        $catLabels = $byCategory->pluck('count')->values();
        $catNames = $byCategory->keys()->values();
        $catStock = $byCategory->pluck('stock')->values();

        // Dona stock status
        $stockStatus = [
            'Sin stock' => $products->where('stock', 0)->count(),
            'Stock bajo' => $products->where('stock', '>', 0)->where('stock', '<', 5)->count(),
            'Normal' => $products->where('stock', '>=', 5)->where('stock', '<', 20)->count(),
            'Alto' => $products->where('stock', '>=', 20)->count(),
        ];

        $categories = Category::orderBy('name')->get();

        return view('admin.reports.inventory', compact(
            'products', 'totalProducts', 'totalStock', 'outOfStock', 'lowStock', 'totalValue',
            'catNames', 'catStock', 'catLabels', 'stockStatus',
            'categories', 'categoryId', 'stockFilter'
        ));
    }

    // ── Reporte 3: Productos más vendidos ────────────────────────────────────
    public function topProducts(Request $request)
    {
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : now()->subDays(29)->startOfDay();
        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : now()->endOfDay();

        $categoryId = $request->get('category', '');

        $itemQuery = OrderItem::with(['product.category', 'order'])
            ->whereHas('order', fn ($q) => $q->whereBetween('created_at', [$dateFrom, $dateTo]));

        $items = $itemQuery->get();

        // Agrupar por producto
        $topProducts = $items->groupBy('product_name')
            ->map(fn ($g) => [
                'name' => $g->first()->product_name,
                'category' => $g->first()->product?->category?->name ?? '—',
                'qty' => $g->sum('quantity'),
                'revenue' => $g->sum('subtotal'),
                'orders' => $g->pluck('order_id')->unique()->count(),
            ])
            ->when($categoryId, fn ($col) => $col->filter(fn ($p) => $items->where('product_name', $p['name'])->first()?->product?->category_id == $categoryId
            ))
            ->sortByDesc('revenue')->take(10)->values();

        // Barras horizontales
        $barLabels = $topProducts->pluck('name')->map(fn ($n) => \Str::limit($n, 20))->values();
        $barQty = $topProducts->pluck('qty')->values();
        $barRevenue = $topProducts->pluck('revenue')->values();

        // KPIs
        $totalQty = $items->sum('quantity');
        $totalRevenue = $items->sum('subtotal');
        $uniqueProds = $items->pluck('product_name')->unique()->count();

        $categories = Category::orderBy('name')->get();

        return view('admin.reports.top-products', compact(
            'topProducts', 'barLabels', 'barQty', 'barRevenue',
            'totalQty', 'totalRevenue', 'uniqueProds',
            'categories', 'categoryId', 'dateFrom', 'dateTo'
        ));
    }

    // ── PDF genérico ─────────────────────────────────────────────────────────
    public function exportPdf(Request $request, string $type)
    {
        return redirect()->route("admin.reports.$type", $request->query() + ['print' => 1]);
    }
}

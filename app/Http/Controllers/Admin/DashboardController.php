<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats cacheadas 2 minutos
        $stats = cache()->remember('admin_dashboard_stats', 120, function () {
            return [
                'products'       => Product::count(),
                'categories'     => Category::count(),
                'users'          => User::count(),
                'low_stock'      => Product::where('stock', '<', 5)->count(),
                'out_stock'      => Product::where('stock', 0)->count(),
                'total_stock'    => Product::sum('stock'),
                'orders'         => Order::count(),
                'orders_pending' => Order::where('status', 'pending')->count(),
            ];
        });

        $recentProducts  = Product::with('category')->latest()->take(5)->get();
        $topCategories   = Category::withCount('products')->orderByDesc('products_count')->take(5)->get();
        $lowStockProducts = Product::where('stock', '<', 5)->orderBy('stock')->take(4)->get();
        $recentOrders    = Order::with('items')->latest()->take(5)->get();

        // Chart: una sola query agrupada en vez de 7 queries separadas
        $chartRaw = Order::selectRaw('YEAR(created_at) as y, MONTH(created_at) as m, COUNT(*) as cnt, SUM(total) as rev')
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(fn($r) => $r->y . '-' . $r->m);

        $months      = collect(range(6, 0))->map(fn($i) => now()->subMonths($i));
        $chartLabels = $months->map(fn($m) => $m->translatedFormat('M y'))->values();
        $chartData   = $months->map(fn($m) => (int)   ($chartRaw[$m->year.'-'.$m->month]->cnt ?? 0))->values();
        $chartRevenue= $months->map(fn($m) => (float) ($chartRaw[$m->year.'-'.$m->month]->rev ?? 0))->values();

        return view('admin.dashboard', compact(
            'stats', 'recentProducts', 'chartLabels', 'chartData', 'chartRevenue',
            'topCategories', 'lowStockProducts', 'recentOrders'
        ));
    }
}

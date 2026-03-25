<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products'   => Product::count(),
            'categories' => Category::count(),
            'users'      => User::count(),
            'low_stock'  => Product::where('stock', '<', 5)->count(),
            'out_stock'  => Product::where('stock', 0)->count(),
            'total_stock'=> Product::sum('stock'),
            'orders'     => \App\Models\Order::count(),
            'orders_pending' => \App\Models\Order::where('status', 'pending')->count(),
        ];

        $recentProducts = Product::with('category')->latest()->take(5)->get();

        $topCategories = Category::withCount('products')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        $lowStockProducts = Product::where('stock', '<', 5)
            ->orderBy('stock')
            ->take(4)
            ->get();

        // Pedidos realizados por mes (últimos 7 meses)
        $months      = collect(range(6, 0))->map(fn($i) => now()->subMonths($i));
        $chartLabels = $months->map(fn($m) => $m->translatedFormat('M y'))->values();
        $chartData   = $months->map(fn($m) => \App\Models\Order::whereYear('created_at', $m->year)
                                                      ->whereMonth('created_at', $m->month)
                                                      ->count())->values();
        $chartRevenue = $months->map(fn($m) => (float) \App\Models\Order::whereYear('created_at', $m->year)
                                                      ->whereMonth('created_at', $m->month)
                                                      ->sum('total'))->values();

        $recentOrders = \App\Models\Order::with('items')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'recentProducts', 'chartLabels', 'chartData', 'chartRevenue',
            'topCategories', 'lowStockProducts', 'recentOrders'
        ));
    }
}

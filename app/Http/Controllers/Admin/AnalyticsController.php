<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * AnalyticsController — Estadísticas avanzadas e IA.
 *
 * Incluye:
 * - Datos históricos por año/mes
 * - Predicción de demanda con regresión lineal simple
 * - Análisis de tendencias de ventas
 */
class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->get('year', now()->year);

        // ── Datos históricos mensuales del año seleccionado ──────────────────
        $months = collect(range(1, 12))->map(function ($m) use ($year) {
            $orders = Order::whereYear('created_at', $year)->whereMonth('created_at', $m)->get();

            return [
                'month' => Carbon::create($year, $m)->translatedFormat('M'),
                'orders' => $orders->count(),
                'revenue' => (float) $orders->sum('total'),
                'avg' => $orders->count() ? round($orders->sum('total') / $orders->count(), 0) : 0,
            ];
        });

        // ── Años disponibles para el selector ────────────────────────────────
        $availableYears = Order::selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();
        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        // ── Top productos del año ─────────────────────────────────────────────
        $topProducts = OrderItem::with('product')
            ->whereHas('order', fn ($q) => $q->whereYear('created_at', $year))
            ->get()
            ->groupBy('product_name')
            ->map(fn ($g) => ['name' => $g->first()->product_name, 'qty' => $g->sum('quantity'), 'revenue' => $g->sum('subtotal')])
            ->sortByDesc('revenue')
            ->take(5)
            ->values();

        // ── KPIs del año ─────────────────────────────────────────────────────
        $yearOrders = Order::whereYear('created_at', $year)->get();
        $kpis = [
            'total_orders' => $yearOrders->count(),
            'total_revenue' => $yearOrders->sum('total'),
            'avg_order' => $yearOrders->count() ? round($yearOrders->sum('total') / $yearOrders->count(), 0) : 0,
            'delivered' => $yearOrders->where('status', 'delivered')->count(),
            'cancelled' => $yearOrders->where('status', 'cancelled')->count(),
            'conversion' => $yearOrders->count()
                ? round($yearOrders->where('status', 'delivered')->count() / $yearOrders->count() * 100, 1)
                : 0,
        ];

        // ── Predicción IA: regresión lineal simple sobre ingresos mensuales ──
        $prediction = $this->predictNextMonths($months->pluck('revenue')->toArray(), 3);

        // ── Comparativa año anterior ──────────────────────────────────────────
        $prevYear = $year - 1;
        $prevYearRevenue = Order::whereYear('created_at', $prevYear)->sum('total');
        $growthRate = $prevYearRevenue > 0
            ? round((($kpis['total_revenue'] - $prevYearRevenue) / $prevYearRevenue) * 100, 1)
            : null;

        return view('admin.analytics.index', compact(
            'months', 'year', 'availableYears', 'topProducts',
            'kpis', 'prediction', 'prevYearRevenue', 'growthRate'
        ));
    }

    /**
     * Predicción de ingresos usando regresión lineal simple (mínimos cuadrados).
     * No requiere dependencias externas — implementación pura en PHP.
     *
     * @param  float[]  $data  Serie histórica de valores
     * @param  int  $steps  Número de períodos a predecir
     * @return float[] Valores predichos
     */
    private function predictNextMonths(array $data, int $steps): array
    {
        // Filtrar solo meses con datos
        $nonZero = array_filter($data, fn($v) => $v > 0);

        if (count($nonZero) < 2) {
            // Si hay solo 1 mes con datos, proyectar con crecimiento del 10% mensual
            $base = count($nonZero) === 1 ? max($nonZero) : 0;
            if ($base === 0) {
                // Sin datos históricos — usar promedio de pedidos recientes
                $base = (float) Order::where('created_at', '>=', now()->subDays(30))->sum('total');
            }
            $predictions = [];
            for ($i = 0; $i < $steps; $i++) {
                $predictions[] = max(0, round($base * pow(1.10, $i + 1), 0));
            }
            return $predictions;
        }

        $n = count($data);
        $sumX = $sumY = $sumXY = $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumX  += $i;
            $sumY  += $data[$i];
            $sumXY += $i * $data[$i];
            $sumX2 += $i * $i;
        }

        $denom = ($n * $sumX2 - $sumX * $sumX);
        if ($denom == 0) {
            return array_fill(0, $steps, end($data));
        }

        $m = ($n * $sumXY - $sumX * $sumY) / $denom;
        $b = ($sumY - $m * $sumX) / $n;

        $predictions = [];
        for ($i = 0; $i < $steps; $i++) {
            $predictions[] = max(0, round($m * ($n + $i) + $b, 0));
        }

        return $predictions;
    }
}

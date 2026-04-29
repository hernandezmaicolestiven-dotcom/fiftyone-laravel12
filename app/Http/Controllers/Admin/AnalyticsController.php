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
            // Usar una sola query agrupada en lugar de 12 queries
            return [
                'month' => $m,
                'orders' => 0,
                'revenue' => 0,
                'customers' => 0,
            ];
        });
        
        // Query optimizada: una sola consulta para todo el año
        $yearData = Order::selectRaw('
                MONTH(created_at) as month,
                COUNT(*) as orders,
                SUM(total) as revenue,
                COUNT(DISTINCT user_id) as customers
            ')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        // Combinar datos
        $months = $months->map(function ($m) use ($yearData, $year) {
            $data = $yearData->get($m['month']);
            return [
                'month' => Carbon::create($year, $m['month'])->translatedFormat('M'),
                'orders' => $data->orders ?? 0,
                'revenue' => (float) ($data->revenue ?? 0),
                'avg' => ($data->orders ?? 0) > 0 ? round(($data->revenue ?? 0) / $data->orders, 0) : 0,
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

        // ── Top productos del año (optimizado) ────────────────────────────────
        $topProducts = OrderItem::selectRaw('
                product_name,
                SUM(quantity) as qty,
                SUM(subtotal) as revenue
            ')
            ->whereHas('order', fn ($q) => $q->whereYear('created_at', $year))
            ->groupBy('product_name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get()
            ->map(fn ($item) => [
                'name' => $item->product_name,
                'qty' => $item->qty,
                'revenue' => $item->revenue
            ]);

        // ── KPIs del año (optimizado con una sola query) ──────────────────────
        $yearStats = Order::selectRaw('
                COUNT(*) as total_orders,
                SUM(total) as total_revenue,
                AVG(total) as avg_order,
                SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled
            ')
            ->whereYear('created_at', $year)
            ->first();
        
        $kpis = [
            'total_orders' => $yearStats->total_orders ?? 0,
            'total_revenue' => $yearStats->total_revenue ?? 0,
            'avg_order' => round($yearStats->avg_order ?? 0, 0),
            'delivered' => $yearStats->delivered ?? 0,
            'cancelled' => $yearStats->cancelled ?? 0,
            'conversion' => ($yearStats->total_orders ?? 0) > 0
                ? round(($yearStats->delivered / $yearStats->total_orders) * 100, 1)
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

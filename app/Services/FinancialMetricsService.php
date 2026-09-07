<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\PrisonUnit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Métricas financeiras do painel (Módulo 2).
 *
 * Toda "receita" aqui é sempre líquida (payments PAID - refunds), na mesma
 * base do LTV do Módulo 1 — nunca orders.total ou order_items.subtotal
 * brutos, que não refletem status de pagamento real nem estorno.
 */
class FinancialMetricsService
{
    public function netRevenue(Carbon $from, Carbon $to): float
    {
        $paid = (float) DB::table('payments')
            ->where('status', PaymentStatus::PAID->name)
            ->whereBetween('created_at', [$from, $to])
            ->sum('amount');

        $refunded = (float) DB::table('refunds')
            ->whereBetween('created_at', [$from, $to])
            ->sum('amount');

        return round($paid - $refunded, 2);
    }

    public function paidOrdersCount(Carbon $from, Carbon $to): int
    {
        return Order::query()
            ->whereHas('payments', fn($q) => $q->where('status', PaymentStatus::PAID->name)
                ->whereBetween('created_at', [$from, $to]))
            ->count();
    }

    /**
     * Receita bruta por canal: 'site' (Mercado Pago, sem cash_session_id)
     * vs 'pdv' (balcão, com cash_session_id). Reembolsos são atribuídos ao
     * canal do primeiro pagamento do pedido — pedidos pagos em mais de um
     * canal (raro) podem ficar levemente imprecisos aqui.
     */
    public function revenueByChannel(Carbon $from, Carbon $to): array
    {
        $paid = DB::table('payments')
            ->selectRaw("CASE WHEN cash_session_id IS NULL THEN 'site' ELSE 'pdv' END as channel")
            ->selectRaw('SUM(amount) as total')
            ->where('status', PaymentStatus::PAID->name)
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('channel')
            ->pluck('total', 'channel');

        $refunded = DB::table('refunds')
            ->join('orders', 'orders.id', '=', 'refunds.order_id')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->selectRaw("CASE WHEN payments.cash_session_id IS NULL THEN 'site' ELSE 'pdv' END as channel")
            ->selectRaw('SUM(refunds.amount) as total')
            ->whereBetween('refunds.created_at', [$from, $to])
            ->groupBy('channel')
            ->pluck('total', 'channel');

        return [
            'site' => round(($paid['site'] ?? 0) - ($refunded['site'] ?? 0), 2),
            'pdv' => round(($paid['pdv'] ?? 0) - ($refunded['pdv'] ?? 0), 2),
        ];
    }

    /**
     * Receita bruta por método de pagamento. Pix/dinheiro/crédito/débito
     * vêm do PDV; tudo do site cai em "Mercado Pago" (o método granular do
     * Payment Brick não é capturado hoje).
     */
    public function revenueByPaymentMethod(Carbon $from, Carbon $to): array
    {
        return DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->join('payment_methods', 'payment_methods.id', '=', 'orders.payment_method_id')
            ->select('payment_methods.display_name')
            ->selectRaw('SUM(payments.amount) as total')
            ->where('payments.status', PaymentStatus::PAID->name)
            ->whereBetween('payments.created_at', [$from, $to])
            ->groupBy('payment_methods.id', 'payment_methods.display_name')
            ->orderByDesc('total')
            ->get()
            ->map(fn($row) => ['method' => $row->display_name, 'total' => (float) $row->total])
            ->all();
    }

    /**
     * Margem bruta no período, usando order_items.cost_price (snapshot).
     * Pedidos anteriores ao rollout dessa coluna usam custo aproximado
     * (ver BackfillOrderItemsCostPrice).
     */
    public function grossMargin(Carbon $from, Carbon $to): array
    {
        $row = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->selectRaw('SUM(order_items.price * order_items.quantity) as revenue')
            ->selectRaw('SUM(order_items.cost_price * order_items.quantity) as cost')
            ->where('payments.status', PaymentStatus::PAID->name)
            ->whereBetween('payments.created_at', [$from, $to])
            ->first();

        $revenue = (float) ($row->revenue ?? 0);
        $cost = (float) ($row->cost ?? 0);
        $profit = $revenue - $cost;

        return [
            'revenue' => round($revenue, 2),
            'cost' => round($cost, 2),
            'profit' => round($profit, 2),
            'margin_percent' => $revenue > 0 ? round($profit / $revenue * 100, 1) : 0,
        ];
    }

    /**
     * Curva ABC de produtos por receita bruta de item, no período.
     * A = até 80% da receita acumulada; B = até 95%; C = resto.
     */
    public function abcCurve(Carbon $from, Carbon $to, ?int $prisonUnitId = null): array
    {
        $rows = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->when($prisonUnitId, fn($q) => $q->where('orders.prison_unit_id', $prisonUnitId))
            ->select('products.id', 'products.name')
            ->selectRaw('SUM(order_items.price * order_items.quantity) as revenue')
            ->selectRaw('SUM(order_items.quantity) as quantity')
            ->where('payments.status', PaymentStatus::PAID->name)
            ->whereBetween('payments.created_at', [$from, $to])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->get();

        $totalRevenue = $rows->sum('revenue');
        $cumulative = 0;

        return $rows->map(function ($row) use (&$cumulative, $totalRevenue) {
            $cumulative += $row->revenue;
            $cumulativePercent = $totalRevenue > 0 ? ($cumulative / $totalRevenue) * 100 : 0;

            return [
                'product_id' => $row->id,
                'name' => $row->name,
                'revenue' => (float) $row->revenue,
                'quantity' => (int) $row->quantity,
                'cumulative_percent' => round($cumulativePercent, 1),
                'class' => $cumulativePercent <= 80 ? 'A' : ($cumulativePercent <= 95 ? 'B' : 'C'),
            ];
        })->all();
    }

    /**
     * Ranking de unidades prisionais por receita líquida no período.
     */
    public function prisonUnitRanking(Carbon $from, Carbon $to, int $limit = 10): array
    {
        $paid = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->select('orders.prison_unit_id')
            ->selectRaw('SUM(payments.amount) as total')
            ->selectRaw('COUNT(DISTINCT orders.id) as orders_count')
            ->where('payments.status', PaymentStatus::PAID->name)
            ->whereBetween('payments.created_at', [$from, $to])
            ->whereNotNull('orders.prison_unit_id')
            ->groupBy('orders.prison_unit_id')
            ->get()
            ->keyBy('prison_unit_id');

        $refunded = DB::table('refunds')
            ->join('orders', 'orders.id', '=', 'refunds.order_id')
            ->select('orders.prison_unit_id')
            ->selectRaw('SUM(refunds.amount) as total')
            ->whereBetween('refunds.created_at', [$from, $to])
            ->whereNotNull('orders.prison_unit_id')
            ->groupBy('orders.prison_unit_id')
            ->pluck('total', 'prison_unit_id');

        $prisonUnits = PrisonUnit::whereIn('id', $paid->keys())->pluck('name', 'id');

        return $paid->map(function ($row, $prisonUnitId) use ($refunded, $prisonUnits) {
            return [
                'prison_unit_id' => $prisonUnitId,
                'name' => $prisonUnits[$prisonUnitId] ?? __('Não identificada'),
                'revenue' => round((float) $row->total - ($refunded[$prisonUnitId] ?? 0), 2),
                'orders_count' => (int) $row->orders_count,
            ];
        })
        ->sortByDesc('revenue')
        ->take($limit)
        ->values()
        ->all();
    }
}
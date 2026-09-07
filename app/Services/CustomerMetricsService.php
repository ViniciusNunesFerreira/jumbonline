<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

/**
 * Mantém ltv_total / paid_orders_count / last_order_at denormalizados em `customers`.
 *
 * LTV = receita líquida: soma de payments.amount com status PAID, menos soma de
 * refunds.amount, dos pedidos do cliente.
 */
class CustomerMetricsService
{
    public function recalculate(Customer $customer): void
    {
        $orderIds = $customer->orders()->pluck('id');

        if ($orderIds->isEmpty()) {
            $customer->forceFill([
                'ltv_total' => 0,
                'paid_orders_count' => 0,
                'last_order_at' => null,
            ])->saveQuietly();

            return;
        }

        $totalPaid = (float) DB::table('payments')
            ->whereIn('order_id', $orderIds)
            ->where('status', PaymentStatus::PAID->name)
            ->sum('amount');

        $totalRefunded = (float) DB::table('refunds')
            ->whereIn('order_id', $orderIds)
            ->sum('amount');

        $paidOrderIds = DB::table('payments')
            ->whereIn('order_id', $orderIds)
            ->where('status', PaymentStatus::PAID->name)
            ->distinct()
            ->pluck('order_id');

        $lastOrderAt = $paidOrderIds->isNotEmpty()
            ? DB::table('orders')->whereIn('id', $paidOrderIds)->max('created_at')
            : null;

        $customer->forceFill([
            'ltv_total' => round($totalPaid - $totalRefunded, 2),
            'paid_orders_count' => $paidOrderIds->count(),
            'last_order_at' => $lastOrderAt,
        ])->saveQuietly();
    }
}
<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Refund;
use App\Services\CustomerMetricsService;
use Illuminate\Support\Facades\Log;

class RefundObserver
{
    public function saved(Refund $refund): void
    {
        $this->recalculate($refund);
    }

    public function deleted(Refund $refund): void
    {
        $this->recalculate($refund);
    }

    protected function recalculate(Refund $refund): void
    {
        try {
            $order = $refund->relationLoaded('order') ? $refund->order : Order::find($refund->order_id);

            if (! $order || ! $order->customer_id) {
                return;
            }

            $customer = Customer::find($order->customer_id);

            if ($customer) {
                app(CustomerMetricsService::class)->recalculate($customer);
            }
        } catch (\Throwable $e) {
            Log::error("CRM: falha ao recalcular métricas do cliente após reembolso #{$refund->id}: " . $e->getMessage());
        }
    }
}
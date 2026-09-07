<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Order;
use App\Services\CustomerMetricsService;

class OrderObserver
{
    public function created(Order $order): void
    {
        $this->recalculate($order);
    }

    public function updated(Order $order): void
    {
        if ($order->wasChanged('payment_status')) {
            $this->recalculate($order);
        }
    }

    public function deleted(Order $order): void
    {
        $this->recalculate($order);
    }

    protected function recalculate(Order $order): void
    {
        if (! $order->customer_id) {
            return;
        }

        $customer = $order->customer ?? Customer::find($order->customer_id);

        if ($customer) {
            app(CustomerMetricsService::class)->recalculate($customer);
        }
    }
}
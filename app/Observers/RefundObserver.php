<?php

namespace App\Observers;

use App\Models\Refund;
use App\Services\CustomerMetricsService;

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
        $customer = $refund->order?->customer;

        if ($customer) {
            app(CustomerMetricsService::class)->recalculate($customer);
        }
    }
}
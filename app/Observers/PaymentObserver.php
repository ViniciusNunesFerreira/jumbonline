<?php

namespace App\Observers;

use App\Models\Payment;
use App\Services\CustomerMetricsService;

class PaymentObserver
{
    public function saved(Payment $payment): void
    {
        $this->recalculate($payment);
    }

    public function deleted(Payment $payment): void
    {
        $this->recalculate($payment);
    }

    protected function recalculate(Payment $payment): void
    {
        $customer = $payment->order?->customer;

        if ($customer) {
            app(CustomerMetricsService::class)->recalculate($customer);
        }
    }
}
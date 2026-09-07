<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Services\CustomerMetricsService;
use Illuminate\Support\Facades\Log;

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
        try {
            $order = $payment->relationLoaded('order') ? $payment->order : Order::find($payment->order_id);

            if (! $order || ! $order->customer_id) {
                return;
            }

            $customer = Customer::find($order->customer_id);

            if ($customer) {
                app(CustomerMetricsService::class)->recalculate($customer);
            }
        } catch (\Throwable $e) {
            Log::error("CRM: falha ao recalcular métricas do cliente após pagamento #{$payment->id}: " . $e->getMessage());
        }
    }
}
<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\CustomerMetricsService;
use Illuminate\Console\Command;

class RecalculateCustomerMetrics extends Command
{
    protected $signature = 'customers:recalculate-metrics';

    protected $description = 'Recalcula LTV, pedidos pagos e última compra de todos os clientes (backfill do CRM)';

    public function handle(CustomerMetricsService $service): int
    {
        $total = Customer::count();

        $this->getOutput()->progressStart($total);

        Customer::query()->chunkById(200, function ($customers) use ($service) {
            foreach ($customers as $customer) {
                $service->recalculate($customer);
                $this->getOutput()->progressAdvance();
            }
        });

        $this->getOutput()->progressFinish();

        $this->info("Métricas recalculadas para {$total} clientes.");

        return self::SUCCESS;
    }
}
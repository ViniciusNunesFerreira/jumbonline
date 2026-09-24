<?php

namespace App\Console\Commands;

use App\Enums\ShippingCarrier;
use App\Models\Shipment;
use App\Services\CorreiosRastreamentoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AtualizarRastreamentoCorreios extends Command
{
    protected $signature = 'correios:atualizar-rastreamento';

    protected $description = 'Atualiza o cache de eventos de rastreio (SRO) das remessas Correios recentes — a página do cliente lê só desse cache, nunca chama a API ao vivo.';

    public function handle(CorreiosRastreamentoService $service): int
    {
        $shipments = Shipment::query()
            ->where('shipping_carrier', ShippingCarrier::CORREIOS->value)
            ->whereNotNull('tracking_number')
            ->where('created_at', '>=', now()->subDays(60))
            ->get();

        foreach ($shipments as $shipment) {
            try {
                $eventos = $service->eventos($shipment->tracking_number);

                Cache::put("correios.rastreio.{$shipment->tracking_number}", $eventos, now()->addMinutes(20));
            } catch (\Throwable $e) {
                Log::warning("Rastreamento Correios: falha ao atualizar {$shipment->tracking_number}: " . $e->getMessage());
            }
        }

        return self::SUCCESS;
    }
}
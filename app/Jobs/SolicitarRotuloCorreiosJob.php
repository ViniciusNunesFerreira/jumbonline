<?php

namespace App\Jobs;

use App\Models\Shipment;
use App\Services\CorreiosPrepostagemService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SolicitarRotuloCorreiosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $shipmentId)
    {
    }

    public function handle(CorreiosPrepostagemService $service): void
    {
        $shipment = Shipment::find($this->shipmentId);

        if (! $shipment || ! $shipment->correios_prepostagem_id || $shipment->correios_label_recibo) {
            return;
        }

        $data = $service->solicitarRotulo($shipment->correios_prepostagem_id);
        $recibo = $data['idRecibo'] ?? null;

        if (! $recibo) {
            Log::error("Correios: solicitação de rótulo do shipment #{$shipment->id} não retornou idRecibo.");
            return;
        }

        $shipment->update(['correios_label_recibo' => $recibo]);

        ConsultarRotuloCorreiosJob::dispatch($shipment->id)->delay(now()->addSeconds(8));
    }
}
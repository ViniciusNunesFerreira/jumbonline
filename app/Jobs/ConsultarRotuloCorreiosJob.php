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
use Illuminate\Support\Facades\Storage;

class ConsultarRotuloCorreiosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 6;

    public function __construct(public int $shipmentId)
    {
    }

    public function handle(CorreiosPrepostagemService $service): void
    {
        $shipment = Shipment::find($this->shipmentId);

        if (! $shipment || ! $shipment->correios_label_recibo) {
            return;
        }

        if (Storage::disk('local')->exists("correios-labels/{$shipment->id}.pdf")) {
            return;
        }

        $dados = $service->consultarRotulo($shipment->correios_label_recibo);

        if (empty($dados['dados'])) {
            $this->release(10);
            return;
        }

        Storage::disk('local')->put("correios-labels/{$shipment->id}.pdf", base64_decode($dados['dados']));
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Correios: rótulo do shipment #{$this->shipmentId} não ficou pronto depois de todas as tentativas — {$exception->getMessage()}");
    }
}
<?php

namespace App\Jobs;

use App\Enums\CorreiosPrepostagemStatus;
use App\Models\Shipment;
use App\Services\CorreiosPrepostagemService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AguardarStatusPrepostagemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 12; // 12 tentativas * 10 segundos = até 2 minutos aguardando

    public function __construct(public int $shipmentId)
    {
    }

    public function handle(CorreiosPrepostagemService $service): void
    {
        $shipment = Shipment::find($this->shipmentId);

        if (! $shipment || ! $shipment->correios_prepostagem_id) {
            return;
        }

        $status = $service->consultarStatus($shipment->correios_prepostagem_id);
        $novoStatus = $status['statusAtual'] ?? null;

        if ($novoStatus) {
            $shipment->update(['correios_status' => $novoStatus]);
        }

        // Se ainda estiver gerando a DCe (status 7), libera para tentar novamente em 10 segundos
        if ($novoStatus === CorreiosPrepostagemStatus::PENDENTE->value) {
            $this->release(10);
            return;
        }

        // Se o status mudou para PREATENDIDO (1) ou PREPOSTADO (2), a etiqueta já pode ser solicitada
        if (in_array($novoStatus, [
            CorreiosPrepostagemStatus::PREATENDIDO->value,
            CorreiosPrepostagemStatus::PREPOSTADO->value,
        ], true)) {
            SolicitarRotuloCorreiosJob::dispatch($shipment->id);
            return;
        }

        // Se cair em status cancelado, expirado ou desconhecido, encerra a execução sem tentar novamente
        Log::warning("Correios: shipment #{$this->shipmentId} encerrou com status não elegível para rótulo: {$novoStatus}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Correios: shipment #{$this->shipmentId} não saiu de Pendente depois de todas as tentativas — {$exception->getMessage()}");
    }
}
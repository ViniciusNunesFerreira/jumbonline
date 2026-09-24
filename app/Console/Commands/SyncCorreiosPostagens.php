<?php

namespace App\Console\Commands;

use App\Enums\CorreiosPrepostagemStatus;
use App\Enums\ShippingCarrier;
use App\Models\Shipment;
use App\Services\CorreiosPrepostagemService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SyncCorreiosPostagens extends Command
{
    protected $signature = 'correios:sincronizar-postagens';

    protected $description = 'Acompanha pré-postagens ainda em processamento e baixa o rótulo assim que a Correios liberar — substitui a necessidade de fila de jobs.';

    public function handle(CorreiosPrepostagemService $service): int
    {
        $pendentes = Shipment::query()
            ->where('shipping_carrier', ShippingCarrier::CORREIOS->value)
            ->whereNotNull('correios_prepostagem_id')
            ->where('correios_status', CorreiosPrepostagemStatus::PENDENTE->value)
            ->get();

        foreach ($pendentes as $shipment) {
            try {
                $status = $service->consultarStatus($shipment->correios_prepostagem_id);
                $novoStatus = $status['statusAtual'] ?? null;

                if ($novoStatus && $novoStatus !== $shipment->correios_status) {
                    $shipment->update(['correios_status' => $novoStatus]);
                    Log::info("Correios: shipment #{$shipment->id} mudou pra status {$novoStatus}.");
                }

                if ($novoStatus === CorreiosPrepostagemStatus::PREPOSTADO->value && ! $shipment->correios_label_recibo) {
                    $rotulo = $service->solicitarRotulo($shipment->correios_prepostagem_id);
                    $shipment->update(['correios_label_recibo' => $rotulo['idRecibo'] ?? null]);
                }
            } catch (\Throwable $e) {
                Log::error("Correios sync: falha ao consultar shipment #{$shipment->id}: " . $e->getMessage());
            }
        }

        $comRotuloPendente = Shipment::query()
            ->where('shipping_carrier', ShippingCarrier::CORREIOS->value)
            ->whereNotNull('correios_label_recibo')
            ->get()
            ->filter(fn($s) => ! Storage::disk('local')->exists("correios-labels/{$s->id}.pdf"));

        foreach ($comRotuloPendente as $shipment) {
            try {
                $dados = $service->consultarRotulo($shipment->correios_label_recibo);

                if (! empty($dados['dados'])) {
                    Storage::disk('local')->put("correios-labels/{$shipment->id}.pdf", base64_decode($dados['dados']));
                    Log::info("Correios: rótulo do shipment #{$shipment->id} salvo.");
                }
            } catch (\Throwable $e) {
                Log::error("Correios sync: falha ao baixar rótulo do shipment #{$shipment->id}: " . $e->getMessage());
            }
        }

        return self::SUCCESS;
    }
}
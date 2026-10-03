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

    /**
     * Status em que o rótulo já pode ser solicitado — os mesmos do
     * AguardarStatusPrepostagemJob. Antes este comando só aceitava
     * PREPOSTADO (2): envio que ia de PENDENTE (7) para PREATENDIDO (1)
     * ficava parado para sempre.
     */
    private const APTOS_PARA_ROTULO = [
        CorreiosPrepostagemStatus::PREATENDIDO,
        CorreiosPrepostagemStatus::PREPOSTADO,
    ];

    private const ENCERRADOS = [
        CorreiosPrepostagemStatus::POSTADO,
        CorreiosPrepostagemStatus::CANCELADO,
        CorreiosPrepostagemStatus::EXPIRADO,
        CorreiosPrepostagemStatus::ESTORNADO,
    ];

    public function handle(CorreiosPrepostagemService $service): int
    {
        // Antes: só status PENDENTE (7). Envio criado já como PREATENDIDO (1),
        // cujo job de rótulo não rodou, nunca era encontrado aqui. Agora: toda
        // pré-postagem sem recibo de rótulo e sem status final.
        $pendentes = Shipment::query()
            ->where('shipping_carrier', ShippingCarrier::CORREIOS->value)
            ->whereNotNull('correios_prepostagem_id')
            ->whereNull('correios_label_recibo')
            ->where(fn ($q) => $q->whereNull('correios_status')->orWhereNotIn('correios_status', array_map(fn ($s) => $s->value, self::ENCERRADOS)))
            ->get();

        $aptos = array_map(fn ($s) => $s->value, self::APTOS_PARA_ROTULO);

        foreach ($pendentes as $shipment) {
            try {
                $status = $service->consultarStatus($shipment->correios_prepostagem_id) ?? [];
                $novoStatus = isset($status['statusAtual']) ? (int) $status['statusAtual'] : null;

                $alteracoes = [];

                if ($novoStatus && $novoStatus !== (int) $shipment->correios_status) {
                    $alteracoes['correios_status'] = $novoStatus;
                    Log::info("Correios: shipment #{$shipment->id} mudou pra status {$novoStatus}.");
                }

                // Custo oficial do envio, só quando ainda não foi gravado.
                $custo = $this->valorMonetario($status['precoPrePostagem'] ?? null);

                if ($shipment->cost === null && $custo !== null) {
                    $alteracoes['cost'] = $custo;
                }

                if ($alteracoes) {
                    $shipment->update($alteracoes);
                }

                if (in_array($novoStatus, $aptos, true)) {
                    $rotulo = $service->solicitarRotulo($shipment->correios_prepostagem_id);
                    $recibo = $rotulo['idRecibo'] ?? null;

                    if ($recibo) {
                        $shipment->update(['correios_label_recibo' => $recibo]);
                    } else {
                        Log::warning("Correios sync: solicitação de rótulo do shipment #{$shipment->id} não retornou idRecibo.");
                    }
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

    /**
     * precoPrePostagem pode vir como número ou como texto no formato
     * brasileiro ("19,00" / "1.234,56").
     */
    private function valorMonetario(mixed $valor): ?float
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        if (is_numeric($valor)) {
            return round((float) $valor, 2);
        }

        $normalizado = str_replace(',', '.', str_replace('.', '', (string) $valor));

        return is_numeric($normalizado) ? round((float) $normalizado, 2) : null;
    }
}
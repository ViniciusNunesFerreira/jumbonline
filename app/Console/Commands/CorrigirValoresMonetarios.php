<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CorrigirValoresMonetarios extends Command
{
    protected $signature = 'money:corrigir-valores
        {--dry-run : Só mostra o que seria corrigido, sem aplicar nada}';

    protected $description = 'Corrige registros cujos campos monetários foram gravados com vírgula decimal solta (bug em NormalizesMoneyInput, corrigido em código, presente em Variant, Refund, Product e OrderItem)';

    protected array $alvos = [
        'variants' => ['price', 'compare_price', 'cost_price'],
        'refunds' => ['amount'],
        'products' => ['price'],
        'order_items' => ['price'],
    ];

    public function handle()
    {
        $totalAfetados = 0;

        foreach ($this->alvos as $tabela => $campos) {
            $afetados = DB::table($tabela)
                ->select('id', ...$campos)
                ->get()
                ->filter(function ($row) use ($campos) {
                    foreach ($campos as $campo) {
                        if ($row->$campo !== null && ! is_numeric($row->$campo)) {
                            return true;
                        }
                    }
                    return false;
                });

            if ($afetados->isEmpty()) {
                $this->info("Nenhum registro corrompido em '{$tabela}'.");
                continue;
            }

            $totalAfetados += $afetados->count();

            $this->line("<comment>{$tabela}</comment>");
            $this->table(['id', ...$campos], $afetados->map(fn($row) => (array) $row));

            if ($this->option('dry-run')) {
                continue;
            }

            if (! $this->confirm("Confirma a correção de {$afetados->count()} registro(s) em '{$tabela}' acima?")) {
                continue;
            }

            foreach ($afetados as $row) {
                $update = [];

                foreach ($campos as $campo) {
                    if ($row->$campo !== null && ! is_numeric($row->$campo)) {
                        $valor = str_replace('.', '', $row->$campo);
                        $valor = str_replace(',', '.', $valor);
                        $update[$campo] = is_numeric($valor) ? $valor : null;
                    }
                }

                if ($update) {
                    DB::table($tabela)->where('id', $row->id)->update($update);
                }
            }

            $this->info("'{$tabela}' corrigida.");
        }

        if ($totalAfetados === 0) {
            $this->info('Nenhum valor corrompido encontrado em nenhuma tabela.');
        } elseif ($this->option('dry-run')) {
            $this->warn('Modo simulação — nada foi alterado. Rode sem --dry-run pra corrigir de verdade.');
        }

        return self::SUCCESS;
    }
}
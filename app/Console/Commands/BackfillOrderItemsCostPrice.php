<?php

namespace App\Console\Commands;

use App\Models\OrderItem;
use Illuminate\Console\Command;

class BackfillOrderItemsCostPrice extends Command
{
    protected $signature = 'orders:backfill-cost-price';

    protected $description = 'Preenche order_items.cost_price em pedidos antigos usando o custo ATUAL do variant (aproximado — o custo histórico real nunca foi registrado)';

    public function handle(): int
    {
        $count = OrderItem::where('cost_price', 0)->count();

        if ($count === 0) {
            $this->info('Nada para preencher.');

            return self::SUCCESS;
        }

        if (! $this->confirm("Isso vai preencher {$count} itens de pedido com o custo ATUAL do variant (aproximado, não o custo histórico real da época da venda). Continuar?")) {
            return self::SUCCESS;
        }

        $this->getOutput()->progressStart($count);

        OrderItem::where('cost_price', 0)
            ->with('variant:id,cost_price')
            ->chunkById(200, function ($items) {
                foreach ($items as $item) {
                    if ($item->variant) {
                        $item->update(['cost_price' => $item->variant->cost_price]);
                    }
                    $this->getOutput()->progressAdvance();
                }
            });

        $this->getOutput()->progressFinish();

        $this->warn('Backfill concluído. Margem de pedidos anteriores a hoje é aproximada, não exata.');

        return self::SUCCESS;
    }
}
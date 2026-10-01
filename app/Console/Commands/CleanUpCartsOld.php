<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanUpCartsOld extends Command
{
    protected $signature = 'carts:cleanupcarts-old {--days=7}';

    protected $description = 'Remove carrinhos parados há X dias sem telefone de contato do cliente (útil pra outreach de vendas).';

    public function handle()
    {
        $days = (int) $this->option('days');

        // Usamos where() direto em vez de whereDate()
        $query = Cart::where('updated_at', '<=', now()->subDays($days))
            ->whereNull('customer_id');

        $count = $query->count();

        if ($count === 0) {
            $this->info('Nenhum carrinho parado pra limpar.');
            return self::SUCCESS;
        }

        $this->info("Encontrados {$count} carrinhos. Iniciando remoção...");

        // Processa em lotes para economizar memória
        $query->chunkById(1000, function ($candidates) {
            DB::transaction(function () use ($candidates) {
                foreach ($candidates as $cart) {
                    $cart->addresses()->delete();
                    $cart->discounts()->delete();
                    $cart->items()->delete();
                    $cart->delete();
                }
            });
        });

        Log::info("Limpeza automática de carrinhos parados: {$count} removidos.");
        $this->info("{$count} carrinhos removidos.");

        return self::SUCCESS;
    }
}
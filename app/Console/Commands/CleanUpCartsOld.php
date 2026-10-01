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

        $candidates = Cart::whereDate('updated_at', '<=', now()->subDays($days))
            ->whereHas('items')
            ->whereNull('customer_id')
            ->get();

        if ($candidates->isEmpty()) {
            $this->info('Nenhum carrinho parado pra limpar.');
            return self::SUCCESS;
        }

        $count = $candidates->count();

        DB::transaction(function () use ($candidates) {
            foreach ($candidates as $cart) {
                $cart->addresses()->delete();
                $cart->discounts()->delete();
                $cart->items()->delete();
                $cart->delete();
            }
        });

        Log::info("Limpeza automática de carrinhos parados: {$count} removidos.");
        $this->info("{$count} carrinhos removidos.");

        return self::SUCCESS;
    }
}
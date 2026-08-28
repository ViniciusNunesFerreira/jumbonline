<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Visitante;
use App\Models\Detento;
use App\Enums\PaymentStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CleanupInvalidCustomers extends Command
{
    protected $signature = 'customers:cleanup-invalid
                            {--force : Executa a remoção de verdade. Sem essa flag, roda sempre em modo simulação}
                            {--limit=0 : Limita a quantidade de clientes verificados nesta execução (0 = sem limite)}
                            {--export= : Caminho de um CSV para exportar a lista completa de candidatos}';

    protected $description = 'Remove cadastros com e-mail inválido que nunca completaram um pedido pago, junto com pedidos, carrinhos, detento e visitante relacionados.';

    protected array $domainCache = [];

    public function handle()
    {
        $isDryRun = ! $this->option('force');
        $limit = (int) $this->option('limit');

        if ($isDryRun) {
            $this->warn('MODO SIMULAÇÃO — nada será apagado. Use --force para executar de verdade.');
        } else {
            $this->error('MODO REAL — os registros serão apagados permanentemente e isso não pode ser desfeito.');
            if (! $this->confirm('Você já fez backup do banco de dados agora, antes de continuar?', false)) {
                $this->info('Operação cancelada. Faça o backup antes de rodar com --force.');
                return self::FAILURE;
            }
        }

        // Clientes que já pagaram pelo menos um pedido — nunca tocar, não importa o e-mail.
        $protectedIds = Order::where('payment_status', PaymentStatus::PAID->name)
            ->whereNotNull('customer_id')
            ->distinct()
            ->pluck('customer_id');

        $query = Customer::whereNotIn('id', $protectedIds);
        if ($limit > 0) {
            $query->limit($limit);
        }

        $total = $query->count();
        $this->info("Clientes protegidos (já pagaram algo): {$protectedIds->count()}");
        $this->info("Clientes a verificar: {$total}");

        $candidates = [];
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $query->chunkById(200, function ($customers) use (&$candidates, $bar) {
            foreach ($customers as $customer) {
                $bar->advance();
                if (! $this->isEmailValid($customer->email)) {
                    $candidates[] = $customer->id;
                }
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info('Candidatos a remoção (e-mail inválido + nunca pagaram): ' . count($candidates));

        if (empty($candidates)) {
            $this->info('Nada a remover.');
            return self::SUCCESS;
        }

        // Trava de sanidade — se mais de 30% da base virou candidata, algo pode estar errado na validação.
        $ratio = count($candidates) / max($total, 1);
        if ($ratio > 0.3) {
            $this->error(sprintf(
                'ATENÇÃO: %d%% dos clientes verificados viraram candidatos. Isso é incomum — confira a lista com atenção redobrada antes de usar --force.',
                round($ratio * 100)
            ));
        }

        $preview = array_slice($candidates, 0, 20);
        $rows = Customer::whereIn('id', $preview)->get()->map(function ($c) {
            return [$c->id, $c->email, $c->name, Order::where('customer_id', $c->id)->count(), $c->created_at->format('d/m/Y')];
        });
        $this->table(['ID', 'E-mail', 'Nome', 'Pedidos (não pagos)', 'Cadastrado em'], $rows);
        if (count($candidates) > 20) {
            $this->line('... e mais ' . (count($candidates) - 20) . ' registros.');
        }

        if ($export = $this->option('export')) {
            $this->exportCsv($export, $candidates);
            $this->info("Lista completa exportada para: {$export}");
        }

        if ($isDryRun) {
            $this->newLine();
            $this->info('Simulação concluída — nenhum dado foi apagado. Revise a lista (use --export pra ver todos) e rode com --force quando confirmar.');
            return self::SUCCESS;
        }

        if (! $this->confirm('Confirma a remoção definitiva de ' . count($candidates) . ' clientes e todos os dados relacionados?', false)) {
            $this->info('Operação cancelada.');
            return self::FAILURE;
        }

        $summary = ['customers' => 0, 'orders' => 0, 'payments' => 0, 'order_items' => 0, 'shipments' => 0, 'carts' => 0, 'cart_items' => 0, 'visitantes' => 0, 'detentos' => 0];

        $bar = $this->output->createProgressBar(count($candidates));
        $bar->start();

        foreach (array_chunk($candidates, 100) as $chunk) {
            DB::transaction(function () use ($chunk, &$summary, $bar) {
                foreach ($chunk as $customerId) {
                    $orders = Order::where('customer_id', $customerId)->get();
                    foreach ($orders as $order) {
                        $summary['payments'] += $order->payments()->count();
                        $order->payments()->delete();
                        $summary['order_items'] += $order->orderItems()->count();
                        $order->orderItems()->delete();
                        $summary['shipments'] += $order->shipments()->count();
                        $order->shipments()->delete();
                        $order->delete();
                        $summary['orders']++;
                    }

                    $carts = Cart::where('customer_id', $customerId)->get();
                    foreach ($carts as $cart) {
                        $summary['cart_items'] += $cart->items()->count();
                        $cart->items()->delete();
                        $cart->discounts()->delete();
                        $cart->addresses()->delete();
                        $cart->delete();
                        $summary['carts']++;
                    }

                    // Visitante tem FK de verdade — precisa sair antes do customer.
                    foreach (Visitante::where('customer_id', $customerId)->get() as $visitante) {
                        $visitante->clearMediaCollection('gallery');
                        $visitante->clearMediaCollection('cover');
                        $visitante->delete();
                        $summary['visitantes']++;
                    }

                    foreach (Detento::where('customer_id', $customerId)->get() as $detento) {
                        $detento->delete();
                        $summary['detentos']++;
                    }

                    Customer::where('id', $customerId)->delete();
                    $summary['customers']++;
                    $bar->advance();
                }
            });
        }

        $bar->finish();
        $this->newLine(2);

        Log::info('Limpeza de clientes com e-mail inválido executada', $summary);

        $this->info('Remoção concluída:');
        foreach ($summary as $label => $count) {
            $this->line("  {$label}: {$count}");
        }

        return self::SUCCESS;
    }

    protected function isEmailValid(?string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        if (Validator::make(['email' => $email], ['email' => 'required|email:rfc'])->fails()) {
            return false;
        }

        $domain = substr((string) strrchr($email, '@'), 1);
        if (! $domain) {
            return false;
        }

        if (! array_key_exists($domain, $this->domainCache)) {
            $this->domainCache[$domain] = checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');
        }

        return $this->domainCache[$domain];
    }

    protected function exportCsv(string $path, array $customerIds): void
    {
        $handle = fopen($path, 'w');
        fputcsv($handle, ['id', 'email', 'name', 'pedidos_nao_pagos', 'cadastrado_em']);

        foreach (array_chunk($customerIds, 200) as $chunk) {
            foreach (Customer::whereIn('id', $chunk)->get() as $c) {
                fputcsv($handle, [$c->id, $c->email, $c->name, Order::where('customer_id', $c->id)->count(), $c->created_at]);
            }
        }

        fclose($handle);
    }
}
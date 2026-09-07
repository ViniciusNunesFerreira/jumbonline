<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Events\PaymentReceived;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Console\Command;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Net\MPSearchRequest;

class ReconcileStuckPaidOrders extends Command
{
    protected $signature = 'orders:reconcile-stuck-payments {--dry-run}';

    protected $description = 'Corrige pedidos de HOJE com pagamento PAID registrado localmente mas o pedido não atualizado — confirma cada um direto na API do Mercado Pago antes de corrigir';

    public function handle(): int
    {
        $mercadopago = PaymentMethod::where('identifier', 'mercadopago')->first();

        if (! $mercadopago || empty($mercadopago->meta['access_token'])) {
            $this->error('Método de pagamento "mercadopago" não encontrado ou sem access_token configurado.');
            return self::FAILURE;
        }

        MercadoPagoConfig::setAccessToken($mercadopago->meta['access_token']);

        $orders = Order::query()
            ->whereHas('payments', fn($q) => $q->where('status', PaymentStatus::PAID->name))
            ->where(function ($q) {
                $q->where('payment_status', '!=', PaymentStatus::PAID->name)
                  ->orWhereNull('payment_status');
            })
            ->whereDate('created_at', today())
            ->get();

        if ($orders->isEmpty()) {
            $this->info('Nenhum pedido de hoje com divergência encontrado.');
            return self::SUCCESS;
        }

        $this->warn("{$orders->count()} pedido(s) de hoje com divergência local. Confirmando cada um direto no Mercado Pago...");

        $client = new PaymentClient();
        $confirmed = [];
        $notConfirmed = [];

        foreach ($orders as $order) {
            try {
                $search = $client->search(new MPSearchRequest(10, 0, [
                    'external_reference' => $order->idempotency_key,
                ]));

                $approved = collect($search->results ?? [])
                    ->first(fn($r) => $r->status === 'approved');

                if (! $approved) {
                    $this->error("Pedido #{$order->id}: Mercado Pago NÃO confirma aprovação para external_reference {$order->idempotency_key}. NÃO será corrigido.");
                    $notConfirmed[] = $order->id;
                    continue;
                }

                $amountDiff = abs(((float) $approved->transaction_amount) - (float) $order->total);

                $this->line("Pedido #{$order->id}: confirmado pelo Mercado Pago (payment_id {$approved->id}, valor R$ " . number_format($approved->transaction_amount, 2, ',', '.') . ($amountDiff > 0.01 ? " ⚠ diverge do total do pedido R$ " . number_format($order->total, 2, ',', '.') : '') . ')');

                $confirmed[] = $order;
            } catch (\Throwable $e) {
                $this->error("Pedido #{$order->id}: erro ao consultar o Mercado Pago — {$e->getMessage()}. NÃO será corrigido.");
                $notConfirmed[] = $order->id;
            }
        }

        if (empty($confirmed)) {
            $this->warn('Nenhum pedido foi confirmado pelo Mercado Pago. Nada a corrigir.');
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->info(count($confirmed) . ' pedido(s) seriam corrigidos (modo --dry-run, nada foi alterado).');
            return self::SUCCESS;
        }

        if (! $this->confirm(count($confirmed) . ' pedido(s) confirmados no Mercado Pago. Corrigir agora (marcar como pagos e disparar PaymentReceived)?')) {
            return self::SUCCESS;
        }

        foreach ($confirmed as $order) {
            $order->payment_status = PaymentStatus::PAID;
            $order->save();

            try {
                PaymentReceived::dispatch($order);
            } catch (\Throwable $e) {
                $this->error("Falha ao disparar PaymentReceived para o pedido #{$order->id}: " . $e->getMessage());
            }
        }

        $this->info(count($confirmed) . ' pedido(s) corrigido(s).');

        if (! empty($notConfirmed)) {
            $this->warn('Pedidos NÃO corrigidos (revisar manualmente): ' . implode(', ', $notConfirmed));
        }

        return self::SUCCESS;
    }
}
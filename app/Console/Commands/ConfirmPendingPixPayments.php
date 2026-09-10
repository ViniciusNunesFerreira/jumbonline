<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Events\PaymentReceived;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Net\MPSearchRequest;

class ConfirmPendingPixPayments extends Command
{
    protected $signature = 'orders:confirm-pending-pix';

    protected $description = 'Fallback do webhook do Mercado Pago: confirma direto na API pedidos do site com pix pendente que o webhook pode não ter processado';

    public function handle(): int
    {
        $mercadopago = PaymentMethod::where('identifier', 'mercadopago')->first();

        if (! $mercadopago || empty($mercadopago->meta['access_token'])) {
            Log::error('Fallback PIX: método mercadopago sem access_token configurado.');
            return self::FAILURE;
        }

        MercadoPagoConfig::setAccessToken($mercadopago->meta['access_token']);


        $orders = Order::query()
            ->where('order_status', OrderStatus::OPEN->name)
            ->where(function ($q) {
                $q->whereNull('payment_status')
                  ->orWhere('payment_status', '!=', PaymentStatus::PAID->name);
            })
            ->where('created_at', '>=', now()->subDays(10))
            ->whereDoesntHave('payments', fn($q) => $q->where('status', PaymentStatus::PAID->name))
            ->get();

        if ($orders->isEmpty()) {
            return self::SUCCESS;
        }

        $client = new PaymentClient();
        $fixed = 0;

        foreach ($orders as $order) {
            try {
                $search = $client->search(new MPSearchRequest(10, 0, [
                    'external_reference' => $order->idempotency_key,
                ]));

                $approved = collect($search->results ?? [])
                    ->first(fn($r) => $r->status === 'approved');

                if (! $approved) {
                    continue;
                }

                $order->payments()->create([
                    'reference' => $order->idempotency_key,
                    'amount' => $approved->transaction_amount,
                    'currency' => Str::upper($approved->currency_id ?? 'BRL'),
                    'status' => PaymentStatus::PAID,
                ]);

                $order->payment_status = PaymentStatus::PAID;
                $order->save();

                try {
                    PaymentReceived::dispatch($order);
                } catch (\Throwable $e) {
                    Log::error("Fallback PIX: falha ao disparar PaymentReceived do pedido #{$order->id}: " . $e->getMessage());
                }

                Log::warning("Fallback PIX confirmou pagamento que o webhook não processou — pedido #{$order->id}, payment_id {$approved->id}.");

                $fixed++;
            } catch (\Throwable $e) {
                Log::error("Fallback PIX: erro ao consultar pedido #{$order->id} no Mercado Pago: " . $e->getMessage());
            }
        }

        if ($fixed > 0) {
            Log::warning("Fallback PIX: {$fixed} pedido(s) confirmado(s) nesta execução que o webhook não tinha processado.");
        }

        return self::SUCCESS;
    }
}
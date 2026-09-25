<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Events\PaymentReceived;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Str;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Net\MPSearchRequest;
use RuntimeException;


class MercadoPagoPaymentVerificationService
{
    protected function autenticar(): void
    {
        $mercadopago = PaymentMethod::where('identifier', 'mercadopago')->first();

        if (! $mercadopago || empty($mercadopago->meta['access_token'])) {
            throw new RuntimeException('Método de pagamento "mercadopago" não encontrado ou sem access_token configurado.');
        }

        MercadoPagoConfig::setAccessToken($mercadopago->meta['access_token']);
    }


    public function verificar(Order $order): array
    {
        $this->autenticar();

        $client = new PaymentClient();

        $search = $client->search(new MPSearchRequest(10, 0, [
            'external_reference' => $order->idempotency_key,
        ]));

        $approved = collect($search->results ?? [])->first(fn($r) => $r->status === 'approved');

        if (! $approved) {
            return ['confirmado' => false];
        }

        return [
            'confirmado' => true,
            'payment_id' => $approved->id,
            'transaction_amount' => (float) $approved->transaction_amount,
            'currency_id' => $approved->currency_id ?? 'BRL',
            'amount_diff' => round(abs(((float) $approved->transaction_amount) - (float) $order->total), 2),
        ];
    }

    /**
     * Só chamar depois de verificar() ter confirmado — nunca aplica sem
     * checagem prévia aprovada.
     */
    public function confirmarPagamento(Order $order, array $dadosVerificados): void
    {
        if (empty($dadosVerificados['confirmado'])) {
            throw new RuntimeException('Tentativa de confirmar pagamento sem verificação prévia aprovada.');
        }

        $jaExiste = $order->payments()->where('reference', $order->idempotency_key)->exists();

        if (! $jaExiste) {
            $order->payments()->create([
                'reference' => $order->idempotency_key,
                'amount' => $dadosVerificados['transaction_amount'],
                'currency' => Str::upper($dadosVerificados['currency_id']),
                'status' => PaymentStatus::PAID,
            ]);
        }

        $order->payment_status = PaymentStatus::PAID;
        $order->save();

        PaymentReceived::dispatch($order);
    }
}
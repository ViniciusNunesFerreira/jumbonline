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



    /**
     * Localiza o pagamento aprovado real na Mercado Pago (mesma busca por
     * external_reference já usada em verificar()) e solicita o estorno de
     * verdade — parcial ou total — direto na API. Nunca mexe em nada local;
     * quem chama decide o que fazer com o resultado.
     */
    public function reembolsar(Order $order, float $valor): array
    {
        $this->autenticar();

        $client = new \MercadoPago\Client\Payment\PaymentClient();

        $search = $client->search(new \MercadoPago\Net\MPSearchRequest(10, 0, [
            'external_reference' => $order->idempotency_key,
        ]));

        $approved = collect($search->results ?? [])->first(fn($r) => $r->status === 'approved');

        if (! $approved) {
            throw new RuntimeException('Não encontrei um pagamento aprovado na Mercado Pago pra este pedido — não é seguro prosseguir com o reembolso.');
        }

        $refundClient = new \MercadoPago\Client\PaymentRefund\PaymentRefundClient();

        // Reembolso parcial se o valor for menor que o total pago; total se
        // for igual (a API da Mercado Pago trata "sem valor" como reembolso
        // integral).
        $ehParcial = round($valor, 2) < round((float) $approved->transaction_amount, 2);

        $resultado = $refundClient->create(
            $approved->id,
            $ehParcial ? ['amount' => round($valor, 2)] : null
        );

        return [
            'mercadopago_payment_id' => $approved->id,
            'mercadopago_refund_id' => $resultado->id ?? null,
            'valor_reembolsado' => $valor,
        ];
    }
}
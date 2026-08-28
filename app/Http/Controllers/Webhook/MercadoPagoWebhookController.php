<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Enums\PaymentStatus;
use App\Enums\OrderStatus;
use App\Events\PaymentReceived;
use App\Services\MercadoPagoWebhookSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret = config('services.mercadopago.webhook_secret');

        if (! MercadoPagoWebhookSignature::isValid($request, $secret)) {
            // Não é necessariamente ataque — pode ser reentrega concorrente da própria MP.
            Log::warning('Webhook Mercado Pago: assinatura inválida', [
                'data_id' => $request->query('data.id') ?? $request->query('id'),
            ]);
            return response()->json(['error' => 'invalid signature'], 401);
        }

        $mpPaymentId = $request->query('data.id')
            ?? $request->query('id')
            ?? data_get($request->all(), 'data.id')
            ?? $request->input('id');

        if (! $mpPaymentId) {
            return response()->json(['success' => true]);
        }

        try {
            $mercadopago = PaymentMethod::where('identifier', 'mercadopago')->firstOrFail();
            MercadoPagoConfig::setAccessToken($mercadopago->meta['access_token']);

            $payment = (new PaymentClient())->get($mpPaymentId);

            if (! empty($payment->external_reference)) {
                $this->processPayment($payment);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook Mercado Pago: ' . $e->getMessage());
        }

        return response()->json(['success' => true]);
    }

    protected function processPayment($data)
    {
        $pdvPayment = Payment::find($data->external_reference);

        if ($pdvPayment) {
            $this->processPdvPayment($pdvPayment, $data);
            return;
        }

        if ($data->status !== 'approved') {
            return;
        }

        $order = Order::where('idempotency_key', $data->external_reference)
            ->where('order_status', 'OPEN')
            ->first();

        if (! $order) {
            return;
        }

        $order->payments()->create([
            'reference' => $data->external_reference,
            'amount' => $data->transaction_amount,
            'currency' => Str::upper($data->currency_id),
            'status' => PaymentStatus::PAID,
        ]);

        $order->payment_status = PaymentStatus::PAID;
        $order->save();

        try {
            PaymentReceived::dispatch($order);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    protected function processPdvPayment(Payment $pdvPayment, $data)
    {
        if ($data->status === 'approved') {
            if ($pdvPayment->status !== PaymentStatus::PAID) {
                $pdvPayment->update(['status' => PaymentStatus::PAID]);

                if ($pdvPayment->order) {
                    $pdvPayment->order->update([
                        'order_status' => OrderStatus::COMPLETED,
                        'payment_status' => PaymentStatus::PAID,
                    ]);
                }

                Log::info("PIX aprovado (PDV): Pagamento ID {$pdvPayment->id}");
            }
            return;
        }

        if (in_array($data->status, ['rejected', 'cancelled', 'refunded'])) {
            if ($pdvPayment->getRawOriginal('status') !== 'cancelled') {
                $pdvPayment->delete();

                if ($pdvPayment->order) {
                    foreach ($pdvPayment->order->orderItems as $item) {
                        Product::find($item->product_id)?->increment('stock_quantity', $item->quantity);
                    }
                    $pdvPayment->order->delete();
                }

                Log::warning("PIX cancelado/recusado (PDV): Pagamento ID {$pdvPayment->id} — estoque devolvido");
            }
            return;
        }

        Log::info("Notificação PDV ignorada (status em processamento): {$data->status}");
    }
}
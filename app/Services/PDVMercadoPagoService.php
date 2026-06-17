<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PDVMercadoPagoService
{
    protected $accessToken;

    public function __construct()
    {
        $this->accessToken = config('services.mercadopago.access_token') ?? env('MP_ACCESS_TOKEN');
    }

    /**
     * Gera um PIX via Checkout Transparente (API)
     */
    public function generatePix($payment, $order, $customer)
    {
        $payload = [
            'transaction_amount' => (float) $payment->amount,
            'description' => 'Pedido PDV #' . $order->id,
            'payment_method_id' => 'pix',
            'external_reference' => (string) $payment->id, // A referência é o ID do pagamento no Jumbonline
            'payer' => [
                'email' => $customer ? $customer->email : 'pdv@jumbonline.com.br',
                'first_name' => $customer ? $customer->name : 'Cliente PDV',
            ]
        ];

        $response = Http::withToken($this->accessToken)
            ->post('https://api.mercadopago.com/v1/payments', $payload);

        if ($response->failed()) {
            Log::error('Erro ao gerar PIX MP (PDV): ', $response->json());
            throw new \Exception('Falha ao gerar o PIX no Mercado Pago.');
        }

        $data = $response->json();

        return [
            'mp_payment_id' => $data['id'],
            'qr_code' => $data['point_of_interaction']['transaction_data']['qr_code'],
            'qr_code_base64' => $data['point_of_interaction']['transaction_data']['qr_code_base64'],
        ];
    }

    /**
     * Busca os detalhes de um pagamento no MP (Usado pelo Webhook)
     */
    public function getPayment($id)
    {
        $response = Http::withToken($this->accessToken)
            ->get("https://api.mercadopago.com/v1/payments/{$id}");

        return $response->json();
    }
}
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Importamos o manipulador de Strings para gerar o UUID

class PDVMercadoPagoService
{
    protected $accessToken;

    public function __construct()
    {
        // Lê diretamente do arquivo config/services.php do Jumbonline
        $this->accessToken = config('services.mercadopago.access_token');
        
        // Trava de segurança para você saber se esqueceu de colocar no .env
        if (empty($this->accessToken)) {
            throw new \Exception('O Access Token do Mercado Pago não foi encontrado nas configurações.');
        }
    }

    public function generatePix($payment, $order, $customer)
    {
        $payload = [
            'transaction_amount' => (float) $payment->amount,
            'description' => 'Venda Balcão PDV #' . $order->id,
            'payment_method_id' => 'pix',
            'external_reference' => (string) $payment->id, 
            'payer' => [
                'email' => $customer ? $customer->email : 'pdv@jumbonline.com.br',
                'first_name' => $customer ? $customer->name : 'Cliente',
                'last_name' => 'Balcão'
            ]
        ];

        // Aqui adicionamos o Cabeçalho X-Idempotency-Key exigido em produção
        $response = Http::withToken($this->accessToken)
            ->withHeaders([
                'X-Idempotency-Key' => (string) Str::uuid() // Gera um código único seguro
            ])
            ->post('https://api.mercadopago.com/v1/payments', $payload);

        if ($response->failed()) {
            Log::error('Erro ao gerar PIX MP (PDV): ', $response->json());
            throw new \Exception('Falha ao gerar o PIX. Verifique os logs para mais detalhes.');
        }

        $data = $response->json();

        return [
            'mp_payment_id' => $data['id'],
            'qr_code' => $data['point_of_interaction']['transaction_data']['qr_code'],
            'qr_code_base64' => $data['point_of_interaction']['transaction_data']['qr_code_base64'],
        ];
    }

    public function getPayment($id)
    {
        return Http::withToken($this->accessToken)
            ->get("https://api.mercadopago.com/v1/payments/{$id}")
            ->json();
    }
}
<?php
// app/Services/CorreiosFreightService.php

namespace App\Services;

use App\Enums\ShippingServices;
use App\Models\ShippingMethod;
use App\Services\Shipping\CustomerFreightPricing;
use App\Services\Shipping\PackageDimensions;
use App\Services\Shipping\PackageEstimator;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Cotação de frete via Correios (contrato "Cartão de Postagem"), reutilizando
 * exatamente a mesma fonte de credenciais e a mesma regra de precificação já
 * usadas pelo checkout do site (ver App\Http\Livewire\Traits\Correios).
 *
 * Diferente do trait original — que depende de propriedades de um componente
 * Livewire ($this->correios) — este serviço é uma classe simples, podendo
 * ser usada tanto pelo site quanto pelo PDV (ou qualquer outro consumidor).
 *
 * Preço ao cliente: calcPrecoFrete() delega para CustomerFreightPricing,
 * a política única (tabela de balcão, ou contrato + margem) usada também
 * pelo checkout do site (Purchase). consultarPrecoCorreios() continua
 * devolvendo o preço de contrato bruto — o custo real da Jumbonline.
 *
 * Embalagem: a caixa fixa 54×36×27 (peso cúbico de 8,75 kg, que fazia todo
 * pedido pequeno ser cobrado como se pesasse ~9 kg) foi substituída pelo
 * PackageEstimator, que encaixa o pedido na menor caixa cadastrada que
 * comporte o volume estimado a partir do peso. Quem já conhece a embalagem
 * real (ex.: atendente que mediu a caixa no balcão) pode informá-la em
 * $pacote.
 */
class CorreiosFreightService
{
    /**
     * Serviços oferecidos ao operador do PDV (PAC e SEDEX "de balcão"/contrato).
     */
    public const AVAILABLE_SERVICES = [
        'pac' => [
            'code' => ShippingServices::PAC_CONTRATO_AG,
            'label' => 'PAC (Convencional)',
        ],
        'sedex' => [
            'code' => ShippingServices::SEDEX_CONTRATO_AG,
            'label' => 'SEDEX (Expresso)',
        ],
    ];

    private function getShippingMethod(): ShippingMethod
    {
        return ShippingMethod::query()->where('identifier', 'correios')->firstOrFail();
    }

    private function getAccessToken(): array
    {
        $config = config('correios');
        $shippingMethod = $this->getShippingMethod();

        $url = $config['host'] . 'token/v1/autentica/cartaopostagem';

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Controle' => 'no-cache',
        ];

        $postParam = ['numero' => $shippingMethod->credentials['cartaopostagem']];

        $client = new Client([
            'auth' => [$shippingMethod->credentials['user_key'], $shippingMethod->credentials['access_key']],
        ]);

        $response = $client->post($url, [
            'headers' => $headers,
            'body' => json_encode($postParam),
        ]);

        $data = json_decode($response->getBody(), true);

        $expiresAt = Carbon::parse($data['expiraEm']);

        Cache::put('correios.token', $data['token'], $expiresAt);
        Cache::put('correios.expired_in', $data['expiraEm'], $expiresAt);

        return [
            'host' => $config['host'],
            'token' => $data['token'],
            'expired_in' => $data['expiraEm'],
        ];
    }

    private function ensureValidToken(): array
    {
        $config = config('correios');
        $token = Cache::get('correios.token');
        $expiredIn = Cache::get('correios.expired_in');

        $current = Carbon::now();
        $newHour = $expiredIn ? new Carbon($expiredIn) : null;

        if (empty($expiredIn) || $current->diffInMinutes($newHour, false) <= 30) {
            return $this->getAccessToken();
        }

        return ['host' => $config['host'], 'token' => $token, 'expired_in' => $expiredIn];
    }

    /**
     * Preço do frete AO CLIENTE para um serviço, segundo a política de
     * CustomerFreightPricing (tabela de balcão ou contrato + margem).
     *
     * @param  string  $cepOrigem  Somente dígitos
     * @param  string  $cepDestino  Somente dígitos
     * @param  float  $pesoGramas  Peso total dos itens (usado para estimar a embalagem quando $pacote não é informado)
     * @param  ShippingServices  $service  Serviço de CONTRATO (03220/03298); o código de varejo é derivado dele
     * @param  PackageDimensions|null  $pacote  Embalagem conhecida; quando informada, seu peso e dimensões prevalecem
     * @return float|null Preço ao cliente, ou null em caso de falha
     */
    public function calcPrecoFrete(string $cepOrigem, string $cepDestino, float $pesoGramas, ShippingServices $service, ?PackageDimensions $pacote = null): ?float
    {
        $cotacao = app(CustomerFreightPricing::class)->cotar($cepOrigem, $cepDestino, $pesoGramas, $service, $pacote);

        return $cotacao['preco'] ?? null;
    }

    /**
     * Preço bruto cobrado pelos Correios (pcFinal), SEM a correção de margem
     * do cliente — é o custo real do envio para a Jumbonline. Usado na
     * conferência de embalagem da pré-postagem para mostrar ao atendente o
     * impacto das medidas reais antes de registrar o envio.
     */
    public function consultarPrecoCorreios(string $cepOrigem, string $cepDestino, float $pesoGramas, ShippingServices $service, ?PackageDimensions $pacote = null): ?float
    {
        $pacote ??= app(PackageEstimator::class)->estimarPorPeso($pesoGramas);

        $query = array_merge(
            ['cepDestino' => $cepDestino, 'cepOrigem' => $cepOrigem],
            $pacote->toPrecoQuery()
        );

        try {
            // Token dentro do try: falha de autenticação vira "sem preço" (e
            // log), nunca uma exceção estourando no checkout ou no PDV.
            $config = $this->ensureValidToken();

            $url = $config['host'] . 'preco/v1/nacional/' . $service->value . '?' . http_build_query($query);

            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Cache-Controle' => 'no-cache',
                'Authorization' => 'Bearer ' . $config['token'],
            ];

            $client = new Client();
            $response = $client->get($url, ['headers' => $headers]);
            $body = (string) $response->getBody();
            $data = json_decode($body);

            if (empty($data->pcFinal)) {
                Log::warning('[CorreiosFreightService] Cotação sem pcFinal', [
                    'service' => $service->value,
                    'cepDestino' => $cepDestino,
                    'pacote' => $pacote->toArray(),
                    'resposta' => mb_substr($body, 0, 1000),
                ]);

                return null;
            }

            $price = str_replace('.', '', $data->pcFinal);

            return (float) str_replace(',', '.', $price);
        } catch (\Throwable $exception) {
            Log::warning('[CorreiosFreightService] Falha ao cotar frete', [
                'resposta' => $exception instanceof \GuzzleHttp\Exception\RequestException && $exception->hasResponse()
                    ? mb_substr((string) $exception->getResponse()->getBody(), 0, 1000)
                    : null,
                'service' => $service->value,
                'cepOrigem' => $cepOrigem,
                'cepDestino' => $cepDestino,
                'pacote' => $pacote->toArray(),
                'error' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Cota PAC e SEDEX para o mesmo trajeto/peso, no formato já pronto para
     * a resposta da API do PDV. Serviços que falharem são omitidos do
     * resultado (o operador ainda pode prosseguir com o outro serviço, ou
     * informar um valor manual pela UI, caso ambos falhem).
     *
     * @return array<int, array{carrier:string, label:string, price:float, service_code:string}>
     */
    public function quoteAll(string $cepOrigem, string $cepDestino, float $pesoGramas, ?PackageDimensions $pacote = null): array
    {
        $results = [];

        // Estima uma única vez: PAC e SEDEX são cotados com a mesma embalagem.
        $pacote ??= app(PackageEstimator::class)->estimarPorPeso($pesoGramas);

        foreach (self::AVAILABLE_SERVICES as $carrier => $meta) {
            $price = $this->calcPrecoFrete($cepOrigem, $cepDestino, $pesoGramas, $meta['code'], $pacote);

            if ($price !== null) {
                $results[] = [
                    'carrier' => $carrier,
                    'label' => $meta['label'],
                    'price' => $price,
                    'service_code' => $meta['code']->value,
                ];
            }
        }

        return $results;
    }
}
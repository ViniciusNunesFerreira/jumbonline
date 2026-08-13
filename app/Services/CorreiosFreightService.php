<?php

namespace App\Services;

use App\Enums\ShippingServices;
use App\Models\ShippingMethod;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * Cotação de frete via Correios (contrato "Cartão de Postagem"), reutilizando
 * exatamente a mesma fonte de credenciais e a mesma regra de precificação já
 * usadas pelo checkout do site (ver App\Http\Livewire\Traits\Correios).
 *
 * Diferente do trait original — que depende de propriedades de um componente
 * Livewire ($this->correios) — este serviço é uma classe simples, podendo
 * ser usada tanto pelo site quanto pelo PDV (ou qualquer outro consumidor).
 *
 * NOTA: o trait Correios usado pelo site (Purchase.php) não foi alterado
 * nesta entrega, para não gerar risco de regressão no checkout já em
 * produção. Uma futura consolidação pode fazer o trait delegar para este
 * serviço.
 */
class CorreiosFreightService
{
    /**
     * Mesma correção percentual aplicada pelo site em
     * Purchase::updateShippingPrice() (margem sobre o preço tabelado dos
     * Correios). Mantido idêntico para não introduzir divergência de
     * precificação entre site e PDV.
     */
    private const PRICE_CORRECTION_PERCENT = 46;

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

        config(['correios.token' => $data['token']]);
        config(['correios.expired_in' => $data['expiraEm']]);

        $fp = fopen(base_path() . '/config/correios.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('correios'), true) . ';');
        fclose($fp);

        return config('correios');
    }

    private function ensureValidToken(): array
    {
        $config = config('correios');

        $current = Carbon::now();
        $newHour = new Carbon($config['expired_in'] ?? null);

        if (empty($config['expired_in']) || $current->diffInMinutes($newHour, false) <= 30) {
            $config = $this->getAccessToken();
        }

        return $config;
    }

    /**
     * Consulta o preço de um serviço específico dos Correios.
     *
     * @param  string  $cepOrigem  Somente dígitos
     * @param  string  $cepDestino  Somente dígitos
     * @param  float  $pesoGramas
     * @return float|null Preço final já com a correção de margem, ou null em caso de falha
     */
    public function calcPrecoFrete(string $cepOrigem, string $cepDestino, float $pesoGramas, ShippingServices $service): ?float
    {
        $config = $this->ensureValidToken();

        $url = $config['host'] . 'preco/v1/nacional/' . $service->value
            . '?cepDestino=' . $cepDestino
            . '&cepOrigem=' . $cepOrigem
            . '&psObjeto=' . $pesoGramas
            . '&tpObjeto=2&comprimento=54&largura=36&altura=27';

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Cache-Controle' => 'no-cache',
            'Authorization' => 'Bearer ' . $config['token'],
        ];

        try {
            $client = new Client();
            $response = $client->get($url, ['headers' => $headers]);
            $data = json_decode($response->getBody());

            if (empty($data->pcFinal)) {
                return null;
            }

            $price = str_replace('.', '', $data->pcFinal);
            $price = (float) str_replace(',', '.', $price);

            $correction = round(($price * self::PRICE_CORRECTION_PERCENT) / 100, 2);

            return round($price + $correction, 2);
        } catch (\Throwable $exception) {
            Log::warning('[CorreiosFreightService] Falha ao cotar frete', [
                'service' => $service->value,
                'cepOrigem' => $cepOrigem,
                'cepDestino' => $cepDestino,
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
    public function quoteAll(string $cepOrigem, string $cepDestino, float $pesoGramas): array
    {
        $results = [];

        foreach (self::AVAILABLE_SERVICES as $carrier => $meta) {
            $price = $this->calcPrecoFrete($cepOrigem, $cepDestino, $pesoGramas, $meta['code']);

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

<?php

namespace App\Concerns;

use App\Models\ShippingMethod;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

/**
 * Autenticação com os Correios (CWS), compartilhada entre CorreiosFreightService
 * e CorreiosPrepostagemService. Token nunca é gravado em config/*.php — só em
 * cache, com TTL igual à validade real informada pelos Correios.
 */
trait AuthenticatesWithCorreios
{
    protected function correiosShippingMethod(): ShippingMethod
    {
        return ShippingMethod::query()->where('identifier', 'correios')->firstOrFail();
    }

    protected function correiosToken(): string
    {
        $token = Cache::get('correios.token');
        $expiredIn = Cache::get('correios.expired_in');

        $newHour = $expiredIn ? new Carbon($expiredIn) : null;

        if (empty($expiredIn) || Carbon::now()->diffInMinutes($newHour, false) <= 30) {
            return $this->refreshCorreiosToken();
        }

        return $token;
    }

    protected function refreshCorreiosToken(): string
    {
        $shippingMethod = $this->correiosShippingMethod();
        $host = rtrim(config('correios.host'), '/');

        $client = new Client([
            'auth' => [$shippingMethod->credentials['user_key'], $shippingMethod->credentials['access_key']],
        ]);

        $response = $client->post($host . '/token/v1/autentica/cartaopostagem', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode(['numero' => $shippingMethod->credentials['cartaopostagem']]),
        ]);

        $data = json_decode($response->getBody(), true);

        $expiresAt = Carbon::parse($data['expiraEm']);

        Cache::put('correios.token', $data['token'], $expiresAt);
        Cache::put('correios.expired_in', $data['expiraEm'], $expiresAt);

        return $data['token'];
    }
}
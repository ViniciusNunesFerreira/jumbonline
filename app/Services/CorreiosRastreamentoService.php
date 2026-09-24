<?php

namespace App\Services;

use App\Concerns\AuthenticatesWithCorreios;
use GuzzleHttp\Client;

class CorreiosRastreamentoService
{
    use AuthenticatesWithCorreios;

    protected function baseUrl(): string
    {
        return rtrim(config('correios.host'), '/') . '/srorastro';
    }

    protected function client(): Client
    {
        return new Client([
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->correiosToken(),
            ],
        ]);
    }

    /**
     * Eventos de rastreio de um objeto, mais recentes primeiro.
     */
    public function eventos(string $codigoObjeto): array
    {
        $response = $this->client()->get($this->baseUrl() . "/v1/objetos/{$codigoObjeto}", [
            'query' => ['resultado' => 'T'],
        ]);

        $data = json_decode($response->getBody(), true);

        $eventos = $data['objetos'][0]['eventos'] ?? [];

        usort($eventos, fn($a, $b) => strcmp($b['dtHrCriado'] ?? '', $a['dtHrCriado'] ?? ''));

        return $eventos;
    }
}
<?php

namespace App\Http\Livewire\Traits;

use App\Models\ShippingMethod;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use App\Enums\ShippingServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait Correios
{

    private function getAccessToken()
    {
        $config = config('correios');
        $url = $config['host'].'token/v1/autentica/cartaopostagem';

        $headers = [
                'Content-Type'=>'application/json',
                'Accept' => 'application/json',
                'Cache-Controle' => 'no-cache'
        ];

        $postParam = [ "numero" => $this->correios->credentials['cartaopostagem'] ];

        $client = new Client([
            'auth' => [$this->correios->credentials['user_key'], $this->correios->credentials['access_key']],
        ]);

        try {

            $response = $client->post($url, [
                'headers' => $headers,
                'body' => json_encode($postParam)
            ]);

            $data = json_decode($response->getBody(), true);

            $expiresAt = Carbon::parse($data['expiraEm']);

            Cache::put('correios.token', $data['token'], $expiresAt);
            Cache::put('correios.expired_in', $data['expiraEm'], $expiresAt);

        } catch (\Exception $exception) {
            $response = $exception->getMessage();
        }

    }

    public function calcPrecoFrete(Array $params)
    {

        //params['cepDestino', 'cepOrigem', 'peso']

        $config = config('correios');
        $service = ShippingServices::SEDEX_CONTRATO_AG;

        $token = Cache::get('correios.token');
        $expiredIn = Cache::get('correios.expired_in');

        $current =  Carbon::now();
        $newHour = $expiredIn ? new Carbon($expiredIn) : null;

        //SE O TOKEN ESTIVER COM VENCIMENTO ABAIXO DE 30 Minutos (ou nunca foi obtido)
       if( empty($expiredIn) || $current->diffInMinutes($newHour, false) <= 30 ){
            $this->getAccessToken();
            $token = Cache::get('correios.token');
       }


       //Chamada GET url
        $url = $config['host'].'preco/v1/nacional/'.$service->value.'?cepDestino='.$params['cepDestino'].'&cepOrigem='.$params['cepOrigem'].'&psObjeto='.$params['peso'].'&tpObjeto=2&comprimento=54&largura=36&altura=27';

        $headers = [
                'Content-Type'=>'application/json',
                'Accept' => 'application/json',
                'Cache-Controle' => 'no-cache',
                'Authorization' => 'Bearer '.$token
        ];

        try {
            $client = new Client();
            $response = $client->get($url, [
                'headers' => $headers
            ]);

            $data = json_decode($response->getBody(), false);


        }catch( \Exception $exception ){

            $data = $exception->getMessage();
        }


        return $data;

    }

    public function getCorreiosProperty()
    {
        return ShippingMethod::query()->where('identifier', 'correios')->firstOrFail();
    }

}
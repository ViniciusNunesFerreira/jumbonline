<?php

namespace App\Http\Livewire\Traits;

use App\Models\ShippingMethod;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use App\Enums\ShippingServices;
use Carbon\Carbon;

trait Correios
{

    private function getAccessToken()
    {
        $config = config('correios');
        $url = $config['host'].'token/v1/autentica/cartaopostagem';

        $correios_params = ShippingMethod::query()->where('identifier', 'correios')->firstOrFail();

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

            config([ 'correios.token' => $data['token'] ]);
            config([ 'correios.expired_in' => $data['expiraEm']]);

            $fp = fopen(base_path() . '/config/correios.php', 'w');
            fwrite($fp, '<?php return ' . var_export(config('correios'), true) . ';');
            fclose($fp);

        } catch (\Exception $exception) {
            $response = $exception->getMessage();

            \Log::debug((object)$response);
        }

    }

    public function calcPrecoFrete(Array $params)
    {
        
        //params['cepDestino', 'cepOrigem', 'peso']

        $config = config('correios');
        $service = ShippingServices::SEDEX_CONTRATO_AG;

        $current =  Carbon::now();
        $newHour = new Carbon($config['expired_in']);
        
        //SE O TOKEN ESTIVER COM VENCIMENTO ABAIXO DE 30 Minutos
       if( empty($config['expired_in']) || $current->diffInMinutes($newHour, false) <= 30 ){
            $this->getAccessToken();
            $config = config('correios');
       }


       //Chamada GET url
        $url = $config['host'].'preco/v1/nacional/'.$service->value.'?cepDestino='.$params['cepDestino'].'&cepOrigem='.$params['cepOrigem'].'&psObjeto='.$params['peso'].'&tpObjeto=2&comprimento=54&largura=36&altura=27';

        $headers = [
                'Content-Type'=>'application/json',
                'Accept' => 'application/json',
                'Cache-Controle' => 'no-cache',
                'Authorization' => 'Bearer '.$config['token']
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
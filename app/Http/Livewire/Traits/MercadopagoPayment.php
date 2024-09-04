<?php

namespace App\Http\Livewire\Traits;

use App\Models\PaymentMethod;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use App\Models\OrderItem;
use Illuminate\Http\Request;

trait MercadopagoPayment
{
    public $preference_id = 1 ;
    public $payment = '';
    public $createRequest = [];

    public function createPreference()
    {
        
            MercadoPagoConfig::setAccessToken($this->mercadopago->meta['access_token']);

            $client = new PreferenceClient();

            $createRequest = [
                "external_reference" => $this->order->id,
                "notification_url" => '',
                "items"=> $this->orderItemsGenerate(),
                "default_payment_method_id" => "master",
                "excluded_payment_types" => array(
                  array(
                    "id" => "debit_card"
                  )
                ),
                "installments" => 3
            ];
    
            $preference = $client->create($createRequest);

            
            if (isset($preference->id)) {
                if ($preference->id != NULL) {
                    $this->preference_id = $preference->id;
                }
            }
        
    }

    private function orderItemsGenerate()
    {
        $this->order->load([
            'orderItems.discount',
            'orderItems.product.media',
            'orderItems.variant.media',
            'orderItems.variant.variantAttributes.option',
            'orderItems.variant.variantAttributes.optionValue',
        ]);

        $lineItems = $this->order->orderItems->map(function (OrderItem $orderItem) {

            $lineItem = [
                "title" => $orderItem->product->name,
                "picture_url" => $orderItem->variant->hasMedia('image') ? $orderItem->variant->getFirstMediaUrl('image') : $orderItem->product->getFirstMediaUrl('gallery'),
                "category_id" => "eletronico",
                "quantity" => $orderItem->quantity,
                "currency_id" => "BRL",
                "unit_price" => ($orderItem->price) * 100
            ];

            return $lineItem;
        })->toArray();

        return $lineItems;
    }

    public function createPaymentOrder(Request $request)
    {

        \Log::debug($request);

        MercadoPagoConfig::setAccessToken($this->mercadopago->meta['access_token']);
        $client = new PaymentClient();


        if($request->payment_method_id == 'pix'){

            //Pagamentos Pix
            $this->createRequest = [
                "transaction_amount" => $request->transaction_amount,
                "external_reference" => 1250,
                "payment_method_id" => $request->payment_method_id,
                    "payer" => [
                        "email" =>  $request->payer['email'],
                    ]
            ];

        }elseif($request->payment_method_id == 'bolbradesco'){

            //Pagamentos Boleto

        }else{

            //Para pagamentos via cartão
            if ( isset($request->token) ) {

                $this->createRequest = [
                    "transaction_amount" => $request->transaction_amount,
                    "issuer_id" => $request->issuer_id,
                    "token" => $request->token,
                    "installments"  => $request->installments,
                    "payment_method_id" => $request->payment_method_id,
                    "payer" => [
                        "email" => $request->payer['email'],
                        "identification" => [
                            "type" => $request->payer['identification']['type'],
                            "number" => $request->payer['identification']['number']
                        ]
                    ]
                ];
            
            }
                
        }


        $this->payment = $client->create($this->createRequest);

        if (isset($this->payment->id)) {

            $copia_cola = $this->payment->point_of_interaction->transaction_data->qr_code;

            \Log::debug($copia_cola);

        }
        
    }

    public function getMercadopagoProperty()
    {
        return PaymentMethod::query()->where('identifier', 'mercadopago')->firstOrFail();
    }
}
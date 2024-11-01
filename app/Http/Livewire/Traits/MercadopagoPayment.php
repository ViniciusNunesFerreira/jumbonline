<?php

namespace App\Http\Livewire\Traits;

use App\Models\PaymentMethod;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Preference\PreferenceClient;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Customer;
use MercadoPago\Exceptions\MPApiException;

trait MercadopagoPayment
{
    public $preference_id = 1 ;
    public $payment = [];
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

        MercadoPagoConfig::setAccessToken($this->mercadopago->meta['access_token']);
        $client = new PaymentClient();

        $request_options = new RequestOptions();
        $request_options->setCustomHeaders(["X-Idempotency-Key: ".$this->order_service->idempotency_key.""]);

        \Log::info($request->payment_method_id);

        if($request->payment_method_id == 'pix'){

            //Pagamentos Pix
            $this->createRequest = [
                "transaction_amount" => $request->transaction_amount,
                "external_reference" => $this->order_service->idempotency_key,
                "notification_url" => env('APP_ENV') == 'local' ? 'https://jumbonline.com.br/webhooks/mercadopago' : route('webhook-client-mercadopago'),
                "payment_method_id" => $request->payment_method_id,
                    "payer" => [
                        "email" =>  $request->payer['email'],
                    ]
            ];

        }elseif($request->payment_method_id == 'bolbradesco'){

            //Pagamentos Boleto

                $this->createRequest = [
                    "transaction_amount" => $request->transaction_amount,
                    "payment_method_id" => $request->payment_method_id,
                    "payer" => [
                        "email" =>  $request->payer['email'],
                    ]
                ];

            
        }else{

            //Para pagamentos via cartão
            if ( isset($request->token) ) {

                $this->createRequest = [
                    "transaction_amount" => $request->transaction_amount,
                    "issuer_id" => $request->issuer_id,
                    "token" => $request->token,
                    "installments"  => $request->installments,
                    "external_reference" => $this->order_service->idempotency_key,
                    "notification_url" => env('APP_ENV') == 'local' ? 'https://jumbonline.com.br/webhooks/mercadopago' : route('webhook-client-mercadopago'),
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
        
        // $this->payment = $client->create($this->createRequest, $request_options);

        try {

            $response = $client->create($this->createRequest, $request_options);

            return response()->json($response);
            
        } catch (MPApiException $e) {

            return response()->json( $e->getApiResponse()->getContent());
        }


    }

    

    
    public function getCustomerProperty(): \App\Models\Customer|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return \Auth::user();
    }

    public function getMercadopagoProperty()
    {
        return PaymentMethod::query()->where('identifier', 'mercadopago')->firstOrFail();
    }

    public function getOrderServiceProperty()
    {
        return Order::query()->where('customer_id',  $this->customer->id)->where('order_status', 'OPEN')->first();
    }

}
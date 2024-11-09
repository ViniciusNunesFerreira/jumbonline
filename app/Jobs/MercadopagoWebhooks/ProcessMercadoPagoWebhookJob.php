<?php

namespace App\Jobs\MercadopagoWebhooks;

use App\Enums\PaymentStatus;
use App\Events\PaymentReceived;
use App\Events\RefundCreated;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

use MercadoPago\MercadoPagoConfig;
use App\Models\PaymentMethod;
use MercadoPago\Client\Payment\PaymentClient;


class ProcessMercadoPagoWebhookJob extends ProcessWebhookJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public WebhookCall $webhookCall;

     /**
     * Create a new job instance.
     */
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;

        $this->onQueue('default');
    }



    public function handle():void
    {
        
       try{
            
                $mercadopago =  PaymentMethod::where('identifier', 'mercadopago')->firstOrFail();

                MercadoPagoConfig::setAccessToken($mercadopago->meta['access_token']);
            
                $client = new PaymentClient();
                
                $id = $this->webhookCall->payload['data']['id'];

                $payment = $client->get($id);

                if(!empty($payment->external_reference) && $payment->status == "approved"){
                    \Log::info('entrei na atualização do pagamento');
                    $this->processOrderPaidEvent($payment);
                }
            
        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }

    }

    public function processOrderPaidEvent($data)
    {
        $order = Order::query()->where('idempotency_key', $data->external_reference)->where('order_status', 'OPEN')->firstOrFail();

        $order->payments()->create([
            'reference' => $data->external_reference,
            'amount' => $data->transaction_amount,
            'currency' => \Str::upper($data->currency_id),
            'status' => PaymentStatus::PAID,
        ]);

        $order->payment_status = PaymentStatus::PAID;

        $order->save();

        try{
            PaymentReceived::dispatch($order);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }
        
    }


}
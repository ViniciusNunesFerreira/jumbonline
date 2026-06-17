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

               
               if( isset( $this->webhookCall->payload['data'] ) ) {
                    
                    $id = $this->webhookCall->payload['data']['id'];
                    $payment = $client->get($id);

                    if(!empty($payment->external_reference) && $payment->status == "approved"){
                        $this->processOrderPaidEvent($payment);
                    }
                }
            
        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }

    }

    public function processOrderPaidEvent($data)
    {

        $pdvPayment = \App\Models\Payment::find($data->external_reference);

        if ($pdvPayment) {
            
            // VALIDAÇÃO DE SEGURANÇA: Checa o status real vindo do Mercado Pago
            if ($data->status === 'approved') {
                if ($pdvPayment->status !== PaymentStatus::PAID) {
                    
                    $pdvPayment->update(['status' => PaymentStatus::PAID]);

                    if ($pdvPayment->order) {
                        $pdvPayment->order->update([
                            'order_status' => \App\Enums\OrderStatus::COMPLETED,
                            'payment_status' => PaymentStatus::PAID
                        ]);
                    }
                    \Log::info("✅ Webhook Jumbonline interceptou PIX APROVADO do PDV: Pagamento ID {$pdvPayment->id}");
                }
            }elseif (in_array($data->status, ['rejected', 'cancelled', 'refunded'])) {
                
                // Marca o pagamento como cancelado/rejeitado no Jumbonline
                if ((string) $pdvPayment->getRawOriginal('status') !== 'cancelled') {
                    
                    // Como a Enum pode variar, usamos um delete ou alteramos o status
                    $pdvPayment->delete(); 

                    if ($pdvPayment->order) {
                        // Devolve o estoque para a prateleira do Jumbonline
                        foreach ($pdvPayment->order->orderItems as $item) {
                            $product = \App\Models\Product::find($item->product_id);
                            if ($product) {
                                $product->increment('stock_quantity', $item->quantity);
                            }
                        }
                        // Deleta o pedido órfão
                        $pdvPayment->order->delete();
                    }
                    \Log::warning("❌ Webhook interceptou PIX CANCELADO/REJEITADO do PDV: Pagamento ID {$pdvPayment->id} (Estoque devolvido)");
                }
            } else {
                \Log::info("⏳ Webhook notificação ignorada (Status em processamento): {$data->status}");
            }
            
            // Retorna para NÃO executar a lógica do E-commerce abaixo
            return; 
        }

        if ($data->status !== 'approved') {
            return;
        }


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
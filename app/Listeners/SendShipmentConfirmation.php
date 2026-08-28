<?php

namespace App\Listeners;

use App\Events\ShipmentCreated;
use App\Mail\DigitalShipmentConfirmed;
use App\Mail\PhysicalShipmentConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendShipmentConfirmation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ShipmentCreated  $event
     * @return void
     */
    public function handle(ShipmentCreated $event)
    {
        $email = $event->shipment->order->customer_email;

        if (empty($email)) {
            Log::warning("Confirmação de envio não enviada: pedido #{$event->shipment->order_id} sem e-mail de cliente cadastrado.");
            return;
        }

        try {
            if ($event->shipment->is_physical) {
                Mail::to($email)->send(new PhysicalShipmentConfirmed($event->shipment));
            } else {
                Mail::to($email)->send(new DigitalShipmentConfirmed($event->shipment));
            }
        } catch (\Exception $e) {
            Log::error("Erro ao enviar confirmação de envio do pedido #{$event->shipment->order_id}: " . $e->getMessage());
        }
    }
}

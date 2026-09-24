<?php

namespace App\Http\Livewire\Employee\Order\Components;

use App\Enums\ShippingCarrier;
use App\Events\ShipmentDeleted;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\CorreiosPrepostagemService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class OrderShipments extends Component
{
    public Order $order;

    public $isEditingShipment = false;

    public $shipmentBeingUpdated;

    protected $listeners = ['refresh' => '$refresh'];

    protected function rules()
    {
        return [
            'shipmentBeingUpdated.shipping_carrier' => ['required', new Enum(ShippingCarrier::class)],
            'shipmentBeingUpdated.tracking_number' => 'required|string',
            'shipmentBeingUpdated.tracking_url' => 'nullable|string',
        ];
    }

    public function edit(Shipment $shipment)
    {
        if ($shipment->shipping_carrier === ShippingCarrier::CORREIOS && $shipment->correios_prepostagem_id) {
            $this->notify(trans('Esta remessa é uma pré-postagem oficial dos Correios — gerencie pelo painel de Correios acima, não por aqui, pra não perder a ligação com a postagem real.'));
            return;
        }

        $this->shipmentBeingUpdated = $shipment;

        $this->shipmentBeingUpdated->shipping_carrier = ShippingCarrier::OTHER->value;

        $this->isEditingShipment = true;
    }

    public function update()
    {
        $this->validate();

        $this->shipmentBeingUpdated->save();

        $this->isEditingShipment = false;

        $this->emit('refresh')->self();

        $this->notify(trans('Tracking information updated.'));
    }

    public function delete(Shipment $shipment, CorreiosPrepostagemService $service)
    {
        if ($shipment->shipping_carrier === ShippingCarrier::CORREIOS && $shipment->correios_prepostagem_id) {
            try {
                $service->cancelar($shipment->correios_prepostagem_id);
            } catch (\Throwable $e) {
                $this->notify(trans('Não foi possível cancelar junto aos Correios — verifique manualmente antes de remover.'));
                return;
            }
        }

        $shipment->delete();

        $this->emit('refresh')->self();

        $this->emit('refresh')->to('employee.order.order-detail');

        $this->emit('refresh')->to('employee.order.components.order-items');

        $this->notify(trans('Shipment removed.'));
    }

    public function getShipmentRowsProperty()
    {
        return Shipment::query()
            ->with([
                'shipmentItems.orderItem.variant.media',
                'shipmentItems.orderItem.variant.product.media',
                'shipmentItems.orderItem.variant.variantAttributes.option',
                'shipmentItems.orderItem.variant.variantAttributes.optionValue',
            ])
            ->where('order_id', $this->order->id)
            ->get();
    }

    public function render()
    {
        return view('livewire.employee.order.components.order-shipments', [
            'shipments' => $this->shipmentRows,
            'shippingCarriers' => ShippingCarrier::cases(),
        ]);
    }
}
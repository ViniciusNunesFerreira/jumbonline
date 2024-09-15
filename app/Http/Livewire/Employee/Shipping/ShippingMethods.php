<?php

namespace App\Http\Livewire\Employee\Shipping;

use Livewire\Component;
use App\Models\ShippingMethod;

class ShippingMethods extends Component
{

    public array $state = [
        'is_enabled' => false,
        'name' => '', 
        'credentials' => [
            'user_key' => '',
            'access_key' => '',
            'contrato' => '',
            'cartaopostagem' => ''
        ],
    ];

    protected $rules = [
        'state.is_enabled' => 'boolean',
        'state.name' => 'required|string',
        'state.credentials.user_key' => 'required_if:state.is_enabled,true|string',
        'state.credentials.access_key' => 'required_if:state.is_enabled,true|string',
        'state.credentials.contrato' => 'required_if:state.is_enabled,true|string',
        'state.credentials.cartaopostagem' => 'required_if:state.is_enabled,true|string'
    ];

    public function mount()
    {
        $this->state = $this->shipping_method->toArray();

    }

    public function save()
    {
        $this->validate();

        $this->shipping_method->update($this->state);

        $this->notify('Configurações de envio salvas com sucesso.');
    }

    public function getShippingMethodProperty()
    {
        return ShippingMethod::query()->firstOrCreate([
            'identifier' => 'correios',
        ], [
            'name' => 'Correios',
            'is_enabled' => false,
            'is_default' => true,
            'credentials' => [
                'user_key' => '',
                'access_key' => '',
                'contrato' => '',
                'cartaopostagem' => ''
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.employee.shipping.shipping-methods', ['shipping_method' => $this->shippingMethod])->layout('layouts.admin');
    }
}

<?php

namespace App\Http\Livewire\Employee\Settings;

use App\Models\PaymentMethod;
use Livewire\Component;

class PaymentSettingManager extends Component
{
    public $mercadopago_state = [
        'is_enabled' => false,
        'display_name' => '',
        'description' => '',
        'meta' => [
            'public_key' => '',
            'access_token' => '',
        ],
    ];

    protected $rules = [
        'mercadopago_state.is_enabled' => 'boolean',
        'mercadopago_state.display_name' => 'required|string',
        'mercadopago_state.description' => 'nullable|string',
        'mercadopago_state.meta.public_key' => 'required_if:mercadopago_state.is_enabled,true|string',
        'mercadopago_state.meta.access_token' => 'required_if:mercadopago_state.is_enabled,true|string',
    ];

    public function mount()
    {
        $this->mercadopago_state = $this->mercadopago->toArray();
    }

    public function save()
    {
        $this->validate();

        $this->mercadopago->update($this->mercadopago_state);

        $this->notify('Configurações de pagamento salvas com sucesso.');
    }

    /**
     * Nome do método/atributo mantido descritivo de propósito — este
     * registro é o Mercado Pago real que processa os pagamentos do site.
     */
    public function getMercadopagoProperty()
    {
        return PaymentMethod::query()->firstOrCreate([
            'identifier' => 'mercadopago',
        ], [
            'name' => 'Mercado Pago',
            'display_name' => 'Mercado Pago',
            'description' => 'Mercado Pago',
            'is_enabled' => false,
            'is_third_party' => true,
            'meta' => [
                'public_key' => '',
                'access_token' => '',
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.employee.settings.payment-setting-manager', [
            'mercadopago' => $this->mercadopago,
        ])->layout('layouts.admin');
    }
}
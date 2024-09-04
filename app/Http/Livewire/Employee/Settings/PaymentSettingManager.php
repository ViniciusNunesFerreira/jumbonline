<?php

namespace App\Http\Livewire\Employee\Settings;

use App\Models\PaymentMethod;
use Livewire\Component;

class PaymentSettingManager extends Component
{
    public $stripe_payment_state = [
        'is_enabled' => false,
        'display_name' => '',
        'description' => '',
        'meta' => [
            'public_key' => '',
            'access_token' => '',
        ],
    ];

    protected $rules = [
        'stripe_payment_state.is_enabled' => 'boolean',
        'stripe_payment_state.display_name' => 'required|string',
        'stripe_payment_state.description' => 'nullable|string',
        'stripe_payment_state.meta.public_key' => 'required_if:stripe_payment_state.is_enabled,true|string',
        'stripe_payment_state.meta.access_token' => 'required_if:stripe_payment_state.is_enabled,true|string',
    ];

    public function mount()
    {
        $this->stripe_payment_state = $this->stripe_payment->toArray();

    }

    public function save()
    {
        $this->validate();

        $this->stripe_payment->update($this->stripe_payment_state);

        $this->notify('Configurações de pagamento salvas com sucesso.');
    }

    public function getStripePaymentProperty()
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
            'stripe_payment' => $this->stripe_payment,
        ])->layout('layouts.admin');
    }
}

<?php

namespace App\Http\Livewire\Employee\Shipping;

use App\Services\Shipping\CustomerFreightPricing;
use App\Settings\ShippingSetting;
use Illuminate\Validation\Rule;
use Livewire\Component;

/**
 * Política de preço do frete ao cliente (site + PDV), embutida na tela de
 * Embalagens. Edita ShippingSetting; o cálculo vive em
 * CustomerFreightPricing.
 */
class ShippingPricingSettings extends Component
{
    public string $modo = CustomerFreightPricing::MODO_BALCAO;

    public $percentual = CustomerFreightPricing::DEFAULT_MARKUP_PERCENT;

    public $minimo = CustomerFreightPricing::DEFAULT_MARKUP_MINIMUM;

    public $contratoExemplo = 19.00;

    public bool $settingsDisponiveis = true;

    protected $validationAttributes = [
        'modo' => 'política',
        'percentual' => 'margem percentual',
        'minimo' => 'margem mínima',
    ];

    public function mount(): void
    {
        try {
            $settings = app(ShippingSetting::class);

            $this->modo = $settings->customer_pricing_mode;
            $this->percentual = (float) $settings->customer_markup_percent;
            $this->minimo = (float) $settings->customer_markup_minimum;
        } catch (\Throwable $e) {
            $this->settingsDisponiveis = false;
        }
    }

    public function save(): void
    {
        $this->validate([
            'modo' => ['required', Rule::in([CustomerFreightPricing::MODO_BALCAO, CustomerFreightPricing::MODO_CONTRATO_MARGEM])],
            'percentual' => ['required', 'numeric', 'min:0', 'max:200'],
            'minimo' => ['required', 'numeric', 'min:0', 'max:200'],
        ]);

        try {
            $settings = app(ShippingSetting::class);
            $settings->customer_pricing_mode = $this->modo;
            $settings->customer_markup_percent = round((float) $this->percentual, 2);
            $settings->customer_markup_minimum = round((float) $this->minimo, 2);
            $settings->save();
        } catch (\Throwable $e) {
            $this->addError('modo', 'Não foi possível salvar — execute "php artisan migrate" para criar os parâmetros de preço.');

            return;
        }

        $this->settingsDisponiveis = true;

        $this->notify(trans('Política de frete salva. Site e PDV já cotam com ela.'));
    }

    public function getExemploProperty(): ?array
    {
        if (! is_numeric($this->contratoExemplo) || ! is_numeric($this->percentual) || ! is_numeric($this->minimo)) {
            return null;
        }

        $contrato = max(0, (float) $this->contratoExemplo);
        $cliente = CustomerFreightPricing::precoPorMargem($contrato, (float) $this->percentual, (float) $this->minimo);
        $margemPercentual = round(($contrato * (float) $this->percentual) / 100, 2);

        return [
            'contrato' => $contrato,
            'cliente' => $cliente,
            'margem' => round($cliente - $contrato, 2),
            'usou_minimo' => (float) $this->minimo > $margemPercentual,
        ];
    }

    public function render()
    {
        return view('livewire.employee.shipping.shipping-pricing-settings', [
            'exemplo' => $this->exemplo,
        ]);
    }
}
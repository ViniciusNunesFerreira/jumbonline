<?php

namespace App\Http\Livewire\Setup;

use App\Settings\GeneralSetting;
use Spatie\LivewireWizard\Components\StepComponent;

class LicenseActivationStep extends StepComponent
{
    public $item_id = '38755184';

    public $state = [
        'license_key' => '',
        'license_user' => '',
        'license_vendor' => 'Envato',
        'license_active' => false,
    ];

    protected function rules()
    {
        return [
            'state.license_key' => 'required|string|uuid',
        ];
    }

    protected function messages()
    {
        return [
            'state.license_key.required' => trans('License key is required.'),
            'state.license_key.uuid' => trans('The license key must be a valid format.'),
        ];
    }

    public function mount()
    {
        $this->state['license_key'] = $this->general_settings->license_key;

        $this->state['license_user'] = $this->general_settings->license_user;

        $this->state['license_vendor'] = $this->general_settings->license_vendor;

        $this->state['license_active'] = $this->general_settings->license_active;
    }

    public function skip()
    {
        $this->nextStep();
    }

    public function save()
    {
        $this->validate();

        $this->general_settings->license_key = $this->state['license_key'];

        $this->general_settings->license_vendor = $this->state['license_vendor'];

        $this->general_settings->license_active = true;

        $this->general_settings->save();

        $this->nextStep();
    }

    public function getGeneralSettingsProperty()
    {
        return app(GeneralSetting::class);
    }

    public function render()
    {
        return view('livewire.setup.license-activation-step');
    }
}

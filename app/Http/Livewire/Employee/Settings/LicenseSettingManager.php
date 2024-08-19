<?php

namespace App\Http\Livewire\Employee\Settings;

use App\Settings\GeneralSetting;
use Livewire\Component;

class LicenseSettingManager extends Component
{
    public $item_id = '38755184';

    public $license_key;

    public $license_active = false;

    protected $listeners = ['refresh' => '$refresh'];

    protected $rules = [
        'license_key' => 'required|string',
    ];

    protected function messages()
    {
        return [
            'license_key.uuid' => trans('The license key must be a valid format.'),
        ];
    }

    public function mount()
    {
        $this->license_key = $this->general_settings->license_key;

        $this->license_active = $this->general_settings->license_active;
    }

    public function activateLicense()
    {
        $this->validate();
        $this->general_settings->license_key = $this->license_key;

        $this->general_settings->license_active = true;

        $this->general_settings->save();

        $this->license_active = true;

        $this->emit('refresh')->self();

        $this->notify(trans('License activated successfully!'));
    }

    public function deactivateLicense()
    {
        $this->general_settings->license_key = '';

        $this->general_settings->license_active = false;

        $this->general_settings->save();

        $this->license_key = '';

        $this->license_active = false;

        $this->notify(trans('License deactivated successfully!'));
    }

    public function getGeneralSettingsProperty()
    {
        return app(GeneralSetting::class);
    }

    public function render()
    {
        return view('livewire.employee.settings.license-setting-manager', [
            'general_settings' => $this->general_settings,
        ])->layout('layouts.admin');
    }
}

<?php

namespace App\Http\Livewire\Employee\Settings;

use App\Settings\CustomerSetting;
use Livewire\Component;

class CustomerSettingManager extends Component
{
    public $state = [
        'frequent_customer_min_orders' => 3,
    ];

    protected $rules = [
        'state.frequent_customer_min_orders' => 'required|integer|min:1|max:100',
    ];

    public function mount()
    {
        $this->state = [
            'frequent_customer_min_orders' => $this->customerSettings->frequent_customer_min_orders,
        ];
    }

    public function save()
    {
        $this->validate();

        $this->customerSettings->fill($this->state);

        $this->customerSettings->save();

        $this->notify(trans('Settings saved successfully!'));
    }

    public function getCustomerSettingsProperty()
    {
        return app(CustomerSetting::class);
    }

    public function render()
    {
        return view('livewire.employee.settings.customer-setting-manager')->layout('layouts.admin');
    }
}
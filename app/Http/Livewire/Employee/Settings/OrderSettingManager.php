<?php

namespace App\Http\Livewire\Employee\Settings;

use App\Settings\OrderSetting;
use Livewire\Component;

class OrderSettingManager extends Component
{
    public $state = [
        'stalled_order_days_threshold' => 2,
    ];

    protected $rules = [
        'state.stalled_order_days_threshold' => 'required|integer|min:1|max:30',
    ];

    public function mount()
    {
        $this->state = [
            'stalled_order_days_threshold' => $this->orderSettings->stalled_order_days_threshold,
        ];
    }

    public function save()
    {
        $this->validate();

        $this->orderSettings->fill($this->state);

        $this->orderSettings->save();

        $this->notify(trans('Settings saved successfully!'));
    }

    public function getOrderSettingsProperty()
    {
        return app(OrderSetting::class);
    }

    public function render()
    {
        return view('livewire.employee.settings.order-setting-manager')->layout('layouts.admin');
    }
}
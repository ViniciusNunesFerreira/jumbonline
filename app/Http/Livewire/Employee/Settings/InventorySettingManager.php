<?php

namespace App\Http\Livewire\Employee\Settings;

use App\Settings\InventorySetting;
use Livewire\Component;

class InventorySettingManager extends Component
{
    public $state = [
        'default_low_stock_threshold' => 5,
    ];

    protected $rules = [
        'state.default_low_stock_threshold' => 'required|integer|min:0',
    ];

    public function mount()
    {
        $this->state = [
            'default_low_stock_threshold' => $this->inventorySettings->default_low_stock_threshold,
        ];
    }

    public function save()
    {
        $this->validate();

        $this->inventorySettings->fill($this->state);

        $this->inventorySettings->save();

        $this->notify(trans('Settings saved successfully!'));
    }

    public function getInventorySettingsProperty()
    {
        return app(InventorySetting::class);
    }

    public function render()
    {
        return view('livewire.employee.settings.inventory-setting-manager')->layout('layouts.admin');
    }
}
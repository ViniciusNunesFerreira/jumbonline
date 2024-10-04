<?php

namespace App\Http\Livewire\Employee\Promotion;

use Livewire\Component;
use App\Enums\DiscountType;
use App\Models\Promotion;

class PromotionManager extends Component
{
    public array $state = [
        'is_enabled' => false,
        'name' => '',
        'os_value' => 0,
        
    ];

    


    protected $rules = [
        'state.is_enabled' => 'boolean',
        'state.name' => 'required|string',
        
        'state.os_value' => 'required',
        
    ];

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->state = $this->promotion->toArray();
        
    }



    public function save()
    {
        $this->validate();

        $this->promotion->update($this->state);

        $this->notify('Configurações de frete salvas com sucesso.');
    }

    public function getPromotionProperty()
    {
        return Promotion::query()->firstOrCreate([
            'name' => 'Frete Grátis',
        ], [
            'is_enabled' => false
        ]);
    }

    public function render()
    {
        return view('livewire.employee.promotion.promotion-manager')->layout('layouts.admin');
    }
}

<?php

namespace App\Http\Livewire\Employee\Product\KitProduct\Components;

use Livewire\Component;
use App\Models\Kit;
use Illuminate\Validation\Rules\Enum;

use App\Enums\KitProductStatus as KitProductStatusEnum;

class KitProductStatus extends Component
{
    public Kit $kit;
   
    protected $listeners = [
        'refresh' => '$refresh',
        'reload' => 'reload',
    ];

    protected function rules()
    {
        return [
            'kit.status' => ['required', new Enum(\App\Enums\KitProductStatus::class)],
        ];
    }


    public function save()
    {
    
        $this->kit->save();
        // emit to parent component to refresh the collection status name
        $this->emitUp('refresh');

        $this->notify(trans('Kit Status Atualizado.'));

    }

    public function render()
    {
        return view('livewire.employee.product.kit-product.components.kit-product-status');
    }
}

<?php

namespace App\Http\Livewire\Employee\Customer\Components;

use Livewire\Component;

use App\Models\Detento;
use App\Models\Customer;

class CustomerDetento extends Component
{

    public Customer $customer;
    public Detento $detento;

    public function mount()
    {
        try{
            $detento = $this->customer->detentos->first();
            if(!empty($detento)){
                $this->detento =  $detento;
            }
        }catch(\Exception $e){

            \Log::debug($e->getMessage());

        }
    }

    public function render()
    {
        return view('livewire.employee.customer.components.customer-detento');
    }
}

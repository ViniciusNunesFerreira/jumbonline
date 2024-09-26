<?php

namespace App\Http\Livewire\Employee\Customer\Components;

use App\Models\Visitante;
use App\Models\Detento;
use App\Models\Customer;

use Livewire\Component;

class CustomerAddress extends Component
{
    public Customer $customer;

   // public $visitantes = [];

    public Visitante $visitante;


    public bool $showAddressForm = false;

    public bool $showAddressesManageModal = false;

    protected $listeners = ['refresh' => '$refresh'];



    public function mount()
    {
        try{
            $visitante = $this->customer->visitantes->first();
            if(!empty($visitante)){
                $this->visitante =  $visitante;
            }
            
        }catch(\Exception $e){
            \Log::debug($e->getMessage());
        }
    }

    public function manageVisitantes()
    {
      //  $this->visitantes = $this->customer->visitantes->sortByDesc('created_at');

        $this->showAddressesManageModal = true;
    }


    public function view()
    {
        if ($this->showAddressesManageModal) $this->showAddressesManageModal = false;

        $this->showAddressForm = true;
    }

    public function save()
    {
        $filepath = optional($this->visitante->getFirstMedia('cover'))->getPath() ;
        $this->showAddressForm = false;

       return \Response::download($filepath);
    }

    public function render()
    {
        return view('livewire.employee.customer.components.customer-address');
    }
}

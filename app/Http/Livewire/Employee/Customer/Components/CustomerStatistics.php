<?php

namespace App\Http\Livewire\Employee\Customer\Components;

use App\Models\Customer;
use Livewire\Component;

class CustomerStatistics extends Component
{
    public Customer $customer;

    protected $listeners = ['refresh' => '$refresh'];

    public function getTicketMedioProperty()
    {
        if (! $this->customer->paid_orders_count) {
            return 0;
        }

        return $this->customer->ltv_total / $this->customer->paid_orders_count;
    }

    public function render()
    {
        return view('livewire.employee.customer.components.customer-statistics');
    }
}
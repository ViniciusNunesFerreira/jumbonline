<?php

namespace App\Http\Livewire\Guest;

use Livewire\Component;

class Purchase extends Component
{
    public $currentTab = 'tabs-entrega';

    public function changeTab($current)
    {
        $this->currentTab = $current;

    }

    public function render()
    {
        return view('livewire.guest.purchase')->layout('layouts.guest');
    }
}

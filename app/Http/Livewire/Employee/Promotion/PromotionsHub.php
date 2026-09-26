<?php

namespace App\Http\Livewire\Employee\Promotion;

use Livewire\Component;

class PromotionsHub extends Component
{
    public string $tab = 'discounts';

    protected $queryString = ['tab'];

    public function setTab(string $tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.employee.promotion.promotions-hub')->layout('layouts.admin');
    }
}
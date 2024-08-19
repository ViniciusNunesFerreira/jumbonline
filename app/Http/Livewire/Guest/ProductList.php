<?php

namespace App\Http\Livewire\Guest;

use Livewire\Component;

use App\Models\PrisonUnit;
use Artesaos\SEOTools\Traits\SEOTools;

class ProductList extends Component
{
    use SEOTools;

    public PrisonUnit $prison;

    public $perPage = 10;


    public function getRowsQueryProperty()
    {
        return $this->prison->collections()->with('categoriesPublished.products')->get();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.guest.product-list', ['collections' => $this->rows ])->layout('layouts.guest');
    }
}

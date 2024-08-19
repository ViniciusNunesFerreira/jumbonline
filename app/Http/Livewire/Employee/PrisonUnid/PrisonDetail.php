<?php

namespace App\Http\Livewire\Employee\PrisonUnid;

use Livewire\Component;
use App\Models\PrisonUnit;
use App\Models\PrisonCategory;
use App\Http\Livewire\Traits\WithBulkActions;

class PrisonDetail extends Component
{

    use WithBulkActions;

    public PrisonCategory $newCategory;
    public PrisonUnit $prison;

    public $categories = [];
    
    protected $rules = [
        'newCategory.name' => 'required|string',
        'prison.name' => 'required', 
        'prison.logradouro' => 'required', 
        'prison.numero' => 'required', 
        'prison.bairro' => 'required', 
        'prison.cidade' => 'required', 
        'prison.uf' => 'required', 
        'prison.cep' => 'required',
        'prison.prison_category_id' => 'required'
    ];

    public function mount(){
        $this->categories = PrisonCategory::all();
    }


    public function save()
    {
        $this->validate([
            'prison.name' => 'required', 
            'prison.logradouro' => 'required', 
            'prison.numero' => 'required', 
            'prison.bairro' => 'required', 
            'prison.cidade' => 'required', 
            'prison.uf' => 'required', 
            'prison.cep' => 'required',
            'prison.prison_category_id' => 'required'
        ]);

        $this->prison->save();

        $this->redirect( route('employee.prison.list'));
    }


    public function render()
    {
        return view('livewire.employee.prison-unid.prison-detail')->layout('layouts.admin');
    }
}

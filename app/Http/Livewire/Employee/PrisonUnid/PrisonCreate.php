<?php

namespace App\Http\Livewire\Employee\PrisonUnid;

use Livewire\Component;
use App\Models\PrisonUnit;
use App\Models\PrisonCategory;
use App\Http\Livewire\Traits\WithBulkActions;

class PrisonCreate extends Component
{
    use WithBulkActions;

    public PrisonCategory $newCategory;
    public $prison = [];
    public $category_id = '';
    public $categories = [];

    public $addingNewCategory = false;

    protected $rules = [
        'newCategory.name' => 'required|string',
        'prison.name' => 'required', 
        'prison.logradouro' => 'required', 
        'prison.numero' => 'required', 
        'prison.bairro' => 'required', 
        'prison.cidade' => 'required', 
        'prison.uf' => 'required', 
        'prison.cep' => 'required',
        'category_id' => 'required'
    ];

    public function mount(){
        $this->categories = PrisonCategory::all();
        $this->prison = new PrisonUnit();
    }

    public function addNewCategory()
    {
        $this->newCategory = new PrisonCategory();

        $this->addingNewCategory = true;
    }

    public function saveNewCategory()
    {
        $this->validate([
            'newCategory.name' => 'required',
        ]);

        $this->newCategory->save();

        $this->addingNewCategory = false;

        $this->redirect( route('employee.prison.create', $this->newCategory));
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
            'category_id' => 'required'
        ]);

        $category = $this->categories->find($this->category_id);
        $this->prison->prisonCategory()->associate($category);
        $this->prison->save();

        $this->redirect( route('employee.prison.list'));
    }

    


    public function render()
    {
        return view('livewire.employee.prison-unid.prison-create')->layout('layouts.admin');
    }
}

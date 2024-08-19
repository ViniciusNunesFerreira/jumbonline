<?php

namespace App\Http\Livewire\Employee\PrisonUnid\Components;

use Livewire\Component;
use App\Models\PrisonUnit;
use App\Models\Collection;

class PrisonCollection extends Component
{
    public PrisonUnit $prison;

    public $collections = [];

    public $selected = [];

    public $search = '';

    public bool $isBrowsingProducts = false;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->prison->load([
            'collections' => function ($query) {
                $query->select('id', 'title', 'slug');
            }
        ]);
    }

    public function browse()
    {
        $this->reset('collections', 'selected', 'search');

        $this->selected = $this->prison->collections->pluck('id')->toArray();

        $this->isBrowsingProducts = true;
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->collections = Collection::query()
            ->select('id', 'title')
            ->when($this->search, fn($query, $search) => $query->where('title', 'like', '%' . $search . '%'))
            ->latest()
            ->get();
    }


    public function save()
    {
        $this->prison->collections()->sync($this->selected);

        $this->emit('refresh')->self();

        $this->notify(trans('Unidade Atualizada'));

        $this->isBrowsingProducts = false;
    }

    public function delete(Collection $collection)
    {
        $this->prison->collections()->detach($collection);

        $this->notify(trans('Grupo removido da Unidade'));

        $this->emit('refresh')->self();
    }




    public function render()
    {
        return view('livewire.employee.prison-unid.components.prison-collection');
    }
}

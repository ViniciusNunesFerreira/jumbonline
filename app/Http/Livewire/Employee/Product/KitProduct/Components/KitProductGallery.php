<?php

namespace App\Http\Livewire\Employee\Product\KitProduct\Components;

use Livewire\Component;
use App\Models\Kit;
use Livewire\WithFileUploads;

class KitProductGallery extends Component
{
    use WithFileUploads;
    public Kit $product;

    public $media = [];

    public $selected = [];

    public bool $confirmingMediaDeletion = false;

    protected $listeners = ['refresh' => '$refresh', 'upload:finished' => 'save'];

    public function save()
    {
        $this->validate([
            'media.*' => 'file|image|max:5120',
        ]);

        collect($this->media)->each(
            fn($medium) => $this->product
                ->addMedia($medium->getRealPath())
                ->usingName(pathinfo($medium->getClientOriginalName(), PATHINFO_FILENAME))
                ->usingFileName($medium->getClientOriginalName())
                ->toMediaCollection('gallery')
        );

        $this->reset('media');

        $this->emitSelf('refresh');

        $this->notify(trans('Media uploaded.'));
    }

    public function delete()
    {
        $media = $this->product->media()->whereIn('id', $this->selected)->get();

        $media->each(fn($medium) => $medium->delete());

        $this->confirmingMediaDeletion = false;

        $this->reset('selected');

        $this->emitSelf('refresh');

        $this->notify(trans('Media deleted.'));
    }


    public function render()
    {
        return view('livewire.employee.product.kit-product.components.kit-product-gallery');
    }
}

<?php

namespace App\Http\Livewire\Guest\PurchaseComponents;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Visitante;

class VisitanteGallery extends Component
{
    use WithFileUploads;

    public Visitante $visitante;

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
            fn($medium) => $this->visitante
                ->addMedia($medium->getRealPath())
                ->usingName(pathinfo($medium->getClientOriginalName(), PATHINFO_FILENAME))
                ->usingFileName($medium->getClientOriginalName())
                ->toMediaCollection('gallery')
        );

        $this->reset('media');

        $this->emitSelf('refresh');

        $this->notify(trans('Imagem Enviada com sucesso.'));
    }

    public function delete()
    {
        $media = $this->visitante->media()->whereIn('id', $this->selected)->get();

        $media->each(fn($medium) => $medium->delete());

        $this->confirmingMediaDeletion = false;

        $this->reset('selected');

        $this->emitSelf('refresh');

        $this->notify(trans('Imagem Deletada.'));
    }


    public function render()
    {
        return view('livewire.guest.purchase-components.visitante-gallery');
    }
}

<?php

namespace App\Http\Livewire\Employee\Product\KitProduct\Components;

use Livewire\Component;
use App\Models\Kit;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class KitProductInformation extends Component
{
    use WithFileUploads;

    public Kit $kit;
    public $image;
    public $imageUrl;
    public $selectedImage;
    public $showImageModal = false;

    protected $listeners = ['openImageModal'];

    protected function rules()
    {
        return [
            'kit.title' => ['required', 'string'],
            'kit.price' => ['required', 'numeric'],
        ];
    }
    

    public function updatedImage()
    {
        $this->validate([
            'image' => 'required|image|max:5120',
        ]);

        $this->uploadImageFromFile();
    }

    public function openImageModal()
    {
        $this->showImageModal = true;
    }

    public function uploadImageFromFile()
    {
        $this->product
            ->addMedia($this->image->getRealPath())
            ->usingName(pathinfo($this->image->getClientOriginalName(), PATHINFO_FILENAME))
            ->usingFileName($this->image->getClientOriginalName())
            ->toMediaCollection('images');

        $this->product->load(['media' => function ($query) {
            return $query->latest();
        }]);

        $this->reset('image');

        $this->dispatchBrowserEvent('upload-image-success', ['imageId' => $this->product->getMedia('images')->last()->id]);
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function uploadImageFromURL()
    {
        $this->validate([
            'imageUrl' => 'required|url',
        ]);

        $this->kit->addMediaFromUrl($this->imageUrl)
            ->toMediaCollection('images');

        $this->kit->load(['media' => function ($query) {
            return $query->latest();
        }]);

        $this->reset('imageUrl');

        $this->dispatchBrowserEvent('upload-image-success', ['imageId' => $this->kit->getMedia('images')->last()->id]);
    }

    public function insertImage(Media $image)
    {
        $this->dispatchBrowserEvent('tiptap-insert-image', ['name' => $image->name, 'url' => $image->getFullUrl()]);

        $this->showImageModal = false;
    }

    public function deleteImage(Media $image)
    {
        $image->delete();

        $this->kit->load(['media' => function ($query) {
            return $query->latest();
        }]);
    }

    public function save()
    {
        $this->validate();

        $this->kit->save();

        $this->emit('refresh');

        $this->notify(trans('Informações do Kit atualizadas.'));
    }

    public function render()
    {
        return view('livewire.employee.product.kit-product.components.kit-product-information');
    }
}

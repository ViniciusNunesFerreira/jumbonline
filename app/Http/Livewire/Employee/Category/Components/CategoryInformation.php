<?php

namespace App\Http\Livewire\Employee\Category\Components;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryInformation extends Component
{
    use WithFileUploads;

    public Category $category;
    public $image;
    public $imageUrl;
    public $selectedImage;
    public $showImageModal = false;

    protected $listeners = ['openImageModal'];

    protected function rules()
    {
        return [
            'category.title' => ['required', 'string'],
            'category.slug' => ['required', 'string', Rule::unique('categories', 'slug')->ignoreModel($this->category)],
            'category.description' => ['nullable', 'string'],
            'category.quantity' => ['nullable', 'integer']
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
        $this->category
            ->addMedia($this->image->getRealPath())
            ->usingName(pathinfo($this->image->getClientOriginalName(), PATHINFO_FILENAME))
            ->usingFileName($this->image->getClientOriginalName())
            ->toMediaCollection('images');

        $this->category->load(['media' => function ($query) {
            return $query->latest();
        }]);

        $this->reset('image');

        $this->dispatchBrowserEvent('upload-image-success', ['imageId' => $this->category->getMedia('images')->last()->id]);
    }

    public function uploadImageFromURL()
    {
        $this->validate([
            'imageUrl' => 'required|url',
        ]);

        $this->category->addMediaFromUrl($this->imageUrl)
            ->toMediaCollection('images');

        $this->category->load(['media' => function ($query) {
            return $query->latest();
        }]);

        $this->reset('imageUrl');

        $this->dispatchBrowserEvent('upload-image-success', ['imageId' => $this->category->getMedia('images')->last()->id]);
    }


    public function insertImage(Media $image)
    {
        $this->dispatchBrowserEvent('tiptap-insert-image', ['name' => $image->name, 'url' => $image->getFullUrl()]);

        $this->showImageModal = false;
    }

    public function deleteImage(Media $image)
    {
        $image->delete();

        $this->category->load(['media' => function ($query) {
            return $query->latest();
        }]);
    }

    public function save()
    {
        $this->validate();

        $this->category->save();

        $this->notify(trans('Informações de Categoria Atualizada.'));
    }


    public function render()
    {
        return view('livewire.employee.category.components.category-information');
    }
}

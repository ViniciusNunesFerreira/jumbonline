<?php

namespace App\Http\Livewire\Employee\Category\Components;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CategoryCover extends Component
{
    use WithFileUploads;

    public Category $category;
    
    public $image;

    protected $listeners = ['refresh' => '$refresh', 'upload:finished' => 'save'];

    public function save()
    {
        $this->validate([
            'image' => 'file|image|max:5120',
        ]);

        try {
            $this->category
                ->addMedia($this->image->getRealPath())
                ->usingName(pathinfo($this->image->getClientOriginalName(), PATHINFO_FILENAME))
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('cover');
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            $this->notify($e->getMessage());
        }

        $this->reset('image');

        $this->emit('refresh')->self();
    }

    public function delete()
    {
        $this->category->getFirstMedia('cover')->delete();

        $this->emit('refresh')->self();
    }

    public function render()
    {
        return view('livewire.employee.category.components.category-cover');
    }
}

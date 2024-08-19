<?php

namespace App\Http\Livewire\Employee\Carousel;

use App\Models\Carousel;
use Illuminate\Support\Str;
use Livewire\Component;

class CarouselList extends Component
{
    public Carousel $carousel;

    public $showCarouselForm = false;

    protected function rules()
    {
        return [
            'carousel.name' => 'required',
            'carousel.slug' => 'required|unique:carousels,slug',
        ];
    }

    public function mount()
    {
        $this->carousel = new Carousel();
    }

    public function create()
    {
        $this->showCarouselForm = true;
    }

    public function updatedCarouselName($value)
    {
        $this->carousel->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate();

        $this->carousel->save();

        $this->redirect(route('employee.settings.carousels.detail', $this->carousel));
    }

    public function getCarouselsProperty()
    {
        return Carousel::withCount('slides')->get();
    }

    public function render()
    {
        return view('livewire.employee.carousel.carousel-list', [
            'carousels' => $this->carousels,
        ])->layout('layouts.admin');
    }
}

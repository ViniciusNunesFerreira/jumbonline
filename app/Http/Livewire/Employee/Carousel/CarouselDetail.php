<?php

namespace App\Http\Livewire\Employee\Carousel;

use App\Models\Carousel;
use App\Models\CarouselSlide;
use Livewire\Component;
use Livewire\WithFileUploads;

class CarouselDetail extends Component
{
    use WithFileUploads;

    public Carousel $carousel;

    public $showCarouselForm = false;

    public CarouselSlide $slide;

    public $slideImage = null;

    public $showSlideForm = false;

    protected function rules()
    {
        return [
            'carousel.name' => 'required',
            'carousel.slug' => 'required|unique:carousels,slug,' . $this->carousel->id,
            'slide.title' => 'nullable',
            'slide.description' => 'nullable',
            'slide.button_link' => 'nullable',
            'slide.button_text' => 'nullable',
        ];
    }

    public function mount()
    {
        $this->slide = new CarouselSlide();

        $this->carousel->load('slides.media');
    }

    public function editCarousel()
    {
        $this->showCarouselForm = true;
    }

    public function saveCarousel()
    {
        $this->validate([
            'carousel.name' => 'required',
            'carousel.slug' => 'required|unique:carousels,slug,' . $this->carousel->id,
        ]);

        $this->carousel->save();

        $this->showCarouselForm = false;
    }

    public function deleteCarousel()
    {
        $this->carousel->delete();

        $this->redirect(route('employee.settings.carousels.list'));
    }

    public function createSlide()
    {
        $this->slideImage = null;

        $this->slide = new CarouselSlide();

        $this->showSlideForm = true;
    }

    public function editSlide(CarouselSlide $slide)
    {
        $this->slide = $slide;

        $this->showSlideForm = true;
    }

    public function saveSlide()
    {
        $this->validate([
            'slide.title' => 'required|string',
            'slide.description' => 'nullable',
            'slide.button_link' => 'nullable',
            'slide.button_text' => 'nullable',
        ]);

        $this->slide->carousel_id = $this->carousel->id;

        $this->slide->save();

        if ($this->slideImage) {
            $this->slide
                ->addMedia($this->slideImage->getRealPath())
                ->usingFileName($this->slideImage->getClientOriginalName())
                ->toMediaCollection('image');
        }

        $this->slideImage = null;

        $this->showSlideForm = false;

        $this->carousel->load('slides')->loadCount('slides');
    }

    public function deleteSlide()
    {
        $this->slide->delete();

        $this->showSlideForm = false;

        $this->carousel->load('slides')->loadCount('slides');
    }

    public function getSlidesProperty()
    {
        return $this->carousel->slides;
    }

    public function render()
    {
        return view('livewire.employee.carousel.carousel-detail', [
            'slides' => $this->slides,
        ])->layout('layouts.admin');
    }
}

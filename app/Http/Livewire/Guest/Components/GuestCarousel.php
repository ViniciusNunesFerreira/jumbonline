<?php

namespace App\Http\Livewire\Guest\Components;

use Livewire\Component;
use App\Models\Carousel;
use App\Settings\TemplateSetting;

class GuestCarousel extends Component
{


    public function getTemplateSettingsProperty()
    {
        return app(TemplateSetting::class);
    }

    public function getCarouselsProperty()
    {
        return Carousel::with('slides.media')->get();
    }

    public function getHeroCarouselProperty()
    {
        if ($this->template_settings->home_page_hero_carousel_handle) {
            return $this->carousels
                ->where('slug', $this->template_settings->home_page_hero_carousel_handle)
                ->first();
        }
    }

    public function getPerkCarouselProperty()
    {
        if ($this->template_settings->home_page_perk_carousel_handle) {
            return $this->carousels
                ->where('slug', $this->template_settings->home_page_perk_carousel_handle)
                ->first();
        }
    }

    public function getCollectionCarouselProperty()
    {
        if ($this->template_settings->home_page_collection_section_carousel_handle) {
            return $this->carousels
                ->where('slug', $this->template_settings->home_page_collection_section_carousel_handle)
                ->first();
        }
    }
    
    public function render()
    {
        return view('livewire.guest.components.guest-carousel');
    }
}

<?php

namespace App\Http\Livewire\Guest;

use App\Models\Carousel;
use App\Settings\TemplateSetting;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use App\Models\PrisonCategory;
use App\Models\Prison;
use Illuminate\Http\Request;


class Welcome extends Component
{
    use SEOTools;


    public $prison_categories = [];

    
    public $prison = "";

    public function mount()
    {
        $this->seo()->setTitle($this->template_settings->home_page_title);

        $this->seo()->setDescription($this->template_settings->home_page_description);

        $this->seo()->opengraph()->addImage(asset('/img/social-card.jpg'), [
            'height' => 1260,
            'width' => 2400,
            'type' => 'image/jpeg'
        ]);

        $this->seo()->twitter()->addValue('card', 'summary_large_image');

        $this->prison_categories = PrisonCategory::with(['prisonUnits'])->get();
    }

    protected $rules = [
        'prison' => 'required',
    ];

    public function messages() 
    {
        return [
            'prison.required' => 'Selecione uma Unidade Prisional',
        ];
    }

    public function getRandomProductsProperty(): \Illuminate\Database\Eloquent\Collection|array
    {
        return \App\Models\Product::query()
            ->with(['reviews', 'media'])
            ->where('name', 'like', "%xiaomi%")
            ->inRandomOrder()
            ->limit(8)
            ->get();
    }

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

    public function getPrisonProducts(Request $request)
    {
        $this->validate();

        
        $request->session()->put('prison', $this->prison);

        $prison_unit_url = route('guest.products.list', $this->prison); 
        return redirect()->to($prison_unit_url);
        
    }

    public function render()
    {
        if(env('APP_MAINTENANCE')){
            return view('livewire.guest.maintenance')->layout('layouts.maintenance');
        }
        
        return view('livewire.guest.welcome', [ 'prison_categories' => $this->prison_categories ])->layout('layouts.guest');
    }
}

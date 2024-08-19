<?php

namespace App\Http\Livewire\Employee\Settings;

use App\Models\Carousel;
use App\Models\Collection;
use App\Models\Product;
use App\Settings\TemplateSetting;
use Livewire\Component;
use Livewire\WithFileUploads;

class TemplateSettingManager extends Component
{
    use WithFileUploads;

    public $addingSection = false;

    public $newSectionData = [
        'type' => '',
        'title' => '',
        'link' => '',
        'link_text' => '',
        'banner_path' => '',
        'carousel_handle' => '',
        'items' => [],
        'order' => 0,
    ];

    public $editingSection = false;

    public $editingSectionIndex = 0;

    public $bannerFile;

    public $bannerMaxSize = 10000; // 10MB

    public $showCollectionModal = false;

    public $browsingCollectionFor;

    public $filterCollectionTitle;

    public $collections = [];

    public $selectedCollections = [];

    public $showProductModal = false;

    public $browsingProductFor;

    public $filterProductName;

    public $products = [];

    public $selectedProducts = [];

    public $state = [
        'home_page_title' => '',
        'home_page_description' => '',
        'home_page_hero_carousel_handle' => '',
        'home_page_perk_carousel_handle' => '',
        'home_page_sections' => [],
    ];

    protected $rules = [
        'state.home_page_title' => 'required',
        'state.home_page_description' => 'required',
        'state.home_page_hero_carousel_handle' => 'sometimes|exists:carousels,slug',
        'state.home_page_carousel_handle' => 'sometimes|exists:carousels,slug',
    ];

    public function mount()
    {
        $this->state = [
            'home_page_title' => $this->template_settings->home_page_title,
            'home_page_description' => $this->template_settings->home_page_description,
            'home_page_hero_carousel_handle' => $this->template_settings->home_page_hero_carousel_handle,
            'home_page_perk_carousel_handle' => $this->template_settings->home_page_perk_carousel_handle,
            'home_page_sections' => $this->template_settings->home_page_sections,
        ];
    }

    public function addSection($type)
    {
        $order = collect($this->state['home_page_sections'])->pluck('order')->max();

        $this->newSectionData['type'] = $type;

        $this->newSectionData['order'] = $order + 1;

        $this->addingSection = true;
    }

    public function saveSection()
    {
        if ($this->bannerFile) {
            $this->newSectionData['banner_path'] = $this->bannerFile->store('templates/home-page/banners', 'public');

            $this->reset('bannerFile');
        }

        $this->state['home_page_sections'][] = $this->newSectionData;

        $this->reset('newSectionData');

        $this->save();

        $this->addingSection = false;
    }

    public function editSection($sectionIndex)
    {
        $this->editingSectionIndex = $sectionIndex;

        $this->editingSection = true;
    }

    public function updateSection()
    {
        if ($this->bannerFile) {
            $this->state['home_page_sections'][$this->editingSectionIndex]['banner_path'] = $this->bannerFile->store('templates/home-page/banners', 'public');
        }

        $this->save();

        $this->editingSection = false;
    }

    public function updatedBannerFile()
    {
        $validator = \Validator::make(
            ['bannerFile' => $this->bannerFile],
            ['bannerFile' => 'required|image|max:' . $this->bannerMaxSize]
        );

        if ($validator->fails()) {
            $this->addError('bannerFile', $validator->errors()->first('bannerFile'));

            $this->reset('bannerFile');
        }
    }

    public function removeSectionBanner()
    {
        if ($this->bannerFile) {
            $this->reset('bannerFile');
        }

        if (isset($this->state['home_page_sections'][$this->editingSectionIndex]['banner_path'])) {
            \Storage::disk('public')->delete($this->state['home_page_sections'][$this->editingSectionIndex]['banner_path']);
        }

        $this->state['home_page_sections'][$this->editingSectionIndex]['banner_path'] = null;
    }

    public function removeSection($sectionIndex)
    {
        $sections = collect($this->state['home_page_sections'])->sortBy('order');

        if (isset($sections[$sectionIndex]['banner_path'])) {
            \Storage::disk('public')->delete($sections[$sectionIndex]['banner_path']);
        }

        $this->state['home_page_sections'] = $sections->reject(fn($section, $index) => $index == $sectionIndex)->values()->toArray();

        $this->save();
    }

    public function save()
    {
        $this->validate();

        $this->template_settings->fill($this->state);

        $this->template_settings->save();

        $this->reset('bannerFile');
        
        $this->notify(trans('Settings saved successfully!'));
    }

    public function getTemplateSettingsProperty()
    {
        return app(TemplateSetting::class);
    }

    public function getCarouselsProperty()
    {
        return Carousel::all();
    }

    public function browseCollections($sectionIndex)
    {
        $this->browsingCollectionFor = $sectionIndex;

        $this->selectedCollections = $this->state['home_page_sections'][$sectionIndex]['items'];

        $this->showCollectionModal = true;
    }

    public function loadCollections(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->collections = Collection::query()
            ->with('media')
            ->when($this->filterCollectionTitle, fn($query, $search) => $query->where('title', 'like', '%' . $search . '%'))
            ->get();
    }

    public function updatedFilterCollectionTitle()
    {
        $this->loadCollections();
    }

    public function addCollections()
    {
        $this->state['home_page_sections'][$this->browsingCollectionFor]['items'] = array_values(array_unique($this->selectedCollections));

        $this->reset(['browsingCollectionFor', 'selectedCollections']);

        $this->showCollectionModal = false;
    }

    public function browseProducts($sectionIndex)
    {
        $this->browsingProductFor = $sectionIndex;

        $this->selectedProducts = $this->state['home_page_sections'][$sectionIndex]['items'];

        $this->showProductModal = true;
    }

    public function loadProducts()
    {
        return $this->products = Product::query()
            ->with('media')
            ->when($this->filterProductName, fn($query, $search) => $query->where('name', 'like', '%' . $search . '%'))
            ->get();
    }

    public function updatedFilterProductName()
    {
        $this->loadProducts();
    }

    public function addProducts()
    {
        $this->state['home_page_sections'][$this->browsingProductFor]['items'] = array_values(array_unique($this->selectedProducts));

        $this->reset(['browsingProductFor', 'selectedProducts']);

        $this->showProductModal = false;
    }

    public function render()
    {
        return view('livewire.employee.settings.template-setting-manager')->layout('layouts.admin');
    }
}

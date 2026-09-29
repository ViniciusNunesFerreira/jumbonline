<?php

namespace App\Http\Livewire\Employee\Settings;

use App\Settings\TemplateSetting;
use Livewire\Component;

class TemplateSettingManager extends Component
{
    public $state = [
        'home_page_title' => '',
        'home_page_description' => '',
    ];

    protected $rules = [
        'state.home_page_title' => 'required|string',
        'state.home_page_description' => 'required|string',
    ];

    public function mount()
    {
        $this->state = [
            'home_page_title' => $this->template_settings->home_page_title,
            'home_page_description' => $this->template_settings->home_page_description,
        ];
    }

    public function save()
    {
        $this->validate();

        // fill() só toca nestas duas chaves — carrosséis e seções antigas
        // já salvas continuam intactas no banco.
        $this->template_settings->fill($this->state);

        $this->template_settings->save();

        $this->notify(trans('Configurações da página inicial salvas com sucesso.'));
    }

    public function getTemplateSettingsProperty()
    {
        return app(TemplateSetting::class);
    }

    public function render()
    {
        return view('livewire.employee.settings.template-setting-manager')->layout('layouts.admin');
    }
}
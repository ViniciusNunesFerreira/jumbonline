<?php

namespace App\Http\Livewire\Employee\Settings;

use App\Models\Menu;
use App\Settings\LayoutSetting;
use Livewire\Component;

class LayoutSettingManager extends Component
{
    public $state = [
        'header_top_bar_enabled' => false,
        'header_top_bar_message' => '',
        'header_top_bar_menu_handle' => '',
        'header_main_menu_handle' => '',
        'footer_bottom_bar_enabled' => false,
        'footer_bottom_bar_message' => '',
        'footer_bottom_bar_menu_handle' => '',
        'footer_main_menu_handle' => '',
    ];

    protected $rules = [
        'state.header_top_bar_enabled' => 'boolean',
        'state.header_top_bar_message' => 'nullable',
        'state.header_top_bar_menu_handle' => 'nullable',
        'state.header_main_menu_handle' => 'nullable',
        'state.footer_bottom_bar_enabled' => 'boolean',
        'state.footer_bottom_bar_message' => 'nullable',
        'state.footer_bottom_bar_menu_handle' => 'nullable',
        'state.footer_main_menu_handle' => 'nullable',
    ];

    public function mount()
    {
        $this->state['header_top_bar_enabled'] = $this->layout_settings->header_top_bar_enabled;
        $this->state['header_top_bar_message'] = $this->layout_settings->header_top_bar_message;
        $this->state['header_top_bar_menu_handle'] = $this->layout_settings->header_top_bar_menu_handle;
        $this->state['header_main_menu_handle'] = $this->layout_settings->header_main_menu_handle;
        $this->state['footer_bottom_bar_enabled'] = $this->layout_settings->footer_bottom_bar_enabled;
        $this->state['footer_bottom_bar_message'] = $this->layout_settings->footer_bottom_bar_message;
        $this->state['footer_bottom_bar_menu_handle'] = $this->layout_settings->footer_bottom_bar_menu_handle;
        $this->state['footer_main_menu_handle'] = $this->layout_settings->footer_main_menu_handle;
    }

    public function save()
    {
        $this->validate();

        $this->layout_settings->fill($this->state);

        $this->layout_settings->save();

        $this->notify(trans('Layout settings saved successfully.'));
    }

    public function getLayoutSettingsProperty()
    {
        return app(LayoutSetting::class);
    }

    public function getMenusProperty()
    {
        return Menu::all();
    }

    public function render()
    {
        return view('livewire.employee.settings.layout-setting-manager', [
            'menus' => $this->menus,
        ])->layout('layouts.admin');
    }
}

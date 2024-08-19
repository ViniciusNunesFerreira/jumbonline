<?php

namespace App\Http\Livewire\Employee\Settings;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Validation\Rule;
use Livewire\Component;

class NavigationSettingManager extends Component
{
    public $menu;

    public $showMenuForm = false;

    public $menuItem;

    public $confirmingMenuDeletion = false;

    public $menuBeingDeleted;

    public $showMenuItemForm = false;

    public $confirmingMenuItemDeletion = false;

    public $menuItemBeingDeleted;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    protected function rules()
    {
        return [
            'menu.name' => ['required'],
            'menu.slug' => ['required', Rule::unique('menus', 'slug')->ignore($this->menu)],
            'menuItem.parent_id' => ['nullable', 'exists:menu_items,id'],
            'menuItem.name' => ['required'],
            'menuItem.url' => ['required'],
            'menuItem.menu_id' => ['required'],
        ];
    }

    public function addMenu()
    {
        $this->menu = new Menu();

        $this->showMenuForm = true;
    }

    public function editMenu(Menu $menu)
    {
        $this->menu = $menu;

        $this->showMenuForm = true;
    }

    public function saveMenu()
    {
        $this->validate([
            'menu.name' => ['required'],
            'menu.slug' => ['required', Rule::unique('menus', 'slug')->ignore($this->menu)],
        ]);

        $this->menu->save();

        $this->reset('menu');

        $this->showMenuForm = false;
    }

    public function confirmMenuDeletion(Menu $menu)
    {
        $this->reset('menu');

        $this->menuBeingDeleted = $menu;

        $this->confirmingMenuDeletion = true;
    }

    public function deleteMenu()
    {
        $this->menuBeingDeleted->delete();

        $this->reset('menuBeingDeleted');

        $this->confirmingMenuDeletion = false;
    }

    public function addMenuItem($menuId, $parentId = null)
    {
        $this->menuItem = new MenuItem([
            'menu_id' => $menuId,
            'parent_id' => $parentId,
        ]);

        $this->showMenuItemForm = true;
    }

    public function editMenuItem(MenuItem $menuItem)
    {
        $this->menuItem = $menuItem;

        $this->showMenuItemForm = true;
    }

    public function saveMenuItem()
    {
        $this->validate([
            'menuItem.parent_id' => ['nullable', 'exists:menu_items,id'],
            'menuItem.name' => ['required'],
            'menuItem.url' => ['required'],
            'menuItem.menu_id' => ['required'],
        ]);

        $this->menuItem->save();

        $this->reset('menuItem');

        $this->showMenuItemForm = false;
    }

    public function confirmMenuItemDeletion(MenuItem $menuItem)
    {
        $this->reset('menuItem');

        $this->menuItemBeingDeleted = $menuItem;

        $this->confirmingMenuItemDeletion = true;
    }

    public function deleteMenuItem()
    {
        $this->menuItemBeingDeleted->delete();

        $this->reset('menuItemBeingDeleted');

        $this->confirmingMenuItemDeletion = false;
    }

    public function getMenusProperty()
    {
        $menus = Menu::all();

        $menus->map(function ($menu) {
            $menu->setRelation('menuItems', MenuItem::with('parent')->treeOf(fn($query) => $query->isRoot()->where('menu_id', $menu->id))->get()->toTree());
        });

        return $menus;
    }

    public function render()
    {
        return view('livewire.employee.settings.navigation-setting-manager', [
            'menus' => $this->menus,
        ])->layout('layouts.admin');
    }
}

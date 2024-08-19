<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Navigation') }}
    </x-slot:title>

    <!-- Page content -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="py-6 xl:flex-auto xl:py-0">
            <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                <div class="ml-4 mt-4">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-slate-200">
                        {{ __('Navigation') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">
                        {{ __('Menus, or link lists, help your customers navigate around your online store.') }}
                    </p>
                </div>
                <div class="ml-4 mt-4 flex-shrink-0">
                    <button
                        wire:click.prevent="addMenu"
                        class="btn btn-primary"
                    >
                        {{ __('Add new menu') }}
                    </button>
                </div>
            </div>
            @foreach($menus as $menu)
                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="col-span-full">
                        <x-card>
                            <x-slot:header>
                                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                    <div class="ml-4 mt-2">
                                        <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                                            {{ $menu->name }}
                                        </h3>
                                    </div>
                                    <div class="ml-4 mt-2 flex-shrink-0">
                                        <x-dropdown>
                                            <x-slot:trigger>
                                                <button
                                                    type="button"
                                                    class="group flex items-center "
                                                >
                                                    <span class="sr-only">
                                                        {{ __('Open menu') }}
                                                    </span>
                                                    <x-heroicon-o-ellipsis-horizontal class="w-5 h-5 fill-current text-slate-700 group-hover:text-slate-900 dark:text-slate-400 dark:group-hover:text-slate-200" />
                                                </button>
                                            </x-slot:trigger>
                                            <x-slot:content>
                                                <x-dropdown-link
                                                    wire:click.prevent="editMenu({{ $menu->id }})"
                                                    role="button"
                                                >
                                                    {{ __('Edit') }}
                                                </x-dropdown-link>
                                                <x-dropdown-link
                                                    wire:click.prevent="confirmMenuDeletion({{ $menu->id }})"
                                                    role="button"
                                                >
                                                    {{ __('Delete') }}
                                                </x-dropdown-link>
                                            </x-slot:content>
                                        </x-dropdown>
                                    </div>
                                </div>
                            </x-slot:header>
                            <x-slot:content class="-mt-5">
                                @if($menu->menuItems->count())
                                    <x-menu-tree :items="$menu->menuItems" />
                                @else
                                    <button
                                        wire:click.prevent="addMenuItem({{ $menu->id }})"
                                        type="button"
                                        class="group relative block w-full rounded-lg border-2 border-dashed border-sky-300 p-4 text-center hover:border-sky-400 focus:outline-none dark:border-white/10 dark:bg-slate-900 dark:hover:border-white/20"
                                    >
                                        <x-heroicon-o-squares-plus class="mx-auto h-10 w-10 text-sky-500 dark:text-slate-400" />
                                        <span class="mt-2 block text-sm text-sky-500 group-hover:text-sky-600 dark:group-hover:text-sky-400">
                                            {{ __('Add first item to this menu') }}
                                        </span>
                                    </button>
                                @endif
                            </x-slot:content>
                        </x-card>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <form wire:submit.prevent="saveMenu">
        <x-slide-over wire:model="showMenuForm">
            <x-slot:title>
                {{ __('Add menu') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label
                            for="menuNameInput"
                            value="{{  __('Title') }}"
                        />
                        <x-input
                            wire:model.defer="menu.name"
                            type="text"
                            id="menuNameInput"
                            class="mt-2 block w-full sm:text-sm"
                            placeholder="{{ __('Eg: Footer menu') }}"
                        />
                        <x-input-error
                            for="menu.name"
                            class="mt-1"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="menuHandleInput"
                            value="{{  __('Handle') }}"
                        />
                        <x-input
                            wire:model.defer="menu.slug"
                            type="text"
                            id="menuHandleInput"
                            class="mt-2 block w-full sm:text-sm"
                            placeholder="{{ __('Eg: footer-menu') }}"
                        />
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('A handle is used to reference a menu in Layout.') }}
                        </p>
                        <x-input-error
                            for="menu.slug"
                            class="mt-1"
                        />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <div class="flex flex-shrink-0 justify-end">
                    <button
                        x-on:click="show = false"
                        type="button"
                        class="btn btn-invisible"
                    >
                        {{ __('Cancel') }}
                    </button>
                    <button
                        type="submit"
                        class="ml-4 btn btn-primary"
                    >
                        {{ __('Save') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-slide-over>
    </form>

    <form wire:submit.prevent="deleteMenu">
        <x-modal-alert wire:model="confirmingMenuDeletion">
            <x-slot:title>
                {{ __('Remove menu?') }}
            </x-slot:title>
            <x-slot:content>
                {{ __('Are you sure you want to delete this menu? This action cannot be undone!') }}
            </x-slot:content>
            <x-slot:footer>
                <button
                    type="submit"
                    class="btn btn-danger w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Delete') }}
                </button>
                <button
                    x-on:click="show = false"
                    type="button"
                    class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancel') }}
                </button>
            </x-slot:footer>
        </x-modal-alert>
    </form>

    <form wire:submit.prevent="saveMenuItem">
        <x-slide-over wire:model="showMenuItemForm">
            <x-slot:title>
                {{ __('Add menu') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label
                            for="menuItemNameInput"
                            value="{{  __('Name') }}"
                        />
                        <x-input
                            wire:model.defer="menuItem.name"
                            type="text"
                            id="menuItemNameInput"
                            class="mt-2 block w-full sm:text-sm"
                            placeholder="{{ __('Eg: Contact us') }}"
                            autofocus
                        />
                        <x-input-error
                            for="menuItem.name"
                            class="mt-1"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="menuItemUrlInput"
                            value="{{  __('Link') }}"
                        />
                        <x-input
                            wire:model.defer="menuItem.url"
                            type="text"
                            id="menuItemUrlInput"
                            class="mt-2 block w-full sm:text-sm"
                            placeholder="{{ __('Eg: https://example.com') }}"
                        />
                        <x-input-error
                            for="menuItem.url"
                            class="mt-1"
                        />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <div class="flex flex-shrink-0 justify-end">
                    <button
                        x-on:click="show = false"
                        type="button"
                        class="btn btn-invisible"
                    >
                        {{ __('Cancel') }}
                    </button>
                    <button
                        type="submit"
                        class="ml-4 btn btn-primary"
                    >
                        {{ __('Save') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-slide-over>
    </form>

    <form wire:submit.prevent="deleteMenuItem">
        <x-modal-alert wire:model="confirmingMenuItemDeletion">
            <x-slot:title>
                {{ __('Remove menu item?') }}
            </x-slot:title>
            <x-slot:content>
                {{ __('Are you sure you want to delete this menu item? This action cannot be undone!') }}
            </x-slot:content>
            <x-slot:footer>
                <button
                    type="submit"
                    class="btn btn-danger w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Delete') }}
                </button>
                <button
                    x-on:click="show = false"
                    type="button"
                    class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancel') }}
                </button>
            </x-slot:footer>
        </x-modal-alert>
    </form>
</div>

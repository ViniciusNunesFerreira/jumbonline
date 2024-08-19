<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Layout') }}
    </x-slot:title>

    <!-- Page content -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <form
            wire:submit.prevent="save"
            class="py-6 xl:flex-auto xl:py-0"
        >
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-slate-200">
                        {{ __('Header') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">
                        {{ __('Customize the header of your store.') }}
                    </p>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div
                            x-data="{ on: @entangle('state.header_top_bar_enabled').defer }"
                            class="col-span-full"
                        >
                            <div class="flex items-center">
                                <button
                                    x-on:click="on = !on"
                                    x-ref="switch"
                                    type="button"
                                    role="switch"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                                    :class="{ 'bg-sky-500': on, 'bg-gray-200 dark:bg-gray-700': !(on) }"
                                    :aria-checked="on.toString()"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="{ 'translate-x-5': on, 'translate-x-0': !(on) }"
                                    ></span>
                                </button>
                                <x-input-label
                                    x-on:click="on = !on; $refs.switch.focus()"
                                    :value="__('Enable top bar')"
                                    class="ml-3"
                                />
                            </div>
                            <x-input-error
                                for="state.header_top_bar_enabled"
                                class="mt-2"
                            />
                        </div>
                        @if($state['header_top_bar_enabled'])
                            <div class="sm:col-span-4">
                                <x-input-label
                                    for="headerTopBarMessageInput"
                                    :value="__('Top bar message')"
                                />
                                <div class="mt-2">
                                    <x-input
                                        wire:model.defer="state.header_top_bar_message"
                                        type="text"
                                        id="headerTopBarMessageInput"
                                        class="block w-full sm:text-sm"
                                    />
                                    <x-input-error
                                        for="state.header_top_bar_message"
                                        class="mt-2"
                                    />
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <x-input-label
                                    for="headerTopBarMenuHandleInput"
                                    :value="__('Top bar menu')"
                                />
                                <div class="mt-2">
                                    <x-select
                                        wire:model.defer="state.header_top_bar_menu_handle"
                                        id="headerTopBarMenuHandleInput"
                                        class="block w-full sm:text-sm"
                                    >
                                        <option value="">
                                            {{ __('Select a menu') }}
                                        </option>
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->slug }}">
                                                {{ $menu->name }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                    <x-input-error
                                        for="state.header_top_bar_menu_handle"
                                        class="mt-2"
                                    />
                                </div>
                            </div>
                        @endif
                        <div class="sm:col-span-3">
                            <x-input-label
                                for="headerMainMenuHandleInput"
                                :value="__('Header Menu')"
                            />
                            <div class="mt-2">
                                <x-select
                                    wire:model.defer="state.header_main_menu_handle"
                                    id="headerMainMenuHandleInput"
                                    class="block w-full sm:text-sm"
                                >
                                    <option value="">
                                        {{ __('Select a menu') }}
                                    </option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->slug }}">
                                            {{ $menu->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                                <x-input-error
                                    for="state.header_main_menu_handle"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-b border-gray-900/10 pb-12 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-slate-200">
                        {{ __('Footer') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-500">
                        {{ __('Customize the footer of your store.') }}
                    </p>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div
                            x-data="{ on: @entangle('state.footer_bottom_bar_enabled').defer }"
                            class="col-span-full"
                        >
                            <div class="flex items-center">
                                <button
                                    x-on:click="on = !on"
                                    x-ref="switch"
                                    type="button"
                                    role="switch"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                                    :class="{ 'bg-sky-500': on, 'bg-gray-200 dark:bg-gray-700': !(on) }"
                                    :aria-checked="on.toString()"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="{ 'translate-x-5': on, 'translate-x-0': !(on) }"
                                    ></span>
                                </button>
                                <x-input-label
                                    x-on:click="on = !on; $refs.switch.focus()"
                                    :value="__('Enable bottom bar')"
                                    class="ml-3"
                                />
                            </div>
                            <x-input-error
                                for="state.footer_bottom_bar_enabled"
                                class="mt-2"
                            />
                        </div>
                        @if($state['header_top_bar_enabled'])
                            <div class="sm:col-span-4">
                                <x-input-label
                                    for="footerBottomBarMessageInput"
                                    :value="__('Bottom bar message')"
                                />
                                <div class="mt-2">
                                    <x-input
                                        wire:model.defer="state.footer_bottom_bar_message"
                                        type="text"
                                        id="footerBottomBarMessageInput"
                                        class="block w-full sm:text-sm"
                                    />
                                    <x-input-error
                                        for="state.footer_bottom_bar_message"
                                        class="mt-2"
                                    />
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <x-input-label
                                    for="footerBottomBarMenuHandleInput"
                                    :value="__('Bottom bar menu')"
                                />
                                <div class="mt-2">
                                    <x-select
                                        wire:model.defer="state.footer_bottom_bar_menu_handle"
                                        id="footerBottomBarMenuHandleInput"
                                        class="block w-full sm:text-sm"
                                    >
                                        <option value="">
                                            {{ __('Select a menu') }}
                                        </option>
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->slug }}">
                                                {{ $menu->name }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                    <x-input-error
                                        for="state.footer_bottom_bar_menu_handle"
                                        class="mt-2"
                                    />
                                </div>
                            </div>
                        @endif
                        <div class="sm:col-span-3">
                            <x-input-label
                                for="footerMainMenuHandleInput"
                                :value="__('Footer Menu')"
                            />
                            <div class="mt-2">
                                <x-select
                                    wire:model.defer="state.footer_main_menu_handle"
                                    id="footerMainMenuHandleInput"
                                    class="block w-full sm:text-sm"
                                >
                                    <option value="">
                                        {{ __('Select a menu') }}
                                    </option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->slug }}">
                                            {{ $menu->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                                <x-input-error
                                    for="state.footer_main_menu_handle"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button
                    type="button"
                    class="btn btn-default"
                >
                    {{ __('Cancel') }}
                </button>
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    {{ __('Save changes') }}
                </button>
            </div>
        </form>
    </div>
</div>

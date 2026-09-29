<div>
    <x-slot:title>
        {{ __('Layout') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 xl:flex xl:gap-x-10 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="xl:flex-auto">
            <div class="sm:flex sm:items-center sm:justify-between mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Layout') }}
                </h1>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Cabeçalho') }}</h3>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ __('Personalize o cabeçalho da sua loja.') }}</p>
                    </x-slot:header>
                    <x-slot:content>
                        <div x-data="{ on: @entangle('state.header_top_bar_enabled').defer }" class="space-y-5">
                            <div>
                                <div class="flex items-center">
                                    <button
                                        x-on:click="on = !on"
                                        x-ref="switch"
                                        type="button"
                                        role="switch"
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                                        :class="{ 'bg-accent-500': on, 'bg-slate-200 dark:bg-slate-700': !(on) }"
                                        :aria-checked="on.toString()"
                                    >
                                        <span
                                            aria-hidden="true"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                            :class="{ 'translate-x-5': on, 'translate-x-0': !(on) }"
                                        ></span>
                                    </button>
                                    <x-input-label x-on:click="on = !on; $refs.switch.focus()" :value="__('Ativar barra superior')" class="ml-3" />
                                </div>
                                <x-input-error for="state.header_top_bar_enabled" class="mt-2" />
                            </div>

                            <div x-show="on" x-cloak class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                                <div class="sm:col-span-2">
                                    <x-input-label for="headerTopBarMessageInput" :value="__('Mensagem da barra superior')" />
                                    <x-input wire:model.defer="state.header_top_bar_message" type="text" id="headerTopBarMessageInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                    <x-input-error for="state.header_top_bar_message" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="headerTopBarMenuHandleInput" :value="__('Menu da barra superior')" />
                                    <x-select wire:model.defer="state.header_top_bar_menu_handle" id="headerTopBarMenuHandleInput" class="mt-1 !h-10 block w-full rounded-xl sm:text-sm">
                                        <option value="">{{ __('Selecione um menu') }}</option>
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->slug }}">{{ $menu->name }}</option>
                                        @endforeach
                                    </x-select>
                                    <x-input-error for="state.header_top_bar_menu_handle" class="mt-2" />
                                </div>
                            </div>

                            <div class="max-w-sm">
                                <x-input-label for="headerMainMenuHandleInput" :value="__('Menu do cabeçalho')" />
                                <x-select wire:model.defer="state.header_main_menu_handle" id="headerMainMenuHandleInput" class="mt-1 !h-10 block w-full rounded-xl sm:text-sm">
                                    <option value="">{{ __('Selecione um menu') }}</option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->slug }}">{{ $menu->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="state.header_main_menu_handle" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Rodapé') }}</h3>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ __('Personalize o rodapé da sua loja.') }}</p>
                    </x-slot:header>
                    <x-slot:content>
                        <div x-data="{ on: @entangle('state.footer_bottom_bar_enabled').defer }" class="space-y-5">
                            <div>
                                <div class="flex items-center">
                                    <button
                                        x-on:click="on = !on"
                                        x-ref="switch"
                                        type="button"
                                        role="switch"
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                                        :class="{ 'bg-accent-500': on, 'bg-slate-200 dark:bg-slate-700': !(on) }"
                                        :aria-checked="on.toString()"
                                    >
                                        <span
                                            aria-hidden="true"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                            :class="{ 'translate-x-5': on, 'translate-x-0': !(on) }"
                                        ></span>
                                    </button>
                                    <x-input-label x-on:click="on = !on; $refs.switch.focus()" :value="__('Ativar barra inferior')" class="ml-3" />
                                </div>
                                <x-input-error for="state.footer_bottom_bar_enabled" class="mt-2" />
                            </div>

                            <div x-show="on" x-cloak class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                                <div class="sm:col-span-2">
                                    <x-input-label for="footerBottomBarMessageInput" :value="__('Mensagem da barra inferior')" />
                                    <x-input wire:model.defer="state.footer_bottom_bar_message" type="text" id="footerBottomBarMessageInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                    <x-input-error for="state.footer_bottom_bar_message" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="footerBottomBarMenuHandleInput" :value="__('Menu da barra inferior')" />
                                    <x-select wire:model.defer="state.footer_bottom_bar_menu_handle" id="footerBottomBarMenuHandleInput" class="mt-1 !h-10 block w-full rounded-xl sm:text-sm">
                                        <option value="">{{ __('Selecione um menu') }}</option>
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->slug }}">{{ $menu->name }}</option>
                                        @endforeach
                                    </x-select>
                                    <x-input-error for="state.footer_bottom_bar_menu_handle" class="mt-2" />
                                </div>
                            </div>

                            <div class="max-w-sm">
                                <x-input-label for="footerMainMenuHandleInput" :value="__('Menu do rodapé')" />
                                <x-select wire:model.defer="state.footer_main_menu_handle" id="footerMainMenuHandleInput" class="mt-1 !h-10 block w-full rounded-xl sm:text-sm">
                                    <option value="">{{ __('Selecione um menu') }}</option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->slug }}">{{ $menu->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="state.footer_main_menu_handle" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Salvar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
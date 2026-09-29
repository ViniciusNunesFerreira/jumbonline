<div>
    <x-slot:title>
        {{ __('Marca') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 xl:flex xl:gap-x-10 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="xl:flex-auto">
            <div class="sm:flex sm:items-center sm:justify-between mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Marca') }}
                </h1>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Logotipos') }}</h3>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ __('Visualize e atualize os logotipos da sua loja.') }}</p>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div x-data>
                                <x-input-label for="logoFileInput" :value="__('Logo')" />
                                <div class="mt-2 flex items-center gap-x-4">
                                    <div class="flex h-16 min-w-[6rem] items-center justify-center rounded-xl border border-slate-100 bg-slate-50 px-3 dark:border-white/5 dark:bg-white/5">
                                        @if($logo_file)
                                            <img src="{{ $logo_file->temporaryUrl() }}" alt="{{ $generalSettings->store_name }}" class="h-10 w-auto" />
                                        @elseif($this->brandSettings->logo_path)
                                            <img src="{{ Storage::url($this->brandSettings->logo_path) }}" alt="{{ $generalSettings->store_name }}" class="h-10 w-auto" />
                                        @else
                                            <x-application-logo class="h-10 w-auto" />
                                        @endif
                                    </div>
                                    <x-input wire:model.defer="logo_file" x-ref="logoFileInput" id="logoFileInput" type="file" class="sr-only" />
                                    <button x-on:click.prevent="$refs.logoFileInput.click()" type="button" class="btn btn-default btn-sm !rounded-xl">
                                        {{ __('Alterar') }}
                                    </button>
                                </div>
                                <x-input-error for="logo_file" class="mt-2" />
                            </div>

                            <div x-data>
                                <x-input-label for="faviconFileInput" :value="__('Favicon')" />
                                <div class="mt-2 flex items-center gap-x-4">
                                    <div class="flex h-16 min-w-[6rem] items-center justify-center rounded-xl border border-slate-100 bg-slate-50 px-3 dark:border-white/5 dark:bg-white/5">
                                        @if($favicon_file)
                                            <img src="{{ $favicon_file->temporaryUrl() }}" alt="{{ $generalSettings->store_name }}" class="h-10 w-auto" />
                                        @elseif($this->brandSettings->favicon_path)
                                            <img src="{{ Storage::url($this->brandSettings->favicon_path) }}" alt="{{ $generalSettings->store_name }}" class="h-10 w-auto" />
                                        @else
                                            <x-application-logo class="h-10 w-auto" />
                                        @endif
                                    </div>
                                    <x-input wire:model.defer="favicon_file" x-ref="faviconFileInput" id="faviconFileInput" type="file" class="sr-only" />
                                    <button x-on:click.prevent="$refs.faviconFileInput.click()" type="button" class="btn btn-default btn-sm !rounded-xl">
                                        {{ __('Alterar') }}
                                    </button>
                                </div>
                                <x-input-error for="favicon_file" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Slogan e descrição') }}</h3>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ __('Frase de efeito usada junto do logo e descrição do negócio usada em perfis e listagens.') }}</p>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="space-y-5">
                            <div>
                                <x-input-label for="sloganInput" :value="__('Slogan')" />
                                <x-input wire:model.defer="state.slogan" type="text" id="sloganInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="state.slogan" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="shortDescriptionInput" :value="__('Descrição curta')" />
                                <x-textarea wire:model.defer="state.short_description" id="shortDescriptionInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="state.short_description" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Redes sociais') }}</h3>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ __('Links das redes do seu negócio, normalmente usados no rodapé do site.') }}</p>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            @foreach($state['social_links'] as $link)
                                <div>
                                    <x-input-label for="{{ $link['name'] }}UrlInput" :value="$link['name']" />
                                    <div class="relative mt-1 rounded-xl shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <x-icon name="simpleicon-{{ strtolower($link['name']) }}" class="h-5 w-5 text-slate-400" />
                                        </div>
                                        <x-input
                                            wire:model.defer="state.social_links.{{ $loop->index }}.url"
                                            type="text"
                                            id="{{ $link['name'] }}UrlInput"
                                            class="block w-full rounded-xl pl-10 !shadow-none sm:text-sm"
                                            placeholder="{{ __('Link') }}"
                                        />
                                    </div>
                                    <p class="mt-1 text-xs text-slate-400">{{ $link['url_placeholder'] }}</p>
                                    <x-input-error for="state.social_links.{{ $loop->index }}.url" class="mt-2" />
                                </div>
                            @endforeach
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
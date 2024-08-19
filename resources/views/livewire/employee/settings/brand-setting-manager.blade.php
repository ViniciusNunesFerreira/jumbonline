<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Brand settings') }}
    </x-slot:title>

    <!-- Page content -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <form
            wire:submit.prevent="save"
            class="py-6 xl:flex-auto xl:py-0"
        >
            <div class="space-y-12">
                <div class="border-b border-slate-900/10 pb-12 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                        {{ __('Logos') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        {{ __('View and update your store logos.') }}
                    </p>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div
                            x-data
                            class="sm:col-span-3"
                        >
                            <label
                                for="logoFileInput"
                                class="block text-sm font-medium leading-6 text-slate-900"
                            >
                                {{ __('Logo') }}
                            </label>
                            <div class="mt-2 flex items-center gap-x-3">
                                @if($logo_file)
                                    <img
                                        src="{{ $logo_file->temporaryUrl() }}"
                                        alt="{{ $generalSettings->store_name }}"
                                        class="h-12 w-auto"
                                    />
                                @elseif($this->brandSettings->logo_path)
                                    <img
                                        src="{{ Storage::url($this->brandSettings->logo_path) }}"
                                        alt="{{ $generalSettings->store_name }}"
                                        class="h-12 w-auto"
                                    />
                                @else
                                    <x-application-logo class="h-12 w-auto" />
                                @endif
                                <x-input
                                    wire:model.defer="logo_file"
                                    x-ref="logoFileInput"
                                    id="logoFileInput"
                                    type="file"
                                    class="sr-only"
                                />
                                <button
                                    x-on:click.prevent="$refs.logoFileInput.click()"
                                    type="button"
                                    class="btn btn-default btn-sm"
                                >
                                    {{ __('Change') }}
                                </button>
                            </div>
                        </div>
                        <div
                            x-data
                            class="sm:col-span-3"
                        >
                            <label
                                for="faviconFileInput"
                                class="block text-sm font-medium leading-6 text-slate-900"
                            >
                                {{ __('Favicon') }}
                            </label>
                            <div class="mt-2 flex items-center gap-x-3">
                                @if($favicon_file)
                                    <img
                                        src="{{ $favicon_file->temporaryUrl() }}"
                                        alt="{{ $generalSettings->store_name }}"
                                        class="h-12 w-auto"
                                    />
                                @elseif($this->brandSettings->favicon_path)
                                    <img
                                        src="{{ Storage::url($this->brandSettings->favicon_path) }}"
                                        alt="{{ $generalSettings->store_name }}"
                                        class="h-12 w-auto"
                                    />
                                @else
                                    <x-application-logo class="h-12 w-auto" />
                                @endif
                                <x-input
                                    wire:model.defer="favicon_file"
                                    x-ref="faviconFileInput"
                                    id="faviconFileInput"
                                    type="file"
                                    class="sr-only"
                                />
                                <button
                                    x-on:click.prevent="$refs.faviconFileInput.click()"
                                    type="button"
                                    class="btn btn-default btn-sm"
                                >
                                    {{ __('Change') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-b border-slate-900/10 pb-12 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                        {{ __('Slogan') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        {{ __('Brand statement or tagline often used along with your logo') }}
                    </p>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-5">
                            <x-input-label
                                for="sloganInput"
                                :value="__('Slogan')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.slogan"
                                    type="text"
                                    id="sloganInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.slogan"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-b border-slate-900/10 pb-12 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                        {{ __('Short description') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        {{ __('Description of your business often used in bios and listings') }}
                    </p>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-5">
                            <x-input-label
                                for="shortDescriptionInput"
                                :value="__('Short description')"
                            />
                            <div class="mt-2">
                                <x-textarea
                                    wire:model.defer="state.short_description"
                                    id="shortDescriptionInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.short_description"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-b border-slate-900/10 pb-12 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                        {{ __('Social Links') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        {{ __('Social links for your business, often used in the theme footer') }}
                    </p>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        @foreach($state['social_links'] as $link)
                            <div class="sm:col-span-4">
                                <x-input-label
                                    for="{{ $link['name'] }}UrlInput"
                                    :value="$link['name']"
                                />
                                <div class="relative mt-2 rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <x-icon
                                            name="simpleicon-{{ strtolower($link['name']) }}"
                                            class="h-5 w-5 text-slate-400"
                                        />
                                    </div>
                                    <x-input
                                        wire:model.defer="state.social_links.{{ $loop->index }}.url"
                                        type="text"
                                        id="{{ $link['name'] }}UrlInput"
                                        class="block w-full pl-10 !shadow-none sm:text-sm"
                                        placeholder="{{ __('Link') }}"
                                    />
                                </div>
                                <div>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $link['url_placeholder'] }}
                                    </p>
                                    <x-input-error
                                        for="state.social_links.{{ $loop->index }}.url"
                                        class="mt-2"
                                    />
                                </div>
                            </div>
                        @endforeach
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
            </div>
        </form>
    </div>
</div>

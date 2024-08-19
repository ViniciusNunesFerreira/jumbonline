<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Carousels') }}
    </x-slot:title>

    <!-- Page content -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="py-6 lg:flex-auto xl:py-0">
            <div class="space-y-12">
                <div class="pb-12">
                    @if($carousels->count())
                        <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                            <div class="ml-4 mt-4">
                                <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                                    {{ __('Carousels') }}
                                </h2>
                                <p class="mt-1 text-sm leading-6 text-slate-500">
                                    {{ __('List of carousels.') }}
                                </p>
                            </div>
                            <div class="ml-4 mt-4 flex-shrink-0">
                                <button
                                    wire:click.prevent="create"
                                    class="btn btn-primary"
                                >
                                    {{ __('Add carousel') }}
                                </button>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="col-span-full">
                                <ul
                                    role="list"
                                    class="divide-y divide-slate-100 dark:divide-slate-800"
                                >
                                    @foreach($carousels as $carousel)
                                        <li class="flex items-center justify-between gap-x-6 py-5">
                                            <div class="min-w-0">
                                                <div class="flex items-start gap-x-3">
                                                    <p class="text-sm font-medium leading-6 text-slate-900 dark:text-slate-200">
                                                        {{ $carousel->name }}
                                                    </p>
                                                </div>
                                                <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-slate-500 dark:text-slate-400">
                                                    <p class="truncate">
                                                        {{ trans_choice(':count slide|:count slides', $carousel->slides_count) }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex flex-none items-center gap-x-4">
                                                <a
                                                    href="{{ route('employee.settings.carousels.detail', $carousel) }}"
                                                    class="btn btn-outline-primary"
                                                >
                                                    {{ __('Manage') }}
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>
                    @else
                        <div class="border border-slate-300 rounded-lg p-6 text-center dark:border-white/10">
                            <svg
                                class="mx-auto h-12 w-12 text-slate-400 fill-current"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512"
                            >
                                <path d="M448 128C483.3 128 512 156.7 512 192V448C512 483.3 483.3 512 448 512H64C28.65 512 0 483.3 0 448V192C0 156.7 28.65 128 64 128H448zM448 160H64C46.33 160 32 174.3 32 192V448C32 465.7 46.33 480 64 480H448C465.7 480 480 465.7 480 448V192C480 174.3 465.7 160 448 160zM448 64C456.8 64 464 71.16 464 80C464 88.84 456.8 96 448 96H64C55.16 96 48 88.84 48 80C48 71.16 55.16 64 64 64H448zM400 0C408.8 0 416 7.164 416 16C416 24.84 408.8 32 400 32H112C103.2 32 96 24.84 96 16C96 7.164 103.2 0 112 0H400z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-200">
                                {{ __('No carousels') }}
                            </h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Use carousels to showcase your products and services.') }}
                            </p>
                            <div class="mt-6">
                                <button
                                    wire:click="create"
                                    type="button"
                                    class="btn btn-primary"
                                >
                                    {{ __('New Carousel') }}
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <form wire:submit.prevent="save">
        <x-slide-over wire:model="showCarouselForm">
            <x-slot:title>
                {{ __('Add carousel') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label
                            for="carouselNameInput"
                            :value="__('Name')"
                        />
                        <x-input
                            wire:model.lazy="carousel.name"
                            type="text"
                            id="carouselNameInput"
                            class="block w-full mt-1 sm:text-sm"
                            placeholder="{{ __('My carousel') }}"
                            required
                            autofocus
                        />
                        <x-input-error
                            for="carousel.name"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="carouselDescriptionInput"
                            :value="__('Handle')"
                        />
                        <x-input
                            wire:model.defer="carousel.slug"
                            type="text"
                            id="carouselDescriptionInput"
                            class="block w-full mt-1 sm:text-sm"
                            placeholder="{{ __('my-carousel') }}"
                            required
                        />
                        <x-input-description>
                            {{ __('The handle is used to identify the carousel in the page.') }}
                        </x-input-description>
                        <x-input-error
                            for="carousel.slug"
                            class="mt-2"
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
</div>

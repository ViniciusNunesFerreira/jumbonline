<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ $carousel->name }} - {{ __('Carousels') }}
    </x-slot:title>

    <!-- Page content -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8">
        @include('layouts.employee-settings-navigation')
        <div class="py-6 xl:flex-auto xl:py-0">
            <div class="space-y-12">
                <div class="pb-12">
                    <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                        <div class="ml-4 mt-4">
                            <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                                {{ $carousel->name }}
                            </h2>
                            <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">
                                {{ trans_choice(':count slide|:count slides', $this->slides->count()) }}
                            </p>
                        </div>
                        <div class="ml-4 mt-4 flex-shrink-0">
                            <button
                                wire:click.prevent="editCarousel"
                                class="btn btn-primary"
                            >
                                {{ __('Edit carousel') }}
                            </button>
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        @foreach($slides as $slideItem)
                            <div class="col-span-full">
                                <div class="relative isolate flex flex-col gap-8 sm:flex-row">
                                    <div class="relative aspect-[16/9] bg-slate-100 rounded-lg sm:w-64 lg:shrink-0 dark:bg-white/10">
                                        @if($slideItem->hasMedia('image'))
                                            <img
                                                src="{{ $slideItem->getFirstMediaUrl('image') }}"
                                                alt="{{ $slideItem->title }}"
                                                class="absolute inset-0 h-full mx-auto rounded-lg object-cover"
                                            >
                                        @else
                                            <x-heroicon-o-camera class="absolute inset-0 h-full mx-auto w-10 text-slate-400 dark:text-slate-500" />
                                        @endif
                                    </div>
                                    <div>
                                        <div class="group max-w-xl">
                                            <h3 class="mt-3 text-lg font-semibold leading-6 text-slate-900 group-hover:text-slate-600 dark:text-slate-200 dark:hover:text-white">
                                                <button wire:click="editSlide({{ $slideItem->id }})">
                                                    <span class="absolute inset-0"></span>
                                                    {{ $slideItem->title }}
                                                </button>
                                            </h3>
                                            <p class="mt-5 text-sm leading-6 text-slate-600 dark:text-slate-400">
                                                {{ $slideItem->description }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-span-full">
                            <button
                                wire:click.prevent="createSlide"
                                type="button"
                                class="relative block w-full rounded-lg border-2 border-dashed border-slate-300 p-12 text-center hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:border-slate-600 dark:hover:border-slate-500 dark:focus:ring-offset-slate-900"
                            >
                                <svg
                                    class="mx-auto h-12 w-12 text-slate-400 fill-current"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 640 512"
                                >
                                    <path d="M448 160H64C46.33 160 32 174.3 32 192V448C32 465.7 46.33 480 64 480H360.2C370.3 492.1 381.9 502.9 394.8 512H64C28.65 512 0 483.3 0 448V192C0 156.7 28.65 128 64 128H448C483.3 128 512 156.7 512 192V192.7C506.7 192.2 501.4 192 496 192C490.6 192 485.3 192.2 480 192.7V192C480 174.3 465.7 160 448 160zM448 64C456.8 64 464 71.16 464 80C464 88.84 456.8 96 448 96H64C55.16 96 48 88.84 48 80C48 71.16 55.16 64 64 64H448zM400 0C408.8 0 416 7.164 416 16C416 24.84 408.8 32 400 32H112C103.2 32 96 24.84 96 16C96 7.164 103.2 0 112 0H400zM512 351.1H560C568.8 351.1 576 359.2 576 367.1C576 376.8 568.8 383.1 560 383.1H512V431.1C512 440.8 504.8 447.1 496 447.1C487.2 447.1 480 440.8 480 431.1V383.1H432C423.2 383.1 416 376.8 416 367.1C416 359.2 423.2 351.1 432 351.1H480V303.1C480 295.2 487.2 287.1 496 287.1C504.8 287.1 512 295.2 512 303.1V351.1zM640 368C640 447.5 575.5 512 496 512C416.5 512 352 447.5 352 368C352 288.5 416.5 224 496 224C575.5 224 640 288.5 640 368zM496 256C434.1 256 384 306.1 384 368C384 429.9 434.1 480 496 480C557.9 480 608 429.9 608 368C608 306.1 557.9 256 496 256z" />
                                </svg>
                                <span class="mt-2 block text-sm font-semibold text-slate-900 dark:text-slate-200">
                                    {{ $this->slides->count() ? __('Add new slide') : __('Add your first slide') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form wire:submit.prevent="saveSlide">
        <x-slide-over wire:model="showSlideForm">
            <x-slot:title>
                {{ __('Add slide') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    @if($slideImage)
                        <div class="w-full bg-white dark:bg-slate-900">
                            <div class="relative mx-auto">
                                <div class="group aspect-[16/9] block w-full overflow-hidden rounded-lg bg-slate-50 dark:bg-white/5">
                                    <img
                                        src="{{ $slideImage->temporaryUrl() }}"
                                        alt=""
                                        class="h-full mx-auto pointer-events-none object-cover group-hover:opacity-75"
                                    >
                                    <label
                                        for="slideImageInput"
                                        class="absolute inset-0 cursor-pointer focus:outline-none"
                                    >
                                        <span class="sr-only">{{ __('Change image') }}</span>
                                    </label>
                                    <input
                                        wire:model.defer="slideImage"
                                        type="file"
                                        id="slideImageInput"
                                        class="hidden"
                                    >
                                </div>
                            </div>
                        </div>
                    @elseif($slide->hasMedia('image'))
                        <div class="w-full bg-white dark:bg-slate-900">
                            <div class="relative mx-auto">
                                <div class="group aspect-[16/9] block w-full overflow-hidden rounded-lg bg-slate-50 dark:bg-white/5">
                                    <img
                                        src="{{ $slide->getFirstMediaUrl('image') }}"
                                        alt=""
                                        class="h-full mx-auto pointer-events-none object-cover object-center group-hover:opacity-75"
                                    >
                                    <label
                                        for="slideImageInput"
                                        class="absolute inset-0 cursor-pointer focus:outline-none"
                                    >
                                        <span class="sr-only">{{ __('Change image') }}</span>
                                    </label>
                                    <input
                                        wire:model="slideImage"
                                        type="file"
                                        id="slideImageInput"
                                        class="hidden"
                                    >
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="aspect-[16/9] w-full flex flex-col justify-center rounded-lg border border-dashed border-slate-900/25 dark:border-white/25">
                            <div class="text-center">
                                <svg
                                    class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                <div class="mt-4 flex justify-center text-sm leading-6 text-slate-600">
                                    <label
                                        for="file-upload"
                                        class="btn btn-link cursor-pointer"
                                    >
                                        <span>{{ __('Upload a file') }}</span>
                                        <input
                                            wire:model.defer="slideImage"
                                            id="file-upload"
                                            name="file-upload"
                                            type="file"
                                            class="sr-only"
                                        >
                                    </label>
                                </div>
                                <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">{{ __('PNG, JPG, GIF up to 10MB') }}</p>
                            </div>
                        </div>
                    @endif
                    <div>
                        <x-input-label
                            for="slideTitleInput"
                            value="{{  __('Title') }}"
                        />
                        <x-input
                            wire:model.defer="slide.title"
                            type="text"
                            id="slideTitleInput"
                            class="mt-2 block w-full sm:text-sm"
                            autofocus
                        />
                        <x-input-error
                            for="slide.title"
                            class="mt-1"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="slideDescriptionInput"
                            value="{{  __('Description') }}"
                        />
                        <x-textarea
                            wire:model.defer="slide.description"
                            id="slideDescriptionInput"
                            class="mt-2 block w-full sm:text-sm"
                        />
                        <x-input-error
                            for="slide.description"
                            class="mt-1"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="slideButtonLinkInput"
                            value="{{  __('Button link') }}"
                        />
                        <x-input
                            wire:model.defer="slide.button_link"
                            type="text"
                            id="slideButtonLinkInput"
                            class="mt-2 block w-full sm:text-sm"
                        />
                        <x-input-error
                            for="slide.button_link"
                            class="mt-1"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="slideButtonTextInput"
                            value="{{  __('Button text') }}"
                        />
                        <x-input
                            wire:model.defer="slide.button_text"
                            type="text"
                            id="slideButtonTextInput"
                            class="mt-2 block w-full sm:text-sm"
                        />
                        <x-input-error
                            for="slide.button_text"
                            class="mt-1"
                        />
                    </div>
                    @if($slide->exists)
                        <div
                            x-data="{ confirmSlideDeletion: false }"
                            class="space-y-3"
                        >
                            <button
                                x-show="!confirmSlideDeletion"
                                x-on:click="confirmSlideDeletion = true"
                                type="button"
                                class="btn btn-outline-danger block w-full"
                            >
                                {{ __('Remove slide') }}
                            </button>
                            <button
                                x-show="confirmSlideDeletion"
                                x-on:click="$wire.deleteSlide"
                                type="button"
                                class="btn btn-danger block w-full"
                            >
                                {{ __('Confirm') }}
                            </button>
                            <button
                                x-show="confirmSlideDeletion"
                                x-on:click="confirmSlideDeletion = false"
                                type="button"
                                class="btn btn-link block w-full"
                            >
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    @endif
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

    <form wire:submit.prevent="saveCarousel">
        <x-slide-over wire:model="showCarouselForm">
            <x-slot:title>
                {{ __('Edit carousel') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label
                            for="carouselNameInput"
                            value="{{  __('Name') }}"
                        />
                        <x-input
                            wire:model.defer="carousel.name"
                            type="text"
                            id="carouselNameInput"
                            class="mt-2 block w-full sm:text-sm"
                            autofocus
                        />
                        <x-input-error
                            for="carousel.name"
                            class="mt-1"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="carouselHandleInput"
                            value="{{  __('Handle') }}"
                        />
                        <x-input
                            wire:model.defer="carousel.slug"
                            type="text"
                            id="carouselHandleInput"
                            class="mt-2 block w-full sm:text-sm"
                        />
                        <x-input-error
                            for="carousel.slug"
                            class="mt-1"
                        />
                    </div>
                    <div
                        x-data="{ confirmCarouselDeletion: false }"
                        class="space-y-3"
                    >
                        <button
                            x-show="!confirmCarouselDeletion"
                            x-on:click="confirmCarouselDeletion = true"
                            type="button"
                            class="btn btn-outline-danger block w-full"
                        >
                            {{ __('Remove carousel') }}
                        </button>
                        <button
                            x-show="confirmCarouselDeletion"
                            x-on:click="$wire.deleteCarousel"
                            type="button"
                            class="btn btn-danger block w-full"
                        >
                            {{ __('Confirm') }}
                        </button>
                        <button
                            x-show="confirmCarouselDeletion"
                            x-on:click="confirmCarouselDeletion = false"
                            type="button"
                            class="btn btn-link block w-full"
                        >
                            {{ __('Cancel') }}
                        </button>
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

<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Template') }}
    </x-slot:title>

    <!-- Page content -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <form
            wire:submit.prevent="save"
            class="py-6 xl:flex-auto xl:py-0"
        >
            <div class="space-y-12">
                <div class="border-b border-slate-900/10 dark:border-white/10">
                    <h2 class="text-base font-semibold leading-7 text-slate-900 dark:text-slate-200">
                        {{ __('Home page') }}
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        {{ __('View and update your store details.') }}
                    </p>
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                            <x-input-label
                                for="homePageTitleInput"
                                :value="__('Title')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.home_page_title"
                                    type="text"
                                    id="homePageTitleInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.home_page_title"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                        <div class="col-span-full">
                            <x-input-label
                                for="homePageDescriptionInput"
                                :value="__('Description')"
                            />
                            <div class="mt-2">
                                <x-textarea
                                    wire:model.defer="state.home_page_description"
                                    id="homePageDescriptionInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.home_page_description"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label
                                for="homePageHeroCarouselInput"
                                :value="__('Hero carousel')"
                            />
                            <div class="mt-2">
                                <x-select
                                    wire:model.defer="state.home_page_hero_carousel_handle"
                                    id="homePageHeroCarouselInput"
                                    class="block w-full sm:text-sm"
                                >
                                    <option value="">{{ __('Select a carousel') }}</option>
                                    @foreach ($this->carousels as $carousel)
                                        <option value="{{ $carousel->slug }}">
                                            {{ $carousel->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                                <x-input-error
                                    for="state.home_page_hero_carousel_handle"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <x-input-label
                                for="homePagePerkCarouselInput"
                                :value="__('Perk carousel')"
                            />
                            <div class="mt-2">
                                <x-select
                                    wire:model.defer="state.home_page_perk_carousel_handle"
                                    id="homePagePerkCarouselInput"
                                    class="block w-full sm:text-sm"
                                >
                                    <option value="">{{ __('Select a carousel') }}</option>
                                    @foreach ($this->carousels as $carousel)
                                        <option value="{{ $carousel->slug }}">
                                            {{ $carousel->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                                <x-input-error
                                    for="state.home_page_perk_carousel_handle"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                        <div class="col-span-full">
                            <div class="flex items-center justify-between">
                                <x-input-label :value="__('Sections')" />

                                <x-dropdown>
                                    <x-slot:trigger>
                                        <button
                                            type="button"
                                            class="btn btn-link"
                                        >
                                            {{ __('Add section') }}
                                        </button>
                                    </x-slot:trigger>
                                    <x-slot:content>
                                        <x-dropdown-link
                                            wire:click="addSection('collection')"
                                            role="button"
                                        >
                                            {{ __('Collection') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link
                                            wire:click="addSection('product')"
                                            role="button"
                                        >
                                            {{ __('Product') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link
                                            wire:click="addSection('blog')"
                                            role="button"
                                        >
                                            {{ __('Blog') }}
                                        </x-dropdown-link>
                                    </x-slot:content>
                                </x-dropdown>
                            </div>

                            <ul
                                role="list"
                                class="mt-2 divide-y divide-slate-100 dark:border-white/10 dark:divide-white/5"
                            >
                                @foreach(collect($state['home_page_sections'])->sortBy('order') as $section)
                                    <li class="flex items-center justify-between gap-x-6 py-5">
                                        <div class="flex gap-x-4">
                                            <div class="flex items-center justify-center h-12 w-12 bg-slate-100 rounded-full dark:bg-slate-800">
                                                @if($section['type'] === 'collection')
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 512 512"
                                                        class="h-10 w-10 p-2 text-slate-500 fill-current dark:text-slate-400"
                                                    >
                                                        <path d="M80 136C80 122.7 90.75 112 104 112C117.3 112 128 122.7 128 136C128 149.3 117.3 160 104 160C90.75 160 80 149.3 80 136zM204.1 32C216.8 32 229.1 37.06 238.1 46.06L410.7 218.7C435.7 243.7 435.7 284.3 410.7 309.3L277.3 442.7C252.3 467.7 211.7 467.7 186.7 442.7L14.06 270.1C5.057 261.1 0 248.8 0 236.1V80C0 53.49 21.49 32 48 32H204.1zM36.69 247.4L209.4 420.1C221.9 432.6 242.1 432.6 254.6 420.1L388.1 286.6C400.6 274.1 400.6 253.9 388.1 241.4L215.4 68.69C212.4 65.69 208.4 64 204.1 64H48C39.16 64 32 71.16 32 80V236.1C32 240.4 33.69 244.4 36.69 247.4V247.4zM308.4 36.95C314.5 30.56 324.7 30.33 331.1 36.43L472.4 171.5C525.1 221.9 525.1 306.1 472.4 356.5L347.8 475.6C341.4 481.7 331.3 481.4 325.2 475.1C319.1 468.7 319.3 458.5 325.7 452.4L450.3 333.4C489.8 295.6 489.8 232.4 450.3 194.6L308.9 59.57C302.6 53.46 302.3 43.34 308.4 36.95V36.95z" />
                                                    </svg>
                                                @elseif($section['type'] === 'product')
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 448 512"
                                                        class="h-10 w-10 p-2 text-slate-500 fill-current dark:text-slate-400"
                                                    >
                                                        <path d="M88 144C88 130.7 98.75 120 112 120C125.3 120 136 130.7 136 144C136 157.3 125.3 168 112 168C98.75 168 88 157.3 88 144zM.0003 80C.0003 53.49 21.49 32 48 32H197.5C214.5 32 230.7 38.74 242.7 50.75L418.7 226.7C443.7 251.7 443.7 292.3 418.7 317.3L285.3 450.7C260.3 475.7 219.7 475.7 194.7 450.7L18.75 274.7C6.743 262.7 0 246.5 0 229.5L.0003 80zM41.37 252.1L217.4 428.1C229.9 440.6 250.1 440.6 262.6 428.1L396.1 294.6C408.6 282.1 408.6 261.9 396.1 249.4L220.1 73.37C214.1 67.37 205.1 64 197.5 64H48C39.16 64 32 71.16 32 80V229.5C32 237.1 35.37 246.1 41.37 252.1L41.37 252.1zM41.37 252.1L18.75 274.7z" />
                                                    </svg>
                                                @elseif($section['type'] === 'blog')
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 512 512"
                                                        class="h-10 w-10 p-2 text-slate-500 fill-current dark:text-slate-400"
                                                    >
                                                        <path d="M464 32h-320C117.5 32 96 53.53 96 80V416c0 17.66-14.36 32-32 32s-32-14.34-32-32V112C32 103.2 24.84 96 16 96S0 103.2 0 112V416c0 35.28 28.7 64 64 64h368c44.11 0 80-35.88 80-80v-320C512 53.53 490.5 32 464 32zM480 400c0 26.47-21.53 48-48 48H119.4C124.9 438.6 128 427.7 128 416V80C128 71.19 135.2 64 144 64h320C472.8 64 480 71.19 480 80V400zM272 304h-96C167.2 304 160 311.2 160 320s7.156 16 16 16h96c8.844 0 16-7.156 16-16S280.8 304 272 304zM432 304h-96C327.2 304 320 311.2 320 320s7.156 16 16 16h96c8.844 0 16-7.156 16-16S440.8 304 432 304zM272 368h-96C167.2 368 160 375.2 160 384s7.156 16 16 16h96c8.844 0 16-7.156 16-16S280.8 368 272 368zM432 368h-96c-8.844 0-16 7.156-16 16s7.156 16 16 16h96c8.844 0 16-7.156 16-16S440.8 368 432 368zM416 96H192C174.3 96 160 110.3 160 128v96c0 17.67 14.33 32 32 32h224c17.67 0 32-14.33 32-32V128C448 110.3 433.7 96 416 96zM416 224H192V128h224V224z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex items-start gap-x-3">
                                                    <p class="text-sm font-medium leading-6 text-slate-900 dark:text-slate-200">
                                                        {{ $section['title'] }}
                                                    </p>
                                                </div>
                                                <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-slate-500 dark:text-slate-400">
                                                    <p class="truncate">
                                                        {{ $section['type'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-none items-center gap-x-4">
                                            <x-dropdown>
                                                <x-slot:trigger>
                                                    <button
                                                        type="button"
                                                        class="-m-2.5 block p-2.5 text-slate-500 hover:text-slate-900 dark:hover:text-slate-400"
                                                    >
                                                        <span class="sr-only">{{ __('Open options') }}</span>
                                                        <x-heroicon-m-ellipsis-vertical class="w-5 h-5" />
                                                    </button>
                                                </x-slot:trigger>
                                                <x-slot:content>
                                                    <x-dropdown-link
                                                        wire:click="editSection({{ $loop->index }})"
                                                        role="button"
                                                    >
                                                        {{ __('Edit details') }}
                                                    </x-dropdown-link>
                                                    @if($section['type'] === 'collection')
                                                        <x-dropdown-link
                                                            wire:click.prevent="browseCollections({{ $loop->index }})"
                                                            role="button"
                                                        >
                                                            {{ __('Browse collection') }}
                                                        </x-dropdown-link>
                                                    @elseif($section['type'] === 'product')
                                                        <x-dropdown-link
                                                            wire:click.prevent="browseProducts({{ $loop->index }})"
                                                            role="button"
                                                        >
                                                            {{ __('Browse product') }}
                                                        </x-dropdown-link>
                                                    @endif
                                                    <hr class="border-slate-200 dark:border-white/20">
                                                    <x-dropdown-link
                                                        wire:click="removeSection({{ $loop->index }})"
                                                        role="button"
                                                        class="!text-red-500"
                                                    >
                                                        {{ __('Remove section') }}
                                                    </x-dropdown-link>
                                                </x-slot:content>
                                            </x-dropdown>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
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

    <form wire:submit.prevent="saveSection">
        <x-modal-dialog wire:model="addingSection">
            <x-slot:title>
                {{ __('Add new section') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label
                            for="newSectionTitleInput"
                            :value="__('Title')"
                        />
                        <div class="mt-2">
                            <x-input
                                wire:model.defer="newSectionData.title"
                                type="text"
                                id="newSectionTitleInput"
                                class="block w-full sm:text-sm"
                                placeholder="{{ __('New section title') }}"
                            />
                            <x-input-error
                                for="newSectionData.title"
                                class="mt-2"
                            />
                        </div>
                    </div>
                    <div>
                        <x-input-label
                            for="newSectionDataLinkInput"
                            :value="__('Link (optional)')"
                        />
                        <div class="mt-2">
                            <x-input
                                wire:model.defer="newSectionData.link"
                                type="text"
                                id="newSectionDataLinkInput"
                                class="block w-full sm:text-sm"
                                placeholder="{{ __('https://example.com') }}"
                            />
                            <x-input-error
                                for="newSectionData.link"
                                class="mt-2"
                            />
                        </div>
                    </div>
                    <div>
                        <x-input-label
                            for="newSectionDataLinkTextInput"
                            :value="__('Link text')"
                        />
                        <div class="mt-2">
                            <x-input
                                wire:model.defer="newSectionData.link_text"
                                type="text"
                                id="newSectionDataLinkTextInput"
                                class="block w-full sm:text-sm"
                                placeholder="{{ __('View all') }}"
                            />
                            <x-input-error
                                for="newSectionData.link_text"
                                class="mt-2"
                            />
                        </div>
                    </div>
                    <div>
                        <x-input-label
                            for="newSectionDataCarouselHandleInput"
                            :value="__('Carousel')"
                        />
                        <div class="mt-2">
                            <x-select
                                wire:model.defer="newSectionData.carousel_handle"
                                id="newSectionDataCarouselHandleInput"
                                class="block w-full sm:text-sm"
                            >
                                <option value="">{{ __('Select a carousel') }}</option>
                                @foreach ($this->carousels as $carousel)
                                    <option value="{{ $carousel->slug }}">
                                        {{ $carousel->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error
                                for="newSectionData.carousel_handle"
                                class="mt-2"
                            />
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label
                                for="slideImageInput"
                                :value="__('Banner')"
                            />
                        </div>
                        @if($bannerFile)
                            <div class="mt-1 w-full bg-white dark:bg-slate-900">
                                <div class="relative mx-auto">
                                    <div class="group aspect-[16/9] block w-full overflow-hidden rounded-lg bg-slate-50 dark:bg-white/5">
                                        <img
                                            src="{{ $bannerFile->temporaryUrl() }}"
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
                                            wire:model="bannerFile"
                                            type="file"
                                            id="slideImageInput"
                                            class="hidden"
                                        >
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-1 aspect-[16/9] w-full flex flex-col justify-center rounded-lg border border-dashed border-slate-900/25 dark:border-white/25">
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
                                                wire:model="bannerFile"
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
                        <x-input-error
                            for="bannerFile"
                            class="mt-2"
                        />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button
                    wire:target="saveBlock"
                    wire:loading.attr="disabled"
                    class="btn btn-primary w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Save') }}
                </button>
                <button
                    x-on:click="show = false"
                    type="button"
                    class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancel') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>

    <form wire:submit.prevent="updateSection">
        <x-modal-dialog wire:model="editingSection">
            <x-slot:title>
                {{ __('Edit section') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label
                            for="blockTitleInput"
                            :value="__('Title')"
                        />
                        <div class="mt-2">
                            <x-input
                                wire:model.defer="state.home_page_sections.{{ $editingSectionIndex }}.title"
                                type="text"
                                id="blockTitleInput"
                                class="block w-full sm:text-sm"
                            />
                            <x-input-error
                                for="state.home_page_sections.{{ $editingSectionIndex }}.title"
                                class="mt-2"
                            />
                        </div>
                    </div>
                    <div>
                        <x-input-label
                            for="blockLinkInput"
                            :value="__('Link')"
                        />
                        <div class="mt-2">
                            <x-input
                                wire:model.defer="state.home_page_sections.{{ $editingSectionIndex }}.link"
                                type="text"
                                id="blockLinkInput"
                                class="block w-full sm:text-sm"
                            />
                            <x-input-error
                                for="state.home_page_sections.{{ $editingSectionIndex }}.link"
                                class="mt-2"
                            />
                        </div>
                    </div>
                    <div>
                        <x-input-label
                            for="blockLinkTextInput"
                            :value="__('Link text')"
                        />
                        <div class="mt-2">
                            <x-input
                                wire:model.defer="state.home_page_sections.{{ $editingSectionIndex }}.link_text"
                                type="text"
                                id="blockLinkTextInput"
                                class="block w-full sm:text-sm"
                            />
                            <x-input-error
                                for="state.home_page_sections.{{ $editingSectionIndex }}.link_text"
                                class="mt-2"
                            />
                        </div>
                    </div>
                    <div>
                        <x-input-label
                            for="blockCarouselHandleInput"
                            :value="__('Carousel')"
                        />
                        <div class="mt-2">
                            <x-select
                                wire:model.defer="state.home_page_sections.{{ $editingSectionIndex }}.carousel_handle"
                                id="blockCarouselHandleInput"
                                class="block w-full sm:text-sm"
                            >
                                <option value="">{{ __('Select a carousel') }}</option>
                                @foreach ($this->carousels as $carousel)
                                    <option value="{{ $carousel->slug }}">
                                        {{ $carousel->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error
                                for="state.home_page_sections.{{ $editingSectionIndex }}.carousel_handle"
                                class="mt-2"
                            />
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label
                                for="slideImageInput"
                                :value="__('Banner')"
                            />
                            @if($bannerFile || count($state['home_page_sections']) && $state['home_page_sections'][$editingSectionIndex]['banner_path'])
                                <button
                                    wire:click.prevent="removeSectionBanner"
                                    type="button"
                                    class="btn btn-link"
                                >
                                    {{ __('Remove') }}
                                </button>
                            @endif
                        </div>
                        @if($bannerFile)
                            <div class="mt-1 w-full bg-white dark:bg-slate-900">
                                <div class="relative mx-auto">
                                    <div class="group aspect-[16/9] block w-full overflow-hidden rounded-lg bg-slate-50 dark:bg-white/5">
                                        <img
                                            src="{{ $bannerFile->temporaryUrl() }}"
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
                                            wire:model="bannerFile"
                                            type="file"
                                            id="slideImageInput"
                                            class="hidden"
                                        >
                                    </div>
                                </div>
                            </div>
                        @elseif(count($state['home_page_sections']) && $state['home_page_sections'][$editingSectionIndex]['banner_path'])
                            <div class="mt-1 w-full bg-white dark:bg-slate-900">
                                <div class="relative mx-auto">
                                    <div class="group aspect-[16/9] block w-full overflow-hidden rounded-lg bg-slate-50 dark:bg-white/5">
                                        <img
                                            src="{{ Storage::url($state['home_page_sections'][$editingSectionIndex]['banner_path']) }}"
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
                                            wire:model="bannerFile"
                                            type="file"
                                            id="slideImageInput"
                                            class="hidden"
                                        >
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-1 aspect-[16/9] w-full flex flex-col justify-center rounded-lg border border-dashed border-slate-900/25 dark:border-white/25">
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
                                                wire:model="bannerFile"
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
                        <x-input-error
                            for="bannerFile"
                            class="mt-2"
                        />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button
                    wire:target="saveBlock"
                    wire:loading.attr="disabled"
                    class="btn btn-primary w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Save') }}
                </button>
                <button
                    x-on:click="show = false"
                    type="button"
                    class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancel') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>

    <form
        x-data="{ selectedCollections: @entangle('selectedCollections').defer }"
        wire:submit.prevent="addCollections"
    >
        <x-modal-dialog wire:model="showCollectionModal">
            <x-slot:title>
                {{ __('Add collections') }}
            </x-slot:title>
            <x-slot:content>
                <div
                    x-init="$watch('show', value => value && $wire.loadCollections())"
                    class="-mx-4 sm:-mx-6"
                >
                    <div class="p-4 sm:px-6">
                        <x-input-label
                            for="search"
                            :value="__('Search')"
                            class="sr-only"
                        />
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <x-heroicon-m-magnifying-glass class="h-5 w-5 text-slate-400" />
                            </div>
                            <x-input
                                wire:model.debounce.500ms="filterCollectionTitle"
                                type="search"
                                id="search"
                                class="block pl-10 w-full sm:text-sm"
                                placeholder="{{ __('Search collections') }}"
                                autofocus
                            />
                        </div>
                    </div>
                    <div
                        wire:target="loadCollections, filterCollectionTitle"
                        wire:loading
                        class="p-4 bg-white w-full border-y border-slate-300 sm:px-6 dark:bg-slate-800 dark:border-slate-700"
                    >
                        <div class="flex space-x-4 animate-pulse">
                            <div class="rounded-full bg-slate-200 dark:bg-slate-700 h-10 w-10"></div>
                            <div class="flex-1 space-y-6 py-1">
                                <div class="space-y-3">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded col-span-2"></div>
                                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded col-span-1"></div>
                                    </div>
                                    <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        wire:target="loadCollections, filterCollectionTitle"
                        wire:loading.remove
                        class="border-y border-slate-200 divide-y divide-slate-200 dark:divide-slate-200/10 dark:border-slate-200/10"
                    >
                        <ul class="overflow-y-auto divide-y divide-slate-200 max-h-[30rem] sm:max-h-[40rem] dark:divide-slate-700/50">
                            @forelse($collections as $collection)
                                <li>
                                    <x-input-label class="p-4 cursor-pointer sm:px-6 hover:bg-slate-50 dark:hover:bg-slate-700/25">
                                        <div class="flex items-center">
                                            <x-input
                                                x-model.number="selectedCollections"
                                                type="checkbox"
                                                value="{{ $collection->id }}"
                                                class="mr-4 !rounded"
                                            />
                                            <div class="flex items-center">
                                                <div class="mr-4 flex-shrink-0 self-center">
                                                    <img
                                                        class="h-10 w-10 rounded object-center object-cover"
                                                        src="{{ $collection->getFirstMediaUrl('cover', 'thumb') }}"
                                                        alt="{{ $collection->title }}"
                                                    >
                                                </div>
                                                <p>
                                                    {{ $collection->title }}
                                                </p>
                                            </div>
                                        </div>
                                    </x-input-label>
                                </li>
                            @empty
                                @unless($filterCollectionTitle)
                                    <li>
                                        <p class="p-4 text-sm sm:px-6">
                                            {{ __('No collections found.') }}
                                        </p>
                                    </li>
                                @else
                                    <li>
                                        <p class="p-4 text-sm sm:px-6">
                                            {{ __('No results found for ":keyword"', ['keyword' => $filterCollectionTitle]) }}
                                        </p>
                                    </li>
                                @endunless
                            @endforelse
                        </ul>
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button
                    x-bind:disabled="selectedCollections.length === 0"
                    type="submit"
                    class="btn btn-primary w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Add') }}
                </button>
                <button
                    x-on:click.prevent="show = false"
                    type="button"
                    class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancel') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>

    <form
        x-data="{ selectedProducts: @entangle('selectedProducts').defer }"
        wire:submit.prevent="addProducts"
    >
        <x-modal-dialog
            wire:model="showProductModal"
            max-width="2xl"
        >
            <x-slot:title>
                {{ __('Add products') }}
            </x-slot:title>
            <x-slot:content>
                <div
                    x-init="$watch('show', value => value && $wire.loadProducts())"
                    class="-mx-4 sm:-mx-6"
                >
                    <div class="p-4 border-y border-slate-200 sm:px-6 dark:border-slate-200/10">
                        <x-input-label
                            for="search"
                            :value="__('Search')"
                            class="sr-only"
                        />
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <x-heroicon-m-magnifying-glass class="h-5 w-5 text-slate-400" />
                            </div>
                            <x-input
                                wire:model.debounce.500ms="filterProductName"
                                type="search"
                                id="search"
                                class="block pl-10 w-full sm:text-sm"
                                autofocus
                            />
                        </div>
                    </div>
                    <div
                        wire:target="loadProducts, filterProductName"
                        wire:loading
                        class="p-4 bg-white w-full border-y border-slate-300 sm:px-6 dark:bg-slate-800 dark:border-slate-700"
                    >
                        <div class="flex space-x-4 animate-pulse">
                            <div class="rounded-full bg-slate-200 dark:bg-slate-700 h-10 w-10"></div>
                            <div class="flex-1 space-y-6 py-1">
                                <div class="space-y-3">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded col-span-2"></div>
                                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded col-span-1"></div>
                                    </div>
                                    <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        wire:target="loadProducts, filterProductName"
                        wire:loading.remove
                        class="border-b border-slate-200 divide-y divide-slate-200 dark:divide-slate-200/10 dark:border-slate-200/10"
                    >
                        <ul class="overflow-y-auto divide-y divide-slate-200 max-h-[30rem] sm:max-h-[40rem] dark:divide-slate-700/50">
                            @forelse($products as $product)
                                <li>
                                    <x-input-label class="p-4 cursor-pointer sm:px-6 hover:bg-slate-50 dark:hover:bg-slate-700/25">
                                        <div class="flex items-center">
                                            <x-input
                                                x-model.number="selectedProducts"
                                                type="checkbox"
                                                value="{{ $product->id }}"
                                                class="mr-4 !rounded"
                                            />
                                            <div class="flex items-center">
                                                <div class="mr-4 flex-shrink-0 self-center">
                                                    <img
                                                        class="h-10 w-10 rounded object-center object-cover"
                                                        src="{{ $product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                        alt="{{ $product->name }}"
                                                    >
                                                </div>
                                                <p>
                                                    {{ $product->name }}
                                                </p>
                                            </div>
                                        </div>
                                    </x-input-label>
                                </li>
                            @empty
                                @unless($filterProductName)
                                    <li>
                                        <p class="p-4 text-sm sm:px-6">
                                            {{ __('No products found.') }}
                                        </p>
                                    </li>
                                @else
                                    <li>
                                        <p class="p-4 text-sm sm:px-6">
                                            {{ __('No results found for ":keyword"', ['keyword' => $filterProductName]) }}
                                        </p>
                                    </li>
                                @endunless
                            @endforelse
                        </ul>
                    </div>

                </div>
            </x-slot:content>
            <x-slot:footer>
                <button
                    x-bind:disabled="selectedProducts.length === 0"
                    type="submit"
                    class="btn btn-primary w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Add') }}
                </button>
                <button
                    x-on:click.prevent="show = false"
                    type="button"
                    class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancel') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>

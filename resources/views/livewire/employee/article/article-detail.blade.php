<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {!! $article->title !!}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex flex-1 items-center space-x-2">
            <a
                href="{{ route('employee.articles.list') }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-medium text-slate-900 sm:truncate dark:text-slate-100">
                {!! $article->title !!}
            </h1>
            @if(!$article->published)
                <x-badge>
                    {{ __('Hidden') }}
                </x-badge>
            @endif
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 xl:col-span-2 space-y-6">
                <x-card>
                    <x-slot:content>
                        <div class="space-y-6">
                            <div>
                                <x-input-label
                                    for="articleTitleInput"
                                    :value="__('Title')"
                                />
                                <x-input
                                    wire:model.defer="article.title"
                                    type="text"
                                    id="articleTitleInput"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="{{ __('Title') }}"
                                />
                                <x-input-error
                                    for="article.title"
                                    class="mt-2"
                                />
                            </div>
                            <div>
                                <x-input-label
                                    for="content"
                                    :value="__('Content')"
                                />
                                <div class="mt-1 border border-slate-300 rounded-md px-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                                    <x-tiptap
                                        wire:target="save"
                                        wire:loading.delay.class="opacity-50"
                                        wire:model.defer="article.content"
                                    />
                                </div>
                                <x-input-error
                                    for="article.content"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <div x-data="{ articleHasExcerpt: @entangle('articleHasExcerpt').defer }">
                    <x-card>
                        <x-slot:header>
                            <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                                <div class="ml-4 mt-2">
                                    <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                                        {{ __('Excerpt') }}
                                    </h3>
                                </div>
                                <div class="ml-4 mt-2 flex-shrink-0">
                                    <button
                                        x-show="!articleHasExcerpt"
                                        x-on:click="articleHasExcerpt = true"
                                        type="button"
                                        class="btn btn-link"
                                    >
                                        {{ __('Add excerpt') }}
                                    </button>
                                </div>
                            </div>
                        </x-slot:header>
                        <x-slot:content class="-mt-10 space-y-5">
                            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                                {{ __('Add a summary of the post to appear on your home page or blog.') }}
                            </p>
                            <div x-show="articleHasExcerpt">
                                <x-input-label
                                    for="excerpt"
                                    :value="__('Excerpt')"
                                    class="sr-only"
                                />
                                <div class="block w-full mt-1 shadow-sm sm:text-sm">
                                    <x-quill
                                        wire:target="save"
                                        wire:loading.delay.class="opacity-50"
                                        wire:model.defer="article.excerpt"
                                    />
                                </div>
                                <x-input-error
                                    for="article.excerpt"
                                    class="mt-2"
                                />
                            </div>
                        </x-slot:content>
                    </x-card>
                </div>

                <livewire:employee.search-engine-information-form :model="$article" />
            </div>
            <div class="col-span-3 xl:col-span-1 space-y-6">
                <x-card>
                    <x-slot:header>
                        <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                            <div class="ml-4 mt-2">
                                <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                                    {{ __('Visibility') }}
                                </h3>
                            </div>
                        </div>
                    </x-slot:header>
                    <x-slot:content class="-mt-5">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <x-input
                                    wire:model.defer="articleStatus"
                                    type="radio"
                                    name="articleStatus"
                                    id="visibleOption"
                                    class="h-4 w-4 !rounded-full !shadow-none"
                                    value="published"
                                />
                                <x-input-label
                                    for="visibleOption"
                                    :value="__('Visible')"
                                    class="ml-3"
                                />
                            </div>
                            <div class="flex items-center">
                                <x-input
                                    wire:model.defer="articleStatus"
                                    type="radio"
                                    name="articleStatus"
                                    id="hiddenOption"
                                    class="h-4 w-4 !rounded-full !shadow-none"
                                    value="hidden"
                                />
                                <x-input-label
                                    for="hiddenOption"
                                    :value="__('Hidden')"
                                    class="ml-3"
                                />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                            <div class="ml-4 mt-2">
                                <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                                    {{ __('Featured Image') }}
                                </h3>
                            </div>
                            @if($article->hasMedia('cover'))
                                <div class="ml-4 mt-2 flex-shrink-0">
                                    <x-dropdown>
                                        <x-slot:trigger>
                                            <button
                                                type="button"
                                                class="btn btn-link space-x-2"
                                            >
                                                <span>{{ __('Update') }}</span>
                                                <x-heroicon-m-chevron-down class="-ml-0.5 w-4 h-4" />
                                            </button>
                                        </x-slot:trigger>
                                        <x-slot:content>
                                            <x-dropdown-link
                                                wire:click.prevent="editFeaturedImage"
                                                role="button"
                                            >
                                                {{ __('Edit') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link
                                                wire:click.prevent="removeFeaturedImage"
                                                role="button"
                                                class="!text-red-500"
                                            >
                                                {{ __('Remove') }}
                                            </x-dropdown-link>
                                        </x-slot:content>
                                    </x-dropdown>
                                </div>
                            @endif
                        </div>
                    </x-slot:header>
                    <x-slot:content class="-mt-5">
                        @if($featuredImage)
                            <div class="w-full bg-white dark:bg-slate-900">
                                <div class="relative mx-auto">
                                    <div class="group aspect-[16/9] block w-full overflow-hidden rounded-lg bg-slate-50 dark:bg-white/5">
                                        <img
                                            src="{{ $featuredImage->temporaryUrl() }}"
                                            alt=""
                                            class="h-full mx-auto pointer-events-none object-cover object-center group-hover:opacity-75"
                                        >
                                        <label
                                            for="featuredImageInput"
                                            class="absolute inset-0 cursor-pointer focus:outline-none"
                                        >
                                            <span class="sr-only">{{ __('Change image') }}</span>
                                        </label>
                                        <input
                                            wire:model="featuredImage"
                                            type="file"
                                            id="featuredImageInput"
                                            class="hidden"
                                        >
                                    </div>
                                </div>
                            </div>
                        @elseif($article->hasMedia('cover'))
                            <div class="w-full bg-white dark:bg-slate-900">
                                <div class="relative mx-auto">
                                    <div class="group aspect-[16/9] block w-full overflow-hidden rounded-lg bg-slate-50 dark:bg-white/5">
                                        <img
                                            src="{{ $article->getFirstMediaUrl('cover') }}"
                                            alt=""
                                            class="h-full mx-auto pointer-events-none object-cover object-center group-hover:opacity-75"
                                        >
                                        <label
                                            for="featuredImageInput"
                                            class="absolute inset-0 cursor-pointer focus:outline-none"
                                        >
                                            <span class="sr-only">{{ __('Change image') }}</span>
                                        </label>
                                        <input
                                            wire:model="featuredImage"
                                            type="file"
                                            id="featuredImageInput"
                                            class="hidden"
                                        >
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-center rounded-lg border border-dashed border-slate-300 px-6 py-10 dark:border-white/20">
                                <div class="text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-300"
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
                                    <div class="mt-4 flex text-sm justify-center leading-6 text-gray-600">
                                        <label
                                            for="file-upload"
                                            class="btn btn-link cursor-pointer"
                                        >
                                            <span>{{ __('Upload a file') }}</span>
                                            <input
                                                wire:model="featuredImage"
                                                id="file-upload"
                                                name="file-upload"
                                                type="file"
                                                class="sr-only"
                                            >
                                        </label>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-500 dark:text-slate-400">
                                        {{ __('PNG, JPG, GIF up to 10MB') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                            <div class="ml-4 mt-2">
                                <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                                    {{ __('Organization') }}
                                </h3>
                            </div>
                        </div>
                    </x-slot:header>
                    <x-slot:content class="-mt-5 space-y-6">
                        <livewire:employee.article.components.article-author-manager :article="$article" />

                        <hr class="-mx-4 border-slate-200 sm:-mx-6 dark:border-white/10" />

                        <livewire:employee.article.components.article-tag-manager :article="$article" />
                    </x-slot:content>
                </x-card>

                <div
                    x-data="{ confirmingDeletion: false }"
                    class="mt-6 pt-6 border-t border-slate-200 dark:border-white/10"
                >
                    <div
                        x-cloak
                        x-show="confirmingDeletion"
                        class="inline-flex w-full space-x-2"
                    >
                        <button
                            x-on:click.prevent="confirmingDeletion = false"
                            class="btn btn-default block w-full"
                        >
                            {{ __('Cancel') }}
                        </button>
                        <button
                            wire:click="removeBlogPost"
                            class="btn btn-danger block w-full"
                        >
                            {{ __('Confirm') }}
                        </button>
                    </div>
                    <div
                        x-show="!confirmingDeletion"
                        class="inline-flex w-full space-x-2"
                    >
                        <button
                            x-show="!confirmingDeletion"
                            x-on:click.prevent="confirmingDeletion = true"
                            class="btn btn-outline-danger block w-full"
                        >
                            {{ __('Delete blog post') }}
                        </button>
                        <button
                            x-show="!confirmingDeletion"
                            wire:click="save"
                            class="btn btn-primary block w-full"
                        >
                            {{ __('Save changes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="updateFeaturedImage">
        <x-slide-over wire:model="editingFeaturedImage">
            <x-slot:title>
                {{ __('Edit image') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div class="w-full bg-white dark:bg-slate-900">
                        <div class="relative mx-auto">
                            <div class="group aspect-[16/9] block w-full overflow-hidden rounded-lg bg-slate-50 dark:bg-white/5">
                                <img
                                    src="{{ $article->getFirstMediaUrl('cover') }}"
                                    alt=""
                                    class="h-full mx-auto pointer-events-none object-cover object-center group-hover:opacity-75"
                                >
                            </div>
                        </div>
                    </div>
                    <div>
                        <x-input-label
                            for="imageAltInput"
                            :value="__('Image alt text')"
                        />
                        <x-input
                            wire:model.defer="featuredImageAlt"
                            id="imageAltInput"
                            type="text"
                            class="block w-full mt-1 sm:text-sm"
                        />
                        <x-input-description>
                            {{ __('Write a brief description of this image to improve search engine optimization (SEO) and accessibility for visually impaired customers.') }}
                        </x-input-description>
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

    <div
        x-data="{ addFromURL: false, selectedMedia: null }"
        x-on:open-media-modal.window="@this.showMediaModal = true"
    >
        <x-modal-dialog wire:model="showMediaModal">
            <x-slot:title>
                {{ __('Media') }}
            </x-slot:title>
            <x-slot:content>
                @if ($errors->has('mediaFile'))
                    <div class="mb-8 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <x-heroicon-s-x-circle class="w-5 h-5 text-red-400" />
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    {{ trans_choice('There were :count error with your submission|There were :count errors with your submission', $errors->count()) }}
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul
                                        role="list"
                                        class="list-disc pl-5 space-y-1"
                                    >
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @unless($this->media->count())
                    {{--Upload form--}}
                    <div
                        x-on:click="$refs.mediaInput.click()"
                        class="flex justify-center rounded-md border-2 border-dashed border-slate-300 px-6 pt-5 pb-6 cursor-pointer hover:border-slate-400 dark:border-slate-500 dark:hover:border-slate-400"
                    >
                        <div class="space-y-1 text-center">
                            <svg
                                wire:target="mediaFile"
                                wire:loading.remove
                                class="mx-auto h-12 w-12 text-slate-400"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 48 48"
                                aria-hidden="true"
                            >
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                            </svg>
                            <x-loading-spinner
                                wire:target="mediaFile"
                                wire:loading.flex
                                class="mx-auto h-10 w-10 text-slate-400"
                            />
                            <div class="flex justify-center text-sm text-slate-600">
                                <label
                                    for="media-upload"
                                    class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 hover:text-blue-500"
                                >
                                    <span>{{ __('Upload a file') }}</span>
                                    <input
                                        x-ref="mediaInput"
                                        wire:model.defer="mediaFile"
                                        id="media-upload"
                                        name="media-upload"
                                        type="file"
                                        class="sr-only"
                                    >
                                </label>
                            </div>
                            <p class="text-xs text-slate-500">
                                {{ __('Maximum file size allowed: :size megabytes', ['size' => $this->maxUploadSize / 1000]) }}
                            </p>
                        </div>
                    </div>
                @else
                    {{--Media list--}}
                    <ul class="mt-8 grid grid-cols-2 auto-rows-fr gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                        @foreach ($this->media as $media)
                            <li class="relative">
                                <div
                                    class="group block w-full aspect-w-10 aspect-h-7 rounded-lg bg-slate-100 overflow-hidden"
                                    :class="{ 'ring-2 ring-offset-2 ring-blue-500 dark:ring-offset-slate-800': selectedMedia === {{ $media->id }} }"
                                >
                                    @if(str_contains($media->mime_type, 'image'))
                                        <img
                                            src="{{ $media->getUrl() }}"
                                            alt="{{ $media->name }}"
                                            class="object-cover pointer-events-none"
                                            :class="{ 'group-hover:opacity-75': selectedMedia !== {{ $media->id }} }"
                                        >
                                    @endif
                                    @if(str_contains($media->mime_type, 'video'))
                                        <video
                                            src="{{ $media->getUrl() }}"
                                            alt="{{ $media->name }}"
                                            class="object-cover pointer-events-none"
                                            :class="{ 'group-hover:opacity-75': selectedMedia !== {{ $media->id }} }"
                                        >
                                            {{ __('Your browser does not support the video tag.') }}
                                        </video>
                                    @endif
                                    <button
                                        x-on:click="selectedMedia = {{ $media->id }}"
                                        class="absolute inset-0 focus:outline-none"
                                    >
                                    <span class="sr-only">
                                        {{ __('Select this media') }}
                                    </span>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                        <li
                            wire:loading.delay
                            class="relative"
                        >
                            <div class="block w-full aspect-w-10 aspect-h-7 rounded-lg bg-slate-100 overflow-hidden">
                                <x-loading-spinner class="absolute inset-0 m-auto w-5 h-5" />
                            </div>
                        </li>
                        <li class="relative">
                            <div
                                x-on:click="$refs.mediaInput.click()"
                                class="flex justify-center items-center h-full w-full rounded-md border-2 border-dashed border-slate-300 cursor-pointer hover:border-slate-400 dark:border-slate-500 dark:hover:border-slate-400"
                            >
                                <div class="space-y-1 text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-slate-400"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 48 48"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></path>
                                    </svg>
                                    <div class="flex text-sm text-slate-600">
                                        <label
                                            for="media-upload"
                                            class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 hover:text-blue-500"
                                        >
                                            <span class="sr-only">{{ __('Upload a file') }}</span>
                                            <input
                                                x-ref="mediaInput"
                                                wire:model.defer="mediaFile"
                                                id="media-upload"
                                                name="media-upload"
                                                type="file"
                                                class="sr-only"
                                            >
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                @endunless
            </x-slot:content>
            <x-slot:footer>
                <div>
                    <button
                        x-on:click="$wire.set('showMediaModal', false)"
                        type="button"
                        class="btn btn-invisible"
                    >
                        {{ __('Cancel') }}
                    </button>
                    <button
                        x-show="selectedMedia"
                        x-on:click.prevent="if(confirm('{{ __('Are you sure you want to delete this media?') }}')) $wire.deleteMedia(selectedMedia); selectedMedia = null;"
                        type="button"
                        class="ml-3 btn btn-outline-danger"
                    >
                        {{ __('Delete') }}
                    </button>
                    <button
                        x-show="selectedMedia"
                        x-on:click="$wire.insertMedia(selectedMedia); selectedMedia = null"
                        type="button"
                        class="ml-3 btn btn-primary"
                    >
                        {{ __('Insert') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-modal-dialog>
    </div>
</div>

<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {!! $page->title !!}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex flex-1 items-center space-x-2">
            <a
                href="{{ route('employee.pages.list') }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-medium text-slate-900 sm:truncate dark:text-slate-100">
                {!! $page->title !!}
            </h1>
            @if(!$page->published)
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
                                    for="pageTitleInput"
                                    :value="__('Title')"
                                />
                                <x-input
                                    wire:model.defer="page.title"
                                    type="text"
                                    id="pageTitleInput"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="{{ __('Title') }}"
                                />
                                <x-input-error
                                    for="page.title"
                                    class="mt-2"
                                />
                            </div>
                            <div>
                                <x-input-label
                                    for="pageContentInput"
                                    :value="__('Content')"
                                />
                                <div class="mt-1 border border-slate-300 rounded-md px-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                                    <x-tiptap
                                        wire:target="save"
                                        wire:loading.delay.class="opacity-50"
                                        wire:model.defer="page.content"
                                    />
                                </div>
                                <x-input-error
                                    for="page.content"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <livewire:employee.search-engine-information-form :model="$page" />
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
                                    wire:model.defer="pageStatus"
                                    type="radio"
                                    name="pageStatus"
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
                                    wire:model.defer="pageStatus"
                                    type="radio"
                                    name="pageStatus"
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
                            wire:click="removePage"
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
                            {{ __('Delete page') }}
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
                        class="btn btn-default"
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

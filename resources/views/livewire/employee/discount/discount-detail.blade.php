<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ $discount->exists ? __("Discount $discount->code") : __('Create discount') }}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 max-w-3xl mx-auto sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex flex-1 items-center space-x-2">
            <a
                href="{{ route('employee.discounts.list') }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5"/>
            </a>
            <h1 class="text-2xl font-medium leading-6 text-slate-900 sm:truncate dark:text-slate-100">
                {{ $discount->exists ? $discount->code : __('Create discount') }}
            </h1>
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-3xl space-y-6 sm:px-6 lg:px-8">
        @if($message = session('success'))
            <x-alert
                type="success"
                :message="$message"
            />
        @endif

        <x-card>
            <x-slot:content>
                <div
                    x-data="{
                        code: @entangle('discount.code').defer,
                        type: @entangle('discount.type').defer,
                        generateCode() {
                            const characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ0123456789';
                            let code = '';
                            for (let i = 0; i < 12; i++) {
                               code += characters.charAt(Math.floor(Math.random() * characters.length));
                            }
                            return this.code = code;
                        }
                    }"
                >
                    <div>
                        <x-input-label
                            for="code"
                            :value="__('Discount code')"
                        />
                        <div class="mt-1 flex">
                            <div class="relative flex flex-grow items-stretch focus-within:z-10">
                                <x-input
                                    x-model="code"
                                    id="code"
                                    type="text"
                                    class="block w-full !rounded-none !rounded-l-md sm:text-sm"
                                    placeholder="Enter discount code"
                                />
                            </div>
                            <button
                                x-on:click="generateCode()"
                                class="relative -ml-px btn btn-default !rounded-none !rounded-r-md focus:!outline-offset-0 focus:!ring-1 focus:!ring-offset-0"
                            >
                                {{ __('Generate code') }}
                            </button>
                        </div>
                        <x-input-error
                            for="discount.code"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label
                                    for="type"
                                    :value="__('Type')"
                                />
                                <div class="mt-1">
                                    <x-select
                                        x-model="type"
                                        id="type"
                                        class="block w-full py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                    >
                                        <option value="percentage">{{ __('Percentage') }}</option>
                                        <option value="fixed">{{ __('Fixed') }}</option>
                                    </x-select>
                                </div>
                            </div>

                            <div>
                                <x-input-label
                                    for="value"
                                    :value="__('Value')"
                                />
                                <div class="relative mt-1">
                                    <x-input
                                        wire:model.defer="discount.value"
                                        id="value"
                                        type="number"
                                        step="any"
                                        class="no-spinners block w-full pr-12 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                        placeholder="Enter discount value"
                                    />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span
                                            x-show="type === 'fixed'"
                                            class="text-gray-500 sm:text-sm"
                                        >
                                            {{ config('app.currency') }}
                                        </span>
                                        <span
                                            x-show="type === 'percentage'"
                                            class="text-gray-500 sm:text-sm"
                                        >
                                            %
                                        </span>
                                    </div>
                                </div>
                                <x-input-error
                                    for="discount.value"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </x-slot:content>
        </x-card>

        <x-card>
            <x-slot:header>
                <h2 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Applies to') }}
                </h2>
            </x-slot:header>

            <x-slot:content class="-mt-5">
                <div
                    x-data="{ applies: @entangle('discount.applies_to'), search: '' }"
                    x-init="$watch('search', value => {
                        if (applies === 'collections') {
                            $wire.searchCollections(value)
                        } else {
                            $wire.searchProducts(value)
                        }
                        search = ''
                    })"
                >
                    <div class="space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
                        <div class="flex items-center">
                            <x-input
                                x-model="applies"
                                wire:model.defer="discount.applies_to"
                                type="radio"
                                id="applies-to-collections"
                                name="applies-to"
                                value="collections"
                                class="!rounded-full"
                            />
                            <x-input-label
                                for="applies-to-collections"
                                :value="__('Specific collections')"
                                class="ml-2"
                            />
                        </div>
                        <div class="flex items-center">
                            <x-input
                                x-model="applies"
                                wire:model.defer="discount.applies_to"
                                type="radio"
                                id="applies-to-products"
                                name="applies-to"
                                value="products"
                                class="!rounded-full"
                            />
                            <x-input-label
                                for="applies-to-products"
                                :value="__('Specific products')"
                                class="ml-2"
                            />
                        </div>
                    </div>

                    <div class="mt-4 flex">
                        <div class="relative flex flex-grow items-stretch focus-within:z-10">
                            <x-input
                                x-model="search"
                                id="code"
                                type="text"
                                class="block w-full !rounded-none !rounded-l-md sm:text-sm"
                                ::placeholder="applies === 'collections' ? '{{ __('Search collection') }}' : '{{ __('Search product') }}'"
                            />
                        </div>
                        <button
                            x-on:click="applies === 'collections' ? $wire.searchCollections() : $wire.searchProducts()"
                            type="button"
                            class="relative -ml-px btn btn-default !rounded-none !rounded-r-md focus:!outline-offset-0 focus:!ring-1 focus:!ring-offset-0"
                        >
                            {{ __('Browse') }}
                        </button>
                    </div>

                    <x-input-error
                        for="selectedCollections"
                        class="mt-2"
                    />

                    <x-input-error
                        for="selectedProducts"
                        class="mt-2"
                    />

                    @if($this->currentCollections->count())
                        <div
                            x-show="applies === 'collections'"
                            class="mt-4"
                        >
                            <ul class="-mx-4 divide-y divide-slate-200 sm:-mx-6 dark:divide-slate-200/10">
                                @foreach($this->currentCollections as $currentCollection)
                                    <li class="p-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="mr-4 flex-shrink-0 self-center">
                                                    <img
                                                        class="h-10 w-10 rounded object-center object-cover"
                                                        src="{{ $currentCollection->getFirstMediaUrl('cover', 'thumb') }}"
                                                        alt="{{ $currentCollection->title }}"
                                                    >
                                                </div>
                                                <p class="block font-medium text-sm text-slate-700 dark:text-slate-200">
                                                    {{ $currentCollection->title }}
                                                </p>
                                            </div>
                                            <button
                                                wire:click.prevent="removeCollections({{ $currentCollection->id }})"
                                                type="button"
                                                class="ml-3"
                                            >
                                                <x-heroicon-o-x-mark
                                                    class="h-5 w-5 text-slate-400 hover:text-slate-500 dark:hover:text-slate-300"/>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($this->currentProducts->count())
                        <div
                            x-show="applies === 'products'"
                            class="mt-4"
                        >
                            <ul class="-mx-4 divide-y divide-slate-200 sm:-mx-6 dark:divide-slate-200/10">
                                @foreach($this->currentProducts as $product)
                                    <li class="p-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="mr-4 flex-shrink-0 self-center">
                                                    <img
                                                        class="h-10 w-10 rounded object-center object-cover"
                                                        src="{{ $product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                        alt="{{ $product->name }}"
                                                    >
                                                </div>
                                                <p class="block font-medium text-sm text-slate-700 dark:text-slate-200">
                                                    {{ $product->name }}
                                                </p>
                                            </div>
                                            <button
                                                wire:click.prevent="removeProducts({{ $product->id }})"
                                                type="button"
                                                class="ml-3"
                                            >
                                                <x-heroicon-o-x-mark
                                                    class="h-5 w-5 text-slate-400 hover:text-slate-500 dark:hover:text-slate-300"/>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </x-slot:content>
        </x-card>

        <x-card>
            <x-slot:header>
                <h2 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Active dates') }}
                </h2>
            </x-slot:header>

            <x-slot:content class="-mt-5">
                <div
                    x-data="{
                        now: new Date(),
                        startDate: @entangle('startDate').defer,
                        startTime: @entangle('startTime').defer,
                        endDate: @entangle('endDate').defer,
                        endTime: @entangle('endTime').defer,
                        hasEnd: @entangle('hasEnd').defer,
                    }"
                    x-init="
                        timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                        flatpickr('.start-date-input', {
                            dateFormat: 'Z',
                            defaultDate: startDate,
                            disableMobile: true,
                            altInput: true,
                            onChange: function(selectedDates, dateStr) {
                                startDate = dateStr;
                            }
                        });
                        flatpickr('.start-time-input', {
                            enableTime: true,
                            noCalendar: true,
                            dateFormat: 'Z',
                            defaultDate: startTime,
                            disableMobile: true,
                            time_24hr: true,
                            altInput: true,
                            onChange: function(selectedDates, dateStr) {
                                startTime = dateStr;
                            }
                        });
                        flatpickr('.end-date-input', {
                            dateFormat: 'Z',
                            defaultDate: endDate,
                            disableMobile: true,
                            altInput: true,
                            onChange: function(selectedDates, dateStr) {
                                endDate = dateStr;
                            }
                        });
                        flatpickr('.end-time-input', {
                            enableTime: true,
                            noCalendar: true,
                            dateFormat: 'Z',
                            defaultDate: endTime,
                            disableMobile: true,
                            time_24hr: true,
                            altInput: true,
                            onChange: function(selectedDates, dateStr) {
                                endTime = dateStr;
                            }
                        });
                    "
                    class="grid sm:grid-cols-2 gap-4"
                >
                    <div wire:ignore>
                        <x-input-label
                            for="starts_at_date"
                            :value="__('Start date')"
                        />
                        <div class="mt-1">
                            <x-input
                                wire:ignore
                                id="starts_at_date"
                                type="text"
                                class="start-date-input block w-full py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            />
                        </div>
                        <x-input-error
                            for="startDate"
                            class="mt-2"
                        />
                    </div>

                    <div wire:ignore>
                        <x-input-label
                            for="starts_at_time"
                            :value="__('Start time')"
                        />
                        <div class="mt-1">
                            <x-input
                                id="starts_at_time"
                                type="text"
                                class="start-time-input block w-full py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            />
                        </div>
                        <x-input-error
                            for="startTime"
                            class="mt-2"
                        />
                    </div>

                    <div class="col-span-2 flex items-center">
                        <x-input
                            x-model="hasEnd"
                            type="checkbox"
                            id="set-ends-at"
                            class="mr-2 !rounded"
                        />
                        <x-input-label
                            for="set-ends-at"
                            :value="__('Set end date')"
                        />
                    </div>

                    <div
                        x-show="hasEnd"
                        wire:ignore
                    >
                        <x-input-label
                            for="ends_at_date"
                            :value="__('End date')"
                        />
                        <div class="mt-1">
                            <x-input
                                id="ends_at_date"
                                type="text"
                                class="end-date-input block w-full py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            />
                        </div>
                        <x-input-error
                            for="endDate"
                            class="mt-2"
                        />
                    </div>

                    <div
                        x-show="hasEnd"
                        wire:ignore
                    >
                        <x-input-label
                            for="ends_at_time"
                            :value="__('End time')"
                        />
                        <div class="mt-1">
                            <x-input
                                id="ends_at_time"
                                type="text"
                                class="end-time-input block w-full py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            />
                        </div>
                        <x-input-error
                            for="endTime"
                            class="mt-2"
                        />
                    </div>
                </div>
            </x-slot:content>
        </x-card>

        <div class="flex justify-end">
            <button
                wire:click="save"
                type="submit"
                class="btn btn-primary"
            >
                {{ __('Save discount') }}
            </button>
        </div>
    </div>

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
                                <x-heroicon-m-magnifying-glass class="h-5 w-5 text-slate-400"/>
                            </div>
                            <x-input
                                wire:model.debounce.500ms="filterCollectionTitle"
                                type="search"
                                id="search"
                                class="block pl-10 w-full sm:text-sm"
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
                                    <x-input-label
                                        class="p-4 cursor-pointer sm:px-6 hover:bg-slate-50 dark:hover:bg-slate-700/25">
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
                                <x-heroicon-m-magnifying-glass class="h-5 w-5 text-slate-400"/>
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
                                    <x-input-label
                                        class="p-4 cursor-pointer sm:px-6 hover:bg-slate-50 dark:hover:bg-slate-700/25">
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

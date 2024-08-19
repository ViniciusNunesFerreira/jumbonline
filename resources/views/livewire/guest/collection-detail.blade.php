<div x-data="{ showMobileFilter: false }">
    <div class="border-b border-slate-200">
        <nav
            aria-label="Breadcrumb"
            class="mx-auto max-w-7xl overflow-hidden whitespace-nowrap px-4 sm:px-6 lg:px-8"
        >
            <ol
                role="list"
                class="flex items-center space-x-4 py-4"
            >
                <li>
                    <div class="flex items-center">
                        <a
                            href="/"
                            class="mr-4 text-sm font-medium text-slate-900"
                        >
                            Home
                        </a>
                        <svg
                            viewBox="0 0 6 20"
                            xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true"
                            class="h-5 w-auto text-slate-300"
                        >
                            <path
                                d="M4.878 4.34H3.551L.27 16.532h1.327l3.281-12.19z"
                                fill="currentColor"
                            />
                        </svg>
                    </div>
                </li>
                <li class="text-sm truncate">
                    <a
                        href="{{ route('guest.collections.detail', $collection) }}"
                        aria-current="page"
                        class="font-medium text-slate-500 hover:text-slate-600"
                    >
                        {{ $collection->title }}
                    </a>
                </li>
            </ol>
        </nav>
    </div>

    <main class="mx-auto max-w-2xl px-4 lg:max-w-7xl lg:px-8">
        <div class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-24">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900">
                {{ $collection->title }}
            </h1>

            <div class="hidden lg:flex lg:items-center">
                <x-dropdown>
                    <x-slot:trigger>
                        <button
                            type="button"
                            class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900"
                            id="menu-button"
                            aria-expanded="false"
                            aria-haspopup="true"
                        >
                            {{ __('Display :resultCount per page', ['resultCount' => $this->perPage]) }}
                            <x-heroicon-m-chevron-down class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500" />
                        </button>
                    </x-slot:trigger>
                    <x-slot:content>
                        <x-dropdown-link
                            role="button"
                            wire:click.prevent="$set('perPage', 12)"
                        >
                            {{ __('12') }}
                        </x-dropdown-link>
                        <x-dropdown-link
                            role="button"
                            wire:click.prevent="$set('perPage', 24)"
                        >
                            {{ __('24') }}
                        </x-dropdown-link>
                        <x-dropdown-link
                            role="button"
                            wire:click.prevent="$set('perPage', 36)"
                        >
                            {{ __('36') }}
                        </x-dropdown-link>
                    </x-slot:content>
                </x-dropdown>

                <x-dropdown trigger-classes="ml-5">
                    <x-slot:trigger>
                        <button
                            type="button"
                            class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900"
                            id="menu-button"
                            aria-expanded="false"
                            aria-haspopup="true"
                        >
                            {{ __('Sort by') }}
                            @if($sortBy === 'price' && $sortDirection === 'asc')
                                {{ __('Price: Low to High') }}
                            @elseif($sortBy === 'price' && $sortDirection === 'desc')
                                {{ __('Price: High to Low') }}
                            @elseif($sortBy === 'name' && $sortDirection === 'desc')
                                {{ __('Alphabetically, Z-A') }}
                            @else
                                {{ __('Alphabetically, A-Z') }}
                            @endif
                            <x-heroicon-m-chevron-down class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500" />
                        </button>
                    </x-slot:trigger>
                    <x-slot:content>
                        <x-dropdown-link
                            role="button"
                            wire:click="applySorting('name', 'asc')"
                        >
                            {{ __('Alphabetically, A-Z') }}
                        </x-dropdown-link>
                        <x-dropdown-link
                            role="button"
                            wire:click="applySorting('name', 'desc')"
                        >
                            {{ __('Alphabetically, Z-A') }}
                        </x-dropdown-link>
                        <x-dropdown-link
                            role="button"
                            wire:click="applySorting('price', 'asc')"
                        >
                            {{ __('Price: Low to High') }}
                        </x-dropdown-link>
                        <x-dropdown-link
                            role="button"
                            wire:click="applySorting('price', 'desc')"
                        >
                            {{ __('Price: High to Low') }}
                        </x-dropdown-link>
                    </x-slot:content>
                </x-dropdown>
            </div>
        </div>

        <div class="pt-12 pb-24 lg:grid lg:grid-cols-3 lg:gap-x-8 xl:grid-cols-4">
            @unless($products->count())
                <div class="text-center lg:col-span-3 xl:col-span-4">
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">
                        {{ __('No products') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('There are no products in this collection.') }}
                    </p>
                    <div class="mt-6">
                        <a
                            href="{{ route('guest.products.list') }}"
                            class="btn btn-primary"
                        >
                            <x-heroicon-m-arrow-left class="-ml-0.5 mr-1.5 h-5 w-5" />
                            {{ __('Back to shop') }}
                        </a>
                    </div>
                </div>
            @else
                <aside>
                    <h2 class="sr-only">
                        {{ __('Filters') }}
                    </h2>

                    {{-- Mobile filter dialog toggle. --}}
                    <button
                        wire:click.prevent="showMobileFilter"
                        type="button"
                        class="inline-flex items-center lg:hidden"
                    >
                        <span class="text-sm font-medium text-slate-700">{{ __('Filters') }}</span>
                        <x-heroicon-m-plus class="ml-1 h-5 w-5 flex-shrink-0 text-slate-400" />
                    </button>

                    <div class="hidden lg:block">
                        <div class="space-y-10 divide-y divide-slate-200">
                            @foreach($this->productOptionValues->unique('label')->sortBy('option.name')->groupBy('option.name')->all() as $key => $data)
                                <div @class(['pt-10' => !$loop->first])>
                                    <fieldset>
                                        <legend class="block text-sm font-medium text-slate-900">
                                            {{ $key }}
                                        </legend>
                                        <div class="space-y-3 pt-6">
                                            @foreach($data as $value)
                                                @if($value->option->visual == 'color' || $value->option->visual == 'image')
                                                    <div
                                                        class="inline-flex"
                                                        data-tippy-content="{{ $value->label }}"
                                                        data-tippy-placement="bottom"
                                                    >
                                                        <label
                                                            for="optionValue-{{ $value->id }}"
                                                            class="block relative w-8 h-8"
                                                        >
                                                            <input
                                                                wire:model="filters.options"
                                                                type="checkbox"
                                                                id="optionValue-{{ $value->id }}"
                                                                value="{{ $value->label }}"
                                                                class="sr-only peer"
                                                            />
                                                            @if($value->option->visual == 'color')
                                                                <span
                                                                    class="inline-flex justify-center items-center w-full h-full rounded-full shadow bg-center bg-cover cursor-pointer duration-150"
                                                                    style="background-color: {{ $value->value }}"
                                                                ></span>
                                                            @else
                                                                <span
                                                                    class="inline-flex justify-center items-center w-full h-full rounded-full shadow bg-center bg-cover cursor-pointer duration-150"
                                                                    style="background-image: url('{{ $value->getFirstMediaUrl('image') }}')"
                                                                ></span>
                                                            @endif
                                                            <x-heroicon-m-check class="w-5 h-5 text-white absolute inset-0 m-auto z-0 pointer-events-none hidden peer-checked:block duration-150" />
                                                        </label>
                                                    </div>
                                                @else
                                                    <div class="flex items-center">
                                                        <x-input
                                                            wire:model="filters.options"
                                                            type="checkbox"
                                                            id="optionValue-{{ $value->id }}"
                                                            value="{{ $value->label }}"
                                                            class="h-4 w-4 !rounded !shadow-none"
                                                        />
                                                        <x-input-label
                                                            for="optionValue-{{ $value->id }}"
                                                            class="ml-3 !font-normal"
                                                        >
                                                            {{ $value->label }}
                                                        </x-input-label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </fieldset>
                                </div>
                            @endforeach

                            {{--Price--}}
                            <div
                                wire:ignore
                                x-data="
                                    {
                                        min: {{ $this->minPrice }},
                                        max: {{ $this->maxPrice }},
                                        selectedMin: @entangle('filters.price.min').defer,
                                        selectedMax: @entangle('filters.price.max').defer
                                    }
                                "
                                x-init="
                                    slider = noUiSlider.create($refs.slider, {
                                        start: [min, max],
                                        step: 1,
                                        format: {
                                            to: function (value) {
                                                return value.toFixed(0);
                                            },
                                            from: function (value) {
                                                return value;
                                            }
                                        },
                                        tooltips: [
                                            false,
                                            false
                                        ],
                                        connect: true,
                                        range: {
                                            'min': min,
                                            'max': max
                                        }
                                    });
                                    slider.on('slide', function (values, handle) {
                                        selectedMin = values[0];
                                        selectedMax = values[1];
                                    });
                                    slider.on('set', function (values, handle) {
                                        $wire.set('filters.price.min', values[0]);
                                        $wire.set('filters.price.max', values[1]);
                                    });
                                "
                                class="pt-10"
                            >
                                <fieldset>
                                    <legend class="block text-sm font-medium text-gray-900">{{ __('Price') }}</legend>
                                    <div class="space-y-3 pt-6">
                                        <div
                                            x-ref="slider"
                                            class="slider-fit"
                                        ></div>
                                        <p class="text-center text-sm text-slate-700">
                                            {{ __('from') }} $<span x-text="selectedMin"></span>
                                            {{ __('to') }} $<span x-text="selectedMax"></span>
                                        </p>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </aside>

                <section
                    aria-labelledby="product-heading"
                    class="mt-6 lg:col-span-2 lg:mt-0 xl:col-span-3"
                >
                    <h2
                        id="product-heading"
                        class="sr-only"
                    >
                        {{ __('Products') }}
                    </h2>

                    <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:gap-x-8 xl:grid-cols-3">
                        @foreach($products as $product)
                            <div
                                wire:key="product-{{ $product->slug }}"
                                class="group relative flex flex-col overflow-hidden rounded-lg border border-slate-200 bg-white hover:border-sky-300 hover:shadow-lg hover:shadow-sky-300/50 transition duration-150"
                            >
                                <div class="aspect-w-3 aspect-h-4 group-hover:opacity-75 sm:aspect-none">
                                    @if($product->hasMedia('gallery'))
                                        {{ $product->getFirstMedia('gallery')('responsive')->attributes(['alt' => $product->name, 'class' => 'h-full w-full object-cover object-center sm:h-full sm:w-full']) }}
                                    @else
                                        <img
                                            src="{{ $product->getFirstMediaUrl('gallery') }}"
                                            alt="{{ $product->name }}"
                                            class="h-full w-full object-cover object-center sm:h-full sm:w-full"
                                        >
                                    @endif
                                </div>
                                <div class="flex flex-1 flex-col items-center text-center space-y-2 p-4">
                                    <h3 class="text-sm font-medium text-slate-900 line-clamp-2">
                                        <a href="{{ route('guest.products.detail', $product) }}">
                                            <span
                                                aria-hidden="true"
                                                class="absolute inset-0"
                                            ></span>
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <ul
                                        role="list"
                                        class="flex items-center space-x-3"
                                    >
                                        @foreach($product->options as $option)
                                            @if($option->visual === 'color')
                                                @foreach($option->optionValues->take(5) as $optionValue)
                                                    <li
                                                        class="h-4 w-4 rounded-full border border-black border-opacity-10"
                                                        style="background-color: {{ $optionValue->value }}"
                                                    >
                                                        <span class="sr-only">{{ $optionValue->label }}</span>
                                                    </li>
                                                @endforeach
                                                @if($option->optionValues->count() > 5)
                                                    <li class="flex-shrink-0 text-xs font-medium leading-5">
                                                        +{{ $option->optionValues->count() - 5 }}
                                                    </li>
                                                @endif
                                            @elseif($option->visual === 'image')
                                                @foreach($option->optionValues->take(5) as $optionValue)
                                                    <li
                                                        class="h-4 w-4 rounded-full border border-black border-opacity-10"
                                                        style="background-image: url('{{ $optionValue->getFirstMediaUrl('image') }}')"
                                                    >
                                                        <span class="sr-only">{{ $optionValue->label }}</span>
                                                    </li>
                                                @endforeach
                                                @if($option->optionValues->count() > 5)
                                                    <li class="flex-shrink-0 text-xs font-medium leading-5">
                                                        +{{ $option->optionValues->count() - 5 }}
                                                    </li>
                                                @endif
                                            @endif
                                        @endforeach
                                    </ul>
                                    <div class="pt-1 flex flex-1 flex-col justify-end">
                                        <p class="text-base font-medium text-slate-900">
                                            <x-money
                                                :amount="$product->price"
                                                :currency="config('app.currency')"
                                            />
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $products->links() }}
                    </div>
                </section>
            @endunless
        </div>
    </main>

    <x-slide-over wire:model="showMobileFilterDialog">
        <x-slot:title>
            {{ __('Filters') }}
        </x-slot:title>
        <x-slot:content>
            <div class="space-y-10 divide-y divide-slate-200">
                @foreach($this->productOptionValues->unique('label')->sortBy('option.name')->groupBy('option.name')->all() as $key => $data)
                    <div @class(['pt-10' => !$loop->first])>
                        <fieldset>
                            <legend class="block text-sm font-medium text-slate-900">
                                {{ $key }}
                            </legend>
                            <div class="space-y-3 pt-6">
                                @foreach($data as $value)
                                    @if($value->option->visual == 'color' || $value->option->visual == 'image')
                                        <div
                                            class="inline-flex"
                                            data-tippy-content="{{ $value->label }}"
                                            data-tippy-placement="bottom"
                                        >
                                            <label
                                                for="mobileFilterOptionValue-{{ $value->id }}"
                                                class="block relative w-8 h-8"
                                            >
                                                <input
                                                    wire:model="filters.options"
                                                    type="checkbox"
                                                    id="mobileFilterOptionValue-{{ $value->id }}"
                                                    value="{{ $value->label }}"
                                                    class="sr-only peer"
                                                />
                                                @if($value->option->visual == 'color')
                                                    <span
                                                        class="inline-flex justify-center items-center w-full h-full rounded-full shadow bg-center bg-cover cursor-pointer duration-150"
                                                        style="background-color: {{ $value->value }}"
                                                    ></span>
                                                @else
                                                    <span
                                                        class="inline-flex justify-center items-center w-full h-full rounded-full shadow bg-center bg-cover cursor-pointer duration-150"
                                                        style="background-image: url('{{ $value->getFirstMediaUrl('image') }}')"
                                                    ></span>
                                                @endif
                                                <x-heroicon-m-check class="w-5 h-5 text-white absolute inset-0 m-auto z-0 pointer-events-none hidden peer-checked:block duration-150" />
                                            </label>
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            <x-input
                                                wire:model="filters.options"
                                                type="checkbox"
                                                id="mobileFilterOptionValue-{{ $value->id }}"
                                                value="{{ $value->label }}"
                                                class="h-4 w-4 !rounded !shadow-none"
                                            />
                                            <x-input-label
                                                for="mobileFilterOptionValue-{{ $value->id }}"
                                                class="ml-3 !font-normal"
                                            >
                                                {{ $value->label }}
                                            </x-input-label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                @endforeach

                {{--Price--}}
                <div
                    wire:ignore
                    x-data="
                        {
                            min: {{ $this->minPrice }},
                            max: {{ $this->maxPrice }},
                            selectedMin: @entangle('filters.price.min').defer,
                            selectedMax: @entangle('filters.price.max').defer
                        }
                    "
                    x-init="
                        slider = noUiSlider.create($refs.slider, {
                            start: [min, max],
                            step: 1,
                            format: {
                                to: function (value) {
                                    return value.toFixed(0);
                                },
                                from: function (value) {
                                    return value;
                                }
                            },
                            tooltips: [
                                false,
                                false
                            ],
                            connect: true,
                            range: {
                                'min': min,
                                'max': max
                            }
                        });
                        slider.on('slide', function (values, handle) {
                            selectedMin = values[0];
                            selectedMax = values[1];
                        });
                        slider.on('set', function (values, handle) {
                            $wire.set('filters.price.min', values[0]);
                            $wire.set('filters.price.max', values[1]);
                        });
                    "
                    class="pt-10"
                >
                    <fieldset>
                        <legend class="block text-sm font-medium text-gray-900">{{ __('Price') }}</legend>
                        <div class="space-y-3 pt-6">
                            <div
                                x-ref="slider"
                                class="slider-fit"
                            ></div>
                            <p class="text-center text-sm text-slate-700">
                                {{ __('from') }} $<span x-text="selectedMin"></span>
                                {{ __('to') }} $<span x-text="selectedMax"></span>
                            </p>
                        </div>
                    </fieldset>
                </div>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <div class="flex justify-end">
                <button
                    x-on:click="show = false"
                    class="btn btn-lg btn-primary w-full"
                >
                    {{ __('View results') }}
                </button>
            </div>
        </x-slot:footer>
    </x-slide-over>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                Livewire.hook('message.processed', () => {
                    window.scrollTo({top: 0, behavior: 'smooth'});
                    refreshImages();
                });
            });

            function refreshImages() {
                const images = document.querySelectorAll('img[srcset*="responsive-images"]');
                window.requestAnimationFrame(function () {
                    for (let i = 0; i < images.length; i++) {
                        const size = images[i].getBoundingClientRect().width;
                        images[i].sizes = Math.ceil(size / window.innerWidth * 100) + 'vw';
                    }
                });
            }
        </script>
    @endpush
</div>

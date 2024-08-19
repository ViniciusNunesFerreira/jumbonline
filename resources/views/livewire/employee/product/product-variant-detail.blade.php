<div>
    <!-- Meta title & description -->
    @php
        $variantName = $variant->variantAttributes->map(function ($attribute) {
            return $attribute->optionValue->label;
        })->implode(' / ');
    @endphp

    <x-slot:title>
        {!! __('Variants - :name', ['name' => $variantName]) !!}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex flex-1 items-center space-x-2">
            <a
                href="{{ route('employee.products.detail', $product) }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-medium text-slate-900 truncate dark:text-slate-100">
                {{ $variantName }}
            </h1>
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 xl:col-span-1 space-y-6">
                <x-card>
                    <x-slot:content>
                        <div class="relative flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <a href="{{ route('employee.products.detail', $product) }}">
                                    <img
                                        src="{{ $product->getFirstMediaUrl('gallery', 'thumb_large') }}"
                                        alt="{{ $product->name }}"
                                        class="h-20 rounded-md"
                                    >
                                </a>
                            </div>
                            <div
                                wire:ignore
                                class="min-w-0 flex-1"
                            >
                                <p class="text-sm font-medium text-slate-900 dark:text-slate-200">
                                    {{ $product->name }}
                                </p>
                                <p class="mt-1">
                                    <x-badge :type="$product->is_active ? 'success' : 'default'">
                                        {{ $product->is_active ? __('Active') : __('Inactive') }}
                                    </x-badge>
                                </p>
                                <p class="mt-1 truncate text-sm text-slate-500 dark:text-slate-400">
                                    {{ trans_choice(':count variant|:count variants', $product->variants_count) }}
                                </p>
                                <a
                                    href="{{ route('employee.products.detail', $product) }}"
                                    class="mt-2 btn btn-link"
                                >
                                    {{ __('Back to product') }}
                                </a>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card class="overflow-hidden">
                    <x-slot:header>
                        <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                            <div class="ml-4 mt-2">
                                <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                                    {{ __('Variants') }}
                                </h3>
                            </div>
                        </div>
                    </x-slot:header>
                    <x-slot:content class="-mt-5">
                        <nav class="-mx-4 -mb-5 border-t border-slate-300 divide-y divide-slate-200 max-h-96 overflow-auto sm:-mx-6 dark:border-slate-200/20 dark:divide-slate-200/10">
                            @foreach($product->variants as $productVariant)
                                <a
                                    href="{{ route('employee.products.variants.detail', [$product, $productVariant]) }}"
                                    @class(['group flex items-center px-4 py-4 sm:px-6 hover:bg-slate-50 dark:hover:bg-slate-800', 'bg-slate-50 dark:bg-slate-800' => Request::segment(3) == $product->id && Request::segment(5) == $productVariant->id])
                                >
                                    <img
                                        src="{{ $productVariant->getFirstMediaUrl('image') }}"
                                        alt="{{ $product->name }}"
                                        class="flex-shrink-0 mr-3 h-10 w-10 rounded"
                                    >
                                    <ul class="space-x-2 divide-x divide-slate-200 font-medium text-sm text-slate-900 dark:divide-slate-200/10 dark:text-slate-200">
                                        @foreach($productVariant->variantAttributes as $attribute)
                                            <li @class(['inline', 'pl-2' => !$loop->first])>{{ $attribute->optionValue->label }}</li>
                                        @endforeach
                                    </ul>
                                </a>
                            @endforeach
                        </nav>
                    </x-slot:content>
                </x-card>

                <livewire:employee.product.components.product-variant-image
                    :product="$product"
                    :variant="$variant"
                />
            </div>

            <div class="col-span-3 xl:col-span-2 space-y-6">
                <livewire:employee.product.components.product-variant-pricing
                    :product="$product"
                    :variant="$variant"
                />

                <livewire:employee.product.components.product-variant-inventory
                    :product="$product"
                    :variant="$variant"
                />

                <livewire:employee.product.components.product-variant-shipping
                    :product="$product"
                    :variant="$variant"
                />
            </div>
        </div>
    </div>
</div>

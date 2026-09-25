<div>
    <x-slot:title>
        {{ __('Produtos - :name', ['name' => $product->name]) }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex flex-1 items-center gap-2.5">
                <a href="{{ route('employee.products.list') }}" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-primary truncate dark:text-white">
                    {{ $product->name }}
                </h1>
                <x-badge :type="$product->is_active ? 'success' : 'default'" size="xs">
                    {{ $product->status->label() }}
                </x-badge>
            </div>
            @if($product->type === \App\Enums\ProductType::KIT && $product->is_active)
                <div class="mt-4 flex sm:mt-0 sm:ml-4">
                    <a href="{{ route('guest.products.detail', $product) }}" target="_blank" class="btn btn-outline-primary w-full !rounded-xl">
                        {{ __('Visualizar') }}
                    </a>
                </div>
            @endif
        </div>

        <div class="mt-6">
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-3 xl:col-span-2 space-y-6">
                    <livewire:employee.product.components.product-information :product="$product" />

                    <livewire:employee.product.components.product-specification :product="$product" />

                    <livewire:employee.product.components.product-gallery :product="$product" />

                    @unless($product_options_count)
                        <livewire:employee.product.components.product-variant-pricing
                            :product="$product"
                            :variant="$product->variants->first()"
                        />

                        <livewire:employee.product.components.product-variant-inventory
                            :product="$product"
                            :variant="$product->variants->first()"
                        />

                        <livewire:employee.product.components.product-variant-shipping
                            :product="$product"
                            :variant="$product->variants->first()"
                        />
                    @endunless
                </div>

                <div class="col-span-3 xl:col-span-1 space-y-6">
                    <livewire:employee.product.components.product-status :product="$product" />

                    <livewire:employee.product.components.product-organization :product="$product" />
                </div>
            </div>
        </div>
    </div>
</div>
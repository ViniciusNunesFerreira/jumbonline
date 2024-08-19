<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Refund') }}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex flex-1 items-center space-x-2">
            <a
                href="{{ route('employee.orders.detail', $order) }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-medium leading-6 text-slate-900 dark:text-slate-100">
                {{ __('Refund') }}
            </h1>
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 space-y-6 xl:col-span-2">
                @if($removedItemsCount)
                    <x-alert
                        type="info"
                        class="text-sm"
                        :message="__('Some items in this order have been removed.')"
                    />
                @endif

                @if($this->unshippedItems->count())
                    <x-card>
                        <x-slot:header>
                            <h2 class="text-base font-medium text-slate-900 dark:text-slate-200">
                                {{ __('Unshipped') }}
                            </h2>
                        </x-slot:header>
                        <x-slot:content class="-mt-5 -mx-4 sm:-mx-6">
                            <div class="divide-y divide-slate-200 dark:divide-slate-200/20">
                                <div class="relative overflow-auto">
                                    <table
                                        class="min-w-full divide-y divide-slate-200 dark:divide-slate-200/10"
                                    >
                                        <thead
                                            class="border-t border-slate-200 bg-slate-50 dark:border-slate-200/10 dark:bg-slate-800/75"
                                        >
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-slate-500 uppercase tracking-wider"
                                                >
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3 sm:px-6 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400"
                                                >
                                                    {{ __('Quantity') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 dark:divide-slate-200/10">
                                            @foreach($this->unshippedItems as $unShippedItem)
                                                <tr>
                                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-slate-5">
                                                        <div class="flex items-center">
                                                            <div class="h-10 w-10 flex-shrink-0">
                                                                <img
                                                                    class="h-10 w-10 rounded object-center object-cover"
                                                                    src="{{ $unShippedItem->variant->hasMedia('image') ? $unShippedItem->variant->getFirstMediaUrl('image', 'thumb') : $unShippedItem->variant->product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                                    alt="{{ $unShippedItem->name }}"
                                                                >
                                                            </div>
                                                            <div class="ml-4 max-w-xs flex flex-col">
                                                                <div
                                                                    class="font-medium text-slate-900 truncate ... dark:text-slate-200"
                                                                >
                                                                    <a href="{{ route('employee.products.detail', $unShippedItem->variant->product) }}">{{ $unShippedItem->name }}</a>
                                                                </div>
                                                                @if($unShippedItem->variant->variantAttributes)
                                                                    <ul class="space-x-2 divide-x divide-slate-200 text-slate-500 dark:divide-slate-200/10 dark:text-slate-400">
                                                                        @foreach($unShippedItem->variant->variantAttributes as $attribute)
                                                                            <li @class(['inline', 'pl-2' => !$loop->first])>{{ $attribute->optionValue->label }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap flex justify-end text-sm text-slate-500">
                                                        <div class="relative w-28">
                                                            <x-input
                                                                wire:model="selectedUnshippedItems.{{ $loop->index }}.selected_quantity"
                                                                type="number"
                                                                class="show-spinners sm:text-sm block w-full pr-12"
                                                                min="0"
                                                                max="{{ $unShippedItem->quantity - ($unShippedItem->shipmentItems->sum('quantity') + $unShippedItem->refundItems->where('is_shipped', false)->sum('quantity')) }}"
                                                            />
                                                            <div
                                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                                                            >
                                                                <span
                                                                    class="text-slate-500 sm:text-sm"
                                                                    id="price-currency"
                                                                >
                                                                    {{ __('of') }} {{ $unShippedItem->quantity - ($unShippedItem->shipmentItems->sum('quantity') + $unShippedItem->refundItems->where('is_shipped', false)->sum('quantity')) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </x-slot:content>
                    </x-card>
                @endif

                @if($this->shippedItems->count())
                    <x-card>
                        <x-slot:header>
                            <h2 class="text-base font-medium text-slate-900 dark:text-slate-200">
                                {{ __('Shipped') }}
                            </h2>
                        </x-slot:header>
                        <x-slot:content class="-mt-5 -mx-4 sm:-mx-6">
                            <div class="divide-y divide-slate-200 dark:divide-slate-200/20">
                                <div class="relative overflow-auto">
                                    <table
                                        class="min-w-full divide-y divide-slate-200 dark:divide-slate-200/10"
                                    >
                                        <thead
                                            class="border-t border-slate-200 bg-slate-50 dark:border-slate-200/10 dark:bg-slate-800/75"
                                        >
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-slate-500 uppercase tracking-wider"
                                                >
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3 sm:px-6 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-slate-400"
                                                >
                                                    {{ __('Quantity') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 dark:divide-slate-200/10">
                                            @foreach($this->shippedItems as $shippedItem)
                                                <tr>
                                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-slate-5">
                                                        <div class="flex items-center">
                                                            <div class="h-10 w-10 flex-shrink-0">
                                                                <img
                                                                    class="h-10 w-10 rounded object-center object-cover"
                                                                    src="{{ $shippedItem->variant->hasMedia('image') ? $shippedItem->variant->getFirstMediaUrl('image', 'thumb') : $shippedItem->variant->product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                                    alt="{{ $shippedItem->name }}"
                                                                >
                                                            </div>
                                                            <div class="ml-4 max-w-xs flex flex-col">
                                                                <div
                                                                    class="font-medium text-slate-900 truncate ... dark:text-slate-200"
                                                                >
                                                                    <a href="{{ route('employee.products.detail', $shippedItem->variant->product) }}">{{ $shippedItem->name }}</a>
                                                                </div>
                                                                @if($shippedItem->variant->variantAttributes)
                                                                    <ul class="space-x-2 divide-x divide-slate-200 text-slate-500 dark:divide-slate-200/10 dark:text-slate-400">
                                                                        @foreach($shippedItem->variant->variantAttributes as $attribute)
                                                                            <li @class(['inline', 'pl-2' => !$loop->first])>{{ $attribute->optionValue->label }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap flex justify-end text-sm text-slate-500">
                                                        <div class="relative w-28">
                                                            <x-input
                                                                wire:model="selectedShippedItems.{{ $loop->index }}.selected_quantity"
                                                                type="number"
                                                                class="show-spinners sm:text-sm block w-full pr-12"
                                                                min="0"
                                                                max="{{ $shippedItem->shipmentItems->sum('quantity') - $shippedItem->refundItems->where('is_shipped', true)->sum('quantity') }}"
                                                            />
                                                            <div
                                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                                                            >
                                                                <span
                                                                    class="text-slate-500 sm:text-sm"
                                                                    id="price-currency"
                                                                >
                                                                    {{ __('of') }} {{ $shippedItem->shipmentItems->sum('quantity') - $shippedItem->refundItems->where('is_shipped', true)->sum('quantity') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </x-slot:content>
                    </x-card>
                @endif

                <x-card>
                    <x-slot:header>
                        <h2 class="text-base font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Reason for refund') }}
                        </h2>
                    </x-slot:header>
                    <x-slot:content class="-mt-5">
                        <div>
                            <x-input-label
                                for="reason"
                                :value="__('Reason')"
                            />
                            <x-input
                                wire:model.defer="refund.reason"
                                type="text"
                                id="reason"
                                class="mt-1 block w-full sm:text-sm"
                            />
                            <x-input-description
                                class="mt-1"
                                :value="__('Only you and other staff can see this reason.')"
                            />
                            <x-input-error
                                for="refund.reason"
                                class="mt-2"
                            />
                        </div>
                    </x-slot:content>
                </x-card>
            </div>

            <div class="col-span-3 xl:col-span-1">
                <x-card>
                    <x-slot:header>
                        <h2 class="text-base font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Summary') }}
                        </h2>
                    </x-slot:header>
                    <x-slot:content class="-mt-5">
                        <div
                            wire:target="selectedShippedItems, selectedUnshippedItems"
                            wire:loading.remove
                        >
                            @if($this->summary['items_count'] > 0)
                                <dl class="text-sm text-slate-700 space-y-3">
                                    <div class="flex items-start justify-between">
                                        <dd>
                                            {{ __('Items subtotal') }}
                                            <br>
                                            <span class="text-slate-500 dark:text-slate-400">{{ trans_choice(':count item|:count items', $this->summary['items_count']) }}</span>
                                        </dd>
                                        <dt>{{ money($this->summary['subtotal']) }}</dt>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dd>{{ __('Discount') }}</dd>
                                        <dt>{{ money($this->summary['discount_total']) }}</dt>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <dd>{{ __('Tax') }}</dd>
                                        <dt>{{ money($this->summary['tax_total']) }}</dt>
                                    </div>
                                    <div class="flex items-center justify-between font-medium">
                                        <dd>{{ __('Refund total') }}</dd>
                                        <dt>{{ money($this->summary['refund_total']) }}</dt>
                                    </div>
                                </dl>
                            @else
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('No items selected.') }}
                                </p>
                            @endif
                        </div>
                        <div
                            wire:target="selectedShippedItems, selectedUnshippedItems"
                            wire:loading.flex
                        >
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Updating...') }}
                            </p>
                        </div>
                        <hr class="my-5">
                        <div>
                            <x-input-label
                                for="amount"
                                :value="__('Refund amount')"
                            />
                            <x-input-money
                                wire:model.lazy="refund.amount"
                                id="amount"
                                class="block w-full sm:text-sm"
                                placeholder="0.00"
                                wrapper-classes="mt-1"
                            />
                            <x-input-description
                                class="mt-1"
                                :value="money($order->total_paid - $order->totalRefunded) . ' ' . __('available for refund')"
                            />
                            <x-input-error
                                for="refund.amount"
                                class="mt-2"
                            />
                        </div>
                        <hr class="my-5">
                        <div>
                            <button
                                wire:click="refund"
                                wire:target="selectedShippedItems, selectedUnshippedItems, refund"
                                wire:loading.attr="disabled"
                                type="button"
                                class="btn btn-primary block w-full"
                                @disabled($refund->amount <= 0)
                            >
                                {{ __('Refund :amount', ['amount' => money($refund->amount ?? 0)]) }}
                            </button>
                        </div>
                    </x-slot:content>
                </x-card>
            </div>
        </div>
    </div>
</div>

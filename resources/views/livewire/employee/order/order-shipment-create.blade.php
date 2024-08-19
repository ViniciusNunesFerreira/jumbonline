<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ trans_choice('Fulfill item|Fulfill items', $this->unshippedItems->count()) }}
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
                {{ trans_choice('Fulfill item|Fulfill items', $this->unshippedItems->count()) }}
            </h1>
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 xl:col-span-2">
                <form wire:submit.prevent="save">
                    <x-card>
                        <x-slot:header>
                            <h2 class="text-base font-medium text-slate-900 dark:text-slate-200">
                                {{ __('Order #:orderId', ['orderId' => $order->id]) }}
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
                                            @foreach($this->unshippedItems as $item)
                                                <tr>
                                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-slate-5">
                                                        <div class="flex items-center">
                                                            <div class="h-10 w-10 flex-shrink-0">
                                                                <img
                                                                    class="h-10 w-10 rounded object-center object-cover"
                                                                    src="{{ $item->variant->hasMedia('image') ? $item->variant->getFirstMediaUrl('image', 'thumb') : $item->variant->product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                                    alt="{{ $item->name }}"
                                                                >
                                                            </div>
                                                            <div class="ml-4 max-w-xs flex flex-col">
                                                                <div
                                                                    class="font-medium text-slate-900 truncate ... dark:text-slate-200"
                                                                >
                                                                    <a href="{{ route('employee.products.detail', $item->variant->product) }}">{{ $item->name }}</a>
                                                                </div>
                                                                @if($item->variant->variantAttributes)
                                                                    <ul class="space-x-2 divide-x divide-slate-200 text-slate-500 dark:divide-slate-200/10 dark:text-slate-400">
                                                                        @foreach($item->variant->variantAttributes as $attribute)
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
                                                                wire:model.defer="shipmentItems.{{ $loop->index }}.quantity"
                                                                type="number"
                                                                class="show-spinners sm:text-sm block w-full pr-12"
                                                                min="0"
                                                                max="{{ $item->quantity - $item->shipmentItems->sum('quantity') }}"
                                                            />
                                                            <div
                                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                                                            >
                                                                <span
                                                                    class="text-slate-500 sm:text-sm"
                                                                    id="price-currency"
                                                                >
                                                                    {{ __('of') }} {{ $item->quantity - ($item->shipmentItems->sum('quantity') + $item->refundItems->sum('quantity')) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="px-4 sm:px-6">
                                    <div class="mt-5">
                                        @if($type == 'physical')
                                            <h4 class="text-xs text-slate-500 font-medium uppercase dark:text-slate-400">
                                                {{ __('Tracking information (optional)') }}
                                            </h4>
                                            <div class="mt-2 grid grid-cols-2 gap-5">
                                                <div>
                                                    <x-input-label
                                                        for="shipping_carrier"
                                                        :value="__('Shipping carrier')"
                                                    />
                                                    <x-select
                                                        wire:model="shipment.shipping_carrier"
                                                        id="shipping_carrier"
                                                        class="mt-1 block w-full sm:text-sm"
                                                    >
                                                        @foreach($shippingCarriers as $shippingCarrier)
                                                            <option
                                                                value="{{ $shippingCarrier->value }}"
                                                            >{{ $shippingCarrier->label() }}</option>
                                                        @endforeach
                                                    </x-select>
                                                    <x-input-error
                                                        for="shipment.shipping_carrier"
                                                        class="mt-2"
                                                    />
                                                </div>
                                                <div>
                                                    <x-input-label
                                                        for="tracking_number"
                                                        :value="__('Tracking number')"
                                                    />
                                                    <x-input
                                                        wire:model.defer="shipment.tracking_number"
                                                        type="text"
                                                        id="tracking_number"
                                                        class="mt-1 block w-full sm:text-sm"
                                                    />
                                                    <x-input-error
                                                        for="shipment.tracking_number"
                                                        class="mt-2"
                                                    />
                                                </div>
                                                @if($shipment->shipping_carrier->value === \App\Enums\ShippingCarrier::OTHER->value)
                                                    <div class="col-span-2">
                                                        <x-input-label
                                                            for="tracking_url"
                                                            :value="__('Tracking URL')"
                                                        />
                                                        <x-input
                                                            wire:model.defer="shipment.tracking_url"
                                                            type="text"
                                                            id="tracking_url"
                                                            class="mt-1 block w-full sm:text-sm"
                                                        />
                                                        <x-input-error
                                                            for="shipment.tracking_url"
                                                            class="mt-2"
                                                        />
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-sm text-slate-700">
                                                {{ __('Shipping not required.') }}
                                            </p>
                                        @endif
                                    </div>
                                    @error('shipmentItems')
                                    <x-alert
                                        type="error"
                                        class="mt-5"
                                        :message="$message"
                                    />
                                    @enderror
                                </div>
                            </div>
                        </x-slot:content>
                        <x-slot:footer class="bg-slate-50 dark:bg-slate-800">
                            <div class="flex items-center justify-end">
                                <button
                                    wire:loading.attr="disabled"
                                    class="btn btn-primary"
                                >
                                    {{ __('Create shipment') }}
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-card>
                </form>
            </div>

            <div class="col-span-3 xl:col-span-1">
                <x-card class="-mx-4 sm:-mx-0 overflow-hidden">
                    <x-slot:header>
                        <h2 class="text-base font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Shipping address') }}
                        </h2>
                    </x-slot:header>
                    <x-slot:content class="-mt-5">
                        @if($order->shippingAddress)
                            <address class="not-italic text-sm">
                                {{ $order->shippingAddress->name }}<br>

                                @if($order->shippingAddress->company_name)
                                    {{ $order->shippingAddress->company_name }}<br>
                                @endif

                                @if($order->shippingAddress->address_line_1)
                                    {{ $order->shippingAddress->address_line_1 }}<br>
                                @endif

                                @if($order->shippingAddress->address_line_2)
                                    {{ $order->shippingAddress->address_line_2 }}<br>
                                @endif

                                @if($order->shippingAddress->city)
                                    {{ $order->shippingAddress->city }}
                                @endif

                                @if($order->shippingAddress->state)
                                    {{ $order->shippingAddress->state }}<br>
                                @endif

                                {{ $order->shippingAddress->country->name }}<br>

                                @if($order->shippingAddress->phone)
                                    {{ $order->shippingAddress->phone }}<br>
                                @endif
                            </address>
                        @endif
                    </x-slot:content>
                </x-card>
            </div>
        </div>
    </div>
</div>

<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Orders - :id', ['id' => $order->id]) }}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="flex px-4 sm:px-6 lg:px-8">
        <div class="mr-2 flex-shrink-0">
            <a
                href="{{ route('employee.orders.list') }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
        </div>
        <div class="mt-0.5">
            <div class="sm:flex sm:items-center sm:space-x-3">
                <h1 class="text-2xl font-medium leading-none text-slate-900 dark:text-slate-100">
                    {{ __('Order: #:orderId', ['orderId' => $order->id]) }}
                </h1>
                <div class="mt-2 space-x-3 sm:mt-0">
                    <x-badge class="space-x-1">
                        <span
                            class="block w-2 h-2 rounded-full"
                            style="background-color: {{ $order->payment_status->color() }}"
                        ></span>
                        <span>{{ $order->payment_status->label() }}</span>
                    </x-badge>
                    <x-badge class="space-x-1">
                        <span
                            class="block w-2 h-2 rounded-full"
                            style="background-color: {{ $order->shipping_status->color() }}"
                        ></span>
                        <span>{{ $order->shipping_status->label() }}</span>
                    </x-badge>
                </div>
            </div>

            <div class="mt-2 flex items-center text-sm text-slate-500 dark:text-slate-400">
                <span>{{ $order->created_at->toDayDateTimeString() }}</span>
            </div>
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 xl:col-span-2 space-y-6">
                @if($order->shipping_status->value != \App\Enums\ShippingStatus::SHIPPED->value)
                    <livewire:employee.order.components.order-items :order="$order" />
                @endif

                @if($order->shipments_count)
                    <livewire:employee.order.components.order-shipments :order="$order" />
                @endif

                @if($order->refunds_count && $order->refund_items_count)
                    <livewire:employee.order.components.order-refunded-items :order="$order" />
                @endif

                <livewire:employee.order.components.order-payment-detail :order="$order" />
            </div>

            <div class="mt-6 col-span-3 xl:col-span-1 space-y-6 xl:mt-0">
                <livewire:employee.order.components.order-notes :order="$order" />

                <livewire:employee.order.components.order-customer-detail :order="$order" />
            </div>
        </div>
    </div>
</div>

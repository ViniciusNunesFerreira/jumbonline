<div>
    <x-slot:title>
        {{ __('Pedidos - :id', ['id' => $order->id]) }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-start gap-3">
            <a href="{{ route('employee.orders.list') }}" class="btn btn-default btn-xs !rounded-xl mt-1">
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                        {{ __('Pedido #:orderId', ['orderId' => $order->id]) }}
                    </h1>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium" style="background-color: {{ $order->payment_status->color() }}1A; color: {{ $order->payment_status->color() }};">
                        <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $order->payment_status->color() }}"></span>
                        {{ $order->payment_status->label() }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium" style="background-color: {{ $order->shipping_status->color() }}1A; color: {{ $order->shipping_status->color() }};">
                        <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $order->shipping_status->color() }}"></span>
                        {{ $order->shipping_status->label() }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ $order->created_at->format('d/m/Y \à\s H:i') }}
                </p>
            </div>
        </div>

        <div class="mt-6">
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

                <div class="col-span-3 xl:col-span-1 space-y-6">
                    <livewire:employee.order.components.order-customer-detail :order="$order" />
                </div>
            </div>
        </div>
    </div>
</div>
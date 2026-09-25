<div>
    <x-card class="overflow-hidden">
        <x-slot:header>
            <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                {{ __('Último pedido realizado') }}
            </h2>
        </x-slot:header>
        <x-slot:content class="{{ $customer->orders->count() ? '-mx-4 -my-5 sm:-mx-6' : '' }}">
            @unless($customer->orders->count())
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Este cliente ainda não fez nenhum pedido.') }}</p>
            @else
                @php $lastOrder = $customer->orders->last(); @endphp
                <div class="px-4 pt-1 sm:px-6">
                    <div class="flex items-center gap-3">
                        
                        <a    href="{{ route('employee.orders.detail', ['order' => $lastOrder->id]) }}"
                            class="btn btn-link text-base"
                        >
                            #{{ $lastOrder->id }}
                        </a>
                        <p class="text-sm text-slate-400">
                            {{ $lastOrder->created_at->format('d/m/Y \à\s H:i') }}
                        </p>
                    </div>
                    <div class="mt-2 flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-xs font-medium" style="background-color: {{ $lastOrder->payment_status->color() }}1A; color: {{ $lastOrder->payment_status->color() }};">
                            <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $lastOrder->payment_status->color() }}"></span>
                            {{ $lastOrder->payment_status->label() }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-1 text-xs font-medium" style="background-color: {{ $lastOrder->shipping_status->color() }}1A; color: {{ $lastOrder->shipping_status->color() }};">
                            <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $lastOrder->shipping_status->color() }}"></span>
                            {{ $lastOrder->shipping_status->label() }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 relative overflow-auto">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-white/5">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-white/5">
                                <th scope="col" class="px-3 py-3 sm:px-6"></th>
                                <th scope="col" class="px-3 py-3 sm:px-6 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    {{ __('Qtd.') }}
                                </th>
                                <th scope="col" class="px-3 py-3 sm:px-6 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    {{ __('Preço') }}
                                </th>
                                <th scope="col" class="px-3 py-3 sm:px-6 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    {{ __('Subtotal') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @foreach($lastOrder->orderItems as $item)
                                <tr>
                                    <td class="px-3 py-4 sm:px-6 w-full max-w-sm text-sm text-slate-500 dark:text-slate-400">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img
                                                    class="h-10 w-10 rounded-lg object-center object-cover ring-1 ring-slate-100 dark:ring-white/10"
                                                    src="{{ $item->variant->hasMedia('image') ? $item->variant->getFirstMediaUrl('image', 'thumb') : $item->variant->product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                    alt="{{ $item->name }}"
                                                >
                                            </div>
                                            <div class="ml-3.5 max-w-xs flex flex-col">
                                                <div class="font-medium text-primary hover:text-accent-600 truncate dark:text-slate-200 dark:hover:text-accent-400">
                                                    <a href="{{ route('employee.products.detail', $item->variant->product) }}">{{ $item->name }}</a>
                                                </div>
                                                @if($item->variant->variantAttributes)
                                                    <ul class="space-x-2 divide-x divide-slate-200 text-slate-500 dark:divide-white/10 dark:text-slate-400">
                                                        @foreach($item->variant->variantAttributes as $attribute)
                                                            <li @class(['inline', 'pl-2' => !$loop->first])>{{ $attribute->optionValue->label }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-center text-sm text-slate-500 tabular-nums dark:text-slate-400">
                                        {{ $item->quantity - $item->shipment_items_sum_quantity }}
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-right text-sm text-slate-500 tabular-nums dark:text-slate-400">
                                        <x-money :amount="$item->price" />
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-right text-sm font-semibold text-primary tabular-nums dark:text-slate-200">
                                        <x-money :amount="$item->subtotal" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endunless
        </x-slot:content>
    </x-card>
</div>
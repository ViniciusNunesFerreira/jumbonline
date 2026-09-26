<div>
    <x-slot:title>
        {{ __('Reembolso') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex flex-1 items-center gap-2.5">
                
                    href="{{ route('employee.orders.detail', $order) }}"
                    class="btn btn-default btn-xs !rounded-xl"
                >
                    <x-heroicon-m-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Reembolso — Pedido #:orderId', ['orderId' => $order->id]) }}
                </h1>
            </div>
        </div>

        <div class="mt-6">
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-3 space-y-6 xl:col-span-2">
                    @if($removedItemsCount)
                        <x-alert
                            type="info"
                            class="text-sm"
                            :message="__('Alguns itens deste pedido já foram removidos.')"
                        />
                    @endif

                    @if($this->unshippedItems->count())
                        <x-card>
                            <x-slot:header>
                                <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                                    {{ __('Não enviados') }}
                                </h2>
                            </x-slot:header>
                            <x-slot:content class="-mt-5 -mx-4 sm:-mx-6">
                                <div class="relative overflow-auto">
                                    <table class="min-w-full divide-y divide-slate-100 dark:divide-white/5">
                                        <thead>
                                            <tr class="border-b border-slate-100 dark:border-white/5">
                                                <th scope="col" class="px-3 py-3 sm:px-6"></th>
                                                <th scope="col" class="px-3 py-3 sm:px-6 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                                    {{ __('Quantidade') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                            @foreach($this->unshippedItems as $unShippedItem)
                                                @php $maxQty = $unShippedItem->quantity - ($unShippedItem->shipmentItems->sum('quantity') + $unShippedItem->refundItems->where('is_shipped', false)->sum('quantity')); @endphp
                                                <tr>
                                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-slate-500">
                                                        <div class="flex items-center">
                                                            <div class="h-10 w-10 flex-shrink-0">
                                                                <img
                                                                    class="h-10 w-10 rounded-lg object-center object-cover ring-1 ring-slate-100 dark:ring-white/10"
                                                                    src="{{ $unShippedItem->variant->hasMedia('image') ? $unShippedItem->variant->getFirstMediaUrl('image', 'thumb') : $unShippedItem->variant->product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                                    alt="{{ $unShippedItem->name }}"
                                                                >
                                                            </div>
                                                            <div class="ml-3.5 max-w-xs flex flex-col">
                                                                <div class="font-medium text-primary hover:text-accent-600 truncate dark:text-slate-200 dark:hover:text-accent-400">
                                                                    <a href="{{ route('employee.products.detail', $unShippedItem->variant->product) }}">{{ $unShippedItem->name }}</a>
                                                                </div>
                                                                @if($unShippedItem->variant->variantAttributes)
                                                                    <ul class="space-x-2 divide-x divide-slate-200 text-slate-500 dark:divide-white/10 dark:text-slate-400">
                                                                        @foreach($unShippedItem->variant->variantAttributes as $attribute)
                                                                            <li @class(['inline', 'pl-2' => !$loop->first])>{{ $attribute->optionValue->label }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-right text-sm text-slate-500">
                                                        <div class="relative w-32 ml-auto">
                                                            <x-input
                                                                wire:model="selectedUnshippedItems.{{ $loop->index }}.selected_quantity"
                                                                type="number"
                                                                class="show-spinners sm:text-sm block w-full rounded-xl pr-14 text-right"
                                                                min="0"
                                                                max="{{ $maxQty }}"
                                                            />
                                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                                <span class="text-slate-400 text-xs">{{ __('de :max', ['max' => $maxQty]) }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </x-slot:content>
                        </x-card>
                    @endif

                    @if($this->shippedItems->count())
                        <x-card>
                            <x-slot:header>
                                <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                                    {{ __('Enviados') }}
                                </h2>
                            </x-slot:header>
                            <x-slot:content class="-mt-5 -mx-4 sm:-mx-6">
                                <div class="relative overflow-auto">
                                    <table class="min-w-full divide-y divide-slate-100 dark:divide-white/5">
                                        <thead>
                                            <tr class="border-b border-slate-100 dark:border-white/5">
                                                <th scope="col" class="px-3 py-3 sm:px-6"></th>
                                                <th scope="col" class="px-3 py-3 sm:px-6 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                                    {{ __('Quantidade') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                            @foreach($this->shippedItems as $shippedItem)
                                                @php $maxQty = $shippedItem->shipmentItems->sum('quantity') - $shippedItem->refundItems->where('is_shipped', true)->sum('quantity'); @endphp
                                                <tr>
                                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-slate-500">
                                                        <div class="flex items-center">
                                                            <div class="h-10 w-10 flex-shrink-0">
                                                                <img
                                                                    class="h-10 w-10 rounded-lg object-center object-cover ring-1 ring-slate-100 dark:ring-white/10"
                                                                    src="{{ $shippedItem->variant->hasMedia('image') ? $shippedItem->variant->getFirstMediaUrl('image', 'thumb') : $shippedItem->variant->product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                                    alt="{{ $shippedItem->name }}"
                                                                >
                                                            </div>
                                                            <div class="ml-3.5 max-w-xs flex flex-col">
                                                                <div class="font-medium text-primary hover:text-accent-600 truncate dark:text-slate-200 dark:hover:text-accent-400">
                                                                    <a href="{{ route('employee.products.detail', $shippedItem->variant->product) }}">{{ $shippedItem->name }}</a>
                                                                </div>
                                                                @if($shippedItem->variant->variantAttributes)
                                                                    <ul class="space-x-2 divide-x divide-slate-200 text-slate-500 dark:divide-white/10 dark:text-slate-400">
                                                                        @foreach($shippedItem->variant->variantAttributes as $attribute)
                                                                            <li @class(['inline', 'pl-2' => !$loop->first])>{{ $attribute->optionValue->label }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-right text-sm text-slate-500">
                                                        <div class="relative w-32 ml-auto">
                                                            <x-input
                                                                wire:model="selectedShippedItems.{{ $loop->index }}.selected_quantity"
                                                                type="number"
                                                                class="show-spinners sm:text-sm block w-full rounded-xl pr-14 text-right"
                                                                min="0"
                                                                max="{{ $maxQty }}"
                                                            />
                                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                                <span class="text-slate-400 text-xs">{{ __('de :max', ['max' => $maxQty]) }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </x-slot:content>
                        </x-card>
                    @endif

                    <x-card>
                        <x-slot:header>
                            <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                                {{ __('Motivo do reembolso') }}
                            </h2>
                        </x-slot:header>
                        <x-slot:content>
                            <x-input-label for="reason" :value="__('Motivo')" />
                            <x-input
                                wire:model.defer="refund.reason"
                                type="text"
                                id="reason"
                                class="mt-1 block w-full rounded-xl sm:text-sm"
                            />
                            <x-input-description
                                class="mt-1"
                                :value="__('Só você e a equipe interna veem esse motivo.')"
                            />
                            <x-input-error for="refund.reason" class="mt-2" />
                        </x-slot:content>
                    </x-card>
                </div>

                <div class="col-span-3 xl:col-span-1">
                    <x-card class="xl:sticky xl:top-24">
                        <x-slot:header>
                            <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                                {{ __('Resumo') }}
                            </h2>
                        </x-slot:header>
                        <x-slot:content>
                            <div wire:target="selectedShippedItems, selectedUnshippedItems" wire:loading.remove>
                                @if($this->summary['items_count'] > 0)
                                    <dl class="text-sm space-y-3">
                                        <div class="flex items-start justify-between">
                                            <dd class="text-slate-500 dark:text-slate-400">
                                                {{ __('Subtotal dos itens') }}
                                                <br>
                                                <span class="text-slate-400">{{ trans_choice(':count item|:count itens', $this->summary['items_count']) }}</span>
                                            </dd>
                                            <dt class="font-medium text-primary dark:text-slate-200"><x-money :amount="$this->summary['subtotal']" /></dt>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dd class="text-slate-500 dark:text-slate-400">{{ __('Desconto') }}</dd>
                                            <dt class="font-medium text-primary dark:text-slate-200"><x-money :amount="$this->summary['discount_total']" /></dt>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <dd class="text-slate-500 dark:text-slate-400">{{ __('Imposto') }}</dd>
                                            <dt class="font-medium text-primary dark:text-slate-200"><x-money :amount="$this->summary['tax_total']" /></dt>
                                        </div>
                                        <div class="flex items-center justify-between border-t border-slate-100 pt-3 font-semibold dark:border-white/5">
                                            <dd class="text-primary dark:text-slate-200">{{ __('Total do reembolso') }}</dd>
                                            <dt class="text-primary dark:text-white"><x-money :amount="$this->summary['refund_total']" /></dt>
                                        </div>
                                    </dl>
                                @else
                                    <p class="text-sm text-slate-500 dark:text-slate-400">
                                        {{ __('Nenhum item selecionado.') }}
                                    </p>
                                @endif
                            </div>
                            <div wire:target="selectedShippedItems, selectedUnshippedItems" wire:loading.flex>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Atualizando...') }}
                                </p>
                            </div>
                            <hr class="my-5 border-slate-100 dark:border-white/5">
                            <div>
                                <x-input-label for="amount" :value="__('Valor do reembolso')" />
                                <x-input-money
                                    wire:model.lazy="refund.amount"
                                    id="amount"
                                    class="block w-full rounded-xl sm:text-sm"
                                    placeholder="0.00"
                                    wrapper-classes="mt-1"
                                />
                                <x-input-description
                                    class="mt-1"
                                    :value="__(':amount disponível para reembolso', ['amount' => money($order->total_paid - $order->totalRefunded)])"
                                />
                                <x-input-error for="refund.amount" class="mt-2" />
                            </div>
                            <hr class="my-5 border-slate-100 dark:border-white/5">
                            <div>
                                <button
                                    wire:click="refund"
                                    wire:target="selectedShippedItems, selectedUnshippedItems, refund"
                                    wire:loading.attr="disabled"
                                    wire:confirm="{{ __('Confirma o reembolso deste valor? Essa ação não pode ser desfeita.') }}"
                                    type="button"
                                    class="btn btn-primary block w-full"
                                    @disabled($refund->amount <= 0)
                                >
                                    {{ __('Reembolsar :amount', ['amount' => money($refund->amount ?? 0)]) }}
                                </button>

                                @error('refund')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </x-slot:content>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</div>
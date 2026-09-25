<div class="space-y-6">
    @foreach($shipments as $shipment)
        <x-card class="overflow-hidden">
            <x-slot:header>
                <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                    <div class="flex items-center gap-3">
                        <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                            {{ __('Remessa') }} #{{ $shipment->id }}
                        </h2>

                        @if($shipment->tracking_number)
                            <span class="text-sm text-slate-500 dark:text-slate-400">
                                {{ $shipment->shipping_carrier->label() }} · {{ $shipment->tracking_number }}
                            </span>
                        @else
                            <button wire:click="edit('{{ $shipment->id }}')" type="button" class="btn btn-link">
                                {{ __('Adicionar código de rastreio') }}
                            </button>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button
                                    type="button"
                                    class="-m-2 rounded-full flex items-center text-slate-400 hover:text-primary dark:hover:text-slate-300"
                                    aria-expanded="false"
                                    aria-haspopup="true"
                                >
                                    <span class="sr-only">{{ __('Opções') }}</span>
                                    <x-heroicon-m-ellipsis-horizontal class="h-5 w-5" />
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link wire:click="edit('{{ $shipment->id }}')" role="button">
                                    {{ __('Editar rastreio') }}
                                </x-dropdown-link>
                                <x-dropdown-link wire:click="delete('{{ $shipment->id }}')" role="button" class="text-red-600 dark:text-red-500">
                                    {{ __('Cancelar envio') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </x-slot:header>
            <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                <div class="relative overflow-auto">
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
                            @foreach($shipment->shipmentItems as $item)
                                <tr>
                                    <td class="px-3 py-4 sm:px-6 w-full max-w-sm text-sm text-slate-500 dark:text-slate-400">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img
                                                    class="h-10 w-10 rounded-lg object-center object-cover ring-1 ring-slate-100 dark:ring-white/10"
                                                    src="{{ $item->orderItem->variant->hasMedia('image') ? $item->orderItem->variant->getFirstMediaUrl('image', 'thumb') : $item->orderItem->variant->product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                    alt="{{ $item->orderItem->name }}"
                                                >
                                            </div>
                                            <div class="ml-3.5 max-w-xs flex flex-col">
                                                <div class="font-medium text-primary hover:text-accent-600 truncate dark:text-slate-200 dark:hover:text-accent-400">
                                                    <a href="{{ route('employee.products.detail', $item->orderItem->variant->product) }}">{{ $item->orderItem->name }}</a>
                                                </div>
                                                @if($item->orderItem->variant->variantAttributes)
                                                    <ul class="space-x-2 divide-x divide-slate-200 text-slate-500 dark:divide-white/10 dark:text-slate-400">
                                                        @foreach($item->orderItem->variant->variantAttributes as $attribute)
                                                            <li @class(['inline', 'pl-2' => !$loop->first])>{{ $attribute->optionValue->label }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-center text-sm text-slate-500 dark:text-slate-400">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-right text-sm text-slate-500 tabular-nums dark:text-slate-400">
                                        <x-money :amount="$item->orderItem->price" :currency="config('app.currency')" />
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-right text-sm font-semibold text-primary tabular-nums dark:text-slate-200">
                                        <x-money :amount="$item->quantity * $item->orderItem->price" :currency="config('app.currency')" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-slot:content>
        </x-card>
    @endforeach

    <form wire:submit.prevent="update">
        <x-modal-dialog wire:model.defer="isEditingShipment">
            <x-slot:title>
                {{ __('Editar rastreio') }}
            </x-slot:title>
            <x-slot:content>
                <div class="mt-2 grid grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="shipping_carrier" value="{{ __('Transportadora') }}" />
                        <x-select wire:model="shipmentBeingUpdated.shipping_carrier" id="shipping_carrier" class="mt-1 block w-full !h-10 rounded-xl sm:text-sm">
                            @foreach($shippingCarriers as $shippingCarrier)
                                <option value="{{ $shippingCarrier->value }}">{{ $shippingCarrier->label() }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="shipmentBeingUpdated.shipping_carrier" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="tracking_number" :value="__('Código de Rastreio')" />
                        <x-input wire:model.defer="shipmentBeingUpdated.tracking_number" type="text" id="tracking_number" class="mt-1 block w-full rounded-xl sm:text-sm" />
                        <x-input-error for="shipmentBeingUpdated.tracking_number" class="mt-2" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="tracking_url" :value="__('URL de rastreio')" />
                        <x-input wire:model.defer="shipmentBeingUpdated.tracking_url" type="text" id="tracking_url" class="mt-1 block w-full rounded-xl sm:text-sm" />
                        <x-input-error for="shipmentBeingUpdated.tracking_url" class="mt-2" />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button type="submit" class="btn btn-primary w-full sm:ml-3 sm:w-auto">
                    {{ __('Salvar') }}
                </button>
                <button wire:click="$set('isEditingShipment', false)" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>
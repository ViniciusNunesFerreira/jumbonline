<div>
    <form x-data="{ dirty: new Set() }" x-on:variant-inventory-updated.window="dirty.clear()" wire:submit.prevent="save">
        <x-card class="relative overflow-hidden">
            <x-slot:header>
                <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                    <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                        {{ __('Controle de Estoque') }}
                    </h3>
                    <div x-show="dirty.size >= 1" class="flex-shrink-0">
                        <button type="submit" class="btn btn-link">{{ __('Salvar') }}</button>
                    </div>
                </div>
            </x-slot:header>
            <x-slot:content>
                <fieldset wire:target="save" wire:loading.delay.attr="disabled" class="grid grid-cols-2 gap-6">
                    <div class="col-span-2 sm:col-span-1">
                        <x-input-label :value="__('Estoque atual')" />
                        <div class="mt-1 flex items-center gap-3">
                            <span class="text-lg font-bold text-primary dark:text-white">
                                {{ $variant->stock_value }}
                            </span>
                            @if($variant->is_low_stock)
                                <x-badge type="danger" size="xs">{{ __('Estoque baixo') }}</x-badge>
                            @endif
                            <button wire:click="openAdjustForm" type="button" class="btn btn-default btn-xs !rounded-xl ml-auto">
                                {{ __('Ajustar estoque') }}
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                            {{ __('A quantidade só muda por aqui — precisa de um motivo e fica registrada no histórico.') }}
                        </p>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <x-input-label for="low_stock_threshold" :value="__('Limite de estoque baixo (opcional)')" />
                        <x-input
                            x-on:change="$nextTick(() => $el.value !== '{{ $variant->getOriginal('low_stock_threshold') }}' ? dirty.add('low_stock_threshold') : dirty.delete('low_stock_threshold'))"
                            wire:model.defer="variant.low_stock_threshold"
                            type="number"
                            id="low_stock_threshold"
                            class="mt-1 block w-full rounded-xl sm:text-sm"
                            placeholder="{{ __('Usa o padrão geral se vazio') }}"
                        />
                        <x-input-error class="mt-2" for="variant.low_stock_threshold" />
                    </div>

                    <div class="col-span-1">
                        <x-input-label for="weight" :value="__('Peso')" />
                        <div class="mt-1 relative">
                            <x-input
                                x-on:change="$nextTick(() => $el.value !== '{{ $variant->getOriginal('weight_value') }}' ? dirty.add('weight_value') : dirty.delete('weight_value'))"
                                wire:model.defer="variant.weight_value"
                                type="number"
                                id="weight"
                                class="block w-full rounded-xl sm:text-sm no-spinners"
                                step="any"
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <label for="weight_unit" class="sr-only">{{ __('Unid do Peso') }}</label>
                                <select
                                    x-on:change="$nextTick(() => $el.value !== '{{ $variant->getOriginal('weight_unit') }}' ? dirty.add('weight_unit') : dirty.delete('weight_unit'))"
                                    wire:model.defer="variant.weight_unit"
                                    id="weight_unit"
                                    name="weight_unit"
                                    class="h-full py-0 pl-2 pr-7 border border-transparent bg-transparent text-slate-500 sm:text-sm rounded-xl focus:border-accent-500 focus:ring-accent-500"
                                >
                                    <option value="lb">lb</option>
                                    <option value="oz">oz</option>
                                    <option value="kg">kg</option>
                                    <option value="g">g</option>
                                </select>
                            </div>
                        </div>
                        <x-input-error for="variant.weight_value" class="mt-2" />
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <x-input-label for="sku" :value="__('SKU (COD. VARIAÇÃO)')" />
                        <x-input
                            x-on:change="$nextTick(() => $el.value !== '{{ $variant->getOriginal('sku') }}' ? dirty.add('sku') : dirty.delete('sku'))"
                            wire:model.defer="variant.sku"
                            type="text"
                            id="sku"
                            class="mt-1 block w-full rounded-xl sm:text-sm"
                        />
                        <x-input-error for="variant.sku" class="mt-2" />
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <x-input-label for="barcode" :value="__('COD. de Barras (ISBN, UPC, GTIN, etc.)')" />
                        <x-input
                            x-on:change="$nextTick(() => $el.value !== '{{ $variant->getOriginal('barcode') }}' ? dirty.add('barcode') : dirty.delete('barcode'))"
                            wire:model.defer="variant.barcode"
                            type="text"
                            id="barcode"
                            class="mt-1 block w-full rounded-xl sm:text-sm"
                        />
                        <x-input-error for="variant.barcode" class="mt-2" />
                    </div>
                </fieldset>
            </x-slot:content>
        </x-card>
    </form>

    @if($recentMovements->isNotEmpty())
        <x-card class="mt-5 overflow-hidden">
            <x-slot:header>
                <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Histórico de movimentação') }}</h3>
            </x-slot:header>
            <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                <ul class="divide-y divide-slate-100 dark:divide-white/5">
                    @foreach($recentMovements as $movement)
                        <li class="flex items-center justify-between px-4 py-3 sm:px-6">
                            <div>
                                <x-badge :type="$movement->type->badgeType()" size="xs">{{ $movement->type->label() }}</x-badge>
                                <span class="ml-2 text-sm text-slate-600 dark:text-slate-300">{{ $movement->reason }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-medium text-primary dark:text-slate-300">{{ $movement->quantity }} un.</span>
                                <span class="block text-xs text-slate-400">
                                    {{ $movement->employee?->name ?? __('Sistema') }} · {{ $movement->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </x-slot:content>
        </x-card>
    @endif

    <x-modal-dialog wire:model.defer="showAdjustForm">
        <x-slot:title>{{ __('Ajustar estoque') }}</x-slot:title>
        <x-slot:content>
            <div class="space-y-4">
                <div>
                    <x-input-label for="newStockValue" :value="__('Nova quantidade em estoque')" />
                    <x-input wire:model.defer="newStockValue" type="number" id="newStockValue" class="mt-1 block w-full rounded-xl" />
                    <x-input-error for="newStockValue" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="adjustReason" :value="__('Motivo do ajuste')" />
                    <x-textarea wire:model.defer="adjustReason" id="adjustReason" rows="3" class="mt-1 block w-full rounded-xl sm:text-sm" placeholder="{{ __('Ex: contagem física, avaria, recebimento de fornecedor...') }}" />
                    <x-input-error for="adjustReason" class="mt-2" />
                </div>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button wire:click="adjustStock" wire:loading.attr="disabled" wire:target="adjustStock" type="button" class="btn btn-primary w-full sm:ml-3 sm:w-auto">
                {{ __('Confirmar ajuste') }}
            </button>
            <button x-on:click="show = false" type="button" class="btn btn-default mt-3 w-full sm:mt-0 sm:w-auto">
                {{ __('Cancelar') }}
            </button>
        </x-slot:footer>
    </x-modal-dialog>
</div>
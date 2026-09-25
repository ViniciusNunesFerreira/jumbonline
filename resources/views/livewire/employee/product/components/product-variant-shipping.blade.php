<div>
    <form
        x-data="{ shippingType: @entangle('variant.shipping_type').defer, dirty: new Set() }"
        x-init="$watch('shippingType', (value) => dirty.add('shipping_type'))"
        x-on:variant-attachment-uploaded.window="dirty.add('attachment')"
        x-on:variant-attachment-deleted.window="dirty.delete('attachment')"
        x-on:variant-shipping-updated.window="dirty.clear()"
        wire:submit.prevent="save"
    >
        <x-card>
            <x-slot:header>
                <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                    <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                        {{ __('Envio') }}
                    </h3>
                    <div x-show="dirty.size >= 1" class="flex-shrink-0">
                        <button type="submit" class="btn btn-link">{{ __('Salvar') }}</button>
                    </div>
                </div>
            </x-slot:header>
            <x-slot:content>
                <div class="space-y-4">
                    <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white divide-y divide-slate-100 shadow-sm dark:border-white/10 dark:bg-slate-900 dark:divide-white/5">
                        <div class="px-6 py-4" :class="{ 'bg-slate-50 dark:bg-white/5': shippingType === 'physical' }">
                            <label class="cursor-pointer flex items-center space-x-2">
                                <x-input x-model="shippingType" type="radio" name="shipping-type" value="physical" class="!rounded-full !shadow-none text-accent-500 focus:ring-accent-500" />
                                <span class="font-medium text-sm text-primary dark:text-slate-200">
                                    {{ __('Produto Físico') }}
                                </span>
                            </label>
                        </div>
                        <div x-show="shippingType === 'physical'" class="px-6 py-4 grid grid-cols-3">
                            <div class="col-span-1">
                                <x-input-label for="weight" :value="__('Peso para envio')" />
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
                                        <label for="weight_unit" class="sr-only">{{ __('Unidade de peso') }}</label>
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
                            </div>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white divide-y divide-slate-100 shadow-sm dark:border-white/10 dark:bg-slate-900 dark:divide-white/5">
                        <div class="px-6 py-4" :class="{ 'bg-slate-50 dark:bg-white/5': shippingType === 'digital' }">
                            <label class="cursor-pointer flex items-center space-x-2">
                                <x-input x-model="shippingType" type="radio" name="shipping-type" value="digital" class="!rounded-full !shadow-none text-accent-500 focus:ring-accent-500" />
                                <span class="font-medium text-sm text-primary dark:text-slate-200">
                                    {{ __('Produto digital ou serviço') }}
                                </span>
                            </label>
                        </div>
                        <div x-show="shippingType === 'digital'" class="px-6 py-4">
                            @if($attachment || $variant->hasMedia('attachment'))
                                <div class="relative flex items-center space-x-3">
                                    <div class="min-w-0 flex flex-1 items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-primary dark:text-slate-200">
                                                {{ $attachment ? $attachment->getClientOriginalName() : $variant->getFirstMedia('attachment')->file_name }}
                                            </p>
                                            <p class="truncate text-sm text-slate-500 dark:text-slate-400">
                                                {{ $attachment ? \Spatie\MediaLibrary\Support\File::getHumanReadableSize($attachment->getSize()) : $variant->getFirstMedia('attachment')->human_readable_size }}
                                            </p>
                                        </div>
                                        <div class="ml-4 flex items-center space-x-2 flex-shrink-0">
                                            @if(!$attachment && $variant->hasMedia('attachment'))
                                                <button
                                                    wire:click="downloadAttachment"
                                                    type="button"
                                                    class="font-medium text-accent-500 hover:text-accent-600 dark:hover:text-accent-400"
                                                    data-tippy-content="{{ __('Baixar') }}"
                                                >
                                                    <span class="sr-only">{{ __('Baixar') }}</span>
                                                    <x-heroicon-m-arrow-down-tray class="w-5 h-5"/>
                                                </button>
                                            @endif

                                            <button
                                                x-on:click.prevent="if(confirm('{{ __('Tem certeza de que deseja excluir este anexo?') }}')) $wire.deleteAttachment();"
                                                type="button"
                                                class="font-medium text-red-500 hover:text-red-600 dark:hover:text-red-400"
                                                data-tippy-content="{{ __('Remover') }}"
                                            >
                                                <span class="sr-only">{{ __('Remover') }}</span>
                                                <x-heroicon-m-trash class="w-5 h-5"/>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <x-upload-widget wire:model.defer="attachment"/>
                            @endif
                        </div>
                    </div>
                </div>
            </x-slot:content>
        </x-card>
    </form>
</div>
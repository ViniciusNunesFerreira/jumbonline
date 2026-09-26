<div>
    <x-card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                    {{ __('Cliente') }}
                </h2>
                <button
                    wire:click.prevent="edit"
                    type="button"
                    class="btn btn-link"
                >
                    {{ __('Editar') }}
                </button>
            </div>
        </x-slot:header>
        <x-slot:content>
            <ul x-data class="space-y-2 sm:text-sm">
                <li class="flex items-center justify-between">
                    
                     <a   href="mailto:{{ $customer->email }}"
                        class="btn btn-link block truncate !justify-start"
                    >
                        {{ $customer->email }}
                    </a>
                    <button
                        x-on:click="$clipboard('{{ $customer->email }}').then(() => $dispatch('notify', '{{ __('Copiado para a área de transferência') }}'))"
                        type="button"
                        data-tippy-content="{{ __('Copiar') }}"
                    >
                        <x-heroicon-m-clipboard class="w-5 h-5 text-slate-400 hover:text-accent-500 dark:hover:text-accent-400" />
                    </button>
                </li>
                @if(optional($customer)->phone)
                    <li class="text-slate-600 dark:text-slate-300">{{ $customer->phone->formatInternational() }}</li>
                @endif
            </ul>
        </x-slot:content>
    </x-card>

    <form wire:submit.prevent="save">
        <x-modal-dialog wire:model.defer="isEditing" max-width="xl">
            <x-slot:title>
                {{ __('Editar cliente') }}
            </x-slot:title>
            <x-slot:content>
                <fieldset
                    x-data="{
                        phoneCountry: @entangle('phone_country').defer,
                        maskPhone(el, country) {
                            let v = el.value.replace(/\D/g, '');
                            if (country === 'BR') {
                                v = v.slice(0, 11);
                                if (v.length > 10) v = v.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
                                else if (v.length > 6) v = v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                                else if (v.length > 2) v = v.replace(/(\d{2})(\d{0,5})/, '($1) $2');
                                else if (v.length > 0) v = '(' + v;
                            } else {
                                v = v.slice(0, 15);
                            }
                            el.value = v;
                        },
                    }"
                    wire:target="save"
                    wire:loading.attr="disabled"
                    class="space-y-6"
                >
                    <div>
                        <x-input-label for="name" :value="__('Nome')" />
                        <x-input
                            wire:model.defer="customer.name"
                            id="name"
                            type="text"
                            class="block w-full mt-1 rounded-xl sm:text-sm"
                            required
                        />
                        <x-input-error for="customer.name" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-input
                            wire:model.defer="customer.email"
                            id="email"
                            type="email"
                            class="block w-full mt-1 rounded-xl sm:text-sm"
                            required
                        />
                        <x-input-error for="customer.email" class="mt-2" />
                    </div>
                    <div x-data="{ open: false }">
                        <x-input-label for="phone-number" value="{{ __('Telefone') }}" />
                        <div class="relative mt-1 flex rounded-xl shadow-sm ring-1 ring-inset ring-slate-300 focus-within:ring-2 focus-within:ring-accent-500 dark:ring-white/10">
                            <div class="relative shrink-0">
                                <button
                                    x-on:click="open = true"
                                    type="button"
                                    class="flex h-full items-center gap-1 rounded-l-xl border-r border-slate-200 bg-slate-50 px-3 text-left dark:border-white/10 dark:bg-white/5"
                                >
                                    <span class="text-xl leading-none">{{ $country->emoji }}</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400">+{{ $country->phonecode }}</span>
                                    <x-heroicon-m-chevron-up-down class="h-4 w-4 text-slate-400" />
                                </button>
                                <ul
                                    x-show="open"
                                    x-on:click.away="open = false"
                                    x-cloak
                                    class="absolute bottom-full left-0 z-10 mb-1.5 max-h-56 w-56 overflow-auto rounded-xl bg-white py-1 text-base shadow-lg ring-1 ring-slate-900/5 focus:outline-none sm:text-sm dark:bg-slate-900"
                                >
                                    @forelse($countries as $country)
                                        <li
                                            x-on:click="$wire.selectCountry('{{ $country->iso2 }}'); open = false;"
                                            class="relative cursor-default select-none py-2 pl-3 pr-9 text-slate-900 hover:bg-accent-500 hover:text-white dark:text-slate-200"
                                        >
                                            <div class="flex items-center">
                                                <span class="text-xl leading-none">{{ $country->emoji }}</span>
                                                <span class="font-normal ml-3 block truncate">{{ $country->name }}</span>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-slate-900 dark:text-slate-200">
                                            {{ __('Sem países') }}
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                            <input
                                wire:model.defer="phoneNumber"
                                x-on:input="maskPhone($el, phoneCountry)"
                                type="tel"
                                inputmode="numeric"
                                id="phone-number"
                                class="block w-full border-0 bg-transparent rounded-r-xl focus:ring-0 sm:text-sm"
                            />
                        </div>
                        <x-input-error for="phone" class="mt-2" />
                    </div>
                </fieldset>
            </x-slot:content>
            <x-slot:footer>
                <button
                    wire:target="save"
                    wire:loading.attr="disabled"
                    type="submit"
                    class="btn btn-primary w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Salvar') }}
                </button>
                <button
                    wire:click="$set('isEditing', false)"
                    wire:target="save"
                    wire:loading.attr="disabled"
                    type="button"
                    class="btn btn-invisible mt-3 w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>
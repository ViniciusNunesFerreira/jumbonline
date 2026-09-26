<div>
    <x-slot:title>
        {{ __('Novo Cliente') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex flex-1 items-center gap-2.5">
                
                <a    href="{{ route('employee.customers.list') }}"
                    class="btn btn-default btn-xs !rounded-xl"
                >
                    <x-heroicon-m-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Novo Cliente') }}
                </h1>
            </div>
        </div>

        <div class="mt-6">
            <form
                x-data="{
                    sameAddressPhone: @entangle('sameAddressPhone'),
                    customerCountry: @entangle('customer_phone_country').defer,
                    addressCountry: @entangle('address_phone_country').defer,
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
                    maskCep(el) {
                        let v = el.value.replace(/\D/g, '').slice(0, 8);
                        v = v.replace(/(\d{5})(\d{0,3})/, '$1-$2').replace(/-$/, '');
                        el.value = v;
                    },
                }"
                wire:submit.prevent="save"
                class="space-y-6"
            >
                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                            {{ __('Dados do cliente') }}
                        </h3>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="grid grid-cols-2 gap-5">
                            <div class="col-span-2">
                                <x-input-label for="full-name" value="{{ __('Nome completo') }}" />
                                <x-input wire:model.defer="customer.name" type="text" id="full-name" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="customer.name" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <x-input-label for="email-address" value="{{ __('Email') }}" />
                                <x-input wire:model.defer="customer.email" type="text" id="email-address" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="customer.email" class="mt-2" />
                            </div>

                            <div x-data="{ show: false }">
                                <x-input-label for="password" value="{{ __('Senha') }}" />
                                <div class="relative mt-1">
                                    <x-input wire:model.defer="customer_password" :type="'password'" x-bind:type="show ? 'text' : 'password'" id="password" class="block w-full rounded-xl pr-10 sm:text-sm" />
                                    <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                        <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                        <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                    </button>
                                </div>
                                <x-input-error for="customer_password" class="mt-2" />
                            </div>

                            <div x-data="{ show: false }">
                                <x-input-label for="password-confirmation" value="{{ __('Confirmar senha') }}" />
                                <div class="relative mt-1">
                                    <x-input wire:model.defer="customer_password_confirmation" :type="'password'" x-bind:type="show ? 'text' : 'password'" id="password-confirmation" class="block w-full rounded-xl pr-10 sm:text-sm" />
                                    <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                        <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                        <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                    </button>
                                </div>
                                <x-input-error for="customer_password_confirmation" class="mt-2" />
                            </div>

                            <div x-data="{ open: false }" class="col-span-2">
                                <x-input-label for="phone-number" value="{{ __('Telefone') }}" />
                                <div class="relative mt-1 flex rounded-xl shadow-sm ring-1 ring-inset ring-slate-300 focus-within:ring-2 focus-within:ring-accent-500 dark:ring-white/10">
                                    <div class="relative shrink-0">
                                        <button
                                            x-on:click="open = true"
                                            type="button"
                                            class="flex h-full items-center gap-1 rounded-l-xl border-r border-slate-200 bg-slate-50 px-3 text-left dark:border-white/10 dark:bg-white/5"
                                        >
                                            <span class="text-xl leading-none">{{ $customer_country->emoji }}</span>
                                            <span class="text-sm text-slate-500 dark:text-slate-400">+{{ $customer_country->phonecode }}</span>
                                            <x-heroicon-m-chevron-up-down class="h-4 w-4 text-slate-400" />
                                        </button>
                                        <ul
                                            x-show="open"
                                            x-on:click.away="open = false"
                                            x-cloak
                                            class="absolute bottom-full left-0 z-10 mb-1.5 max-h-56 w-56 overflow-auto rounded-xl bg-white py-1 text-base shadow-lg ring-1 ring-slate-900/5 focus:outline-none sm:text-sm dark:bg-slate-900"
                                        >
                                            @foreach($countries as $country)
                                                <li
                                                    x-on:click="$wire.selectCustomerCountry('{{ $country->iso2 }}'); open = false;"
                                                    class="relative cursor-default select-none py-2 pl-3 pr-9 text-slate-900 hover:bg-accent-500 hover:text-white dark:text-slate-200"
                                                >
                                                    <div class="flex items-center">
                                                        <span class="text-xl leading-none">{{ $country->emoji }}</span>
                                                        <span class="font-normal ml-3 block truncate">{{ $country->name }}</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <input
                                        wire:model.defer="customer_phone_number"
                                        x-on:input="maskPhone($el, customerCountry)"
                                        type="tel"
                                        inputmode="numeric"
                                        id="phone-number"
                                        class="block w-full border-0 bg-transparent rounded-r-xl focus:ring-0 sm:text-sm"
                                    />
                                </div>
                                <x-input-error for="customer_phone" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                            {{ __('Endereço principal') }}
                        </h3>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="grid grid-cols-2 gap-5">
                            <div class="col-span-2">
                                <x-input-label for="address-name" :value="__('Nome do destinatário')" />
                                <x-input wire:model.defer="address.name" type="text" id="address-name" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="address.name" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <x-input-label for="addressLine1" :value="__('Endereço')" />
                                <x-input wire:model.defer="address.address_line_1" type="text" id="addressLine1" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="address.address_line_1" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <x-input-label for="addressLine2" :value="__('Complemento (apto, bloco, etc.)')" />
                                <x-input wire:model.defer="address.address_line_2" type="text" id="addressLine2" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="address.address_line_2" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="city" :value="__('Cidade')" />
                                <x-input wire:model.defer="address.city" type="text" id="city" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="address.city" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="state" :value="__('Estado')" />
                                <x-input wire:model.defer="address.state" type="text" id="state" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="address.state" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="postcode" :value="__('CEP')" />
                                <x-input wire:model.defer="address.postcode" x-on:input="maskCep($el)" type="text" inputmode="numeric" maxlength="9" id="postcode" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="address.postcode" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="country" :value="__('País')" />
                                <x-select wire:model="address.country_id" id="country" class="mt-1 !h-10 block w-full rounded-xl sm:text-sm">
                                    <option value="">{{ __('Selecione uma opção') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="address.country" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 border-t border-slate-100 pt-5 dark:border-white/5">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <x-input x-model="sameAddressPhone" wire:model="sameAddressPhone" type="checkbox" class="!rounded !shadow-none text-accent-500 focus:ring-accent-500" />
                                <span class="text-sm text-slate-600 dark:text-slate-300">{{ __('Usar o mesmo telefone do cliente pra este endereço') }}</span>
                            </label>

                            <div x-show="!sameAddressPhone" x-cloak class="mt-4" x-data="{ open: false }">
                                <x-input-label for="address-phone-number" value="{{ __('Telefone do endereço') }}" />
                                <div class="relative mt-1 flex rounded-xl shadow-sm ring-1 ring-inset ring-slate-300 focus-within:ring-2 focus-within:ring-accent-500 dark:ring-white/10">
                                    <div class="relative shrink-0">
                                        <button
                                            x-on:click="open = true"
                                            type="button"
                                            class="flex h-full items-center gap-1 rounded-l-xl border-r border-slate-200 bg-slate-50 px-3 text-left dark:border-white/10 dark:bg-white/5"
                                        >
                                            <span class="text-xl leading-none">{{ $address_country->emoji }}</span>
                                            <span class="text-sm text-slate-500 dark:text-slate-400">+{{ $address_country->phonecode }}</span>
                                            <x-heroicon-m-chevron-up-down class="h-4 w-4 text-slate-400" />
                                        </button>
                                        <ul
                                            x-show="open"
                                            x-on:click.away="open = false"
                                            x-cloak
                                            class="absolute bottom-full left-0 z-10 mb-1.5 max-h-56 w-56 overflow-auto rounded-xl bg-white py-1 text-base shadow-lg ring-1 ring-slate-900/5 focus:outline-none sm:text-sm dark:bg-slate-900"
                                        >
                                            @foreach($countries as $country)
                                                <li
                                                    x-on:click="$wire.selectAddressCountry('{{ $country->iso2 }}'); open = false;"
                                                    class="relative cursor-default select-none py-2 pl-3 pr-9 text-slate-900 hover:bg-accent-500 hover:text-white dark:text-slate-200"
                                                >
                                                    <div class="flex items-center">
                                                        <span class="text-xl leading-none">{{ $country->emoji }}</span>
                                                        <span class="font-normal ml-3 block truncate">{{ $country->name }}</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <input
                                        wire:model.defer="address_phone_number"
                                        x-on:input="maskPhone($el, addressCountry)"
                                        type="tel"
                                        inputmode="numeric"
                                        id="address-phone-number"
                                        class="block w-full border-0 bg-transparent rounded-r-xl focus:ring-0 sm:text-sm"
                                    />
                                </div>
                                <x-input-error for="address_phone" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                            {{ __('Anotações') }}
                        </h3>
                    </x-slot:header>
                    <x-slot:content>
                        <x-textarea wire:model.defer="customer.notes" id="notes" rows="3" class="block w-full rounded-xl sm:text-sm" :placeholder="__('Alguma observação sobre este cliente...')" />
                        <x-input-error for="customer.notes" class="mt-2" />
                    </x-slot:content>
                </x-card>

                <div class="flex justify-end">
                    <a href="{{ route('employee.customers.list') }}" class="btn btn-invisible">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" class="ml-3 btn btn-primary">
                        {{ __('Salvar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
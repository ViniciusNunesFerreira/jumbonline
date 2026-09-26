<div>
    <x-slot:title>
        {{ __('Configurações Gerais') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 xl:flex xl:gap-x-10 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="xl:flex-auto">
            <div class="sm:flex sm:items-center sm:justify-between mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Configurações Gerais') }}
                </h1>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Dados da loja') }}</h3>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <x-input-label for="storeNameInput" :value="__('Nome da loja')" />
                                <x-input wire:model.defer="state.store_name" type="text" id="storeNameInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="state.store_name" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="contactEmailInput" :value="__('E-mail de contato')" />
                                <x-input wire:model.defer="state.contact_email" type="text" id="contactEmailInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="state.contact_email" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="contactPhoneInput" :value="__('Telefone de contato')" />
                                <x-input wire:model.defer="state.contact_phone" type="text" id="contactPhoneInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="state.contact_phone" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Banner de cookies') }}</h3>
                    </x-slot:header>
                    <x-slot:content>
                        <div x-data="{ on: @entangle('state.cookie_consent_enabled').defer }">
                            <div class="flex items-center">
                                <button
                                    x-on:click="on = !on"
                                    x-ref="switch"
                                    type="button"
                                    role="switch"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                                    :class="{ 'bg-accent-500': on, 'bg-slate-200 dark:bg-slate-700': !(on) }"
                                    :aria-checked="on.toString()"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="{ 'translate-x-5': on, 'translate-x-0': !(on) }"
                                    ></span>
                                </button>
                                <x-input-label x-on:click="on = !on; $refs.switch.focus()" :value="__('Banner ativo')" class="ml-3" />
                            </div>
                            <x-input-error for="state.cookie_consent_enabled" class="mt-2" />
                        </div>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <x-input-label for="cookieConsentMessageInput" :value="__('Mensagem')" />
                                <x-textarea wire:model.defer="state.cookie_consent_message" id="cookieConsentMessageInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="state.cookie_consent_message" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="cookieConsentAgreeInput" :value="__('Texto do botão de aceite')" />
                                <x-input wire:model.defer="state.cookie_consent_agree" type="text" id="cookieConsentAgreeInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="state.cookie_consent_agree" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="cookieConsentRejectInput" :value="__('Texto do botão de recusa')" />
                                <x-input wire:model.defer="state.cookie_consent_reject" type="text" id="cookieConsentRejectInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="state.cookie_consent_reject" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Salvar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
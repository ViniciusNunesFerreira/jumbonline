<div>
    <x-slot:title>
        {{ __('Pagamentos') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 xl:flex xl:gap-x-10 xl:px-8">
        @include('layouts.employee-settings-navigation')

        <div class="xl:flex-auto">
            <div class="sm:flex sm:items-center sm:justify-between mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Pagamentos') }}
                </h1>
            </div>

            <form wire:submit.prevent="save">
                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ $mercadopago->name }}</h3>
                    </x-slot:header>
                    <x-slot:content>
                        <div x-data="{ on: @entangle('mercadopago_state.is_enabled').defer }">
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
                                <x-input-label x-on:click="on = !on; $refs.switch.focus()" :value="__('Ativo')" class="ml-3" />
                            </div>
                            <x-input-error for="mercadopago_state.is_enabled" class="mt-2" />
                        </div>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="displayNameInput" :value="__('Nome')" />
                                <x-input wire:model.defer="mercadopago_state.display_name" type="text" id="displayNameInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="mercadopago_state.display_name" class="mt-2" />
                            </div>
                            <div class="sm:col-span-2">
                                <x-input-label for="descriptionInput" :value="__('Informações adicionais')" />
                                <x-textarea wire:model.defer="mercadopago_state.description" id="descriptionInput" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <p class="mt-1 text-xs text-slate-400">{{ __('Exibido ao cliente na tela de escolha do método de pagamento.') }}</p>
                                <x-input-error for="mercadopago_state.description" class="mt-2" />
                            </div>
                            <div x-data="{ show: false }">
                                <x-input-label for="publicKeyInput" :value="__('Chave pública')" />
                                <div class="relative mt-1">
                                    <x-input
                                        wire:model.defer="mercadopago_state.meta.public_key"
                                        :type="'password'"
                                        x-bind:type="show ? 'text' : 'password'"
                                        id="publicKeyInput"
                                        class="block w-full rounded-xl pr-10 font-mono text-xs sm:text-sm"
                                        placeholder="pk_..."
                                    />
                                    <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                        <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                        <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                    </button>
                                </div>
                                <x-input-error for="mercadopago_state.meta.public_key" class="mt-2" />
                            </div>
                            <div x-data="{ show: false }">
                                <x-input-label for="accessTokenInput" :value="__('Token de acesso')" />
                                <div class="relative mt-1">
                                    <x-input
                                        wire:model.defer="mercadopago_state.meta.access_token"
                                        :type="'password'"
                                        x-bind:type="show ? 'text' : 'password'"
                                        id="accessTokenInput"
                                        class="block w-full rounded-xl pr-10 font-mono text-xs sm:text-sm"
                                    />
                                    <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                        <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                        <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                    </button>
                                </div>
                                <x-input-error for="mercadopago_state.meta.access_token" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                    <x-slot:footer>
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Salvar') }}
                            </button>
                        </div>
                    </x-slot:footer>
                </x-card>
            </form>
        </div>
    </div>
</div>
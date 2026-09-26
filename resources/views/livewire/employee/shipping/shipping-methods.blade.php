<div>
    <x-slot:title>
        {{ __('Integração Correios') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                {{ __('Integração Correios') }}
            </h1>
        </div>

        <div class="mt-6 max-w-3xl">
            <x-card>
                <form wire:submit.prevent="save">
                    <x-slot:content>
                        <div x-data="{ on: @entangle('state.is_enabled').defer }">
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
                                <x-input-label
                                    x-on:click="on = !on; $refs.switch.focus()"
                                    :value="__('Integração ativa')"
                                    class="ml-3"
                                />
                            </div>
                            <x-input-error for="state.is_enabled" class="mt-2" />
                        </div>

                        <div class="mt-6">
                            <x-input-label for="displayNameInput" :value="__('Nome')" />
                            <x-input
                                wire:model.defer="state.name"
                                type="text"
                                id="displayNameInput"
                                class="mt-1 block w-full rounded-xl sm:text-sm"
                            />
                            <x-input-error for="state.name" class="mt-2" />
                        </div>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div x-data="{ show: false }">
                                <x-input-label for="userKeyInput" :value="__('User key')" />
                                <div class="relative mt-1">
                                    <x-input
                                        wire:model.defer="state.credentials.user_key"
                                        :type="'password'"
                                        x-bind:type="show ? 'text' : 'password'"
                                        id="userKeyInput"
                                        class="block w-full rounded-xl pr-10 font-mono text-xs sm:text-sm"
                                        placeholder="pk_..."
                                    />
                                    <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                        <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                        <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                    </button>
                                </div>
                                <x-input-error for="state.credentials.user_key" class="mt-2" />
                            </div>

                            <div x-data="{ show: false }">
                                <x-input-label for="accessKeyInput" :value="__('Access key')" />
                                <div class="relative mt-1">
                                    <x-input
                                        wire:model.defer="state.credentials.access_key"
                                        :type="'password'"
                                        x-bind:type="show ? 'text' : 'password'"
                                        id="accessKeyInput"
                                        class="block w-full rounded-xl pr-10 font-mono text-xs sm:text-sm"
                                    />
                                    <button type="button" x-on:click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-500">
                                        <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                                        <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                                    </button>
                                </div>
                                <x-input-error for="state.credentials.access_key" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="contratoInput" :value="__('Número do contrato')" />
                                <x-input
                                    wire:model.defer="state.credentials.contrato"
                                    type="text"
                                    id="contratoInput"
                                    class="mt-1 block w-full rounded-xl sm:text-sm"
                                />
                                <x-input-error for="state.credentials.contrato" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="cartaoPostagemInput" :value="__('Número do cartão de postagem')" />
                                <x-input
                                    wire:model.defer="state.credentials.cartaopostagem"
                                    type="text"
                                    id="cartaoPostagemInput"
                                    class="mt-1 block w-full rounded-xl sm:text-sm"
                                />
                                <x-input-error for="state.credentials.cartaopostagem" class="mt-2" />
                            </div>
                        </div>

                        <p class="mt-4 text-xs text-slate-400">
                            {{ __('Essas credenciais são as mesmas usadas na integração de pré-postagem e rastreamento em /admin/correios.') }}
                        </p>
                    </x-slot:content>
                    <x-slot:footer>
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Salvar') }}
                            </button>
                        </div>
                    </x-slot:footer>
                </form>
            </x-card>
        </div>
    </div>
</div>
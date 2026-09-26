<div>
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
                            :value="__('Frete grátis ativo')"
                            class="ml-3"
                        />
                    </div>
                    <x-input-error for="state.is_enabled" class="mt-2" />
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="displayNameInput" :value="__('Nome')" />
                        <x-input
                            wire:model.defer="state.name"
                            type="text"
                            id="displayNameInput"
                            class="mt-1 block w-full rounded-xl sm:text-sm"
                        />
                        <x-input-error for="state.name" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="discount" :value="__('Valor mínimo da compra')" />
                        <x-input-money
                            wire:model.defer="state.os_value"
                            id="discount"
                            class="block w-full rounded-xl sm:text-sm"
                            wrapper-classes="mt-1"
                        />
                        <x-input-error for="state.os_value" class="mt-2" />
                        <p class="mt-1 text-xs text-slate-400">{{ __('Pedidos com subtotal a partir deste valor têm frete grátis automaticamente.') }}</p>
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
        </form>
    </x-card>
</div>
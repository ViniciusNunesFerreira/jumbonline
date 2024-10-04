<div>
    <x-slot:title>
        {{ __('Promoção no Frete') }}
    </x-slot:title>

    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:flex lg:gap-x-16 lg:px-8">

        <form wire:submit.prevent="save" class="py-6 lg:flex-auto lg:py-0">
            
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12 dark:border-white/10">

                    <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-slate-200">
                        Cadastrar Promoção no Frete
                    </h2>

                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div x-data="{ on: @entangle('state.is_enabled').defer }" class="col-span-full">
                            
                            <div class="flex items-center">
                                <button
                                    x-on:click="on = !on"
                                    x-ref="switch"
                                    type="button"
                                    role="switch"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                                    :class="{ 'bg-sky-500': on, 'bg-gray-200 dark:bg-gray-700': !(on) }"
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
                                    :value="__('Ativo')"
                                    class="ml-3"
                                />
                            </div>
                            <x-input-error
                                for="state.is_enabled"
                                class="mt-2"
                            />
                    
                        </div>

                        <div class="sm:col-span-3">
                            <x-input-label
                                for="displayNameInput"
                                :value="__('Nome')"
                            />
                            <div class="mt-2">
                                <x-input
                                    wire:model.defer="state.name"
                                    type="text"
                                    id="displayNameInput"
                                    class="block w-full sm:text-sm"
                                />
                                <x-input-error
                                    for="state.name"
                                    class="mt-2"
                                />
                            </div>
                        </div>

                

                        <div class="sm:col-span-3">

                            <x-input-label
                                for="discount"
                                :value="__('Valor da Compra')"
                            />

                            <div class="mt-2">

                                <x-input-money
                                    wire:model.defer="state.os_value"
                                    id="discount"
                                    class="mt-1 block w-full sm:text-sm"
                                />

                                <x-input-error
                                    for="state.os_value"
                                    class="mt-2"
                                />
                            </div>

                        </div>    

                    </div>

                </div>
            </div>
            
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button
                    type="button"
                    class="btn btn-default"
                >
                    {{ __('Cancelar') }}
                </button>
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    {{ __('Salvar') }}
                </button>
            </div>
            
        </form>

    </div>
</div>

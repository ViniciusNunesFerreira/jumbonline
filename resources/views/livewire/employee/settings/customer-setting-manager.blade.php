<div>
    <x-slot:title>{{ __('Configurações de clientes') }}</x-slot:title>

    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8" >

        @include('layouts.employee-settings-navigation')

        <div class="space-y-6 sm:px-6 lg:col-span-9 lg:px-0">
            <form wire:submit.prevent="save">
                <x-card>
                    <x-slot:header>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Cliente frequente') }}
                        </h3>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="max-w-sm">
                            <x-input-label for="threshold" :value="__('Nº mínimo de pedidos pagos')" />
                            <x-input wire:model.defer="state.frequent_customer_min_orders" type="number" min="1" max="100" id="threshold" class="mt-1 block w-full" />
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Clientes com esse número de pedidos pagos ou mais ganham o selo "Cliente frequente" na lista e no cadastro.') }}
                            </p>
                            <x-input-error for="state.frequent_customer_min_orders" class="mt-2" />
                        </div>
                    </x-slot:content>
                    <x-slot:footer>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </x-slot:footer>
                </x-card>
            </form>
        </div>

    </div>
</div>
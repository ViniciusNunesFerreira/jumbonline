<div>
    <x-slot:title>{{ __('Configurações de pedidos') }}</x-slot:title>

    <div class="px-4 mx-auto max-w-7xl sm:px-6 xl:flex xl:gap-x-16 xl:px-8" >

        @include('layouts.employee-settings-navigation')

        <div class="space-y-6 sm:px-6 lg:col-span-9 lg:px-0">
            <form wire:submit.prevent="save">
                <x-card>
                    <x-slot:header>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Alerta de pedido parado') }}
                        </h3>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="max-w-sm">
                            <x-input-label for="threshold" :value="__('Dias sem envio após o pagamento')" />
                            <x-input wire:model.defer="state.stalled_order_days_threshold" type="number" min="1" max="30" id="threshold" class="mt-1 block w-full" />
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Pedidos pagos que passarem desse número de dias sem nenhuma pré-postagem/envio criado serão sinalizados na lista de Pedidos.') }}
                            </p>
                            <x-input-error for="state.stalled_order_days_threshold" class="mt-2" />
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
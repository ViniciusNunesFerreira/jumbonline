<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Etiqueta para pedido - :id', ['id' => $order->id]) }}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="flex px-4 sm:px-6 lg:px-8">
        <div class="mr-2 flex-shrink-0">
            <a
                href="{{ route('employee.orders.detail', $order) }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
        </div>

        <div class="mt-0.5">
            <div class="sm:flex sm:items-center sm:space-x-3">
                <h1 class="text-2xl font-medium leading-none text-slate-900 dark:text-slate-100">
                    {{ __('Etiqueta para pedido: #:orderId', ['orderId' => $order->id]) }}
                </h1>
            </div>

            <div class="mt-2 flex items-center text-sm text-slate-500 dark:text-slate-400">
                <span>{{ $order->created_at->toDayDateTimeString() }}</span>
            </div>
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8 mt-5">
        <div class="grid grid-cols-1 gap-6">
            <div class="mt-6 col-span-3 xl:col-span-1 space-y-6 xl:mt-0">
                <x-card>
                    <x-slot:header>
                        <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap w-full">

                            <div class="ml-4 mt-2">
                                <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                                    Confira as informações da etiqueta
                                </h3>
                            </div>

                            <div class="ml-4 mt-2 flex-shrink-0">
                                <button
                                    wire:click.prevent="geraEtiqueta"
                                    class="btn btn-primary"
                                >
                                    {{ __('Gerar Etiquera') }}
                                </button>
                            </div>
                            
                        </div>
                    </x-slot:header>

                    <x-slot:content class="-mx-4 -mt-5 sm:-mx-6">
                        <div class="-mb-5 space-y-6">
                            <div class="relative overflow-auto grid md:grid-cols-2 gap-4">

                                <div class=" m-2 border p-2 grid grid-cols-2 gap-4">

                                    <div class="col-span-2 mb-4 ">Remetente</div>

                                    <div class="col-span-1">
                                        <x-input-label
                                            for="remetente.cep"
                                            value="{{ __('CEP') }}"
                                        />
                                        <x-input
                                            wire:model="state.remetente.endereco.cep"
                                            type="text"
                                            id="remetente.cep"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.remetente.endereco.cep"
                                            class="mt-2"
                                        />

                                    </div>


                                    <div class="col-span-2">
                                        <x-input-label
                                            for="remetente.nome"
                                            value="{{ __('Nome - (VISITANTE)') }}"
                                        />
                                        <x-input
                                            wire:model="state.remetente.nome"
                                            type="text"
                                            id="remetente.nome"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.remetente.nome"
                                            class="mt-2"
                                        />

                                    </div>

                                    <div class=" col-span-1">

                                        <x-input-label
                                            for="remetente.endereco.logradouro"
                                            value="{{ __('Logradouro') }}"
                                        />
                                        <x-input
                                            wire:model="state.remetente.endereco.logradouro"
                                            type="text"
                                            id="remetente.endereco.logradouro"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.remetente.endereco.lograroudo"
                                            class="mt-2"
                                        />

                                    </div>

                                    <div class=" col-span-1">

                                        <x-input-label
                                            for="remetente.endereco.numero"
                                            value="{{ __('Numero') }}"
                                        />
                                        <x-input
                                            wire:model="state.remetente.endereco.numero"
                                            type="text"
                                            id="remetente.endereco.numero"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.remetente.endereco.numero"
                                            class="mt-2"
                                        />

                                    </div>

                                    <div class=" col-span-2">

                                        <x-input-label
                                            for="remetente.endereco.bairro"
                                            value="{{ __('Bairro') }}"
                                        />
                                        <x-input
                                            wire:model="state.remetente.endereco.bairro"
                                            type="text"
                                            id="remetente.endereco.bairro"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.remetente.endereco.bairro"
                                            class="mt-2"
                                        />

                                    </div>

                                    <div class=" col-span-1">

                                        <x-input-label
                                            for="remetente.endereco.cidade"
                                            value="{{ __('Cidade') }}"
                                        />
                                        <x-input
                                            wire:model="state.remetente.endereco.cidade"
                                            type="text"
                                            id="remetente.endereco.cidade"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.remetente.endereco.cidade"
                                            class="mt-2"
                                        />

                                    </div>
                                    <div class=" col-span-1">

                                        <x-input-label
                                            for="remetente.endereco.uf"
                                            value="{{ __('Estado') }}"
                                        />
                                        <x-input
                                            wire:model="state.remetente.endereco.uf"
                                            type="text"
                                            id="remetente.endereco.uf"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.remetente.endereco.uf"
                                            class="mt-2"
                                        />

                                    </div>



                                </div>

                                <div class="m-2 border p-2 grid grid-cols-2 gap-4">

                                    <div class="col-span-2 mb-4 ">Destinatário</div>

                                    <div class="col-span-1">
                                        <x-input-label
                                            for="destinatario.cep"
                                            value="{{ __('CEP') }}"
                                        />
                                        <x-input
                                            wire:model="state.destinatario.endereco.cep"
                                            type="text"
                                            id="destinatario.cep"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.destinatario.endereco.cep"
                                            class="mt-2"
                                        />

                                    </div>

                                    <div class="col-span-2">
                                        <x-input-label
                                            for="destinatario.nome"
                                            value="{{ __('Nome - (DETENTO)') }}"
                                        />
                                        <x-input
                                            wire:model="state.destinatario.nome"
                                            type="text"
                                            id="destinatario.nome"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.destinatario.nome"
                                            class="mt-2"
                                        />

                                    </div>

                                    <div class="col-span-1">

                                        <x-input-label
                                            for="destinatario.endereco.logradouro"
                                            value="{{ __('Logradouro') }}"
                                        />
                                        <x-input
                                            wire:model="state.destinatario.endereco.logradouro"
                                            type="text"
                                            id="destinatario.endereco.logradouro"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.destinatario.endereco.lograroudo"
                                            class="mt-2"
                                        />

                                    </div>

                                    <div class=" col-span-1">

                                        <x-input-label
                                            for="destinatario.endereco.numero"
                                            value="{{ __('Numero') }}"
                                        />
                                        <x-input
                                            wire:model="state.destinatario.endereco.numero"
                                            type="text"
                                            id="destinatario.endereco.numero"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.destinatario.endereco.numero"
                                            class="mt-2"
                                        />

                                    </div>

                                    <div class=" col-span-2">

                                        <x-input-label
                                            for="destinatario.endereco.bairro"
                                            value="{{ __('Bairro') }}"
                                        />
                                        <x-input
                                            wire:model="state.destinatario.endereco.bairro"
                                            type="text"
                                            id="destinatario.endereco.bairro"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.destinatario.endereco.bairro"
                                            class="mt-2"
                                        />

                                    </div>

                                    <div class=" col-span-1">

                                        <x-input-label
                                            for="destinatario.endereco.cidade"
                                            value="{{ __('Cidade') }}"
                                        />
                                        <x-input
                                            wire:model="state.destinatario.endereco.cidade"
                                            type="text"
                                            id="destinatario.endereco.cidade"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.destinatario.endereco.cidade"
                                            class="mt-2"
                                        />

                                    </div>
                                    <div class=" col-span-1">

                                        <x-input-label
                                            for="destinatario.endereco.uf"
                                            value="{{ __('Estado') }}"
                                        />
                                        <x-input
                                            wire:model="state.destinatario.endereco.uf"
                                            type="text"
                                            id="remetente.destinatario.uf"
                                            class="mt-1 block w-full sm:text-sm"
                                        />
                                            
                                        <x-input-error
                                            for="state.destinatario.endereco.uf"
                                            class="mt-2"
                                        />

                                    </div>
                                
                                </div>

                                <div class="col-span-2 m-2 p-2">
                                        <x-input-label
                                            for="destinatario.obs"
                                            value="{{ __('Observação (máx 60 caracteres)') }}"
                                        />
                                        <x-input
                                            wire:model="state.destinatario.obs"
                                            type="text"
                                            id="destinatario.obs"
                                            class="mt-1 block w-full sm:text-sm"
                                            minlength="4" maxlength="60"
                                        />

                                        
                                            
                                        <x-input-error
                                            for="state.destinatario.obs"
                                            class="mt-2"
                                        />
                                </div>

                            </div>
                        </div>
                    </x-slot:content>
                </x-card>
            </div>
        </div>
    </div>

</div>

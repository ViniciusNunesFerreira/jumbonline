<div>
    <!-- Meta title & description -->
    <x-slot:title>
        Nova Unidade Prisional
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex flex-1 items-center space-x-2">
            <a
                href="{{ route('employee.prison.list') }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-medium leading-6 text-slate-900 dark:text-slate-100">
                Nova Unidade Prisional
            </h1>
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <form
            class="space-y-8"
            wire:submit.prevent="save"
        >
        <x-card>
            <x-slot:content>
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-slate-900 dark:text-slate-200">
                            Visão Geral da Unidade
                        </h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Informações Detalhadas sobre a Unidade Prisional
                        </p>
                    </div>

                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="mt-5 md:col-span-2 md:mt-0">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-6">
                                    <x-input-label
                                        for="name"
                                        value="Nome da Unidade"
                                    />
                                    <x-input
                                        wire:model.defer="prison.name"
                                        type="text"
                                        id="name"
                                        class="mt-1 block w-full sm:text-sm"
                                    />
                                    <x-input-error
                                        for="prison.name"
                                        class="mt-2"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-4">

                                    <x-input-label
                                        for="logradouro"
                                        value="Endereço"
                                    />    
                                    
                                    <x-input
                                        wire:model.defer="prison.logradouro"
                                        type="text"
                                        id="logradouro"
                                        class="mt-1 block w-full sm:text-sm"
                                    />

                                    <x-input-error
                                        for="prison.logradouro"
                                        class="mt-2"
                                    />

                                </div>

                                <div class="col-span-6 sm:col-span-2">
                                    <x-input-label
                                        for="numero"
                                        value="Número"
                                    />   
                                    
                                    <x-input
                                        wire:model.defer="prison.numero"
                                        type="text"
                                        id="numero"
                                        class="mt-1 block w-full sm:text-sm"
                                    />

                                    <x-input-error
                                        for="prison.numero"
                                        class="mt-2"
                                    />

                                </div>

                                <div class="col-span-6 sm:col-span-6">
                                    <x-input-label
                                        for="bairro"
                                        value="Bairro"
                                    />
                                    <x-input
                                         wire:model.defer="prison.bairro"
                                        type="text"
                                        id="bairro"
                                        class="mt-1 block w-full sm:text-sm"
                                    />
                                    <x-input-error
                                        for="prison.bairro"
                                        class="mt-2"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <x-input-label
                                        for="cidade"
                                        value="Cidade"
                                    />
                                    <x-input
                                         wire:model.defer="prison.cidade"
                                        type="text"
                                        id="cidade"
                                        class="mt-1 block w-full sm:text-sm"
                                    />
                                    <x-input-error
                                        for="prison.cidade"
                                        class="mt-2"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-2">
                                    <x-input-label
                                        for="uf"
                                        value="Estado"
                                    />
                                    <x-input
                                         wire:model.defer="prison.uf"
                                        type="text"
                                        id="uf"
                                        class="mt-1 block w-full sm:text-sm"
                                    />
                                    <x-input-error
                                        for="prison.uf"
                                        class="mt-2"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <x-input-label
                                        for="cep"
                                        value="CEP"
                                    />
                                    <x-input
                                         wire:model.defer="prison.cep"
                                        type="text"
                                        id="cep"
                                        class="mt-1 block w-full sm:text-sm"
                                    />
                                    <x-input-error
                                        for="prison.cep"
                                        class="mt-2"
                                    />
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <x-input-label
                                        for="phone"
                                        value="Telefone"
                                    />
                                    <x-input
                                         wire:model.defer="prison.phone"
                                        type="text"
                                        id="phone"
                                        class="mt-1 block w-full sm:text-sm"
                                    />
                                    <x-input-error
                                        for="prison.phone"
                                        class="mt-2"
                                    />
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </x-slot:content>
        </x-card>

        <x-card>
            <x-slot:content>
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-slate-900 dark:text-slate-200">
                            Categoria
                        </h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Categoria das Unidades Prisionais
                        </p>
                    </div>

                    <div class="md:col-span-2 mt-5 md:mt-0">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-6">
                                <x-input-label
                                    for="category_id"
                                    value="Categoria"
                                />
                                <x-select wire:model.defer="category_id"
                                    id="category_id"
                                    class="mt-1 block w-full sm:text-sm !h-11"
                                >
                                        <option disabled value="">Selecione uma categoria...</option>
                                        @forelse($categories as $cat)
                                            <option value="{{$cat->id}}">{{ $cat->name }}</option>
                                        @empty
                                            <option> Sem Categorias Cadastradas </option>
                                        @endforelse

                                </x-select>

                                <x-input-error
                                    for="category_id"
                                    class="mt-2"
                                />
                            </div>

                            
                            <div class="col-span-6 sm:col-span-4">

                                <button
                                    wire:click.prevent="addNewCategory"
                                    class="btn btn-primary"
                                >
                                    Nova Categoria
                                </button>

                            </div>
                        </div>
                    </div>
                </div> 
            </x-slot:content>
        </x-card>

        <div class="flex justify-end">
                <a
                    href="{{ route('employee.prison.list') }}"
                    class="btn btn-invisible"
                >
                    {{ __('Cancelar') }}
                </a>
                <button
                    type="submit"
                    class="ml-3 btn btn-primary"
                >
                    {{ __('Salvar') }}
                </button>
        </div>
    </form>
    </div>

    <form wire:submit.prevent="saveNewCategory">
        <x-modal-dialog wire:model="addingNewCategory">
            <x-slot:title>
                Adicionar Nova Categoria
            </x-slot:title>
            <x-slot:content>

                <div class="space-y-6">
                    <div>
                        <x-input-label
                            for="newCategoryNameInput"
                            :value="__('Nome')"
                        />
                        <x-input
                            wire:model.defer="newCategory.name"
                            type="text"
                            id="newCategoryNameInput"
                            class="block w-full mt-1 sm:text-sm"
                            placeholder="{{ __('Exemplo: CDP, CPP, Penit ...') }}"
                            autofocus
                        />
                        <x-input-error
                            for="newCategory.name"
                            class="mt-2"
                        />
                    </div>
                </div>

            </x-slot:content>
            <x-slot:footer>
                <div class="flex flex-shrink-0 justify-end">
                    <button
                        x-on:click="show = false"
                        type="button"
                        class="btn btn-invisible"
                    >
                        {{ __('Cancel') }}
                    </button>
                    <button
                        type="submit"
                        class="ml-4 btn btn-primary gap-x-2"
                    >
                        Salvar
                        <x-heroicon-o-arrow-small-right class="-mr-0.5 w-5 h-5" />
                    </button>
                </div>
            </x-slot:footer>
        </x-modal-dialog>
    </form>

</div>

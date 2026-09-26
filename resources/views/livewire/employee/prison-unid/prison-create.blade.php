<div>
    <x-slot:title>
        {{ __('Nova Unidade Prisional') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex flex-1 items-center gap-2.5">
                <a href="{{ route('employee.prison.list') }}" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Nova Unidade Prisional') }}
                </h1>
            </div>
        </div>

        <div class="mt-6">
            <form wire:submit.prevent="save" class="space-y-6">
                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Dados da unidade') }}</h3>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="grid grid-cols-6 gap-5">
                            <div class="col-span-6">
                                <x-input-label for="name" :value="__('Nome da Unidade')" />
                                <x-input wire:model.defer="prison.name" type="text" id="name" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="prison.name" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <x-input-label for="logradouro" :value="__('Endereço')" />
                                <x-input wire:model.defer="prison.logradouro" type="text" id="logradouro" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="prison.logradouro" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-2">
                                <x-input-label for="numero" :value="__('Número')" />
                                <x-input wire:model.defer="prison.numero" type="text" id="numero" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="prison.numero" class="mt-2" />
                            </div>
                            <div class="col-span-6">
                                <x-input-label for="bairro" :value="__('Bairro')" />
                                <x-input wire:model.defer="prison.bairro" type="text" id="bairro" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="prison.bairro" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-4">
                                <x-input-label for="cidade" :value="__('Cidade')" />
                                <x-input wire:model.defer="prison.cidade" type="text" id="cidade" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="prison.cidade" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-2">
                                <x-input-label for="uf" :value="__('Estado')" />
                                <x-input wire:model.defer="prison.uf" type="text" id="uf" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="prison.uf" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <x-input-label for="cep" :value="__('CEP')" />
                                <x-input wire:model.defer="prison.cep" type="text" id="cep" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="prison.cep" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <x-input-label for="phone" :value="__('Telefone')" />
                                <x-input wire:model.defer="prison.phone" type="text" id="phone" class="mt-1 block w-full rounded-xl sm:text-sm" />
                                <x-input-error for="prison.phone" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Categoria') }}</h3>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="grid grid-cols-6 gap-5 items-end">
                            <div class="col-span-6 sm:col-span-4">
                                <x-input-label for="category_id" :value="__('Categoria')" />
                                <x-select wire:model.defer="category_id" id="category_id" class="mt-1 !h-10 block w-full rounded-xl sm:text-sm">
                                    <option disabled value="">{{ __('Selecione uma categoria...') }}</option>
                                    @forelse($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @empty
                                        <option>{{ __('Sem categorias cadastradas') }}</option>
                                    @endforelse
                                </x-select>
                                <x-input-error for="category_id" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-2">
                                <button wire:click.prevent="addNewCategory" type="button" class="btn btn-default !rounded-xl w-full">
                                    {{ __('Nova Categoria') }}
                                </button>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <div class="flex justify-end">
                    <a href="{{ route('employee.prison.list') }}" class="btn btn-invisible">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" class="ml-3 btn btn-primary">
                        {{ __('Salvar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <form wire:submit.prevent="saveNewCategory">
        <x-modal-dialog wire:model="addingNewCategory">
            <x-slot:title>
                {{ __('Adicionar Nova Categoria') }}
            </x-slot:title>
            <x-slot:content>
                <x-input-label for="newCategoryNameInput" :value="__('Nome')" />
                <x-input
                    wire:model.defer="newCategory.name"
                    type="text"
                    id="newCategoryNameInput"
                    class="block w-full mt-1 rounded-xl sm:text-sm"
                    placeholder="{{ __('Exemplo: CDP, CPP, Penit ...') }}"
                    autofocus
                />
                <x-input-error for="newCategory.name" class="mt-2" />
            </x-slot:content>
            <x-slot:footer>
                <button type="submit" class="btn btn-primary w-full sm:ml-3 sm:w-auto">
                    {{ __('Salvar') }}
                </button>
                <button x-on:click="show = false" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>
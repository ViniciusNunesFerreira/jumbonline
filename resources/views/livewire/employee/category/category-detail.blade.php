<div>
    <x-slot:title>
        {!! $category->title !!}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex flex-1 items-center gap-2.5">
                
                <a href="{{ route('employee.categories.list') }}"
                    class="btn btn-default btn-xs !rounded-xl"
                >
                    <x-heroicon-m-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-primary truncate dark:text-white">
                    {{ $category->title }}
                </h1>
            </div>

            {{-- "Visualizar" removido temporariamente — não existe rota pública de categoria
                 no sistema (diferente de Grupos). Confirme com Vinicius antes de reativar. --}}
        </div>

        <div class="mt-6">
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-3 xl:col-span-2 space-y-6">
                    <livewire:employee.category.components.category-information :category="$category" />

                    <livewire:employee.category.components.category-product :category="$category" />

                    <livewire:employee.search-engine-information-form :model="$category" />
                </div>
                <div class="col-span-3 xl:col-span-1 space-y-6">
                    <livewire:employee.category.components.category-availability :category="$category" />

                    <livewire:employee.category.components.category-cover :category="$category" />

                    <button
                        wire:click="$set('confirmingCategoryDeletion', true)"
                        type="button"
                        class="btn btn-outline-warning hover:bg-warning-500 hover:text-white block w-full !rounded-xl sm:text-sm"
                    >
                        {{ __('Excluir Categoria') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-modal-alert wire:model.defer="confirmingCategoryDeletion">
        <x-slot:title>
            {{ __('Por favor, confirme sua ação!') }}
        </x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Tem certeza de que deseja excluir esta categoria? Essa ação não pode ser desfeita!') }}
            </p>
        </x-slot:content>
        <x-slot:footer>
            <button
                wire:click.prevent="delete"
                type="button"
                class="btn btn-warning text-white w-full sm:ml-3 sm:w-auto"
            >
                {{ __('Excluir') }}
            </button>
            <button
                x-on:click="show = false"
                type="button"
                class="mt-3 btn btn-primary w-full sm:mt-0 sm:w-auto"
            >
                {{ __('Cancelar') }}
            </button>
        </x-slot:footer>
    </x-modal-alert>
</div>
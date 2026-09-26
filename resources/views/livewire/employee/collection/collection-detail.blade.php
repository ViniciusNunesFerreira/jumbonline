<div>
    <x-slot:title>
        {!! $collection->title !!}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex flex-1 items-center gap-2.5">
                
                <a href="{{ route('employee.collections.list') }}"
                    class="btn btn-default btn-xs !rounded-xl"
                >
                    <x-heroicon-m-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-primary truncate dark:text-white">
                    {{ $collection->title }}
                </h1>
            </div>
            <div class="mt-4 flex sm:mt-0 sm:ml-4">
                
                <a  href="{{ route('guest.collections.detail', $collection) }}"
                    target="_blank"
                    class="btn btn-outline-primary w-full !rounded-xl"
                >
                    {{ __('Visualizar') }}
                </a>
            </div>
        </div>

        <div class="mt-6">
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-3 xl:col-span-2 space-y-6">
                    <livewire:employee.collection.components.collection-information :collection="$collection" />

                    <livewire:employee.collection.components.collection-product :collection="$collection" />

                    <livewire:employee.search-engine-information-form :model="$collection" />
                </div>
                <div class="col-span-3 xl:col-span-1 space-y-6">
                    <livewire:employee.collection.components.collection-availability :collection="$collection" />

                    <livewire:employee.collection.components.collection-cover :collection="$collection" />

                    <button
                        wire:click="$set('confirmingCollectionDeletion', true)"
                        type="button"
                        class="btn btn-outline-warning hover:bg-warning-500 hover:text-white block w-full !rounded-xl sm:text-sm"
                    >
                        {{ __('Deletar Grupo') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-modal-alert wire:model.defer="confirmingCollectionDeletion">
        <x-slot:title>
            {{ __('Por favor confirme sua ação!') }}
        </x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Tem certeza de que deseja excluir este grupo? Essa ação não pode ser desfeita!') }}
            </p>
        </x-slot:content>
        <x-slot:footer>
            <button
                wire:click.prevent="delete"
                type="button"
                class="btn btn-warning w-full sm:ml-3 sm:w-auto"
            >
                {{ __('Deletar') }}
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
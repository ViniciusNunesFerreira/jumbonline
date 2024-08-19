<div>
    <!-- Meta title & description -->
    <x-slot:title>
        Grupo de Produtos
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-medium text-slate-900 sm:truncate dark:text-slate-100">
                Grupos
            </h1>
        </div>
        @if($collections->count())
            <div class="mt-4 flex sm:mt-0 sm:ml-4">
                <button
                    wire:click.prevent="createNewCollection"
                    class="btn btn-primary block w-full order-0 sm:order-1 sm:ml-3"
                >
                    Criar novo Grupo
                </button>
            </div>
        @endif
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        @if(!$collections->count() && !$search)
            <x-card>
                <x-slot:content>
                    <div class="max-w-lg mx-auto text-center">
                        <x-heroicon-o-rectangle-stack class="mx-auto h-12 w-12 text-slate-400" />

                        <h3 class="mt-2 text-lg font-medium text-slate-900 dark:text-slate-200">
                            Parece que você ainda não tem nenhum Grupo de Produto criado
                        </h3>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('As coleções são uma ótima maneira de organizar seus produtos. Você pode criar uma coleção para um tipo específico de produto, como "Alimentos" ou "Vestuários".') }}
                        </p>

                        <div class="mt-6">
                            <button
                                wire:click.prevent="createNewCollection"
                                type="button"
                                class="btn btn-primary"
                            >
                                <x-heroicon-m-plus class="-ml-1 mr-2 h-5 w-5" />
                                {{ __('Novo Grupo') }}
                            </button>
                        </div>
                    </div>
                </x-slot:content>
            </x-card>
        @else
            <x-card class="overflow-hidden">
                <x-slot:header>
                    <div
                        x-data="{ search: @entangle('search')}"
                        class="relative max-w-sm text-slate-400 focus-within:text-slate-600 dark:focus-within:text-slate-200"
                    >
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                        </div>
                        <x-input
                            wire:model.debounce.500ms="search"
                            type="text"
                            class="placeholder-slate-500 w-full pl-10 sm:text-sm focus:placeholder-slate-400 dark:focus:placeholder-slate-600"
                            ::class="{ 'pr-10' : search }"
                            placeholder="{{ __('Filter collections') }}"
                        />
                        <button
                            x-show="search.length"
                            x-on:click="search = ''"
                            type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3"
                        >
                            <x-heroicon-s-x-circle class="w-5 h-5 text-slate-500 hover:text-slate-600 dark:hover:text-slate-400" />
                        </button>
                    </div>
                </x-slot:header>
                <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <div class="relative overflow-hidden">
                                <div
                                    wire:loading.delay
                                    class="absolute inset-0 z-10 bg-slate-100/50 dark:bg-slate-800/50"
                                >
                                    <div
                                        wire:loading.flex
                                        class="h-full w-screen items-center justify-center sm:w-auto"
                                    >
                                        <div class="m-auto flex items-center space-x-2">
                                            <p class="text-sm dark:text-slate-200">{{ 'Loading collections...' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-200/10">
                                    <thead class="border-t border-slate-200 bg-slate-50 dark:border-slate-200/10 dark:bg-slate-800/75">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="relative w-12 px-6 sm:w-16 sm:px-8"
                                            >
                                                <x-input
                                                    wire:model="selectPage"
                                                    type="checkbox"
                                                    class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6"
                                                />
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                            >
                                                @unless(count($selected))
                                                    {{ __('Grupo') }}
                                                @else
                                                    <div class="space-x-0.5">
                                                        <span>{{ trans(':count selected', ['count' => count($selected)]) }}</span>
                                                        <button
                                                            wire:click="$set('showDeleteConfirmationModal', true)"
                                                            class="btn btn-default btn-xs -my-1.5"
                                                        >
                                                            {{ __('Delete') }}
                                                        </button>
                                                        @if($collections->total() > $collections->count())
                                                            <button
                                                                wire:click="$toggle('selectAll')"
                                                                class="btn btn-link"
                                                            >
                                                                {{ $selectAll ? __('Clear selection') : __('Select all :count collections', ['count' => $collections->total()]) }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endunless
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-4 text-center text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                            >
                                                {{ __('Status') }}
                                            </th>
                                            <th
                                                scope="col"
                                                class="pl-3 pr-4 py-4 text-right text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap sm:pr-6 dark:text-slate-200"
                                            >
                                                {{ __('Categorias') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-200/10">
                                        @forelse($collections as $collection)
                                            <tr
                                                wire:loading.class.delay="opacity-50"
                                                class="relative hover:bg-slate-50 dark:hover:bg-slate-800/75"
                                            >
                                                <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                    @if(in_array($collection->id, $selected))
                                                        <div class="absolute inset-y-0 left-0 w-0.5 bg-sky-500 dark:bg-sky-400"></div>
                                                    @endif
                                                    <x-input
                                                        wire:model="selected"
                                                        wire:key="checkbox-{{ $collection->id }}"
                                                        type="checkbox"
                                                        value="{{ $collection->id }}"
                                                        class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6"
                                                    />
                                                </td>
                                                <td class="relative px-3 py-4 font-medium text-sm text-slate-900 text-left whitespace-nowrap dark:text-slate-200">
                                                    <div class="flex items-center">
                                                        <div class="relative overflow-hidden h-10 w-10 rounded bg-slate-100 flex-shrink-0 dark:bg-white/10">
                                                            @if($collection->hasMedia('cover'))
                                                                <img
                                                                    class="h-10 w-10 rounded object-center object-cover"
                                                                    src="{{ $collection->getFirstMediaUrl('cover') }}"
                                                                    alt="{{ $collection->title }}"
                                                                >
                                                            @else
                                                                <x-heroicon-o-camera class="absolute inset-0 h-full w-6 mx-auto text-slate-400 dark:text-slate-500" />
                                                            @endif
                                                        </div>
                                                        <div class="ml-4">
                                                            <a
                                                                href="{{ route('employee.collections.detail', $collection->id) }}"
                                                                class="inline-flex items-center truncate hover:text-sky-600 dark:hover:text-sky-400"
                                                            >
                                                                <span>{{ $collection->title }}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="relative px-3 py-4 text-sm text-slate-500 text-center whitespace-nowrap dark:text-slate-400">
                                                    @if($collection->status == 'Unavailable')
                                                        <x-badge type="default">{{ $collection->status }}</x-badge>
                                                    @elseif($collection->status == 'Scheduled')
                                                        <x-badge type="warning">{{ $collection->status }}</x-badge>
                                                    @else
                                                        <x-badge type="success">{{ $collection->status }}</x-badge>
                                                    @endif
                                                </td>
                                                <td class="pl-3 pr-4 py-4 text-right text-sm text-slate-500 whitespace-nowrap sm:pr-6 dark:text-slate-400">
                                                    {{ trans_choice(':count Categoria| :count Categorias', $collection->categories()->count()) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td
                                                    class="px-3 py-4 text-sm text-slate-500 text-center whitespace-nowrap dark:text-slate-400"
                                                    colspan="4"
                                                >
                                                    <div class="max-w-lg mx-auto text-center">
                                                        <x-heroicon-o-magnifying-glass class="inline-block w-10 h-10 text-slate-400 dark:text-slate-300" />
                                                        <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-200">
                                                            {{ __('Nenhum grupo encontrado') }}
                                                        </h3>
                                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                            {{ __('Tente alterar os filtros ou o termo de pesquisa') }}
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-slot:content>
            </x-card>

            <div class="mt-6">
                {{ $collections->links() }}
            </div>

            <x-modal-alert wire:model="showDeleteConfirmationModal">
                <x-slot:title>
                    {{ __('Por favor, confirme sua ação!') }}
                </x-slot:title>
                <x-slot:content>
                    {{ trans_choice('Tem certeza de que deseja excluir :count grupo?|Tem certeza de que deseja excluir :count grupos?', count($selected)) }}
                    {{ __('Essa ação não pode ser desfeita!') }}
                </x-slot:content>
                <x-slot:footer>
                    <button
                        wire:click.prevent="deleteSelected"
                        class="btn btn-danger w-full sm:ml-3 sm:w-auto"
                    >
                        {{ __('Delete') }}
                    </button>
                    <button
                        x-on:click.prevent="show = false"
                        class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                    >
                        {{ __('Cancelar') }}
                    </button>
                </x-slot:footer>
            </x-modal-alert>
        @endunless
    </div>

    <form wire:submit.prevent="saveNewCollection">
        <x-modal-dialog wire:model="showNewCollectionCreationModal">
            <x-slot:title>
                Criar Novo Grupo
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-4">
                    <div>
                        <x-input-label
                            for="title"
                            :value="__('Title')"
                        />
                        <x-input
                            wire:model.defer="newCollection.title"
                            id="title"
                            type="text"
                            class="block w-full mt-1 sm:text-sm"
                            required
                            autofocus
                        />
                        <x-input-error
                            for="newCollection.title"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="description"
                            :value="__('Description')"
                        />
                        <x-textarea
                            wire:model.defer="newCollection.description"
                            id="description"
                            type="text"
                            class="block w-full mt-1 sm:text-sm"
                        />
                        <x-input-error
                            for="newCollection.description"
                            class="mt-2"
                        />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button
                    type="submit"
                    class="btn btn-primary w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Criar') }}
                </button>
                <button
                    x-on:click.prevent="show = false"
                    class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>

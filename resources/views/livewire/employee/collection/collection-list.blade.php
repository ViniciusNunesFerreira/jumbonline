<div>
    <x-slot:title>
        {{ __('Grupo de Produtos') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Grupos') }}
                </h1>
            </div>
            @if($collections->count())
                <div class="mt-4 flex sm:mt-0 sm:ml-4">
                    <button
                        wire:click.prevent="createNewCollection"
                        class="btn btn-primary block w-full order-0 sm:order-1 sm:ml-3"
                    >
                        {{ __('Criar novo Grupo') }}
                    </button>
                </div>
            @endif
        </div>

        <div class="mt-6">
            @if(!$collections->count() && !$search)
                <x-card>
                    <x-slot:content>
                        <div class="max-w-lg mx-auto text-center py-6">
                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent-50 dark:bg-accent-500/10">
                                <x-heroicon-o-rectangle-stack class="h-7 w-7 text-accent-500" />
                            </span>

                            <h3 class="mt-4 text-base font-semibold text-primary dark:text-slate-200">
                                {{ __('Parece que você ainda não tem nenhum Grupo de Produto criado') }}
                            </h3>

                            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
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
                        @if(count($selected))
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-medium text-primary dark:text-slate-200">
                                    {{ trans_choice(':count grupo selecionado|:count grupos selecionados', count($selected)) }}
                                </p>
                                <div class="flex items-center gap-2">
                                    @if($collections->total() > $collections->count())
                                        <button wire:click="$toggle('selectAll')" type="button" class="btn btn-link text-xs">
                                            {{ $selectAll ? __('Limpar seleção') : __('Selecionar todos os :count', ['count' => $collections->total()]) }}
                                        </button>
                                    @endif
                                    <button wire:click="$set('showDeleteConfirmationModal', true)" type="button" class="btn btn-outline-danger btn-xs !rounded-xl">
                                        <x-heroicon-m-trash class="w-4 h-4 mr-1" />
                                        {{ __('Excluir') }}
                                    </button>
                                </div>
                            </div>
                        @else
                            <div
                                x-data="{ search: @entangle('search')}"
                                class="relative max-w-sm text-slate-400 focus-within:text-primary dark:focus-within:text-slate-200"
                            >
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                                </div>
                                <x-input
                                    wire:model.debounce.500ms="search"
                                    type="text"
                                    class="placeholder-slate-400 w-full rounded-xl pl-10 sm:text-sm focus:placeholder-slate-400 dark:focus:placeholder-slate-600"
                                    ::class="{ 'pr-10' : search }"
                                    placeholder="{{ __('Filtrar grupos') }}"
                                />
                                <button
                                    x-show="search.length"
                                    x-on:click="search = ''"
                                    type="button"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3"
                                >
                                    <x-heroicon-s-x-circle class="w-5 h-5 text-slate-400 hover:text-slate-500 dark:hover:text-slate-400" />
                                </button>
                            </div>
                        @endif
                    </x-slot:header>
                    <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="relative overflow-hidden">
                                    <div
                                        wire:loading.delay
                                        class="absolute inset-0 z-10 bg-white/60 backdrop-blur-[1px] dark:bg-slate-900/60"
                                    >
                                        <div wire:loading.flex class="h-full w-screen items-center justify-center sm:w-auto">
                                            <p class="text-sm text-slate-500 dark:text-slate-300">{{ __('Carregando grupos...') }}</p>
                                        </div>
                                    </div>
                                    <table class="min-w-full divide-y divide-slate-100 dark:divide-white/5">
                                        <thead>
                                            <tr class="border-b border-slate-100 dark:border-white/5">
                                                <th scope="col" class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                    <x-input
                                                        wire:model="selectPage"
                                                        type="checkbox"
                                                        class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6 text-accent-500 focus:ring-accent-500"
                                                    />
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Grupo') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Status') }}
                                                </th>
                                                <th scope="col" class="pl-3 pr-4 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap sm:pr-6 dark:text-slate-500">
                                                    {{ __('Categorias') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                            @forelse($collections as $collection)
                                                <tr
                                                    wire:loading.class.delay="opacity-50"
                                                    class="relative transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.03]"
                                                >
                                                    <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                        @if(in_array($collection->id, $selected))
                                                            <div class="absolute inset-y-0 left-0 w-0.5 bg-accent-500"></div>
                                                        @endif
                                                        <x-input
                                                            wire:model="selected"
                                                            wire:key="checkbox-{{ $collection->id }}"
                                                            type="checkbox"
                                                            value="{{ $collection->id }}"
                                                            class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6 text-accent-500 focus:ring-accent-500"
                                                        />
                                                    </td>
                                                    <td class="relative px-3 py-4 font-medium text-sm text-left whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="relative overflow-hidden h-10 w-10 rounded-lg bg-slate-100 flex-shrink-0 ring-1 ring-slate-100 dark:bg-white/10 dark:ring-white/10">
                                                                @if($collection->hasMedia('cover'))
                                                                    <img
                                                                        class="h-10 w-10 rounded-lg object-center object-cover"
                                                                        src="{{ $collection->getFirstMediaUrl('cover') }}"
                                                                        alt="{{ $collection->title }}"
                                                                    >
                                                                @else
                                                                    <x-heroicon-o-camera class="absolute inset-0 h-full w-6 mx-auto text-slate-400 dark:text-slate-500" />
                                                                @endif
                                                            </div>
                                                            <div class="ml-3.5">
                                                                
                                                                <a  href="{{ route('employee.collections.detail', $collection->id) }}"
                                                                    class="inline-flex items-center truncate font-semibold text-primary hover:text-accent-600 dark:text-slate-200 dark:hover:text-accent-400"
                                                                >
                                                                    {{ $collection->title }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-center whitespace-nowrap">
                                                        @if($collection->status == 'Unavailable')
                                                            <x-badge type="default" size="xs">{{ __('Indisponível') }}</x-badge>
                                                        @elseif($collection->status == 'Scheduled')
                                                            <x-badge type="warning" size="xs">{{ __('Agendado') }}</x-badge>
                                                        @else
                                                            <x-badge type="success" size="xs">{{ __('Disponível') }}</x-badge>
                                                        @endif
                                                    </td>
                                                    <td class="pl-3 pr-4 py-4 text-right text-sm text-slate-500 whitespace-nowrap sm:pr-6 dark:text-slate-400">
                                                        {{ trans_choice(':count categoria|:count categorias', $collection->categories()->count()) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="px-3 py-12 text-sm text-center whitespace-nowrap" colspan="4">
                                                        <div class="max-w-lg mx-auto text-center">
                                                            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 dark:bg-white/5">
                                                                <x-heroicon-o-magnifying-glass class="h-6 w-6 text-slate-400" />
                                                            </span>
                                                            <h3 class="mt-3 text-sm font-semibold text-primary dark:text-slate-200">
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
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ trans_choice('Tem certeza de que deseja excluir :count grupo?|Tem certeza de que deseja excluir :count grupos?', count($selected)) }}
                            {{ __('Essa ação não pode ser desfeita!') }}
                        </p>
                    </x-slot:content>
                    <x-slot:footer>
                        <button
                            wire:click.prevent="deleteSelected"
                            class="btn btn-danger w-full sm:ml-3 sm:w-auto"
                        >
                            {{ __('Excluir') }}
                        </button>
                        <button
                            x-on:click.prevent="show = false"
                            class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                        >
                            {{ __('Cancelar') }}
                        </button>
                    </x-slot:footer>
                </x-modal-alert>
            @endif
        </div>
    </div>

    <form wire:submit.prevent="saveNewCollection">
        <x-modal-dialog wire:model="showNewCollectionCreationModal">
            <x-slot:title>
                {{ __('Criar Novo Grupo') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-4">
                    <div>
                        <x-input-label for="title" :value="__('Título')" />
                        <x-input
                            wire:model.defer="newCollection.title"
                            id="title"
                            type="text"
                            class="block w-full mt-1 rounded-xl sm:text-sm"
                            required
                            autofocus
                        />
                        <x-input-error for="newCollection.title" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="description" :value="__('Descrição')" />
                        <x-textarea
                            wire:model.defer="newCollection.description"
                            id="description"
                            type="text"
                            class="block w-full mt-1 rounded-xl sm:text-sm"
                        />
                        <x-input-error for="newCollection.description" class="mt-2" />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button type="submit" class="btn btn-primary w-full sm:ml-3 sm:w-auto">
                    {{ __('Criar') }}
                </button>
                <button x-on:click.prevent="show = false" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>
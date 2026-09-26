<div>
    <x-slot:title>
        {{ __('Blog Jumbonline') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Artigos do Blog') }}
                </h1>
            </div>
            @if($articles->count())
                <div class="mt-4 flex sm:mt-0 sm:ml-4">
                    <button wire:click.prevent="addNewArticle" type="button" class="btn btn-primary w-full order-0 sm:order-1 sm:ml-3">
                        {{ __('Criar Artigo') }}
                    </button>
                </div>
            @endif
        </div>

        <div class="mt-6">
            @if(!$articles->count() && !$search)
                <x-card>
                    <x-slot:content>
                        <div class="max-w-lg mx-auto text-center py-6">
                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent-50 dark:bg-accent-500/10">
                                <x-heroicon-o-document-text class="h-7 w-7 text-accent-500" />
                            </span>

                            <h3 class="mt-4 text-base font-semibold text-primary dark:text-slate-200">
                                {{ __('Escreva um artigo para o blog') }}
                            </h3>

                            <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Postagens no blog são uma ótima maneira de construir uma comunidade em torno de seus produtos e da sua marca.') }}
                            </p>

                            <div class="mt-6">
                                <button wire:click.prevent="addNewArticle" type="button" class="btn btn-primary">
                                    <x-heroicon-m-plus class="-ml-1 mr-2 h-5 w-5" />
                                    {{ __('Criar novo artigo') }}
                                </button>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>
            @else
                <div class="mb-4 flex w-fit rounded-xl bg-slate-100 p-1 dark:bg-white/5">
                    @foreach(['' => 'Todos', 'published' => 'Publicados', 'scheduled' => 'Agendados', 'draft' => 'Rascunhos'] as $key => $label)
                        <button
                            wire:click="$set('statusFilter', '{{ $key }}')"
                            type="button"
                            @class(['rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors', 'bg-white text-primary shadow-sm dark:bg-slate-800 dark:text-white' => $statusFilter === $key, 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white' => $statusFilter !== $key])
                        >
                            {{ __($label) }}
                        </button>
                    @endforeach
                </div>

                <x-card class="overflow-hidden">
                    <x-slot:header>
                        @if(count($selected))
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-medium text-primary dark:text-slate-200">
                                    {{ trans_choice(':count artigo selecionado|:count artigos selecionados', count($selected)) }}
                                </p>
                                <div class="flex items-center gap-2">
                                    @if($articles->total() > $articles->count())
                                        <button wire:click="$toggle('selectAll')" type="button" class="btn btn-link text-xs">
                                            {{ $selectAll ? __('Limpar seleção') : __('Selecionar todos os :count', ['count' => $articles->total()]) }}
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
                                    placeholder="{{ __('Filtrar artigos') }}"
                                />
                                <button x-show="search.length" x-on:click="search = ''" type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <x-heroicon-s-x-circle class="w-5 h-5 text-slate-400 hover:text-slate-500 dark:hover:text-slate-400" />
                                </button>
                            </div>
                        @endif
                    </x-slot:header>
                    <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="relative overflow-hidden">
                                    <div wire:loading.delay class="absolute inset-0 z-10 bg-white/60 backdrop-blur-[1px] dark:bg-slate-900/60">
                                        <div wire:loading.flex class="h-full w-screen items-center justify-center sm:w-auto">
                                            <p class="text-sm text-slate-500 dark:text-slate-300">{{ __('Carregando artigos...') }}</p>
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
                                                    {{ __('Artigo') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Autor') }}
                                                </th>
                                                <th scope="col" class="px-3 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">
                                                    {{ __('Status') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                            @forelse($articles as $article)
                                                <tr wire:loading.class.delay="opacity-50" class="relative transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.03]">
                                                    <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                        @if(in_array($article->id, $selected))
                                                            <div class="absolute inset-y-0 left-0 w-0.5 bg-accent-500"></div>
                                                        @endif
                                                        <x-input
                                                            wire:model="selected"
                                                            wire:key="checkbox-{{ $article->id }}"
                                                            type="checkbox"
                                                            value="{{ $article->id }}"
                                                            class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6 text-accent-500 focus:ring-accent-500"
                                                        />
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-left whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="h-10 w-14 flex-shrink-0 overflow-hidden rounded-lg bg-slate-100 ring-1 ring-slate-100 dark:bg-white/10 dark:ring-white/10">
                                                                <img class="h-full w-full object-cover" src="{{ $article->getFirstMediaUrl('cover', 'thumb') }}" alt="{{ $article->title }}">
                                                            </div>
                                                            <div class="ml-3.5 min-w-0">
                                                                
                                                                <a href="{{ route('employee.articles.detail', $article) }}"
                                                                    class="inline-flex items-center truncate font-semibold text-primary hover:text-accent-600 dark:text-slate-200 dark:hover:text-accent-400"
                                                                >
                                                                    {{ $article->title }}
                                                                </a>
                                                                <p class="truncate text-xs text-slate-400">{{ \Illuminate\Support\Str::limit(strip_tags($article->displayExcerpt), 100) }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-slate-500 text-left whitespace-nowrap dark:text-slate-400">
                                                        {{ $article->author?->name ?? __('Sem autor') }}
                                                    </td>
                                                    <td class="relative px-3 py-4 text-sm text-center whitespace-nowrap">
                                                        @if($article->published_at && $article->published_at->isFuture())
                                                            <x-badge type="warning" size="xs">{{ __('Agendado') }} · {{ $article->published_at->format('d/m H:i') }}</x-badge>
                                                        @elseif($article->published_at)
                                                            <x-badge type="success" size="xs">{{ __('Publicado') }}</x-badge>
                                                        @else
                                                            <x-badge type="default" size="xs">{{ __('Rascunho') }}</x-badge>
                                                        @endif
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
                                                                {{ __('Nenhuma postagem encontrada') }}
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
                    {{ $articles->links() }}
                </div>

                <x-modal-alert wire:model="showDeleteConfirmationModal">
                    <x-slot:title>
                        {{ __('Por favor, confirme sua ação!') }}
                    </x-slot:title>
                    <x-slot:content>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ trans_choice('Tem certeza de que deseja excluir :count artigo?|Tem certeza de que deseja excluir :count artigos?', count($selected)) }}
                            {{ __('Essa ação não pode ser desfeita!') }}
                        </p>
                    </x-slot:content>
                    <x-slot:footer>
                        <button wire:click.prevent="deleteSelected" class="btn btn-danger w-full sm:ml-3 sm:w-auto">
                            {{ __('Excluir') }}
                        </button>
                        <button x-on:click.prevent="show = false" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                            {{ __('Cancelar') }}
                        </button>
                    </x-slot:footer>
                </x-modal-alert>
            @endif
        </div>
    </div>

    <form wire:submit.prevent="saveNewArticle">
        <x-modal-dialog wire:model="addingNewArticle">
            <x-slot:title>
                {{ __('Novo Artigo') }}
            </x-slot:title>
            <x-slot:content>
                <x-input-label for="newArticleTitleInput" :value="__('Título')" />
                <x-input
                    wire:model.defer="newArticle.title"
                    type="text"
                    id="newArticleTitleInput"
                    class="block w-full mt-1 rounded-xl sm:text-sm"
                    placeholder="{{ __('Ex.: Novidades sobre nossos produtos ou ofertas mais recentes') }}"
                    autofocus
                />
                <x-input-error for="newArticle.title" class="mt-2" />
            </x-slot:content>
            <x-slot:footer>
                <button type="submit" class="btn btn-primary w-full sm:ml-3 sm:w-auto gap-x-2">
                    {{ __('Continuar') }}
                    <x-heroicon-o-arrow-small-right class="-mr-0.5 w-5 h-5" />
                </button>
                <button x-on:click="show = false" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>
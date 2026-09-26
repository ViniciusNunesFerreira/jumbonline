<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {!! $article->title !!}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex flex-1 items-center gap-2.5">
                <a href="{{ route('employee.articles.list') }}" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-primary truncate dark:text-white">
                    {{ $article->title ?: __('Novo artigo') }}
                </h1>
                @if($article->published_at && $article->published_at->isFuture())
                    <x-badge type="warning" size="xs">{{ __('Agendado') }}</x-badge>
                @elseif($article->published)
                    <x-badge type="success" size="xs">{{ __('Publicado') }}</x-badge>
                @else
                    <x-badge type="default" size="xs">{{ __('Rascunho') }}</x-badge>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-3 xl:col-span-2 space-y-6">
                    <x-card>
                        <x-slot:content>
                            <div
                                x-data="{
                                    content: @entangle('article.content').defer,
                                    get readingTime() {
                                        const words = this.content ? this.content.replace(/<[^>]*>/g, ' ').trim().split(/\s+/).filter(Boolean).length : 0;
                                        return Math.max(1, Math.ceil(words / 200));
                                    },
                                    get wordCount() {
                                        return this.content ? this.content.replace(/<[^>]*>/g, ' ').trim().split(/\s+/).filter(Boolean).length : 0;
                                    },
                                }"
                                class="space-y-6"
                            >
                                <div>
                                    <x-input-label for="articleTitleInput" :value="__('Título')" />
                                    <x-input
                                        wire:model.defer="article.title"
                                        type="text"
                                        id="articleTitleInput"
                                        class="mt-1 block w-full rounded-xl text-lg font-semibold sm:text-lg"
                                        placeholder="{{ __('Título do artigo') }}"
                                    />
                                    <x-input-error for="article.title" class="mt-2" />
                                </div>
                                <div>
                                    <div class="flex items-center justify-between">
                                        <x-input-label for="content" :value="__('Conteúdo')" />
                                        <span class="flex items-center gap-1.5 text-xs text-slate-400">
                                            <x-heroicon-o-clock class="h-3.5 w-3.5" />
                                            <span x-text="wordCount + ' ' + '{{ __('palavras') }}' + ' · ' + readingTime + ' '"></span>
                                            <span x-text="readingTime === 1 ? '{{ __('minuto de leitura') }}' : '{{ __('minutos de leitura') }}'"></span>
                                        </span>
                                    </div>
                                    <div class="mt-1 border border-slate-200 rounded-xl px-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                                        <x-tiptap
                                            wire:target="save"
                                            wire:loading.delay.class="opacity-50"
                                            wire:model.defer="article.content"
                                        />
                                    </div>
                                    <x-input-error for="article.content" class="mt-2" />
                                </div>
                            </div>
                        </x-slot:content>
                    </x-card>

                    <div x-data="{ articleHasExcerpt: @entangle('articleHasExcerpt').defer }">
                        <x-card>
                            <x-slot:header>
                                <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                                    <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                                        {{ __('Resumo') }}
                                    </h3>
                                    <button x-show="!articleHasExcerpt" x-on:click="articleHasExcerpt = true" type="button" class="btn btn-link">
                                        {{ __('Adicionar resumo') }}
                                    </button>
                                </div>
                            </x-slot:header>
                            <x-slot:content>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Aparece na página inicial do blog e serve de descrição de reserva pro SEO caso o campo de descrição não seja preenchido.') }}
                                </p>
                                <div x-show="articleHasExcerpt" x-cloak class="mt-4">
                                    <x-input-label for="excerpt" :value="__('Resumo')" class="sr-only" />
                                    <div class="block w-full rounded-xl shadow-sm sm:text-sm">
                                        <x-quill
                                            wire:target="save"
                                            wire:loading.delay.class="opacity-50"
                                            wire:model.defer="article.excerpt"
                                        />
                                    </div>
                                    <x-input-error for="article.excerpt" class="mt-2" />
                                </div>
                            </x-slot:content>
                        </x-card>
                    </div>

                    <livewire:employee.search-engine-information-form :model="$article" />
                </div>

                <div class="col-span-3 xl:col-span-1 space-y-6">
                    <x-card>
                        <x-slot:header>
                            <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                                {{ __('Visibilidade') }}
                            </h3>
                        </x-slot:header>
                        <x-slot:content>
                            <div class="space-y-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <x-input wire:model.defer="articleStatus" type="radio" name="articleStatus" id="visibleOption" class="h-4 w-4 !rounded-full !shadow-none text-accent-500 focus:ring-accent-500" value="published" />
                                    {{ __('Publicado') }}
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <x-input wire:model.defer="articleStatus" type="radio" name="articleStatus" id="hiddenOption" class="h-4 w-4 !rounded-full !shadow-none text-accent-500 focus:ring-accent-500" value="hidden" />
                                    {{ __('Rascunho') }}
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <x-input wire:model.defer="articleStatus" type="radio" name="articleStatus" id="scheduledOption" class="h-4 w-4 !rounded-full !shadow-none text-accent-500 focus:ring-accent-500" value="scheduled" />
                                    {{ __('Agendado') }}
                                </label>
                                <div x-show="$wire.articleStatus === 'scheduled'" x-cloak class="ml-6">
                                    <x-input wire:model="scheduledAt" type="datetime-local" class="w-full rounded-xl sm:text-sm" />
                                </div>
                            </div>
                        </x-slot:content>
                    </x-card>

                    <x-card>
                        <x-slot:header>
                            <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                                <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                                    {{ __('Imagem Destaque') }}
                                </h3>
                                @if($article->hasMedia('cover'))
                                    <x-dropdown>
                                        <x-slot:trigger>
                                            <button type="button" class="btn btn-link space-x-1">
                                                <span>{{ __('Atualizar') }}</span>
                                                <x-heroicon-m-chevron-down class="-ml-0.5 w-4 h-4" />
                                            </button>
                                        </x-slot:trigger>
                                        <x-slot:content>
                                            <x-dropdown-link wire:click.prevent="editFeaturedImage" role="button">
                                                {{ __('Editar texto alternativo') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link wire:click.prevent="removeFeaturedImage" role="button" class="!text-red-500">
                                                {{ __('Remover') }}
                                            </x-dropdown-link>
                                        </x-slot:content>
                                    </x-dropdown>
                                @endif
                            </div>
                        </x-slot:header>
                        <x-slot:content>
                            @if($featuredImage)
                                <div class="group relative aspect-[16/9] block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-white/5">
                                    <img src="{{ $featuredImage->temporaryUrl() }}" alt="" class="h-full w-full mx-auto pointer-events-none object-cover object-center group-hover:opacity-75">
                                    <label for="featuredImageInput" class="absolute inset-0 cursor-pointer focus:outline-none">
                                        <span class="sr-only">{{ __('Alterar imagem') }}</span>
                                    </label>
                                    <input wire:model="featuredImage" type="file" id="featuredImageInput" class="hidden">
                                </div>
                            @elseif($article->hasMedia('cover'))
                                <div class="group relative aspect-[16/9] block w-full overflow-hidden rounded-xl bg-slate-50 dark:bg-white/5">
                                    <img src="{{ $article->getFirstMediaUrl('cover') }}" alt="{{ $article->getFirstMedia('cover')?->getCustomProperty('alt') ?: '' }}" class="h-full w-full mx-auto pointer-events-none object-cover object-center group-hover:opacity-75">
                                    <label for="featuredImageInput" class="absolute inset-0 cursor-pointer focus:outline-none">
                                        <span class="sr-only">{{ __('Alterar imagem') }}</span>
                                    </label>
                                    <input wire:model="featuredImage" type="file" id="featuredImageInput" class="hidden">
                                </div>
                                @unless($article->getFirstMedia('cover')?->getCustomProperty('alt'))
                                    <p class="mt-2 flex items-center gap-1.5 text-xs text-amber-600 dark:text-amber-400">
                                        <x-heroicon-s-exclamation-triangle class="h-3.5 w-3.5 flex-shrink-0" />
                                        {{ __('Sem texto alternativo — importante pro SEO de imagens e acessibilidade.') }}
                                        <button wire:click.prevent="editFeaturedImage" type="button" class="font-semibold underline">{{ __('Adicionar') }}</button>
                                    </p>
                                @endunless
                            @else
                                <label
                                    for="file-upload"
                                    class="flex cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 px-6 py-10 hover:border-accent-300 dark:border-white/20 dark:hover:border-accent-500/50"
                                >
                                    <x-heroicon-o-photo class="mx-auto h-10 w-10 text-slate-300" />
                                    <span class="mt-3 text-sm font-semibold text-accent-600 hover:underline dark:text-accent-400">{{ __('Carregar um arquivo') }}</span>
                                    <input wire:model="featuredImage" id="file-upload" name="file-upload" type="file" class="sr-only">
                                    <p class="mt-1.5 text-xs text-slate-400">{{ __('PNG, JPG ou GIF até 10MB') }}</p>
                                </label>
                            @endif
                        </x-slot:content>
                    </x-card>

                    <x-card>
                        <x-slot:header>
                            <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                                {{ __('Organização') }}
                            </h3>
                        </x-slot:header>
                        <x-slot:content class="space-y-6">
                            <livewire:employee.article.components.article-author-manager :article="$article" />

                            <hr class="-mx-4 border-slate-100 sm:-mx-6 dark:border-white/5" />

                            <livewire:employee.article.components.article-category-manager :article="$article" />

                            <livewire:employee.article.components.article-tag-manager :article="$article" />
                        </x-slot:content>
                    </x-card>

                    <div x-data="{ confirmingDeletion: false }" class="pt-2">
                        <div x-cloak x-show="confirmingDeletion" class="flex w-full gap-2">
                            <button x-on:click.prevent="confirmingDeletion = false" type="button" class="btn btn-default block w-full !rounded-xl">
                                {{ __('Cancelar') }}
                            </button>
                            <button wire:click="removeBlogPost" type="button" class="btn btn-danger block w-full !rounded-xl">
                                {{ __('Confirmar exclusão') }}
                            </button>
                        </div>
                        <div x-show="!confirmingDeletion" class="flex w-full gap-2">
                            <button x-on:click.prevent="confirmingDeletion = true" type="button" class="btn btn-outline-danger block w-full !rounded-xl">
                                {{ __('Excluir artigo') }}
                            </button>
                            <button wire:click="save" type="button" class="btn btn-primary block w-full !rounded-xl">
                                {{ __('Salvar Alterações') }}
                            </button>
                        </div>
                    </div>
                </div>
        </div>
    </div>

                            

    <form wire:submit.prevent="updateFeaturedImage">
        <x-slide-over wire:model="editingFeaturedImage">
            <x-slot:title>
                {{ __('Editar imagem') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div class="w-full bg-white dark:bg-slate-900">
                        <div class="relative mx-auto">
                            <div class="group aspect-[16/9] block w-full overflow-hidden rounded-lg bg-slate-50 dark:bg-white/5">
                                <img
                                    src="{{ $article->getFirstMediaUrl('cover') }}"
                                    alt=""
                                    class="h-full mx-auto pointer-events-none object-cover object-center group-hover:opacity-75"
                                >
                            </div>
                        </div>
                    </div>
                    <div>
                        <x-input-label for="imageAltInput" :value="__('Texto alternativo da imagem')" />
                        <x-input
                            wire:model.defer="featuredImageAlt"
                            id="imageAltInput"
                            type="text"
                            class="block w-full mt-1 rounded-xl sm:text-sm"
                            placeholder="{{ __('Ex.: Detento recebendo kit de higiene pessoal') }}"
                        />
                        <x-input-description>
                            {{ __('Escreva uma breve descrição desta imagem para melhorar a otimização para mecanismos de busca (SEO) e a acessibilidade para clientes com deficiência visual.') }}
                        </x-input-description>
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
                        {{ __('Cancelar') }}
                    </button>
                    <button
                        type="submit"
                        class="ml-4 btn btn-primary"
                    >
                        {{ __('Salvar') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-slide-over>
    </form>

    <div
        x-data="{ addFromURL: false, selectedMedia: null }"
        x-on:open-media-modal.window="@this.showMediaModal = true"
    >
        <x-modal-dialog wire:model="showMediaModal">
            <x-slot:title>
                {{ __('Media') }}
            </x-slot:title>
            <x-slot:content>
                @if ($errors->has('mediaFile'))
                    <div class="mb-8 rounded-xl bg-red-50 p-4 dark:bg-red-900/20">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <x-heroicon-s-x-circle class="w-5 h-5 text-red-400" />
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-300">
                                    {{ trans_choice(':count erro no envio|:count erros no envio', $errors->count()) }}
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul
                                        role="list"
                                        class="list-disc pl-5 space-y-1"
                                    >
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @unless($this->media->count())
                    {{--Upload form--}}
                    <div
                        x-on:click="$refs.mediaInput.click()"
                        class="flex justify-center rounded-md border-2 border-dashed border-slate-300 px-6 pt-5 pb-6 cursor-pointer hover:border-slate-400 dark:border-slate-500 dark:hover:border-slate-400"
                    >
                        <div class="space-y-1 text-center">
                            <svg
                                wire:target="mediaFile"
                                wire:loading.remove
                                class="mx-auto h-12 w-12 text-slate-400"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 48 48"
                                aria-hidden="true"
                            >
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                            </svg>
                            <x-loading-spinner
                                wire:target="mediaFile"
                                wire:loading.flex
                                class="mx-auto h-10 w-10 text-slate-400"
                            />
                            <div class="flex justify-center text-sm text-slate-600">
                                <label
                                    for="media-upload"
                                    class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 hover:text-blue-500"
                                >
                                    <span>{{ __('Enviar Arquivo') }}</span>
                                    <input
                                        x-ref="mediaInput"
                                        wire:model.defer="mediaFile"
                                        id="media-upload"
                                        name="media-upload"
                                        type="file"
                                        class="sr-only"
                                    >
                                </label>
                            </div>
                            <p class="text-xs text-slate-500">
                                {{ __('Tamanho máximo de arquivo permitido: :size megabytes', ['size' => $this->maxUploadSize / 1000]) }}
                            </p>
                        </div>
                    </div>
                @else
                    {{--Media list--}}
                    <ul class="mt-8 grid grid-cols-2 auto-rows-fr gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                        @foreach ($this->media as $media)
                            <li class="relative">
                                <div
                                    class="group block w-full aspect-w-10 aspect-h-7 rounded-lg bg-slate-100 overflow-hidden"
                                    :class="{ 'ring-2 ring-offset-2 ring-blue-500 dark:ring-offset-slate-800': selectedMedia === {{ $media->id }} }"
                                >
                                    @if(str_contains($media->mime_type, 'image'))
                                        <img
                                            src="{{ $media->getUrl() }}"
                                            alt="{{ $media->name }}"
                                            class="object-cover pointer-events-none"
                                            :class="{ 'group-hover:opacity-75': selectedMedia !== {{ $media->id }} }"
                                        >
                                    @endif
                                    @if(str_contains($media->mime_type, 'video'))
                                        <video
                                            src="{{ $media->getUrl() }}"
                                            alt="{{ $media->name }}"
                                            class="object-cover pointer-events-none"
                                            :class="{ 'group-hover:opacity-75': selectedMedia !== {{ $media->id }} }"
                                        >
                                            {{ __('Seu navegador não suporta a tag de vídeo..') }}
                                        </video>
                                    @endif
                                    <button
                                        x-on:click="selectedMedia = {{ $media->id }}"
                                        class="absolute inset-0 focus:outline-none"
                                    >
                                    <span class="sr-only">
                                        {{ __('Selecione esta mídia') }}
                                    </span>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                        <li
                            wire:loading.delay
                            class="relative"
                        >
                            <div class="block w-full aspect-w-10 aspect-h-7 rounded-lg bg-slate-100 overflow-hidden">
                                <x-loading-spinner class="absolute inset-0 m-auto w-5 h-5" />
                            </div>
                        </li>
                        <li class="relative">
                            <div
                                x-on:click="$refs.mediaInput.click()"
                                class="flex justify-center items-center h-full w-full rounded-md border-2 border-dashed border-slate-300 cursor-pointer hover:border-slate-400 dark:border-slate-500 dark:hover:border-slate-400"
                            >
                                <div class="space-y-1 text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-slate-400"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 48 48"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></path>
                                    </svg>
                                    <div class="flex text-sm text-slate-600">
                                        <label
                                            for="media-upload"
                                            class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 hover:text-blue-500"
                                        >
                                            <span class="sr-only">{{ __('Enviar arquivo') }}</span>
                                            <input
                                                x-ref="mediaInput"
                                                wire:model.defer="mediaFile"
                                                id="media-upload"
                                                name="media-upload"
                                                type="file"
                                                class="sr-only"
                                            >
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                @endunless
            </x-slot:content>
            <x-slot:footer>
                <div>
                    <button
                        x-on:click="$wire.set('showMediaModal', false)"
                        type="button"
                        class="btn btn-invisible"
                    >
                        {{ __('Cancelar') }}
                    </button>
                    <button
                        x-show="selectedMedia"
                        x-on:click.prevent="if(confirm('{{ __('Tem certeza de que deseja excluir esta mídia?') }}')) $wire.deleteMedia(selectedMedia); selectedMedia = null;"
                        type="button"
                        class="ml-3 btn btn-outline-danger"
                    >
                        {{ __('Deletar') }}
                    </button>
                    <button
                        x-show="selectedMedia"
                        x-on:click="$wire.insertMedia(selectedMedia); selectedMedia = null"
                        type="button"
                        class="ml-3 btn btn-primary"
                    >
                        {{ __('Inserir') }}
                    </button>
                </div>
            </x-slot:footer>
        </x-modal-dialog>
    </div>
</div>

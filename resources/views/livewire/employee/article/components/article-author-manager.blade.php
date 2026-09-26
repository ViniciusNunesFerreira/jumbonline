<div x-data="{ open: false, searching: false }" x-on:click.away="open = false; searching = false">
    <x-input-label :value="__('Autor')" />

    <div x-show="!searching" class="mt-1.5 flex items-center justify-between rounded-xl border border-slate-200 px-3 py-2 dark:border-white/10">
        <div class="flex items-center gap-2">
            <img src="{{ $article->author->getFirstMediaUrl('avatar') }}" alt="" class="h-6 w-6 rounded-full bg-slate-100 dark:bg-slate-800">
            <span class="text-sm font-medium text-primary dark:text-slate-200">{{ $article->author->name }}</span>
        </div>
        <button x-on:click="searching = true; open = true; $wire.loadAuthors()" type="button" class="btn btn-link text-xs">
            {{ __('Trocar') }}
        </button>
    </div>

    <div x-show="searching" x-cloak class="relative mt-1.5">
        <x-input
            wire:target="setAuthor"
            wire:loading.attr="disabled"
            x-on:input.debounce.500ms="$wire.set('filterAuthorName', $event.target.value)"
            type="text"
            class="block w-full rounded-xl sm:text-sm"
            placeholder="{{ __('Buscar autor...') }}"
            autocomplete="off"
            autofocus
        />
        <ul
            x-show="open"
            class="absolute z-10 mt-1 max-h-48 w-full overflow-auto rounded-xl bg-white py-1 text-base shadow-lg ring-1 ring-slate-900/5 focus:outline-none sm:text-sm dark:bg-slate-900"
            role="listbox"
        >
            @forelse($authors as $author)
                <li
                    x-on:click="$wire.setAuthor({{ $author->id }}); searching = false; open = false"
                    id="author-{{ $author->id }}"
                    class="group relative cursor-default select-none py-2 pl-3 pr-9 hover:bg-accent-500 hover:text-white"
                    role="option"
                    tabindex="-1"
                >
                    <div class="flex items-center">
                        <img src="{{ $author->getFirstMediaUrl('avatar') }}" alt="" class="h-6 w-6 flex-shrink-0 rounded-full bg-slate-100 dark:bg-slate-800">
                        <span @class(['ml-3 truncate text-slate-700 group-hover:text-white dark:text-slate-200', 'font-semibold text-primary dark:text-white' => $author->id === $article->author->id])>
                            {{ $author->name }}
                        </span>
                    </div>
                    @if($author->id === $article->author->id)
                        <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-accent-500 group-hover:text-white">
                            <x-heroicon-o-check class="h-5 w-5" />
                        </span>
                    @endif
                </li>
            @empty
                <li class="py-2 px-3 text-sm text-slate-400">
                    {{ __('Nenhum autor encontrado') }}
                </li>
            @endforelse
        </ul>
    </div>
</div>
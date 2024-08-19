<div>
    <div
        x-data="{ open: false }"
        x-init="$watch('open', value => { if (value) $wire.loadAuthors() })"
    >
        <x-input-label
            for="author"
            :value="__('Author')"
        />
        <div class="relative mt-1">
            <x-input
                wire:target="setAuthor"
                wire:loading.attr="disabled"
                x-on:focus="open = true"
                x-on:click.away="open = false"
                x-on:input.debounce.500ms="$wire.set('filterAuthorName', $event.target.value)"
                id="author"
                type="text"
                value="{{ $article->author->name }}"
                class="block w-full sm:text-sm"
                autocomplete="off"
            />
            <ul
                x-show="open"
                class="absolute z-10 mt-1 max-h-48 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm dark:bg-slate-800"
                role="listbox"
            >
                @forelse($authors as $author)
                    <li
                        x-on:click="$wire.setAuthor({{ $author->id }})"
                        id="author-{{ $author->id }}"
                        class="group relative cursor-default select-none py-2 pl-3 pr-9 text-slate-900 hover:text-white hover:bg-sky-600"
                        role="option"
                        tabindex="-1"
                    >
                        <div class="flex items-center">
                            <img
                                src="{{ $author->getFirstMediaUrl('avatar') }}"
                                alt=""
                                class="h-6 w-6 flex-shrink-0 rounded-full bg-slate-100"
                            >
                            <span @class(['ml-3 truncate text-slate-900 group-hover:text-white dark:text-slate-200', 'font-semibold' => $author->id === $article->author->id])>
                                {{ $author->name }}
                            </span>
                        </div>
                        @if($author->id === $article->author->id)
                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-sky-600 group-hover:text-white">
                                <x-heroicon-o-check class="h-5 w-5" />
                            </span>
                        @endif
                    </li>
                @empty
                    <li class="py-2 pl-3 pr-9 text-slate-900">
                        {{ __('No authors found.') }}
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

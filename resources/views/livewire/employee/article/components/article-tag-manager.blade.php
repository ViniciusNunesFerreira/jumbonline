<div>
    <div>
        <div
            x-data="{ open: false }"
            x-on:click.away="open = false"
            x-init="$watch('open', value => { if (value) $wire.loadTags() })"
        >
            <x-input-label
                for="tags"
                :value="__('Tags')"
            />
            <div class="relative mt-1">
                <x-input
                    wire:target="setAuthor"
                    wire:loading.attr="disabled"
                    wire:model.debounce.500ms="filterTagName"
                    x-on:click="open = true"
                    id="tags"
                    type="text"
                    class="block w-full sm:text-sm"
                    placeholder="{{ __('Add a tag') }}"
                    autocomplete="off"
                />
                @if(count($tags) >=1 || $filterTagName)
                    <ul
                        x-show="open"
                        class="absolute z-10 mt-1 max-h-48 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm dark:bg-slate-800"
                        role="listbox"
                    >
                        @if($filterTagName && !in_array($filterTagName, $tags->pluck('name')->toArray()))
                            <li
                                x-on:click="$wire.setTag('{{ $filterTagName }}')"
                                class="group relative cursor-default select-none py-2 pl-9 pr-4 text-slate-900 hover:text-white hover:bg-sky-600"
                                role="option"
                                tabindex="-1"
                            >
                                <span class="block truncate">
                                    <span class="font-semibold">{{ __('Add') }}</span>
                                    {{ $filterTagName }}
                                </span>
                                <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 text-sky-600 group-hover:text-white">
                                    <x-heroicon-o-plus-circle class="h-5 w-5" />
                                </span>
                            </li>
                        @endif
                        @foreach($tags as $tag)
                            <li
                                x-on:click="$wire.toggleTag('{{ $tag->id }}')"
                                id="tag-{{ $tag->id }}"
                                class="group relative cursor-default select-none py-2 pl-9 pr-4 hover:bg-sky-600"
                                role="option"
                                tabindex="-1"
                            >
                                <span class="block truncate text-slate-900 group-hover:text-white dark:text-slate-200">
                                    {{ $tag->name }}
                                </span>
                                <span class="absolute inset-y-0 left-0 flex items-center pl-2.5">
                                    <x-input
                                        type="checkbox"
                                        class="!rounded !shadow-none"
                                        :checked="in_array($tag->id, $article->tags->pluck('id')->toArray())"
                                    />
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        <ul class="mt-2">
            @foreach($article->tags as $tag)
                <li class="inline-flex">
                    <x-badge class="gap-x-0.5 !rounded-md">
                        {{ $tag->name }}
                        <button
                            wire:click.prevent="removeTag({{ $tag->id }})"
                            type="button"
                            class="group relative -mr-1 h-3 w-3 rounded-sm hover:bg-slate-500/20 dark:hover:bg-slate-500/30"
                        >
                            <span class="sr-only">{{ __('Remove') }}</span>
                            <x-heroicon-o-x-mark class="h-3 w-3 stroke-slate-600/50 group-hover:stroke-slate-600/75 dark:stroke-slate-500 dark:group-hover:stroke-slate-400" />
                            <span class="absolute -inset-1"></span>
                        </button>
                    </x-badge>
                </li>
            @endforeach
        </ul>
    </div>
</div>

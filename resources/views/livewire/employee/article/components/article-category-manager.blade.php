<div x-data="{ open: false }">
    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Categoria') }}</label>

    @if($this->currentCategory)
        <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-sky-50 px-3 py-1.5 text-sm font-medium text-sky-700 dark:bg-sky-900/30 dark:text-sky-400">
            {{ $this->currentCategory->name }}
            <button wire:click="removeCategory" type="button" class="text-sky-400 hover:text-sky-600">
                <x-heroicon-s-x-mark class="h-3.5 w-3.5" />
            </button>
        </div>
    @else
        <div class="relative mt-2 max-w-xs" x-on:click.outside="open = false">
            <x-input
                wire:model.debounce.300ms="filterCategoryName"
                x-on:focus="open = true"
                type="text"
                placeholder="{{ __('Buscar ou criar categoria...') }}"
                class="w-full"
            />
            <div x-show="open" x-cloak class="absolute z-10 mt-1 w-full rounded-md border border-slate-200 bg-white shadow-lg dark:border-slate-700 dark:bg-slate-800">
                @forelse($categories as $category)
                    <button
                        wire:click="setCategory('{{ $category->name }}')"
                        x-on:click="open = false"
                        type="button"
                        class="block w-full px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-slate-700"
                    >
                        {{ $category->name }}
                    </button>
                @empty
                    @if($filterCategoryName)
                        <button
                            wire:click="setCategory('{{ $filterCategoryName }}')"
                            x-on:click="open = false"
                            type="button"
                            class="block w-full px-3 py-2 text-left text-sm text-sky-600 hover:bg-slate-50 dark:hover:bg-slate-700"
                        >
                            {{ __('Criar categoria ":name"', ['name' => $filterCategoryName]) }}
                        </button>
                    @else
                        <p class="px-3 py-2 text-sm text-slate-400">{{ __('Digite para buscar ou criar') }}</p>
                    @endif
                @endforelse
            </div>
        </div>
    @endif
</div>
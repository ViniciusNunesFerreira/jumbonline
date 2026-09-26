<div x-data="{ open: false }">
    <x-input-label :value="__('Categoria')" />

    @if($this->currentCategory)
        <div class="mt-1.5 inline-flex items-center gap-2 rounded-full bg-accent-50 px-3 py-1.5 text-sm font-medium text-accent-700 dark:bg-accent-500/10 dark:text-accent-400">
            {{ $this->currentCategory->name }}
            <button wire:click="removeCategory" type="button" class="text-accent-400 hover:text-accent-600">
                <x-heroicon-s-x-mark class="h-3.5 w-3.5" />
            </button>
        </div>
    @else
        <div class="relative mt-1.5 max-w-xs" x-on:click.outside="open = false">
            <x-input
                wire:model.debounce.300ms="filterCategoryName"
                x-on:focus="open = true"
                type="text"
                placeholder="{{ __('Buscar ou criar categoria...') }}"
                class="w-full rounded-xl sm:text-sm"
            />
            <div x-show="open" x-cloak class="absolute z-10 mt-1 w-full rounded-xl border border-slate-100 bg-white shadow-lg dark:border-white/10 dark:bg-slate-900">
                @forelse($categories as $category)
                    <button
                        wire:click="setCategory('{{ $category->name }}')"
                        x-on:click="open = false"
                        type="button"
                        class="block w-full px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-white/5"
                    >
                        {{ $category->name }}
                    </button>
                @empty
                    @if($filterCategoryName)
                        <button
                            wire:click="setCategory('{{ $filterCategoryName }}')"
                            x-on:click="open = false"
                            type="button"
                            class="block w-full px-3 py-2 text-left text-sm font-medium text-accent-600 hover:bg-slate-50 dark:text-accent-400 dark:hover:bg-white/5"
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
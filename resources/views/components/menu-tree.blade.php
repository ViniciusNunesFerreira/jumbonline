@props(['items' => []])

<div class="space-y-2">
    @foreach($items as $item)
        <div
            wire:key="menu-{{ $item->menu_id }}-item-{{ $item->id }}"
            class="relative flex items-center gap-3 rounded-xl border border-slate-100 bg-white px-3 py-2.5 dark:border-white/5 dark:bg-slate-900"
        >
            <div class="flex-shrink-0 rounded-full bg-slate-50 p-2 ring-1 ring-slate-100 dark:bg-white/10 dark:ring-white/5">
                <x-heroicon-o-link class="h-4 w-4 text-slate-500 dark:text-slate-400" />
            </div>
            <div class="min-w-0 flex flex-1 items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-primary dark:text-white">
                        {{ $item->name }}
                    </p>
                    <p class="truncate text-xs text-slate-400">
                        {{ $item->url }}
                    </p>
                </div>
                <span class="isolate inline-flex flex-shrink-0 rounded-xl shadow-sm">
                    <button
                        wire:click.prevent="addMenuItem({{ $item->menu_id }}, {{ $item->id }})"
                        type="button"
                        class="relative inline-flex items-center rounded-l-xl bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-200 hover:bg-slate-50 focus:z-10 dark:bg-slate-900 dark:ring-white/10 dark:text-white dark:hover:bg-slate-800"
                    >
                        {{ __('Subitem') }}
                    </button>
                    <button
                        wire:click.prevent="editMenuItem({{ $item->id }})"
                        type="button"
                        class="relative -ml-px inline-flex items-center bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-200 hover:bg-slate-50 focus:z-10 dark:bg-slate-900 dark:ring-white/10 dark:text-white dark:hover:bg-slate-800"
                    >
                        {{ __('Editar') }}
                    </button>
                    <button
                        wire:click.prevent="confirmMenuItemDeletion({{ $item->id }})"
                        type="button"
                        class="relative -ml-px inline-flex items-center rounded-r-xl bg-white px-3 py-1.5 text-xs font-semibold text-red-600 ring-1 ring-inset ring-slate-200 hover:bg-red-50 focus:z-10 dark:bg-slate-900 dark:ring-white/10 dark:text-red-400 dark:hover:bg-red-500/10"
                    >
                        {{ __('Excluir') }}
                    </button>
                </span>
            </div>
        </div>

        @if(count($item->children))
            <div class="ml-4 sm:ml-12">
                <x-menu-tree :items="$item->children" />
            </div>
        @endif

        @if($loop->last)
            <button
                wire:click.prevent="addMenuItem({{ $item->menu_id }}, {{ $item->parent_id ?? 'null' }})"
                type="button"
                class="group relative inline-flex w-full items-center gap-3 rounded-xl border border-dashed border-slate-200 bg-white px-3 py-2.5 focus:outline-none hover:border-accent-300 dark:border-white/10 dark:bg-slate-900 dark:hover:border-accent-500/50"
            >
                <div class="flex-shrink-0 rounded-full bg-slate-50 p-2 ring-1 ring-slate-100 dark:bg-white/10 dark:ring-white/5">
                    <x-heroicon-o-plus class="h-4 w-4 text-accent-500" />
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-accent-600 group-hover:text-accent-700 dark:text-accent-400">
                        {{ $item->parent_id ? __('Adicionar item em ') : __('Adicionar item ao menu') }}
                        @if($item->parent_id)
                            <span class="font-semibold">{{ $item->parent->name }}</span>
                        @endif
                    </p>
                </div>
            </button>
        @endif
    @endforeach
</div>
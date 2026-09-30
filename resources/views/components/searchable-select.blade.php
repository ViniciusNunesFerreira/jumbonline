@props(['options', 'placeholder' => null])


<div
    x-data="{
        open: false,
        query: '',
        value: @entangle($attributes->wire('model')).defer,
        options: {{ \Illuminate\Support\Js::from(collect($options)->map(fn ($o) => ['id' => $o['id'], 'label' => $o['label']])->values()) }},
        get filtered() {
            if (!this.query) return this.options;
            const q = this.query.toLowerCase();
            return this.options.filter(o => o.label.toLowerCase().includes(q));
        },
        get selectedLabel() {
            const found = this.options.find(o => String(o.id) === String(this.value));
            return found ? found.label : '';
        },
        select(option) {
            this.value = option.id;
            this.query = '';
            this.open = false;
        },
        openList() {
            this.open = true;
            this.query = '';
            this.$nextTick(() => this.$refs.searchInput?.focus());
        },
    }"
    x-on:keydown.escape="open = false"
    x-on:click.outside="open = false"
    class="relative"
>
    <button
        type="button"
        x-on:click="openList()"
        {{ $attributes->except(['wire:model'])->merge(['class' => 'flex h-10 w-full items-center justify-between gap-2 rounded-xl bg-white px-3 text-left text-sm shadow-sm ring-1 ring-inset ring-slate-300 focus:outline-none focus:ring-2 focus:ring-accent-500 dark:bg-white/5 dark:ring-white/10 dark:text-slate-200']) }}
    >
        <span x-text="selectedLabel || {{ \Illuminate\Support\Js::from($placeholder ?? __('Selecione...')) }}" :class="selectedLabel ? '' : 'text-slate-400'" class="truncate"></span>
        <x-heroicon-m-chevron-up-down class="h-4 w-4 flex-shrink-0 text-slate-400" />
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition.origin.top
        class="absolute z-20 mt-1.5 w-full rounded-xl bg-white py-1.5 shadow-lg ring-1 ring-slate-900/5 dark:bg-slate-900 dark:ring-white/10"
    >
        <div class="px-2 pb-1.5">
            <input
                type="text"
                x-ref="searchInput"
                x-model="query"
                x-on:click.stop
                placeholder="{{ __('Buscar...') }}"
                class="block w-full rounded-lg border-0 px-2 py-1.5 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-accent-500 dark:bg-white/5 dark:text-slate-200 dark:ring-white/10"
            />
        </div>
        <ul class="max-h-56 overflow-auto">
            <template x-for="option in filtered" :key="option.id">
                <li
                    x-on:click="select(option)"
                    x-text="option.label"
                    x-bind:class="String(option.id) === String(value) ? 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' : 'text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-white/5'"
                    class="cursor-pointer truncate px-3 py-2 text-sm"
                ></li>
            </template>
            <li x-show="filtered.length === 0" class="px-3 py-2 text-sm text-slate-400">{{ __('Nenhum resultado') }}</li>
        </ul>
    </div>
</div>
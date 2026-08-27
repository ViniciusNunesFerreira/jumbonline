{{-- resources/views/components/prison-unit-select.blade.php --}}
@props(['categories', 'model' => 'prison', 'selected' => null, 'placeholder' => 'Buscar unidade prisional...'])

@php
    $flatUnits = $categories->flatMap(fn($category) => $category->prisonUnits->map(fn($unit) => [
        'slug' => $unit->slug,
        'name' => $unit->name,
        'category' => $category->name,
    ]));
    $selectedUnit = $selected ? $flatUnits->firstWhere('slug', $selected) : null;
@endphp

<div
    x-data="{
        open: false,
        search: '{{ $selectedUnit['name'] ?? '' }}',
        units: {{ Illuminate\Support\Js::from($flatUnits->values()) }},
        get filtered() {
            if (this.search.length < 2) return this.units;
            const normalize = (str) => str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
            const searchTerms = normalize(this.search).split(/\s+/).filter(Boolean);
            return this.units.filter(unit => {
                const targetText = normalize(`${unit.name} ${unit.category}`);
                
                return searchTerms.every(term => targetText.includes(term));
            });
        },
        select(unit) {
            $wire.set('{{ $model }}', unit.slug);
            this.search = unit.name;
            this.open = false;
        }
    }"
    @click.outside="open = false"
    class="relative w-full"
>
    <div class="relative">
        <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-5 top-1/2 h-5 w-5 -translate-y-1/2 text-purple" />
        <input
            type="text"
            x-model="search"
            @focus="open = true"
            @input="open = true"
            autocomplete="off"
            placeholder="{{ $placeholder }}"
            class="w-full rounded-2xl border-2 border-secondary bg-white py-5 pl-14 pr-5 font-urbanist text-lg text-primary placeholder:text-slate-400 shadow-sm transition-colors focus:border-accent focus:outline-none focus:ring-4 focus:ring-accent/10"
        >
    </div>

    <div
        x-show="open"
        x-transition.opacity
        style="display: none;"
        class="absolute z-30 mt-2 max-h-72 w-full overflow-y-auto rounded-2xl border border-secondary bg-white shadow-xl"
    >
        <template x-if="filtered.length === 0">
            <p class="px-5 py-4 text-sm text-slate-500">Nenhuma unidade encontrada — confira a grafia</p>
        </template>
        <template x-for="unit in filtered" :key="unit.slug">
            <button
                type="button"
                @click="select(unit)"
                class="flex w-full flex-col items-start px-5 py-3 text-left transition-colors hover:bg-secondary/30"
            >
                <span class="text-xs font-semibold uppercase tracking-wide text-purple" x-text="unit.category"></span>
                <span class="font-medium text-primary" x-text="unit.name"></span>
            </button>
        </template>
    </div>

    <x-input-error for="{{ $model }}" class="mt-2" />
</div>
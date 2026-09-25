<div
    x-data="{ dirty: false, original: @json($selectedCollections), selected: @json($selectedCollections) }"
    x-init="$watch('selected', () => dirty = selected.sort().toString() !== original.sort().toString())"
    x-on:saved.window="dirty = false; original = selected"
>
    <x-card class="overflow-hidden">
        <x-slot:header>
            <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                    {{ __('Grupo / Categoria') }}
                </h3>
                <div x-show="dirty" class="flex-shrink-0">
                    <button
                        wire:target="save"
                        wire:loading.delay.attr="disabled"
                        wire:click.prevent="save"
                        class="btn btn-link"
                    >
                        {{ __('Salvar') }}
                    </button>
                </div>
            </div>
        </x-slot:header>
        <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
            <div class="max-h-72 overflow-y-auto sidebar-scroll">
                @if($this->categories->count())
                    <ul class="divide-y divide-slate-100 dark:divide-white/5">
                        @foreach($this->categories as $category)
                            <div class="relative flex items-start px-4 py-3.5 sm:px-6 hover:bg-slate-50/80 dark:hover:bg-white/[0.03]">
                                <span
                                    onclick="event.preventDefault(); document.querySelector('#category-{{ $category->id }}').click()"
                                    class="absolute inset-0 cursor-pointer"
                                ></span>
                                <div class="min-w-0 flex-1 text-sm">
                                    <x-input-label
                                        for="category-{{ $category->id }}"
                                        :value="$category->title"
                                        class="!text-slate-600 dark:!text-slate-300"
                                    />
                                </div>
                                <div class="ml-3 flex items-center h-5">
                                    <x-input
                                        x-model.number="selected"
                                        wire:model.defer="selectedCollections"
                                        id="category-{{ $category->id }}"
                                        type="checkbox"
                                        value="{{ $category->id }}"
                                        class="h-4 w-4 !rounded !shadow-none text-accent-500 focus:ring-accent-500"
                                    />
                                </div>
                            </div>
                        @endforeach
                    </ul>
                @else
                    <p class="py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Nenhum Grupo disponível') }}
                    </p>
                @endif
            </div>
        </x-slot:content>
    </x-card>
</div>
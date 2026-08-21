<div class="border-b border-secondary/60 last:border-b-0">

    <button
        type="button"
        wire:click="selectCategory"
        class="flex w-full items-center gap-4 px-4 py-4 text-left transition-colors hover:bg-secondary/10 sm:px-6"
    >
        <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-xl bg-complement-500">
            @if($category->hasMedia('cover'))
                <img class="h-full w-full object-cover" src="{{ $category->getFirstMediaUrl('cover', 'thumb') }}" alt="{{ $category->title }}">
            @else
                <div class="flex h-full w-full items-center justify-center">
                    <x-heroicon-o-camera class="h-6 w-6 text-slate-400" />
                </div>
            @endif
        </div>

        <div class="flex-1">
            <span class="font-urbanist text-base font-semibold text-primary">{{ $category->title }}</span>
            @if($selectedProductId)
                <span class="ml-2 inline-flex items-center rounded-full bg-accent/10 px-2.5 py-0.5 text-xs font-semibold text-accent">
                    {{ $selectedQuantity }} de {{ $category->quantity }} escolhido{{ $selectedQuantity > 1 ? 's' : '' }}
                </span>
            @else
                <p class="text-sm text-slate-500">até {{ $category->quantity }} {{ $category->quantity > 1 ? 'unidades' : 'unidade' }}</p>
            @endif
        </div>

        <x-heroicon-s-chevron-down class="h-5 w-5 flex-shrink-0 text-purple transition-transform {{ $showProducts ? 'rotate-180' : '' }}" />
    </button>

    @if($showProducts)
        <div class="bg-complement-500/40 px-4 py-5 sm:px-6">
            <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-4 xl:grid-cols-5">
                @forelse($category->products as $prod)
                    @php
                        $isSelected = ($selectedProductId == $prod->id);
                        $variant = $prod->variants->first();
                        $variantId = $variant?->id ?? 0;
                        $unitWeight = $variant ? ($variant->weight_unit === 'g' ? ($variant->weight_value / 1000) : $variant->weight_value) : 0;
                    @endphp
                    <li wire:key="prod-{{ $category->id }}-{{ $prod->id }}" @class([
                        'group relative flex flex-col overflow-hidden rounded-2xl border-2 transition duration-150',
                        'border-accent shadow-lg shadow-accent/10 bg-accent/5' => $isSelected,
                        'border-secondary hover:border-accent/50 hover:shadow-md' => !$isSelected,
                    ])>
                        <div class="aspect-square bg-complement-500">
                            @if($prod->hasMedia('gallery'))
                                {{ $prod->getFirstMedia('gallery')('responsive')->attributes(['alt' => $prod->name, 'class' => 'h-full w-full object-cover object-center p-2']) }}
                            @else
                                <img src="{{ $prod->getFirstMediaUrl('gallery') }}" alt="{{ $prod->name }}" class="h-full w-full object-cover object-center">
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col items-center gap-2 p-4 text-center">
                            <h3 class="line-clamp-2 font-urbanist text-sm font-bold text-primary">{{ $prod->name }}</h3>
                            <p class="text-base font-semibold text-primary">
                                <x-money :amount="$prod->price" :currency="config('app.currency')" />
                            </p>

                            <div class="flex w-full flex-col items-center gap-2 pt-1">
                                @if($isSelected)
                                    <div class="flex items-center gap-3 rounded-full border border-accent bg-white px-3 py-1.5 shadow-sm">
                                        <button type="button" 
                                                wire:click="decrementQuantity({{ $variantId }}, {{ $unitWeight }})" 
                                                wire:loading.attr="disabled"
                                                class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-primary shadow-sm hover:bg-slate-200 disabled:opacity-50">
                                            −
                                        </button>
                                        <span class="w-4 text-center font-urbanist font-bold text-primary" wire:loading.class="opacity-40">
                                            {{ $selectedQuantity }}
                                        </span>
                                        <button type="button" 
                                                wire:click="incrementQuantity({{ $variantId }}, {{ $unitWeight }})" 
                                                @disabled($selectedQuantity >= $category->quantity) 
                                                wire:loading.attr="disabled"
                                                class="flex h-6 w-6 items-center justify-center rounded-full bg-accent text-white shadow-sm hover:bg-primary disabled:opacity-30">
                                            +
                                        </button>
                                    </div>
                                    <button type="button" 
                                            wire:click="removeSelection" 
                                            wire:loading.attr="disabled"
                                            class="text-xs text-slate-400 underline hover:text-accent disabled:opacity-50">
                                        Remover
                                    </button>
                                @else
                                    <button type="button" 
                                            wire:click="selectProduct({{ $prod->id }}, {{ $variantId }}, {{ $unitWeight }})" 
                                            wire:loading.attr="disabled"
                                            class="w-full rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-accent disabled:opacity-50">
                                        <span wire:loading.remove wire:target="selectProduct({{ $prod->id }}, {{ $variantId }}, {{ $unitWeight }})">
                                            {{ $selectedProductId ? __('Trocar por este') : __('Escolher') }}
                                        </span>
                                        <span wire:loading wire:target="selectProduct({{ $prod->id }}, {{ $variantId }}, {{ $unitWeight }})">
                                            Aguarde...
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-6 text-sm text-slate-500">Sem produtos disponíveis nesta categoria no momento.</li>
                @endforelse
            </ul>
        </div>
    @endif
</div>
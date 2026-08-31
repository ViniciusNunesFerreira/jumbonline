<div>
    

    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

            <div class="relative overflow-hidden rounded-3xl border border-secondary bg-gradient-to-br from-complement-500 via-white to-secondary/30 p-6 sm:p-10">
                <img src="{{ asset('img/estrelas.png') }}" alt="" class="pointer-events-none absolute -right-4 -top-4 w-20 opacity-70">
                <img src="{{ asset('img/maskote.png') }}" alt="" class="pointer-events-none absolute -bottom-6 right-4 hidden h-40 w-auto opacity-90 sm:block lg:h-48">

                <div class="relative max-w-xl">
                    <div class="inline-flex items-center gap-2 rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-accent">
                        <x-heroicon-s-check-badge class="h-4 w-4" /> Unidade confirmada
                    </div>

                    <h1 class="mt-3 font-urbanist text-2xl font-extrabold tracking-tight text-primary sm:text-3xl">
                        {{ $prison->name }}
                    </h1>

                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        <li class="flex items-center gap-2">
                            <x-heroicon-s-map-pin class="h-4 w-4 flex-shrink-0 text-accent" />
                            {{ $prison->logradouro }}, {{ $prison->numero }}, {{ $prison->bairro }} — {{ $prison->cidade }}/{{ $prison->uf }} · CEP {{ $prison->cep }}
                        </li>
                        @if($prison_phone_format)
                            <li class="flex items-center gap-2">
                                <x-heroicon-s-phone class="h-4 w-4 flex-shrink-0 text-accent" />
                                {{ $prison_phone_format }}
                            </li>
                        @endif
                    </ul>

                    <a href="/" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-purple hover:text-accent">
                        <x-heroicon-s-arrow-path class="h-4 w-4" /> Trocar unidade
                    </a>
                </div>

                @php $weightPercent = $weight_max > 0 ? min(100, ($weight / $weight_max) * 100) : 0; @endphp
                <div class="relative mt-8 max-w-xl">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-primary">Peso do Jumbo</span>
                        <span class="text-slate-500">{{ number_format($weight, 2) }}kg de {{ number_format($weight_max, 0) }}kg</span>
                    </div>
                    <div class="mt-2 h-3 w-full overflow-hidden rounded-full bg-white/70">
                        <div class="h-full rounded-full bg-accent transition-all duration-300" style="width: {{ $weightPercent }}%"></div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col items-center gap-3 text-center">
                <p class="font-urbanist text-lg font-bold text-primary sm:text-xl">
                    Monte o Jumbo escolhendo os produtos que deseja enviar
                </p>
                <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-1 text-xs text-slate-500">
                    <span class="flex items-center gap-1"><x-heroicon-s-shield-check class="h-3.5 w-3.5 text-accent" /> Compra segura</span>
                    <span class="flex items-center gap-1"><x-heroicon-s-truck class="h-3.5 w-3.5 text-accent" /> Entrega direta na unidade</span>
                    <span class="flex items-center gap-1"><x-heroicon-s-check-circle class="h-3.5 w-3.5 text-accent" /> Dentro das normas</span>
                </div>
            </div>

            <div class="mt-8 space-y-12">
                @forelse($collections as $collection)
                    <section>
                        <div class="mb-4 flex items-center gap-3">
                            <span class="h-8 w-1.5 rounded-full bg-accent"></span>
                            <h2 class="font-urbanist text-2xl font-extrabold tracking-tight text-primary">{{ $collection->title }}</h2>
                        </div>

                        <div class="overflow-hidden rounded-3xl border border-secondary bg-white">
                            @forelse($collection->categoriesPublished as $cat)
                                @php $itemData = $cartCategories[$cat->id] ?? null; @endphp
                                <livewire:guest.components.category-products :category="$cat" :selectedProductId="$itemData['product_id'] ?? null" :selectedQuantity="$itemData['quantity'] ?? 0" :wire:key="'category-item-'.$collection->id.'-'.$cat->id" />
                            @empty
                                <p class="p-6 text-sm text-slate-500">Sem categorias disponíveis.</p>
                            @endforelse
                        </div>
                    </section>
                @empty
                    <p class="text-center text-slate-500">Nenhum grupo de produtos cadastrado para esta unidade.</p>
                @endforelse
            </div>

        </div>
    </div>

    @if(count($cartCategories) > 0)
        <div wire:key="floating-cart-card" class="fixed bottom-4 left-4 z-30 w-72">
            <div class="rounded-2xl border border-secondary bg-white p-5 shadow-2xl shadow-primary/10">
                <div class="flex items-center gap-2 text-accent">
                    <x-heroicon-s-shopping-bag class="h-5 w-5" />
                    <span class="font-urbanist text-sm font-bold uppercase tracking-wide">Seu Jumbo</span>
                </div>
                <dl class="mt-3 space-y-1.5 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Itens escolhidos</dt>
                        <dd class="font-semibold text-primary">{{ count($cartCategories) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Subtotal</dt>
                        <dd class="font-semibold text-primary">
                            <x-money :amount="$subTotal" :currency="config('app.currency')" />
                        </dd>
                    </div>
                </dl>
                <button type="button" wire:click="openCart" class="mt-4 w-full rounded-full bg-accent py-3 text-sm font-semibold text-white transition-colors hover:bg-primary">
                    Ver Jumbo completo
                </button>
            </div>
        </div>
    @endif
</div>
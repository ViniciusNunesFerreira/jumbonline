<div x-data>
    <div class="bg-complement-500 w-full">

        @php $weightPercent = ($this->cart->weight ?? 0) > 0 ? min(100, (($this->cart->weight ?? 0) / 12) * 100) : 0; @endphp

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Stepper -->
            <ol class="flex items-center justify-between">
                @foreach(['Entrega' => 1, 'Detento' => 2, 'Pagamento' => 3] as $label => $stepNumber)
                    <li class="flex flex-1 items-center {{ !$loop->last ? 'after:ml-4 after:h-0.5 after:flex-1 after:content-[\'\'] ' . ($step > $stepNumber ? 'after:bg-accent' : 'after:bg-secondary') : '' }}">
                        <button
                            type="button"
                            @if($step >= $stepNumber) wire:click.prevent="changeTab('tabs-{{ Str::lower($label) }}')" @endif
                            @disabled($step < $stepNumber)
                            class="flex items-center gap-2 {{ $step < $stepNumber ? 'cursor-not-allowed opacity-40' : '' }}"
                        >
                            <span @class([
                                'flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full font-urbanist text-sm font-bold',
                                'bg-accent text-white' => $step >= $stepNumber,
                                'bg-white text-slate-400 border border-secondary' => $step < $stepNumber,
                            ])>
                                @if($step > $stepNumber)
                                    <x-heroicon-s-check class="h-4 w-4" />
                                @else
                                    {{ $stepNumber }}
                                @endif
                            </span>
                            <span @class([
                                'hidden text-sm font-semibold sm:block',
                                'text-primary' => $step >= $stepNumber,
                                'text-slate-400' => $step < $stepNumber,
                            ])>{{ $label }}</span>
                        </button>
                    </li>
                @endforeach
            </ol>

            
            <div class="mt-8">

                <div class="{{ 'tabs-entrega' == $currentTab ? '' : 'hidden' }}">
                    <h1 class="py-6 text-center font-urbanist text-2xl font-bold text-primary">
                        Confira os dados, onde o jumbo será entregue.
                    </h1>

                    <div class="space-y-4">
                        <div class="rounded-3xl border border-secondary bg-white p-6 sm:p-8">
                            <div class="flex items-center justify-between">
                                <h2 class="flex items-center gap-2 font-urbanist text-lg font-semibold text-primary">
                                    <x-heroicon-s-map-pin class="h-5 w-5 text-accent" /> Entregar na Unidade
                                </h2>
                                <a href="#" wire:click.prevent="limpaSession" class="flex items-center gap-1.5 text-sm font-semibold text-purple hover:text-accent">
                                    Trocar unidade <x-heroicon-s-arrow-path class="h-4 w-4" />
                                </a>
                            </div>

                            @if($prison)
                                <div class="mt-4">
                                    <h3 class="font-urbanist text-xl font-bold text-primary">{{ optional($prisonUnit)->name }}</h3>
                                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                        <li class="flex items-center gap-2">
                                            <x-heroicon-s-map-pin class="h-4 w-4 flex-shrink-0 text-accent" />
                                            {{ $prisonUnit->logradouro }}, {{ $prisonUnit->numero }}, {{ $prisonUnit->bairro }} — {{ $prisonUnit->cidade }}/{{ $prisonUnit->uf }} · CEP {{ $prisonUnit->cep }}
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <x-heroicon-s-phone class="h-4 w-4 flex-shrink-0 text-accent" />
                                            {{ phone(optional($prisonUnit)->phone, 'BR'); }}
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="mt-4">
                                    <x-prison-unit-select
                                        :categories="$this->prison_categories"
                                        model="prison"
                                        placeholder="Digite o nome da unidade prisional..."
                                    />
                                </div>
                            @endif
                        </div>

                        <div class="rounded-3xl border border-secondary bg-white p-6 sm:p-8">
                            <h2 class="flex items-center gap-2 font-urbanist text-lg font-semibold text-primary">
                                <x-heroicon-s-user class="h-5 w-5 text-accent" /> Informações de Contato
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Mantenha seu cadastro atualizado — usamos isso pra falar com você caso surja algum problema.
                            </p>

                            <form wire:submit.prevent="saveCustomer" class="mt-6 space-y-5">
                                <div>
                                    <x-input-label class="!text-sm !font-semibold !text-primary" for="full-name" value="Nome completo" />
                                    <x-input wire:model.defer="state.name" type="text" id="full-name" class="mt-1.5 block w-full" placeholder="Nome completo para contato" />
                                    <x-input-error for="state.name" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label class="!text-sm !font-semibold !text-primary" for="email" value="E-mail" />
                                    <x-input wire:model.defer="state.email" type="text" id="email" class="mt-1.5 block w-full" placeholder="Email" />
                                    <x-input-error for="state.email" class="mt-2" />
                                </div>

                                <div class="flex gap-3">
                                    <div class="w-32 flex-shrink-0">
                                        <x-input-label class="!text-sm !font-semibold !text-primary" for="state.phone_country" value="País" />
                                        <x-select wire:model.change="state.phone_country" id="state.phone_country" name="state.phone_country" autocomplete="country-name" class="mt-1.5 block w-full !h-[42px] text-sm">
                                            @foreach($this->availableCountries as $country)
                                                <option value="{{ $country->iso2 }}">{{ $country->emoji }} +{{ $country->phonecode }}</option>
                                            @endforeach
                                        </x-select>
                                        <x-input-error for="state.phone_country" class="mt-2" />
                                    </div>
                                    <div class="flex-1">
                                        <x-input-label class="!text-sm !font-semibold !text-primary" for="phone" value="Telefone (WhatsApp)" />
                                        <x-input wire:model.defer="state.phone" type="text" id="phone" class="mt-1.5 block w-full" x-mask="(99) 99999-9999" placeholder="(11) 99999-9999" />
                                        <x-input-error for="state.phone" class="mt-2" />
                                    </div>
                                </div>

                                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-full bg-accent py-3.5 text-sm font-semibold text-white shadow-lg shadow-accent/30 transition-transform hover:scale-[1.01] hover:bg-primary sm:w-auto sm:px-8">
                                    Continuar <x-heroicon-s-arrow-right class="h-4 w-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="{{ 'tabs-detento' == $currentTab ? '' : 'hidden' }}">
                    <h1 class="py-6 text-center font-urbanist text-2xl font-bold text-primary">
                        Informe os dados do detento que irá receber o jumbo.
                    </h1>
                    <livewire:guest.purchase-components.cadastro-detento :prison="$this->prisonUnit" />
                </div>

                <div class="{{ 'tabs-pagamento' == $currentTab ? '' : 'hidden' }}">
                    <h1 class="py-6 text-center font-urbanist text-2xl font-bold text-primary">
                        Falta pouco para concluir e finalizar seu pedido.
                    </h1>

                    <div class="rounded-3xl border border-secondary bg-white p-6 sm:p-8">
                        <h2 class="font-urbanist text-lg font-bold text-primary">Resumo do Pedido</h2>

                        <ul role="list" class="mt-4 divide-y divide-secondary/60">
                            @foreach($cartItems as $item)
                                <li class="flex items-center gap-4 py-4">
                                    <div class="relative h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl border border-secondary bg-complement-500">
                                        @if($item->variant->hasMedia('image'))
                                            {{ $item->variant->getFirstMedia('image')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-full w-full object-cover']) }}
                                        @elseif($item->product->hasMedia('gallery'))
                                            {{ $item->product->getFirstMedia('gallery')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-full w-full object-cover']) }}
                                        @else
                                            <x-heroicon-o-camera class="h-full w-full p-4 text-slate-400" />
                                        @endif
                                        <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-accent text-[10px] font-bold text-white">{{ $item->quantity }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="line-clamp-2 text-sm font-medium text-primary">{{ $item->product->name }}</p>
                                        @if($item->variant->variantAttributes->count())
                                            <p class="text-xs text-slate-500">
                                                @foreach($item->variant->variantAttributes as $attribute){{ $attribute->optionValue->label }}{{ !$loop->last ? ' · ' : '' }}@endforeach
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right text-sm font-semibold text-primary">
                                        @if($item->discount)
                                            <span class="block text-xs text-slate-400 line-through"><x-money :amount="$item->subtotal" :currency="config('app.currency')" /></span>
                                            <x-money :amount="$item->discountedPrice" :currency="config('app.currency')" />
                                        @else
                                            <x-money :amount="$item->subtotal" :currency="config('app.currency')" />
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <dl class="space-y-3 border-t border-secondary pt-4 text-sm">
                            <div class="flex justify-between"><dt class="text-slate-500">Subtotal</dt><dd class="font-medium text-primary"><x-money :amount="$cart->subtotal" :currency="config('app.currency')" /></dd></div>
                            <div class="flex justify-between"><dt class="text-slate-500">Frete</dt><dd class="font-medium text-primary"><x-money :amount="optional($this->order)->shipping_price" :currency="config('app.currency')" /></dd></div>
                            <div class="flex justify-between border-t border-secondary pt-3 text-base"><dt class="font-bold text-primary">Total</dt><dd class="font-bold text-primary"><x-money :amount="optional($this->order)->total" :currency="config('app.currency')" /></dd></div>
                        </dl>

                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row">
                            <button type="button" wire:click.prevent="changeTab('tabs-detento')" class="flex flex-1 items-center justify-center gap-2 rounded-full border border-secondary py-3.5 text-sm font-semibold text-primary hover:bg-complement-500">
                                <x-heroicon-s-chevron-left class="h-4 w-4" /> Voltar
                            </button>
                            <button wire:click.prevent="preparePayment()" class="flex flex-1 items-center justify-center gap-2 rounded-full bg-accent py-3.5 text-sm font-semibold text-white shadow-lg shadow-accent/30 hover:bg-primary">
                                Confirmar e Pagar <x-heroicon-s-arrow-right class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>

            </div>

    
        </div>

        <!-- Barra fixa no rodapé -->
        <div class="fixed inset-x-0 bottom-0 z-30 border-t border-secondary bg-white/95 shadow-[0_-4px_16px_rgba(0,0,0,0.06)] backdrop-blur-sm">
            <div class="mx-auto flex max-w-2xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="hidden text-sm text-slate-500 sm:inline">{{ $cartItems->count() }} {{ $cartItems->count() == 1 ? 'item' : 'itens' }}</span>
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-16 overflow-hidden rounded-full bg-complement-500 sm:w-24">
                            <div class="h-full rounded-full bg-accent transition-all duration-300" style="width: {{ $weightPercent }}%"></div>
                        </div>
                        <span class="text-xs text-slate-500">{{ number_format($this->cart->weight ?? 0, 2) }}/12kg</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="block text-[11px] leading-none text-slate-500">Subtotal</span>
                    <span class="font-urbanist text-lg font-bold text-primary">
                        <x-money :amount="$cart->subtotal" :currency="config('app.currency')" />
                    </span>
                </div>
            </div>
        </div>
        
    </div>
</div>
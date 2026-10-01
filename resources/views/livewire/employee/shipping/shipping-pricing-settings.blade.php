<div>
    <form wire:submit.prevent="save">
        <x-card>
            <x-slot:header>
                <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Preço do frete ao cliente') }}</h3>
                <p class="mt-0.5 text-xs text-slate-400">{{ __('Vale para o checkout do site e para o PDV.') }}</p>
            </x-slot:header>
            <x-slot:content class="space-y-5">
                @unless($settingsDisponiveis)
                    <div class="flex items-start gap-2 rounded-xl bg-amber-50 p-3 text-xs text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">
                        <x-heroicon-m-exclamation-triangle class="mt-px h-4 w-4 shrink-0" />
                        {{ __('Parâmetros ainda não criados no banco — o sistema está usando a política padrão. Execute "php artisan migrate".') }}
                    </div>
                @endunless

                <fieldset>
                    <legend class="sr-only">{{ __('Política') }}</legend>
                    <div class="space-y-2">
                        @foreach([
                            \App\Services\Shipping\CustomerFreightPricing::MODO_BALCAO => [__('Tabela de balcão dos Correios'), __('O cliente paga o preço de cliente final. O desconto do contrato cobre embalagem e transporte. Se a tabela falhar, usa contrato + margem.')],
                            \App\Services\Shipping\CustomerFreightPricing::MODO_CONTRATO_MARGEM => [__('Contrato + margem'), __('O cliente paga o preço de contrato acrescido da margem abaixo, com valor mínimo por envio.')],
                        ] as $valor => [$titulo, $descricao])
                            <label wire:key="modo-{{ $valor }}" @class([
                                'flex cursor-pointer gap-3 rounded-xl p-3 ring-1 transition-colors',
                                'bg-accent-50 ring-2 ring-accent-500 dark:bg-accent-500/10' => $modo === $valor,
                                'ring-slate-200 hover:bg-slate-50 dark:ring-white/10 dark:hover:bg-white/5' => $modo !== $valor,
                            ])>
                                <input wire:model="modo" type="radio" value="{{ $valor }}" class="mt-0.5 h-4 w-4 border-slate-300 text-accent-500 focus:ring-accent-500" />
                                <span class="min-w-0">
                                    <span class="block text-sm font-semibold text-primary dark:text-slate-200">{{ $titulo }}</span>
                                    <span class="mt-0.5 block text-xs text-slate-500 dark:text-slate-400">{{ $descricao }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error for="modo" class="mt-2" />
                </fieldset>

                <div>
                    <p class="text-sm font-semibold text-primary dark:text-slate-300">
                        {{ $modo === \App\Services\Shipping\CustomerFreightPricing::MODO_BALCAO ? __('Margem de contingência') : __('Margem sobre o contrato') }}
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ $modo === \App\Services\Shipping\CustomerFreightPricing::MODO_BALCAO ? __('Usada só se a tabela de balcão não responder.') : __('O cliente paga o contrato + o maior entre o percentual e o mínimo.') }}
                    </p>
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label for="pricing_percentual" :value="__('Percentual')" />
                            <div class="relative mt-1">
                                <x-input wire:model.debounce.400ms="percentual" id="pricing_percentual" type="number" step="0.5" min="0" max="200" class="block w-full rounded-xl pr-8 tabular-nums sm:text-sm" />
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-slate-400">%</span>
                            </div>
                            <x-input-error for="percentual" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="pricing_minimo" :value="__('Mínimo por envio')" />
                            <div class="relative mt-1">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-xs text-slate-400">R$</span>
                                <x-input wire:model.debounce.400ms="minimo" id="pricing_minimo" type="number" step="0.10" min="0" max="200" class="block w-full rounded-xl pl-9 tabular-nums sm:text-sm" />
                            </div>
                            <x-input-error for="minimo" class="mt-1.5" />
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-inset ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                    <div class="flex items-center justify-between gap-3">
                        <x-input-label for="pricing_exemplo" :value="__('Exemplo: preço de contrato')" />
                        <x-loading-spinner wire:loading.delay wire:target="contratoExemplo,percentual,minimo" class="h-4 w-4 text-accent-500" />
                    </div>
                    <div class="relative mt-1">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-xs text-slate-400">R$</span>
                        <x-input wire:model.debounce.400ms="contratoExemplo" id="pricing_exemplo" type="number" step="0.01" min="0" class="block w-full rounded-xl pl-9 tabular-nums sm:text-sm" />
                    </div>
                    @if($exemplo)
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">
                            {{ __('Contrato') }} <span class="font-semibold tabular-nums">R$ {{ number_format($exemplo['contrato'], 2, ',', '.') }}</span>
                            → {{ __('cliente') }} <span class="font-bold tabular-nums text-primary dark:text-white">R$ {{ number_format($exemplo['cliente'], 2, ',', '.') }}</span>
                        </p>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Margem') }} R$ {{ number_format($exemplo['margem'], 2, ',', '.') }}
                            {{ $exemplo['usou_minimo'] ? '(' . __('aplicado o mínimo') . ')' : '(' . __('aplicado o percentual') . ')' }}
                        </p>
                    @endif
                </div>
            </x-slot:content>
            <x-slot:footer class="flex justify-end">
                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="btn btn-primary !rounded-xl">
                    <x-loading-spinner wire:loading wire:target="save" class="-ml-1 mr-2 h-4 w-4" />
                    {{ __('Salvar política') }}
                </button>
            </x-slot:footer>
        </x-card>
    </form>
</div>
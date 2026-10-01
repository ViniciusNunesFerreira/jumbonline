
@php
    $c = (int) ($conferencia['comprimento'] ?: 0);
    $l = (int) ($conferencia['largura'] ?: 0);
    $a = (int) ($conferencia['altura'] ?: 0);
    $pesoKg = is_numeric($conferencia['peso_kg']) ? (float) $conferencia['peso_kg'] : 0;
    $volume = $c * $l * $a;
    $cubicoKg = $volume / 6000;
    $tarifadoKg = max($pesoKg, $cubicoKg);
    $soma = $c + $l + $a;

    $est = $conferenciaEstimativa;
    $estPesoKg = isset($est['peso_gramas']) ? $est['peso_gramas'] / 1000 : 0;
    $estCubicoKg = isset($est['comprimento']) ? ($est['comprimento'] * $est['largura'] * $est['altura']) / 6000 : 0;

    $alterado = ! empty($est) && (
        $c !== (int) ($est['comprimento'] ?? 0)
        || $l !== (int) ($est['largura'] ?? 0)
        || $a !== (int) ($est['altura'] ?? 0)
        || (int) round($pesoKg * 1000) !== (int) ($est['peso_gramas'] ?? 0)
    );

    $delta = ($custoConferido !== null && $custoEstimado !== null) ? $custoConferido - $custoEstimado : null;
    $freteCobrado = (float) ($conferenciaPedido['frete_cobrado'] ?? 0);
    $margem = $custoConferido !== null ? $freteCobrado - $custoConferido : null;
    $caixaSelecionada = $conferencia['caixa_id'] ? $caixasConferencia->firstWhere('id', (int) $conferencia['caixa_id']) : null;
@endphp

<x-modal wire:model="mostrarConferencia" maxWidth="2xl">
    {{-- Cabeçalho --}}
    <div class="flex items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-accent-50 dark:bg-accent-500/10">
                <x-heroicon-o-cube class="h-6 w-6 text-accent-500" />
            </span>
            <div class="min-w-0">
                <h3 id="modal-title" class="font-display text-lg font-semibold leading-6 text-primary dark:text-slate-100">
                    {{ __('Conferir embalagem') }}
                </h3>
                <p class="mt-0.5 truncate text-sm text-slate-500 dark:text-slate-400">
                    #{{ $conferenciaPedido['id'] ?? '' }} — {{ $conferenciaPedido['cliente'] ?? '' }}
                    @if(! empty($conferenciaPedido['destino']))
                        <span class="text-slate-300 dark:text-slate-600">·</span> {{ $conferenciaPedido['destino'] }}
                    @endif
                </p>
            </div>
        </div>
        <button x-on:click="show = false" type="button" class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/5">
            <span class="sr-only">{{ __('Fechar') }}</span>
            <x-heroicon-m-x-mark class="h-5 w-5" />
        </button>
    </div>

    {{-- Sugestão do sistema --}}
    @if(! empty($est))
        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-slate-50 p-4 ring-1 ring-inset ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
            <div class="min-w-0">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ __('Sugestão do sistema') }}</p>
                <p class="mt-1 text-sm font-semibold text-primary dark:text-slate-200">
                    {{ $est['caixa_nome'] ?? __('Estimativa proporcional') }}
                    <span class="font-normal tabular-nums text-slate-500 dark:text-slate-400">· {{ $est['comprimento'] }} × {{ $est['largura'] }} × {{ $est['altura'] }} cm</span>
                </p>
                <p class="mt-0.5 text-xs tabular-nums text-slate-500 dark:text-slate-400">
                    {{ __('Peso dos itens (cadastro)') }}: {{ number_format($estPesoKg, 3, ',', '.') }} kg
                    · {{ __('peso cúbico') }} {{ number_format($estCubicoKg, 2, ',', '.') }} kg
                    · {{ trans_choice(':count item|:count itens', $conferenciaPedido['itens'] ?? 0) }}
                </p>
            </div>
            @if(! empty($est['excede_capacidade']))
                <x-badge type="warning" size="sm">{{ __('Acima da maior caixa — confira') }}</x-badge>
            @elseif($alterado)
                <button wire:click="restaurarEstimativa" type="button" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-arrow-uturn-left class="mr-1 h-3.5 w-3.5" />
                    {{ __('Voltar à sugestão') }}
                </button>
            @else
                <x-badge type="primary" size="sm">{{ __('Em uso') }}</x-badge>
            @endif
        </div>
    @endif

    {{-- Caixa usada --}}
    @if($caixasConferencia->isNotEmpty())
        <div class="mt-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-primary dark:text-slate-200">{{ __('Caixa usada no despacho') }}</p>
                @if(! $caixaSelecionada)
                    <x-badge type="default" size="xs">{{ __('Medida personalizada') }}</x-badge>
                @endif
            </div>
            <div class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-3">
                @foreach($caixasConferencia as $caixa)
                    @php $ativa = (int) $conferencia['caixa_id'] === $caixa->id; @endphp
                    <button
                        wire:key="conferencia-caixa-{{ $caixa->id }}"
                        wire:click="selecionarCaixa({{ $caixa->id }})"
                        wire:loading.attr="disabled"
                        wire:target="selecionarCaixa"
                        type="button"
                        @class([
                            'group flex items-center gap-3 rounded-xl p-3 text-left ring-1 transition-all disabled:opacity-60',
                            'bg-accent-50 ring-2 ring-accent-500 dark:bg-accent-500/10' => $ativa,
                            'bg-white ring-slate-200 hover:ring-accent-300 hover:bg-slate-50 dark:bg-slate-900 dark:ring-white/10 dark:hover:bg-white/5' => ! $ativa,
                        ])
                    >
                        <span @class([
                            'flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xs font-bold',
                            'bg-accent-500 text-white' => $ativa,
                            'bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-slate-300' => ! $ativa,
                        ])>{{ $caixa->code }}</span>
                        <span class="min-w-0">
                            <span class="block truncate text-xs font-semibold text-primary dark:text-slate-200">{{ $caixa->name }}</span>
                            <span class="block text-[11px] tabular-nums text-slate-500 dark:text-slate-400">{{ $caixa->length_cm }}×{{ $caixa->width_cm }}×{{ $caixa->height_cm }}</span>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Medidas reais --}}
    <div class="mt-6">
        <p class="text-sm font-semibold text-primary dark:text-slate-200">{{ __('Medidas e peso reais') }}</p>
        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Meça a caixa fechada (externa) e pese já embalada.') }}</p>

        <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach(['comprimento' => __('Comprimento'), 'largura' => __('Largura'), 'altura' => __('Altura')] as $campo => $rotulo)
                <div>
                    <x-input-label :for="'conf_' . $campo" :value="$rotulo" />
                    <div class="relative mt-1">
                        <x-input wire:model.lazy="conferencia.{{ $campo }}" :id="'conf_' . $campo" type="number" inputmode="numeric" min="1" max="100" step="1" class="block w-full rounded-xl pr-10 tabular-nums sm:text-sm" />
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-slate-400">cm</span>
                    </div>
                    <x-input-error :for="'conferencia.' . $campo" class="mt-1.5" />
                </div>
            @endforeach
            <div>
                <x-input-label for="conf_peso" :value="__('Peso')" />
                <div class="relative mt-1">
                    <x-input wire:model.lazy="conferencia.peso_kg" id="conf_peso" type="number" inputmode="decimal" min="0.001" max="30" step="0.001" class="block w-full rounded-xl pr-10 tabular-nums sm:text-sm" />
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-slate-400">kg</span>
                </div>
                <x-input-error for="conferencia.peso_kg" class="mt-1.5" />
            </div>
        </div>

        @if($volume > 0)
            <div class="mt-3 flex flex-wrap gap-2">
                <x-badge type="default" size="sm">{{ __('Peso cúbico') }}: {{ number_format($cubicoKg, 2, ',', '.') }} kg</x-badge>
                <x-badge :type="$cubicoKg > $pesoKg ? 'warning' : 'default'" size="sm">
                    {{ __('Peso considerado') }} ≈ {{ number_format($tarifadoKg, 2, ',', '.') }} kg {{ $cubicoKg > $pesoKg ? '(' . __('cúbico') . ')' : '' }}
                </x-badge>
                <x-badge :type="$soma > 200 ? 'danger' : 'default'" size="sm">{{ __('Soma') }}: {{ $soma }} / 200 cm</x-badge>
            </div>
        @endif
    </div>

    {{-- Frete --}}
    <div class="mt-6 rounded-2xl ring-1 ring-slate-200/70 dark:ring-white/10">
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-4 py-3 dark:border-white/5">
            <p class="text-sm font-semibold text-primary dark:text-slate-200">{{ __('Frete') }}</p>
            <button wire:click="cotarFrete" wire:loading.attr="disabled" wire:target="cotarFrete,confirmarPostagem" type="button" class="btn btn-default btn-xs !rounded-xl">
                <x-loading-spinner wire:loading wire:target="cotarFrete" class="mr-1.5 h-3.5 w-3.5" />
                <x-heroicon-m-arrow-path wire:loading.remove wire:target="cotarFrete" class="mr-1 h-3.5 w-3.5" />
                {{ __('Recalcular frete') }}
            </button>
        </div>

        <div class="relative grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0 dark:divide-white/5">
            <div wire:loading.flex wire:target="cotarFrete" class="absolute inset-0 z-10 hidden items-center justify-center gap-2 rounded-b-2xl bg-white/70 text-sm text-slate-500 backdrop-blur-[1px] dark:bg-slate-800/70 dark:text-slate-300">
                <x-loading-spinner class="h-4 w-4 text-accent-500" />
                {{ __('Consultando os Correios...') }}
            </div>

            <div class="px-4 py-3">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ __('Cobrado do cliente') }}</p>
                <p class="mt-1 text-lg font-bold tabular-nums text-primary dark:text-slate-100">R$ {{ number_format($freteCobrado, 2, ',', '.') }}</p>
                <p class="text-[11px] text-slate-400">{{ $freteCobrado > 0 ? __('no checkout') : __('frete grátis / balcão') }}</p>
            </div>

            <div class="px-4 py-3">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ __('Correios · sugestão') }}</p>
                <p class="mt-1 text-lg font-bold tabular-nums text-slate-600 dark:text-slate-300">
                    {{ $custoEstimado !== null ? 'R$ ' . number_format($custoEstimado, 2, ',', '.') : '—' }}
                </p>
                <p class="text-[11px] text-slate-400">{{ __('custo da embalagem estimada') }}</p>
            </div>

            <div class="px-4 py-3">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ __('Correios · medidas reais') }}</p>
                <p @class(['mt-1 text-lg font-bold tabular-nums', 'text-primary dark:text-slate-100' => ! $cotacaoDesatualizada, 'text-slate-300 dark:text-slate-600' => $cotacaoDesatualizada])>
                    {{ $custoConferido !== null ? 'R$ ' . number_format($custoConferido, 2, ',', '.') : '—' }}
                </p>
                @if($delta !== null && abs($delta) >= 0.01)
                    <p @class(['text-[11px] font-semibold tabular-nums', 'text-red-600 dark:text-red-400' => $delta > 0, 'text-emerald-600 dark:text-emerald-400' => $delta < 0])>
                        {{ $delta > 0 ? '+' : '−' }} R$ {{ number_format(abs($delta), 2, ',', '.') }} {{ __('vs. sugestão') }}
                    </p>
                @else
                    <p class="text-[11px] text-slate-400">{{ $cotacaoDesatualizada ? __('recalcule para atualizar') : __('igual à sugestão') }}</p>
                @endif
            </div>
        </div>

        @if($cotacaoDesatualizada && $alterado)
            <div class="flex items-start gap-2 border-t border-slate-100 bg-amber-50/70 px-4 py-2.5 text-xs text-amber-800 dark:border-white/5 dark:bg-amber-500/10 dark:text-amber-300">
                <x-heroicon-m-exclamation-triangle class="mt-px h-4 w-4 shrink-0" />
                {{ __('Medidas alteradas — clique em "Recalcular frete" para ver o novo custo antes de confirmar.') }}
            </div>
        @elseif($erroCotacao)
            <div class="flex items-start gap-2 border-t border-slate-100 bg-amber-50/70 px-4 py-2.5 text-xs text-amber-800 dark:border-white/5 dark:bg-amber-500/10 dark:text-amber-300">
                <x-heroicon-m-exclamation-triangle class="mt-px h-4 w-4 shrink-0" />
                {{ $erroCotacao }}
            </div>
        @elseif($margem !== null && $freteCobrado > 0 && $margem < 0)
            <div class="flex items-start gap-2 border-t border-slate-100 bg-red-50/70 px-4 py-2.5 text-xs text-red-700 dark:border-white/5 dark:bg-red-500/10 dark:text-red-300">
                <x-heroicon-m-arrow-trending-down class="mt-px h-4 w-4 shrink-0" />
                {{ __('O custo real supera o frete cobrado do cliente em') }} R$ {{ number_format(abs($margem), 2, ',', '.') }}.
            </div>
        @endif
    </div>

    <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">
        {{ __('Prévia pela tabela de preço dos Correios. O valor oficial do envio vem na resposta da pré-postagem e fica registrado como custo do frete.') }}
    </p>

    {{-- Rodapé --}}
    <div class="mt-5 flex flex-col-reverse gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between dark:border-white/5">
        <p class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
            <x-heroicon-m-shield-exclamation class="h-4 w-4 text-amber-500" />
            {{ __('Registra um envio real nos Correios.') }}
        </p>
        <div class="flex flex-col-reverse gap-2 sm:flex-row">
            <button x-on:click="show = false" type="button" class="btn btn-invisible w-full sm:w-auto">
                {{ __('Cancelar') }}
            </button>
            <button wire:click="confirmarPostagem" wire:loading.attr="disabled" wire:target="confirmarPostagem,cotarFrete" type="button" class="btn btn-primary w-full !rounded-xl sm:w-auto">
                <x-loading-spinner wire:loading wire:target="confirmarPostagem" class="-ml-1 mr-2 h-4 w-4" />
                <span wire:loading.remove wire:target="confirmarPostagem">{{ __('Confirmar e gerar pré-postagem') }}</span>
                <span wire:loading wire:target="confirmarPostagem">{{ __('Gerando...') }}</span>
            </button>
        </div>
    </div>
</x-modal>
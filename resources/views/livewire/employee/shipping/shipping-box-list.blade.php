<div>
    <x-slot:title>
        {{ __('Embalagens') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ __('Embalagens') }}
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Caixas reais usadas no despacho. O frete do site, do PDV e a pré-postagem encaixam cada pedido na menor caixa ativa que comporte o volume estimado.') }}
                </p>
            </div>
            <div class="mt-4 flex sm:mt-0 sm:ml-4">
                <a href="{{ route('employee.correios.postagem') }}" class="btn btn-default !rounded-xl">
                    <x-heroicon-m-arrow-left class="-ml-1 mr-1.5 h-4 w-4" />
                    {{ __('Correios') }}
                </a>
                <button wire:click.prevent="create" type="button" class="btn btn-primary !rounded-xl ml-3">
                    <x-heroicon-m-plus class="-ml-1 mr-1.5 h-5 w-5" />
                    {{ __('Nova embalagem') }}
                </button>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- LISTA DE CAIXAS --}}
            <div class="lg:col-span-2">
                <x-card class="overflow-hidden">
                    <x-slot:header>
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Caixas cadastradas') }}</h3>
                                <p class="mt-0.5 text-xs text-slate-400">{{ __('Ordenadas da menor para a maior. Medidas internas, em centímetros.') }}</p>
                            </div>
                            <x-badge type="primary" size="sm">
                                {{ trans_choice(':count ativa|:count ativas', $boxes->where('is_active', true)->count()) }}
                            </x-badge>
                        </div>
                    </x-slot:header>
                    <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                        <div class="relative overflow-x-auto">
                            <div wire:loading.delay wire:target="toggleActive,delete,save" class="absolute inset-0 z-10 bg-white/60 backdrop-blur-[1px] dark:bg-slate-900/60">
                                <div class="flex h-full items-center justify-center gap-2 text-sm text-slate-500 dark:text-slate-300">
                                    <x-loading-spinner class="h-4 w-4" />
                                    {{ __('Atualizando embalagens...') }}
                                </div>
                            </div>

                            @if($boxes->isEmpty())
                                <div class="px-6 py-14 text-center">
                                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent-50 dark:bg-accent-500/10">
                                        <x-heroicon-o-cube class="h-7 w-7 text-accent-500" />
                                    </span>
                                    <h3 class="mt-4 text-base font-semibold text-primary dark:text-slate-200">{{ __('Nenhuma embalagem cadastrada') }}</h3>
                                    <p class="mx-auto mt-1.5 max-w-md text-sm text-slate-500 dark:text-slate-400">
                                        {{ __('Sem caixas, o frete é cotado por uma estimativa proporcional ao peso. Cadastre as caixas do balcão para cotações mais precisas.') }}
                                    </p>
                                    <button wire:click.prevent="create" type="button" class="btn btn-primary !rounded-xl mt-6">
                                        <x-heroicon-m-plus class="-ml-1 mr-1.5 h-5 w-5" />
                                        {{ __('Cadastrar primeira caixa') }}
                                    </button>
                                </div>
                            @else
                                <table class="min-w-full divide-y divide-slate-100 dark:divide-white/5">
                                    <thead>
                                        <tr class="border-b border-slate-100 dark:border-white/5">
                                            <th scope="col" class="px-4 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap sm:pl-6 dark:text-slate-500">{{ __('Embalagem') }}</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">{{ __('Dimensões') }}</th>
                                            <th scope="col" class="px-3 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">{{ __('Volume') }}</th>
                                            <th scope="col" class="px-3 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">{{ __('Comporta até') }}</th>
                                            <th scope="col" class="px-3 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">{{ __('Peso cúbico') }}</th>
                                            <th scope="col" class="px-3 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap dark:text-slate-500">{{ __('Status') }}</th>
                                            <th scope="col" class="py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">{{ __('Ações') }}</span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                        @foreach($boxes as $box)
                                            @php
                                                $capacidade = $estimator->capacidadeEstimadaGramas($box->volume_cm3);
                                                if ($box->max_weight_g) {
                                                    $capacidade = min($capacidade, $box->max_weight_g);
                                                }
                                            @endphp
                                            <tr wire:key="shipping-box-{{ $box->id }}" @class(['transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.03]', 'opacity-60' => ! $box->is_active])>
                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 sm:pl-6">
                                                    <div class="flex items-center gap-3">
                                                        <span @class(['flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-xs font-bold', 'bg-accent-50 text-accent-700 dark:bg-accent-500/10 dark:text-accent-400' => $box->is_active, 'bg-slate-100 text-slate-400 dark:bg-white/5' => ! $box->is_active])>
                                                            {{ $box->code }}
                                                        </span>
                                                        <div class="min-w-0">
                                                            <p class="truncate text-sm font-semibold text-primary dark:text-slate-200">{{ $box->name }}</p>
                                                            <p class="text-xs text-slate-400">
                                                                {{ trans_choice(':count envio registrado|:count envios registrados', $box->shipments_count) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm font-medium tabular-nums text-slate-700 dark:text-slate-300">
                                                    {{ $box->dimensions_label }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-right text-sm tabular-nums text-slate-500 dark:text-slate-400">
                                                    {{ number_format($box->volume_cm3 / 1000, 1, ',', '.') }} L
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-right text-sm tabular-nums text-slate-500 dark:text-slate-400">
                                                    ~{{ number_format($capacidade / 1000, 2, ',', '.') }} kg
                                                    @if($box->max_weight_g && $box->max_weight_g <= $estimator->capacidadeEstimadaGramas($box->volume_cm3))
                                                        <span class="block text-[11px] text-amber-600 dark:text-amber-400">{{ __('limitado pelo peso máx.') }}</span>
                                                    @endif
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-right text-sm tabular-nums text-slate-500 dark:text-slate-400">
                                                    {{ number_format($box->cubic_weight_kg, 2, ',', '.') }} kg
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-center">
                                                    <button
                                                        wire:click="toggleActive({{ $box->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="toggleActive({{ $box->id }})"
                                                        type="button"
                                                        role="switch"
                                                        aria-checked="{{ $box->is_active ? 'true' : 'false' }}"
                                                        @class(['relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2 disabled:opacity-50 dark:focus:ring-offset-slate-900', 'bg-accent-500' => $box->is_active, 'bg-slate-200 dark:bg-white/10' => ! $box->is_active])
                                                    >
                                                        <span class="sr-only">{{ __('Ativar/desativar') }}</span>
                                                        <span @class(['pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out', 'translate-x-5' => $box->is_active, 'translate-x-0' => ! $box->is_active])></span>
                                                    </button>
                                                </td>
                                                <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right sm:pr-6">
                                                    <div class="flex items-center justify-end gap-1">
                                                        <button wire:click="edit({{ $box->id }})" type="button" class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-accent-500 dark:hover:bg-white/5" title="{{ __('Editar') }}">
                                                            <x-heroicon-o-pencil-square class="h-5 w-5" />
                                                        </button>
                                                        <button wire:click="confirmDelete({{ $box->id }})" type="button" class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500/10" title="{{ __('Excluir') }}">
                                                            <x-heroicon-o-trash class="h-5 w-5" />
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </x-slot:content>
                </x-card>

                <div class="mt-4 flex items-start gap-3 rounded-2xl border border-sky-100 bg-sky-50/70 p-4 text-sm text-sky-800 dark:border-sky-500/20 dark:bg-sky-500/10 dark:text-sky-300">
                    <x-heroicon-o-information-circle class="mt-0.5 h-5 w-5 shrink-0" />
                    <p>
                        {{ __('Os Correios cobram pelo maior valor entre o peso real e o peso cúbico (C × L × A ÷ 6000). Uma caixa grande demais para o pedido encarece o frete mesmo com poucos itens dentro — por isso o sistema sempre escolhe a menor caixa que comporte o pedido.') }}
                    </p>
                </div>
            </div>

            {{-- PARÂMETROS + SIMULADOR --}}
            <div class="space-y-6">
                
                @livewire('employee.shipping.shipping-pricing-settings', key('shipping-pricing-settings'))
                
                <form wire:submit.prevent="saveSettings">
                    <x-card>
                        <x-slot:header>
                            <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Parâmetros da estimativa') }}</h3>
                            <p class="mt-0.5 text-xs text-slate-400">{{ __('Usados quando o produto não tem dimensões cadastradas.') }}</p>
                        </x-slot:header>
                        <x-slot:content class="space-y-5">
                            <div>
                                <x-input-label for="estimated_density" :value="__('Densidade média (g/cm³)')" />
                                <x-input wire:model.debounce.400ms="settings.estimated_density" id="estimated_density" type="number" step="0.01" min="0.05" max="1.5" class="mt-1 block w-full rounded-xl tabular-nums sm:text-sm" />
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('Faixa típica de e-commerce: 0,20 a 0,25. Valores menores geram caixas maiores (mais conservador).') }}</p>
                                <x-input-error for="settings.estimated_density" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="packaging_margin_percent" :value="__('Margem de embalagem (%)')" />
                                <x-input wire:model.debounce.400ms="settings.packaging_margin_percent" id="packaging_margin_percent" type="number" step="1" min="0" max="50" class="mt-1 block w-full rounded-xl tabular-nums sm:text-sm" />
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('Folga para proteção e acomodação. Recomendado: 10% a 15%.') }}</p>
                                <x-input-error for="settings.packaging_margin_percent" class="mt-2" />
                            </div>
                        </x-slot:content>
                        <x-slot:footer class="flex justify-end">
                            <button type="submit" wire:loading.attr="disabled" wire:target="saveSettings" class="btn btn-primary !rounded-xl">
                                <x-loading-spinner wire:loading wire:target="saveSettings" class="-ml-1 mr-2 h-4 w-4" />
                                {{ __('Salvar parâmetros') }}
                            </button>
                        </x-slot:footer>
                    </x-card>
                </form>

                <x-card>
                    <x-slot:header>
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <h3 class="text-base font-semibold text-primary dark:text-slate-200">{{ __('Simulador') }}</h3>
                                <p class="mt-0.5 text-xs text-slate-400">{{ __('Prévia com os parâmetros acima, mesmo antes de salvar.') }}</p>
                            </div>
                            <x-loading-spinner wire:loading.delay wire:target="simuladorPeso,settings" class="h-4 w-4 text-accent-500" />
                        </div>
                    </x-slot:header>
                    <x-slot:content class="space-y-5">
                        <div>
                            <x-input-label for="simuladorPeso" :value="__('Peso total do pedido')" />
                            <div class="relative mt-1">
                                <x-input wire:model.debounce.400ms="simuladorPeso" id="simuladorPeso" type="number" min="1" step="1" class="block w-full rounded-xl pr-12 tabular-nums sm:text-sm" />
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-sm text-slate-400">g</span>
                            </div>
                        </div>

                        @if($simulacao)
                            @php $p = $simulacao['pacote']; @endphp
                            <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-inset ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-slate-200/70 dark:bg-slate-800 dark:ring-white/10">
                                        <x-heroicon-o-cube class="h-6 w-6 text-accent-500" />
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-primary dark:text-slate-200">
                                            {{ $p->caixaNome ?? __('Estimativa proporcional') }}
                                        </p>
                                        <p class="text-xs tabular-nums text-slate-500 dark:text-slate-400">
                                            {{ $p->comprimento }} × {{ $p->largura }} × {{ $p->altura }} cm
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                        <span>{{ __('Ocupação estimada') }}</span>
                                        <span class="font-semibold tabular-nums">{{ number_format($simulacao['ocupacao_real'], 0, ',', '.') }}%</span>
                                    </div>
                                    <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-white/10">
                                        <div @class(['h-full rounded-full transition-all duration-500', 'bg-accent-500' => ! $p->excedeCapacidade, 'bg-amber-500' => $p->excedeCapacidade]) style="width: {{ $simulacao['ocupacao'] }}%"></div>
                                    </div>
                                </div>

                                <dl class="mt-4 grid grid-cols-2 gap-3 text-xs">
                                    <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200/70 dark:bg-slate-900 dark:ring-white/10">
                                        <dt class="text-slate-400">{{ __('Volume dos itens') }}</dt>
                                        <dd class="mt-0.5 font-semibold tabular-nums text-primary dark:text-slate-200">{{ number_format($p->volumeEstimadoCm3 / 1000, 2, ',', '.') }} L</dd>
                                    </div>
                                    <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200/70 dark:bg-slate-900 dark:ring-white/10">
                                        <dt class="text-slate-400">{{ __('Peso cúbico') }}</dt>
                                        <dd class="mt-0.5 font-semibold tabular-nums text-primary dark:text-slate-200">{{ number_format($p->pesoCubicoKg(), 2, ',', '.') }} kg</dd>
                                    </div>
                                </dl>

                                @if($p->excedeCapacidade)
                                    <p class="mt-3 flex items-start gap-1.5 text-xs text-amber-700 dark:text-amber-400">
                                        <x-heroicon-m-exclamation-triangle class="mt-px h-4 w-4 shrink-0" />
                                        {{ __('Nenhuma caixa ativa comporta esse volume/peso — será usada a maior disponível. O atendente confere as medidas reais na pré-postagem.') }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Informe um peso para simular.') }}</p>
                        @endif
                    </x-slot:content>
                </x-card>
            </div>
        </div>
    </div>

    {{-- MODAL CRIAR/EDITAR --}}
    <form wire:submit.prevent="save">
        <x-modal-dialog wire:model="showModal">
            <x-slot:title>
                {{ $editingId ? __('Editar embalagem') : __('Nova embalagem') }}
            </x-slot:title>
            <x-slot:content>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <x-input-label for="box_name" :value="__('Nome')" />
                        <x-input wire:model.defer="state.name" id="box_name" type="text" placeholder="Caixa P" class="mt-1 block w-full rounded-xl sm:text-sm" />
                        <x-input-error for="state.name" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="box_code" :value="__('Código')" />
                        <x-input wire:model.defer="state.code" id="box_code" type="text" maxlength="10" placeholder="P" class="mt-1 block w-full rounded-xl uppercase sm:text-sm" />
                        <x-input-error for="state.code" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-sm font-semibold text-primary dark:text-slate-300">{{ __('Medidas internas (cm)') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Mínimo dos Correios: 16 × 11 × 2 cm. Máximo: 100 cm por lado e 200 cm na soma.') }}</p>
                    <div class="mt-3 grid grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="box_length" :value="__('Comprimento')" />
                            <x-input wire:model.lazy="state.length_cm" id="box_length" type="number" min="16" max="100" class="mt-1 block w-full rounded-xl tabular-nums sm:text-sm" />
                            <x-input-error for="state.length_cm" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="box_width" :value="__('Largura')" />
                            <x-input wire:model.lazy="state.width_cm" id="box_width" type="number" min="11" max="100" class="mt-1 block w-full rounded-xl tabular-nums sm:text-sm" />
                            <x-input-error for="state.width_cm" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="box_height" :value="__('Altura')" />
                            <x-input wire:model.lazy="state.height_cm" id="box_height" type="number" min="2" max="100" class="mt-1 block w-full rounded-xl tabular-nums sm:text-sm" />
                            <x-input-error for="state.height_cm" class="mt-2" />
                        </div>
                    </div>

                    @php
                        $l = (int) ($state['length_cm'] ?: 0);
                        $w = (int) ($state['width_cm'] ?: 0);
                        $h = (int) ($state['height_cm'] ?: 0);
                        $vol = $l * $w * $h;
                    @endphp
                    @if($vol > 0)
                        <div class="mt-3 flex flex-wrap gap-2 text-xs">
                            <x-badge type="default" size="sm">{{ __('Volume') }}: {{ number_format($vol / 1000, 1, ',', '.') }} L</x-badge>
                            <x-badge type="default" size="sm">{{ __('Peso cúbico') }}: {{ number_format($vol / 6000, 2, ',', '.') }} kg</x-badge>
                            <x-badge :type="($l + $w + $h) > 200 ? 'danger' : 'default'" size="sm">{{ __('Soma') }}: {{ $l + $w + $h }} cm</x-badge>
                            <x-badge type="primary" size="sm">{{ __('Comporta') }} ~{{ number_format($estimator->capacidadeEstimadaGramas($vol) / 1000, 2, ',', '.') }} kg</x-badge>
                        </div>
                    @endif
                </div>

                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="box_max_weight" :value="__('Peso máximo (kg)')" />
                        <x-input wire:model.defer="state.max_weight_kg" id="box_max_weight" type="number" step="0.1" min="0.1" max="30" placeholder="{{ __('Opcional') }}" class="mt-1 block w-full rounded-xl tabular-nums sm:text-sm" />
                        <x-input-error for="state.max_weight_kg" class="mt-2" />
                    </div>
                    <div class="flex items-end pb-2">
                        <label for="box_active" class="flex cursor-pointer items-center gap-3">
                            <x-input wire:model.defer="state.is_active" id="box_active" type="checkbox" class="h-4 w-4 !rounded text-accent-500 focus:ring-accent-500" />
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ __('Disponível para sugestão') }}</span>
                        </label>
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="btn btn-primary w-full sm:ml-3 sm:w-auto">
                    <x-loading-spinner wire:loading wire:target="save" class="-ml-1 mr-2 h-4 w-4" />
                    {{ $editingId ? __('Salvar') : __('Cadastrar') }}
                </button>
                <button x-on:click.prevent="show = false" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>

    {{-- CONFIRMAÇÃO DE EXCLUSÃO --}}
    <x-modal-alert wire:model="showDeleteModal">
        <x-slot:title>
            {{ __('Excluir embalagem?') }}
        </x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Ela deixa de ser sugerida nas cotações e pré-postagens. Envios já registrados mantêm as medidas gravadas. Se a caixa só está em falta temporariamente, prefira desativá-la.') }}
            </p>
        </x-slot:content>
        <x-slot:footer>
            <button wire:click.prevent="delete" wire:loading.attr="disabled" wire:target="delete" class="btn btn-danger w-full sm:ml-3 sm:w-auto">
                {{ __('Excluir') }}
            </button>
            <button x-on:click.prevent="show = false" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                {{ __('Cancelar') }}
            </button>
        </x-slot:footer>
    </x-modal-alert>
</div>
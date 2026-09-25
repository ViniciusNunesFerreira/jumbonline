<div>
    <x-slot:title>{{ __('Correios') }}</x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">{{ __('Correios — Pré-postagem') }}</h1>
        </div>

        @error('postagem')
            <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-900/20 dark:text-red-300">
                {{ $message }}
            </div>
        @enderror

        <div class="mt-6 space-y-6">
            <div class="flex w-fit rounded-xl bg-slate-100 p-1 dark:bg-white/5">
                <button wire:click="setTab('pendentes')" type="button" @class(['rounded-lg px-4 py-2 text-sm font-semibold transition-colors', 'bg-white text-primary shadow-sm dark:bg-slate-800 dark:text-white' => $tab === 'pendentes', 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white' => $tab !== 'pendentes'])>
                    {{ __('Pendentes de envio') }}
                </button>
                <button wire:click="setTab('processamento')" type="button" @class(['rounded-lg px-4 py-2 text-sm font-semibold transition-colors', 'bg-white text-primary shadow-sm dark:bg-slate-800 dark:text-white' => $tab === 'processamento', 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white' => $tab !== 'processamento'])>
                    {{ __('Em processamento') }}
                </button>
                <button wire:click="setTab('concluidos')" type="button" @class(['rounded-lg px-4 py-2 text-sm font-semibold transition-colors', 'bg-white text-primary shadow-sm dark:bg-slate-800 dark:text-white' => $tab === 'concluidos', 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white' => $tab !== 'concluidos'])>
                    {{ __('Concluídos') }}
                </button>
            </div>

            @if($tab === 'pendentes')
                <x-card>
                    <x-slot:header>
                        <div class="relative max-w-sm text-slate-400">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                            </div>
                            <x-input wire:model.debounce.500ms="search" type="text" class="w-full rounded-xl pl-10 sm:text-sm" placeholder="{{ __('Buscar por cliente') }}" />
                        </div>
                    </x-slot:header>
                    <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                        <ul class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($pedidosPendentes as $order)
                                <li wire:key="pedido-pendente-{{ $order->id }}" class="flex items-center justify-between gap-3 px-4 py-4 sm:px-6">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-primary dark:text-slate-200">#{{ $order->id }} — {{ $order->customer?->name }}</p>
                                        <p class="truncate text-xs text-slate-400 mt-0.5">
                                            {{ __('Para') }}: {{ $order->detento?->name }} — {{ $order->prison_unit?->name }}
                                        </p>
                                    </div>
                                    @if(! $order->visitante)
                                        <button wire:click="abrirPostagemManual({{ $order->id }})" wire:loading.attr="disabled" wire:target="criarPostagem,criarPostagemManual" type="button" class="btn btn-default btn-xs !rounded-xl shrink-0">
                                            <x-heroicon-m-pencil-square class="w-4 h-4 mr-1 text-amber-500" />
                                            {{ __('Preencher dados') }}
                                        </button>
                                    @elseif(empty($order->visitante->cpf))
                                        <button wire:click="abrirEdicaoCpf({{ $order->visitante->id }})" wire:loading.attr="disabled" wire:target="criarPostagem,criarPostagemManual" type="button" class="btn btn-default btn-xs !rounded-xl shrink-0">
                                            <x-heroicon-m-exclamation-triangle class="w-4 h-4 mr-1 text-amber-500" />
                                            {{ __('CPF pendente') }}
                                        </button>
                                    @else
                                        <button
                                            wire:click="criarPostagem({{ $order->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="criarPostagem,criarPostagemManual"
                                            wire:confirm="{{ __('Confirma criar a pré-postagem oficial pra este pedido? Isso registra um envio real nos Correios.') }}"
                                            type="button"
                                            class="btn btn-primary btn-xs !rounded-xl shrink-0"
                                        >
                                            <span wire:loading.remove wire:target="criarPostagem({{ $order->id }})">{{ __('Criar pré-postagem') }}</span>
                                            <span wire:loading wire:target="criarPostagem({{ $order->id }})">{{ __('Criando...') }}</span>
                                        </button>
                                    @endif
                                </li>
                            @empty
                                <li class="px-4 py-12 text-center sm:px-6">
                                    <x-heroicon-o-check-circle class="mx-auto h-8 w-8 text-emerald-400" />
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('Nenhum pedido pendente de envio.') }}</p>
                                </li>
                            @endforelse
                        </ul>
                    </x-slot:content>
                </x-card>
                <div class="mt-4">{{ $pedidosPendentes?->links() }}</div>
            @endif

            @if($tab === 'processamento')
                <x-card class="overflow-hidden">
                    <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                        <ul class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($emProcessamento as $shipment)
                                <li class="flex items-center justify-between gap-3 px-4 py-4 sm:px-6">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-primary dark:text-slate-200">
                                            #{{ $shipment->order_id }} — {{ $shipment->order?->customer?->name }}
                                        </p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $shipment->tracking_number }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if($shipment->correios_status)
                                            <x-badge :type="\App\Enums\CorreiosPrepostagemStatus::from($shipment->correios_status)->badgeType()" size="xs">
                                                {{ \App\Enums\CorreiosPrepostagemStatus::from($shipment->correios_status)->label() }}
                                            </x-badge>
                                        @endif
                                        <button wire:click="cancelarPostagem({{ $shipment->id }})" wire:confirm="{{ __('Cancelar esta pré-postagem?') }}" type="button" class="btn btn-default btn-xs !rounded-xl">
                                            {{ __('Cancelar') }}
                                        </button>
                                    </div>
                                </li>
                            @empty
                                <li class="px-4 py-12 text-center sm:px-6">
                                    <x-heroicon-o-clock class="mx-auto h-8 w-8 text-slate-300" />
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('Nada em processamento.') }}</p>
                                </li>
                            @endforelse
                        </ul>
                    </x-slot:content>
                </x-card>
                <div class="mt-4">{{ $emProcessamento?->links() }}</div>
            @endif

            @if($tab === 'concluidos')
                <x-card class="overflow-hidden">
                    <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                        <ul class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($concluidos as $shipment)
                                <li class="flex items-center justify-between gap-3 px-4 py-4 sm:px-6">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-500/10">
                                            <x-heroicon-o-check class="h-5 w-5 text-emerald-500" />
                                        </span>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-primary dark:text-slate-200">
                                                #{{ $shipment->order_id }} — {{ $shipment->order?->customer?->name }}
                                            </p>
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $shipment->tracking_number }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 shrink-0">
                                        <button wire:click="baixarRotulo({{ $shipment->id }})" type="button" class="btn btn-default btn-xs !rounded-xl">{{ __('Etiqueta') }}</button>
                                        <button wire:click="baixarDeclaracao({{ $shipment->id }})" type="button" class="btn btn-default btn-xs !rounded-xl">{{ __('Declaração') }}</button>
                                    </div>
                                </li>
                            @empty
                                <li class="px-4 py-12 text-center sm:px-6">
                                    <x-heroicon-o-inbox class="mx-auto h-8 w-8 text-slate-300" />
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('Nenhuma postagem concluída ainda.') }}</p>
                                </li>
                            @endforelse
                        </ul>
                    </x-slot:content>
                </x-card>
                <div class="mt-4">{{ $concluidos?->links() }}</div>
            @endif
        </div>
    </div>

    <x-modal-dialog wire:model.defer="visitanteEditandoId">
        <x-slot:title>{{ __('Cadastrar CPF do visitante') }}</x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                {{ __('Obrigatório pela Correios — o visitante é o declarante legal do envio. Confirme o CPF com ele antes de salvar.') }}
            </p>
            <x-input-label for="cpfEditando" :value="__('CPF')" />
            <x-input wire:model.defer="cpfEditando" type="text" id="cpfEditando" maxlength="11" placeholder="Somente números" class="mt-1 block w-full rounded-xl" />
            <x-input-error for="cpfEditando" class="mt-2" />
        </x-slot:content>
        <x-slot:footer>
            <button wire:click="salvarCpf" type="button" class="btn btn-primary w-full sm:ml-3 sm:w-auto">{{ __('Salvar') }}</button>
            <button x-on:click="show = false" type="button" class="btn btn-default mt-3 w-full sm:mt-0 sm:w-auto">{{ __('Cancelar') }}</button>
        </x-slot:footer>
    </x-modal-dialog>

    <x-modal-dialog wire:model.defer="pedidoManualId">
        <x-slot:title>{{ __('Postagem manual — pedido de balcão') }}</x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                {{ __('Este pedido não tem destino a uma unidade prisional. Preencha quem está enviando e para onde vai.') }}
            </p>

            <h4 class="text-sm font-semibold text-primary dark:text-slate-300 mb-3">{{ __('Remetente') }}</h4>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="col-span-2">
                    <x-input-label for="rem_nome" :value="__('Nome completo')" />
                    <x-input wire:model.defer="remetenteManual.nome" id="rem_nome" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.nome" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_cpf" :value="__('CPF (só números)')" />
                    <x-input wire:model.defer="remetenteManual.cpf" id="rem_cpf" type="text" maxlength="11" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.cpf" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_cep" :value="__('CEP')" />
                    <x-input wire:model.defer="remetenteManual.cep" id="rem_cep" type="text" maxlength="8" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.cep" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-input-label for="rem_logradouro" :value="__('Logradouro')" />
                    <x-input wire:model.defer="remetenteManual.logradouro" id="rem_logradouro" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.logradouro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_numero" :value="__('Número')" />
                    <x-input wire:model.defer="remetenteManual.numero" id="rem_numero" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.numero" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_bairro" :value="__('Bairro')" />
                    <x-input wire:model.defer="remetenteManual.bairro" id="rem_bairro" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.bairro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_cidade" :value="__('Cidade')" />
                    <x-input wire:model.defer="remetenteManual.cidade" id="rem_cidade" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.cidade" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_uf" :value="__('UF')" />
                    <x-input wire:model.defer="remetenteManual.uf" id="rem_uf" type="text" maxlength="2" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.uf" class="mt-2" />
                </div>
            </div>

            <h4 class="text-sm font-semibold text-primary dark:text-slate-300 mb-3">{{ __('Destinatário') }}</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-input-label for="dest_nome" :value="__('Nome completo')" />
                    <x-input wire:model.defer="destinatarioManual.nome" id="dest_nome" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.nome" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="dest_cep" :value="__('CEP')" />
                    <x-input wire:model.defer="destinatarioManual.cep" id="dest_cep" type="text" maxlength="8" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.cep" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="dest_numero" :value="__('Número')" />
                    <x-input wire:model.defer="destinatarioManual.numero" id="dest_numero" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.numero" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-input-label for="dest_logradouro" :value="__('Logradouro')" />
                    <x-input wire:model.defer="destinatarioManual.logradouro" id="dest_logradouro" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.logradouro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="dest_bairro" :value="__('Bairro')" />
                    <x-input wire:model.defer="destinatarioManual.bairro" id="dest_bairro" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.bairro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="dest_cidade" :value="__('Cidade')" />
                    <x-input wire:model.defer="destinatarioManual.cidade" id="dest_cidade" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.cidade" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="dest_uf" :value="__('UF')" />
                    <x-input wire:model.defer="destinatarioManual.uf" id="dest_uf" type="text" maxlength="2" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.uf" class="mt-2" />
                </div>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button wire:click="criarPostagemManual" wire:loading.attr="disabled" type="button" class="btn btn-primary w-full sm:ml-3 sm:w-auto">
                {{ __('Criar pré-postagem') }}
            </button>
            <button x-on:click="show = false" type="button" class="btn btn-default mt-3 w-full sm:mt-0 sm:w-auto">{{ __('Cancelar') }}</button>
        </x-slot:footer>
    </x-modal-dialog>
</div>
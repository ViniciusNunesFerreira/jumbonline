<div>
    <x-slot:title>{{ __('Correios') }}</x-slot:title>

    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <h1 class="text-2xl font-medium text-slate-900 dark:text-slate-100">{{ __('Correios — Pré-postagem') }}</h1>
    </div>

    @error('postagem')
        <div class="mx-4 mt-4 sm:mx-6 lg:mx-8 rounded-md bg-red-50 dark:bg-red-900/20 p-4 text-sm text-red-700 dark:text-red-300">
            {{ $message }}
        </div>
    @enderror

    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
        <div class="flex gap-2">
            <button wire:click="setTab('pendentes')" type="button" class="btn btn-xs {{ $tab === 'pendentes' ? 'btn-primary' : 'btn-default' }}">
                {{ __('Pendentes de envio') }}
            </button>
            <button wire:click="setTab('processamento')" type="button" class="btn btn-xs {{ $tab === 'processamento' ? 'btn-primary' : 'btn-default' }}">
                {{ __('Em processamento') }}
            </button>
            <button wire:click="setTab('concluidos')" type="button" class="btn btn-xs {{ $tab === 'concluidos' ? 'btn-primary' : 'btn-default' }}">
                {{ __('Concluídos') }}
            </button>
        </div>

        @if($tab === 'pendentes')
            <x-card>
                <x-slot:header>
                    <x-input wire:model.debounce.500ms="search" type="text" class="w-full sm:w-64 sm:text-sm" placeholder="{{ __('Buscar por cliente') }}" />
                </x-slot:header>
                <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                    <ul class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($pedidosPendentes as $order)
                            <li wire:key="pedido-pendente-{{ $order->id }}" class="flex items-center justify-between px-4 py-3 sm:px-6">
                                <div>
                                    <p class="text-sm text-slate-900 dark:text-slate-200">#{{ $order->id }} — {{ $order->customer?->name }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ __('Para') }}: {{ $order->detento?->name }} — {{ $order->prison_unit?->name }}
                                    </p>
                                </div>
                                @if(! $order->visitante)
                                    <button wire:click="abrirPostagemManual({{ $order->id }})" wire:loading.attr="disabled" wire:target="criarPostagem,criarPostagemManual" type="button" class="btn btn-default btn-xs">
                                        <x-heroicon-m-pencil-square class="w-4 h-4 mr-1 text-amber-500" />
                                        {{ __('Sem visitante — preencher remetente/destinatário') }}
                                    </button>
                                @elseif(empty($order->visitante->cpf))
                                    <button wire:click="abrirEdicaoCpf({{ $order->visitante->id }})" wire:loading.attr="disabled" wire:target="criarPostagem,criarPostagemManual" type="button" class="btn btn-default btn-xs">
                                        <x-heroicon-m-exclamation-triangle class="w-4 h-4 mr-1 text-amber-500" />
                                        {{ __('Visitante sem CPF — cadastrar') }}
                                    </button>
                                @else
                                    <button
                                        wire:click="criarPostagem({{ $order->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="criarPostagem,criarPostagemManual"
                                        wire:confirm="{{ __('Confirma criar a pré-postagem oficial pra este pedido? Isso registra um envio real nos Correios.') }}"
                                        type="button"
                                        class="btn btn-primary btn-xs"
                                    >
                                        <span wire:loading.remove wire:target="criarPostagem({{ $order->id }})">{{ __('Criar pré-postagem') }}</span>
                                        <span wire:loading wire:target="criarPostagem({{ $order->id }})">{{ __('Criando...') }}</span>
                                    </button>
                                @endif
                            </li>
                        @empty
                            <li class="px-4 py-6 text-sm text-center text-slate-500 dark:text-slate-400">{{ __('Nenhum pedido pendente de envio.') }}</li>
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
                            <li class="flex items-center justify-between px-4 py-3 sm:px-6">
                                <div>
                                    <p class="text-sm text-slate-900 dark:text-slate-200">
                                        #{{ $shipment->order_id }} — {{ $shipment->order?->customer?->name }}
                                    </p>
                                    <p class="text-xs text-slate-400">{{ $shipment->tracking_number }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($shipment->correios_status)
                                        <x-badge :type="\App\Enums\CorreiosPrepostagemStatus::from($shipment->correios_status)->badgeType()" size="xs">
                                            {{ \App\Enums\CorreiosPrepostagemStatus::from($shipment->correios_status)->label() }}
                                        </x-badge>
                                    @endif
                                    <button wire:click="cancelarPostagem({{ $shipment->id }})" wire:confirm="{{ __('Cancelar esta pré-postagem?') }}" type="button" class="btn btn-default btn-xs">
                                        {{ __('Cancelar') }}
                                    </button>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-6 text-sm text-center text-slate-500 dark:text-slate-400">{{ __('Nada em processamento.') }}</li>
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
                            <li class="flex items-center justify-between px-4 py-3 sm:px-6">
                                <div>
                                    <p class="text-sm text-slate-900 dark:text-slate-200">
                                        #{{ $shipment->order_id }} — {{ $shipment->order?->customer?->name }}
                                    </p>
                                    <p class="text-xs text-slate-400">{{ $shipment->tracking_number }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <button wire:click="baixarRotulo({{ $shipment->id }})" type="button" class="btn btn-default btn-xs">{{ __('Etiqueta') }}</button>
                                    <button wire:click="baixarDeclaracao({{ $shipment->id }})" type="button" class="btn btn-default btn-xs">{{ __('Declaração') }}</button>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-6 text-sm text-center text-slate-500 dark:text-slate-400">{{ __('Nenhuma postagem concluída ainda.') }}</li>
                        @endforelse
                    </ul>
                </x-slot:content>
            </x-card>
            <div class="mt-4">{{ $concluidos?->links() }}</div>
        @endif
    </div>

    <x-modal-dialog wire:model.defer="visitanteEditandoId">
        <x-slot:title>{{ __('Cadastrar CPF do visitante') }}</x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                {{ __('Obrigatório pela Correios — o visitante é o declarante legal do envio. Confirme o CPF com ele antes de salvar.') }}
            </p>
            <x-input-label for="cpfEditando" :value="__('CPF')" />
            <x-input wire:model.defer="cpfEditando" type="text" id="cpfEditando" maxlength="11" placeholder="Somente números" class="mt-1 block w-full" />
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

            <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">{{ __('Remetente') }}</h4>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="col-span-2">
                    <x-input-label for="rem_nome" :value="__('Nome completo')" />
                    <x-input wire:model.defer="remetenteManual.nome" id="rem_nome" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.nome" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_cpf" :value="__('CPF (só números)')" />
                    <x-input wire:model.defer="remetenteManual.cpf" id="rem_cpf" type="text" maxlength="11" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.cpf" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_cep" :value="__('CEP')" />
                    <x-input wire:model.defer="remetenteManual.cep" id="rem_cep" type="text" maxlength="8" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.cep" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-input-label for="rem_logradouro" :value="__('Logradouro')" />
                    <x-input wire:model.defer="remetenteManual.logradouro" id="rem_logradouro" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.logradouro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_numero" :value="__('Número')" />
                    <x-input wire:model.defer="remetenteManual.numero" id="rem_numero" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.numero" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_bairro" :value="__('Bairro')" />
                    <x-input wire:model.defer="remetenteManual.bairro" id="rem_bairro" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.bairro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_cidade" :value="__('Cidade')" />
                    <x-input wire:model.defer="remetenteManual.cidade" id="rem_cidade" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.cidade" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="rem_uf" :value="__('UF')" />
                    <x-input wire:model.defer="remetenteManual.uf" id="rem_uf" type="text" maxlength="2" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.uf" class="mt-2" />
                </div>
            </div>

            <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">{{ __('Destinatário') }}</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-input-label for="dest_nome" :value="__('Nome completo')" />
                    <x-input wire:model.defer="destinatarioManual.nome" id="dest_nome" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.nome" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="dest_cep" :value="__('CEP')" />
                    <x-input wire:model.defer="destinatarioManual.cep" id="dest_cep" type="text" maxlength="8" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.cep" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="dest_numero" :value="__('Número')" />
                    <x-input wire:model.defer="destinatarioManual.numero" id="dest_numero" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.numero" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-input-label for="dest_logradouro" :value="__('Logradouro')" />
                    <x-input wire:model.defer="destinatarioManual.logradouro" id="dest_logradouro" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.logradouro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="dest_bairro" :value="__('Bairro')" />
                    <x-input wire:model.defer="destinatarioManual.bairro" id="dest_bairro" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.bairro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="dest_cidade" :value="__('Cidade')" />
                    <x-input wire:model.defer="destinatarioManual.cidade" id="dest_cidade" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.cidade" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="dest_uf" :value="__('UF')" />
                    <x-input wire:model.defer="destinatarioManual.uf" id="dest_uf" type="text" maxlength="2" class="mt-1 block w-full sm:text-sm" />
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
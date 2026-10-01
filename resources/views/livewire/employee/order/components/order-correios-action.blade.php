<div x-data x-on:correios-conferencia-aberta.window="if ($event.detail.id === '{{ $this->id }}') $wire.cotarFrete()">
    @error('postagem')
        <div class="mb-3 flex items-start gap-2 rounded-xl bg-red-50 p-3 text-xs text-red-700 dark:bg-red-900/20 dark:text-red-300">
            <x-heroicon-m-exclamation-circle class="h-4 w-4 shrink-0" />
            <span>{{ $message }}</span>
        </div>
    @enderror

    @if($this->shipment)
        @php $shipment = $this->shipment; @endphp
        <div class="flex flex-col items-start gap-2 sm:items-end">
            <div class="flex flex-wrap items-center gap-2">
                @if($shipment->correios_status)
                    <x-badge :type="\App\Enums\CorreiosPrepostagemStatus::from($shipment->correios_status)->badgeType()" size="xs">
                        {{ \App\Enums\CorreiosPrepostagemStatus::from($shipment->correios_status)->label() }}
                    </x-badge>
                @endif
                <span class="text-sm font-medium tabular-nums text-slate-600 dark:text-slate-300">{{ $shipment->tracking_number }}</span>
                <button wire:click="baixarRotulo" wire:loading.attr="disabled" wire:target="baixarRotulo" type="button" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-printer class="mr-1 h-3.5 w-3.5" />
                    {{ __('Etiqueta') }}
                </button>
                <button wire:click="baixarDeclaracao" wire:loading.attr="disabled" wire:target="baixarDeclaracao" type="button" class="btn btn-default btn-xs !rounded-xl">
                    <x-loading-spinner wire:loading wire:target="baixarDeclaracao" class="mr-1 h-3.5 w-3.5" />
                    <x-heroicon-m-document-text wire:loading.remove wire:target="baixarDeclaracao" class="mr-1 h-3.5 w-3.5" />
                    {{ __('Declaração') }}
                </button>
            </div>
            @include('livewire.employee.correios.partials.resumo-embalagem', ['shipment' => $shipment])
        </div>
    @elseif(! $order->visitante)
        <button wire:click="abrirManual" type="button" class="btn btn-primary btn-sm !rounded-xl">
            <x-heroicon-m-pencil-square class="mr-1 h-4 w-4" />
            {{ __('Postagem manual (sem visitante)') }}
        </button>
    @elseif(empty($order->visitante->cpf))
        <button wire:click="abrirCpf" type="button" class="btn btn-default btn-sm !rounded-xl">
            <x-heroicon-m-exclamation-triangle class="w-4 h-4 mr-1 text-amber-500" />
            {{ __('Visitante sem CPF — cadastrar') }}
        </button>
    @else
        <button
            wire:click="abrirConferencia({{ $order->id }})"
            wire:loading.attr="disabled"
            wire:target="abrirConferencia,confirmarPostagem"
            type="button"
            class="btn btn-primary btn-sm !rounded-xl"
        >
            <x-loading-spinner wire:loading wire:target="abrirConferencia" class="-ml-0.5 mr-1.5 h-4 w-4" />
            <x-heroicon-m-cube wire:loading.remove wire:target="abrirConferencia" class="-ml-0.5 mr-1.5 h-4 w-4" />
            <span wire:loading.remove wire:target="abrirConferencia">{{ __('Conferir e solicitar pré-postagem') }}</span>
            <span wire:loading wire:target="abrirConferencia">{{ __('Abrindo...') }}</span>
        </button>
    @endif

    <x-modal-dialog wire:model.defer="showCpf">
        <x-slot:title>{{ __('Cadastrar CPF do visitante') }}</x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ __('Obrigatório pela Correios — o visitante é o declarante legal do envio.') }}</p>
            <x-input-label for="oca_cpf" :value="__('CPF')" />
            <x-input wire:model.defer="cpfEditando" id="oca_cpf" type="text" maxlength="11" placeholder="Somente números" class="mt-1 block w-full rounded-xl" />
            <x-input-error for="cpfEditando" class="mt-2" />
        </x-slot:content>
        <x-slot:footer>
            <button wire:click="salvarCpf" wire:loading.attr="disabled" wire:target="salvarCpf" type="button" class="btn btn-primary w-full sm:ml-3 sm:w-auto">{{ __('Salvar') }}</button>
            <button x-on:click="show = false" type="button" class="btn btn-default mt-3 w-full sm:mt-0 sm:w-auto">{{ __('Cancelar') }}</button>
        </x-slot:footer>
    </x-modal-dialog>

    <x-modal-dialog wire:model.defer="showManual">
        <x-slot:title>{{ __('Postagem manual') }}</x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ __('Este pedido não tem destino a uma unidade prisional. Preencha quem está enviando e para onde vai — na próxima etapa você confere a embalagem.') }}</p>

            <h4 class="text-sm font-semibold text-primary dark:text-slate-300 mb-3">{{ __('Remetente') }}</h4>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="col-span-2">
                    <x-input-label for="oca_rem_nome" :value="__('Nome completo')" />
                    <x-input wire:model.defer="remetenteManual.nome" id="oca_rem_nome" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.nome" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_cpf" :value="__('CPF (só números)')" />
                    <x-input wire:model.defer="remetenteManual.cpf" id="oca_rem_cpf" type="text" maxlength="11" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.cpf" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_cep" :value="__('CEP')" />
                    <x-input wire:model.defer="remetenteManual.cep" id="oca_rem_cep" type="text" maxlength="8" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.cep" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-input-label for="oca_rem_logradouro" :value="__('Logradouro')" />
                    <x-input wire:model.defer="remetenteManual.logradouro" id="oca_rem_logradouro" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.logradouro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_numero" :value="__('Número')" />
                    <x-input wire:model.defer="remetenteManual.numero" id="oca_rem_numero" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.numero" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_bairro" :value="__('Bairro')" />
                    <x-input wire:model.defer="remetenteManual.bairro" id="oca_rem_bairro" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.bairro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_cidade" :value="__('Cidade')" />
                    <x-input wire:model.defer="remetenteManual.cidade" id="oca_rem_cidade" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.cidade" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_uf" :value="__('UF')" />
                    <x-input wire:model.defer="remetenteManual.uf" id="oca_rem_uf" type="text" maxlength="2" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="remetenteManual.uf" class="mt-2" />
                </div>
            </div>

            <h4 class="text-sm font-semibold text-primary dark:text-slate-300 mb-3">{{ __('Destinatário') }}</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-input-label for="oca_dest_nome" :value="__('Nome completo')" />
                    <x-input wire:model.defer="destinatarioManual.nome" id="oca_dest_nome" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.nome" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_dest_cep" :value="__('CEP')" />
                    <x-input wire:model.defer="destinatarioManual.cep" id="oca_dest_cep" type="text" maxlength="8" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.cep" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_dest_numero" :value="__('Número')" />
                    <x-input wire:model.defer="destinatarioManual.numero" id="oca_dest_numero" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.numero" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-input-label for="oca_dest_logradouro" :value="__('Logradouro')" />
                    <x-input wire:model.defer="destinatarioManual.logradouro" id="oca_dest_logradouro" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.logradouro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_dest_bairro" :value="__('Bairro')" />
                    <x-input wire:model.defer="destinatarioManual.bairro" id="oca_dest_bairro" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.bairro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_dest_cidade" :value="__('Cidade')" />
                    <x-input wire:model.defer="destinatarioManual.cidade" id="oca_dest_cidade" type="text" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.cidade" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_dest_uf" :value="__('UF')" />
                    <x-input wire:model.defer="destinatarioManual.uf" id="oca_dest_uf" type="text" maxlength="2" class="mt-1 block w-full rounded-xl sm:text-sm" />
                    <x-input-error for="destinatarioManual.uf" class="mt-2" />
                </div>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button wire:click="criarManual" wire:loading.attr="disabled" wire:target="criarManual" type="button" class="btn btn-primary w-full sm:ml-3 sm:w-auto">
                <x-loading-spinner wire:loading wire:target="criarManual" class="-ml-1 mr-2 h-4 w-4" />
                {{ __('Continuar para conferência') }}
            </button>
            <button x-on:click="show = false" type="button" class="btn btn-default mt-3 w-full sm:mt-0 sm:w-auto">{{ __('Cancelar') }}</button>
        </x-slot:footer>
    </x-modal-dialog>

    @include('livewire.employee.correios.partials.conferencia-embalagem')
</div>
<div>
    @error('postagem')
        <div class="mb-3 rounded-md bg-red-50 dark:bg-red-900/20 p-3 text-xs text-red-700 dark:text-red-300">{{ $message }}</div>
    @enderror

    @if($this->shipment)
        <div class="flex items-center gap-2">
            @if($this->shipment->correios_status)
                <x-badge :type="\App\Enums\CorreiosPrepostagemStatus::from($this->shipment->correios_status)->badgeType()" size="xs">
                    {{ \App\Enums\CorreiosPrepostagemStatus::from($this->shipment->correios_status)->label() }}
                </x-badge>
            @endif
            <span class="text-sm text-slate-500 dark:text-slate-400">{{ $this->shipment->tracking_number }}</span>
            <button wire:click="baixarRotulo" type="button" class="btn btn-default btn-xs">{{ __('Etiqueta') }}</button>
            <button wire:click="baixarDeclaracao" type="button" class="btn btn-default btn-xs">{{ __('Declaração') }}</button>
        </div>
    @elseif(! $order->visitante)
        <button wire:click="abrirManual" type="button" class="btn btn-primary btn-sm">{{ __('Postagem manual (sem visitante)') }}</button>
    @elseif(empty($order->visitante->cpf))
        <button wire:click="abrirCpf" type="button" class="btn btn-default btn-sm">
            <x-heroicon-m-exclamation-triangle class="w-4 h-4 mr-1 text-amber-500" />
            {{ __('Visitante sem CPF — cadastrar') }}
        </button>
    @else
        <button
            wire:click="criar"
            wire:loading.attr="disabled"
            wire:target="criar,criarManual"
            wire:confirm="{{ __('Confirma solicitar a pré-postagem oficial dos Correios pra este pedido?') }}"
            type="button"
            class="btn btn-primary btn-sm"
        >
            <span wire:loading.remove wire:target="criar">{{ __('Solicitar pré-postagem') }}</span>
            <span wire:loading wire:target="criar">{{ __('Solicitando...') }}</span>
        </button>
    @endif

    <x-modal-dialog wire:model.defer="showCpf">
        <x-slot:title>{{ __('Cadastrar CPF do visitante') }}</x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ __('Obrigatório pela Correios — o visitante é o declarante legal do envio.') }}</p>
            <x-input-label for="oca_cpf" :value="__('CPF')" />
            <x-input wire:model.defer="cpfEditando" id="oca_cpf" type="text" maxlength="11" class="mt-1 block w-full" />
            <x-input-error for="cpfEditando" class="mt-2" />
        </x-slot:content>
        <x-slot:footer>
            <button wire:click="salvarCpf" type="button" class="btn btn-primary w-full sm:ml-3 sm:w-auto">{{ __('Salvar') }}</button>
            <button x-on:click="show = false" type="button" class="btn btn-default mt-3 w-full sm:mt-0 sm:w-auto">{{ __('Cancelar') }}</button>
        </x-slot:footer>
    </x-modal-dialog>

    <x-modal-dialog wire:model.defer="showManual">
        <x-slot:title>{{ __('Postagem manual') }}</x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ __('Este pedido não tem destino a uma unidade prisional. Preencha quem está enviando e para onde vai.') }}</p>

            <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">{{ __('Remetente') }}</h4>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="col-span-2">
                    <x-input-label for="oca_rem_nome" :value="__('Nome completo')" />
                    <x-input wire:model.defer="remetenteManual.nome" id="oca_rem_nome" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.nome" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_cpf" :value="__('CPF (só números)')" />
                    <x-input wire:model.defer="remetenteManual.cpf" id="oca_rem_cpf" type="text" maxlength="11" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.cpf" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_cep" :value="__('CEP')" />
                    <x-input wire:model.defer="remetenteManual.cep" id="oca_rem_cep" type="text" maxlength="8" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.cep" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-input-label for="oca_rem_logradouro" :value="__('Logradouro')" />
                    <x-input wire:model.defer="remetenteManual.logradouro" id="oca_rem_logradouro" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.logradouro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_numero" :value="__('Número')" />
                    <x-input wire:model.defer="remetenteManual.numero" id="oca_rem_numero" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.numero" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_bairro" :value="__('Bairro')" />
                    <x-input wire:model.defer="remetenteManual.bairro" id="oca_rem_bairro" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.bairro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_cidade" :value="__('Cidade')" />
                    <x-input wire:model.defer="remetenteManual.cidade" id="oca_rem_cidade" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.cidade" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_rem_uf" :value="__('UF')" />
                    <x-input wire:model.defer="remetenteManual.uf" id="oca_rem_uf" type="text" maxlength="2" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="remetenteManual.uf" class="mt-2" />
                </div>
            </div>

            <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">{{ __('Destinatário') }}</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <x-input-label for="oca_dest_nome" :value="__('Nome completo')" />
                    <x-input wire:model.defer="destinatarioManual.nome" id="oca_dest_nome" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.nome" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_dest_cep" :value="__('CEP')" />
                    <x-input wire:model.defer="destinatarioManual.cep" id="oca_dest_cep" type="text" maxlength="8" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.cep" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_dest_numero" :value="__('Número')" />
                    <x-input wire:model.defer="destinatarioManual.numero" id="oca_dest_numero" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.numero" class="mt-2" />
                </div>
                <div class="col-span-2">
                    <x-input-label for="oca_dest_logradouro" :value="__('Logradouro')" />
                    <x-input wire:model.defer="destinatarioManual.logradouro" id="oca_dest_logradouro" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.logradouro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_dest_bairro" :value="__('Bairro')" />
                    <x-input wire:model.defer="destinatarioManual.bairro" id="oca_dest_bairro" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.bairro" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_dest_cidade" :value="__('Cidade')" />
                    <x-input wire:model.defer="destinatarioManual.cidade" id="oca_dest_cidade" type="text" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.cidade" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="oca_dest_uf" :value="__('UF')" />
                    <x-input wire:model.defer="destinatarioManual.uf" id="oca_dest_uf" type="text" maxlength="2" class="mt-1 block w-full sm:text-sm" />
                    <x-input-error for="destinatarioManual.uf" class="mt-2" />
                </div>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button wire:click="criarManual" wire:loading.attr="disabled" wire:target="criarManual" type="button" class="btn btn-primary w-full sm:ml-3 sm:w-auto">{{ __('Solicitar pré-postagem') }}</button>
            <button x-on:click="show = false" type="button" class="btn btn-default mt-3 w-full sm:mt-0 sm:w-auto">{{ __('Cancelar') }}</button>
        </x-slot:footer>
    </x-modal-dialog>
</div>
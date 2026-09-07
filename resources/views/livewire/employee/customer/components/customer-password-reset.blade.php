<div>
    <x-card>
        <x-slot:header>
            <h3 class="font-display font-medium text-base text-slate-900 dark:text-slate-200">
                {{ __('Acesso e segurança') }}
            </h3>
        </x-slot:header>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Use quando o cliente perder acesso ao e-mail e não conseguir redefinir a própria senha. Uma senha temporária é gerada e o cliente é obrigado a trocá-la no próximo login.') }}
            </p>
            <button
                wire:click="openConfirm"
                type="button"
                class="btn btn-default btn-sm mt-4"
            >
                <x-heroicon-m-key class="w-4 h-4 mr-1" />
                {{ __('Gerar senha temporária') }}
            </button>
        </x-slot:content>
    </x-card>

    <x-modal-dialog wire:model.defer="showConfirm">
        <x-slot:title>
            {{ __('Gerar senha temporária') }}
        </x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                {{ __('A senha atual do cliente será substituída imediatamente. Ele precisará definir uma nova senha no próximo login.') }}
            </p>
            <x-input-label for="reset-channel" :value="__('Como a senha será repassada ao cliente?')" />
            <x-select wire:model="channel" id="reset-channel" class="mt-1">
                <option value="">{{ __('Selecione...') }}</option>
                @foreach($this->channels as $case)
                    <option value="{{ $case->name }}">{{ $case->label() }}</option>
                @endforeach
            </x-select>
            <x-input-error for="channel" class="mt-2" />
        </x-slot:content>
        <x-slot:footer>
            <button
                wire:click="generate"
                wire:loading.attr="disabled"
                wire:target="generate"
                type="button"
                class="btn btn-primary w-full sm:ml-3 sm:w-auto"
            >
                {{ __('Gerar e substituir senha') }}
            </button>
            <button
                x-on:click="show = false"
                type="button"
                class="btn btn-default mt-3 w-full sm:mt-0 sm:w-auto"
            >
                {{ __('Cancelar') }}
            </button>
        </x-slot:footer>
    </x-modal-dialog>

    <x-modal-dialog wire:model.defer="showReveal" max-width="md">
        <x-slot:title>
            {{ __('Senha temporária gerada') }}
        </x-slot:title>
        <x-slot:content>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">
                {{ __('Repasse esta senha ao cliente agora. Ela não será mostrada novamente.') }}
            </p>
            <div class="flex items-center justify-between gap-2 rounded-md bg-slate-100 dark:bg-slate-800 px-4 py-3 font-mono text-lg tracking-widest text-slate-900 dark:text-slate-100">
                <span>{{ $generatedPassword }}</span>
                <button
                    type="button"
                    x-on:click="$clipboard('{{ $generatedPassword }}').then(() => $dispatch('notify', '{{ __('Copiado para a área de transferência') }}'))"
                >
                    <x-heroicon-m-clipboard class="w-5 h-5 text-slate-500 hover:text-slate-600 dark:hover:text-slate-400" />
                </button>
            </div>
        </x-slot:content>
        <x-slot:footer>
            <button
                wire:click="closeReveal"
                type="button"
                class="btn btn-primary w-full sm:w-auto"
            >
                {{ __('Concluído') }}
            </button>
        </x-slot:footer>
    </x-modal-dialog>
</div>
<div>
    <x-slot:title>{{ __('Defina sua nova senha') }}</x-slot:title>

    <div class="mb-6 text-center">
        <h1 class="font-urbanist text-xl font-bold text-primary">{{ __('Defina sua nova senha') }}</h1>
        <p class="mt-1 text-sm text-slate-500">
            {{ __('Por segurança, você precisa trocar a senha temporária antes de continuar.') }}
        </p>
    </div>

    <form wire:submit.prevent="save" class="space-y-5">
        <div>
            <x-input-label for="currentPasswordInput" value="{{ __('Senha temporária recebida') }}" />
            <x-input wire:model.defer="state.current_password" type="password" id="currentPasswordInput" class="mt-1.5 block w-full" />
            <x-input-error for="state.current_password" class="mt-2" />
        </div>
        <div>
            <x-input-label for="newPasswordInput" value="{{ __('Nova senha') }}" />
            <x-input wire:model.defer="state.password" type="password" id="newPasswordInput" class="mt-1.5 block w-full" />
            <x-input-error for="state.password" class="mt-2" />
        </div>
        <div>
            <x-input-label for="confirmNewPasswordInput" value="{{ __('Confirmar nova senha') }}" />
            <x-input wire:model.defer="state.password_confirmation" type="password" id="confirmNewPasswordInput" class="mt-1.5 block w-full" />
            <x-input-error for="state.password_confirmation" class="mt-2" />
        </div>
        <button type="submit" class="w-full rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white hover:bg-primary">
            {{ __('Salvar nova senha e continuar') }}
        </button>
    </form>
</div>
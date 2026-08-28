<div>
    <form wire:submit.prevent="save" class="space-y-5">
        <div>
            <x-input-label for="currentPasswordInput" value="Senha atual" />
            <x-input wire:model.defer="state.current_password" type="password" id="currentPasswordInput" class="mt-1.5 block w-full" />
            <x-input-error for="state.current_password" class="mt-2" />
        </div>
        <div>
            <x-input-label for="newPasswordInput" value="Nova senha" />
            <x-input wire:model.defer="state.password" type="password" id="newPasswordInput" class="mt-1.5 block w-full" />
            <x-input-error for="state.password" class="mt-2" />
        </div>
        <div>
            <x-input-label for="confirmNewPasswordInput" value="Confirmar nova senha" />
            <x-input wire:model.defer="state.password_confirmation" type="password" id="confirmNewPasswordInput" class="mt-1.5 block w-full" />
            <x-input-error for="state.password_confirmation" class="mt-2" />
        </div>
        <button type="submit" class="rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white hover:bg-primary">
            Atualizar Senha
        </button>
    </form>
</div>
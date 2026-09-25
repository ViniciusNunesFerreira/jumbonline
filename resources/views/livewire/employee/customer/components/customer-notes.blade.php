<div>
    <x-card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                    {{ __('Anotações') }}
                </h2>
                <button
                    wire:click="$set('isEditing', true)"
                    type="button"
                    class="btn btn-link"
                >
                    {{ __('Editar') }}
                </button>
            </div>
        </x-slot:header>
        <x-slot:content>
            @unless($customer->notes)
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Nenhuma nota sobre este cliente') }}
                </p>
            @else
                <p class="text-sm text-slate-600 whitespace-pre-line dark:text-slate-300">
                    {{ $customer->notes }}
                </p>
            @endunless
        </x-slot:content>
    </x-card>

    <form wire:submit.prevent="save">
        <x-modal-dialog wire:model.defer="isEditing" max-width="xl">
            <x-slot:title>
                {{ __('Editar anotações') }}
            </x-slot:title>
            <x-slot:content>
                <fieldset wire:target="save" wire:loading.attr="disabled">
                    <x-input-label for="notes" :value="__('Anotações')" />
                    <x-textarea
                        wire:model.defer="customer.notes"
                        id="notes"
                        rows="3"
                        class="mt-1 block w-full rounded-xl sm:text-sm"
                        :placeholder="__('Inserir anotações sobre este cliente')"
                    />
                    <x-input-error for="customer.notes" class="mt-2" />
                </fieldset>
            </x-slot:content>
            <x-slot:footer>
                <button
                    wire:target="save"
                    wire:loading.attr="disabled"
                    type="submit"
                    class="btn btn-primary w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Salvar') }}
                </button>
                <button
                    wire:click="$set('isEditing', false)"
                    wire:target="save"
                    wire:loading.attr="disabled"
                    type="button"
                    class="btn btn-invisible mt-3 w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>
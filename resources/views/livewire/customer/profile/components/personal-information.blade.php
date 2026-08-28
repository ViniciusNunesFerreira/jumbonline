<div>
    <form wire:submit.prevent="save" class="space-y-5">
        <div>
            <x-input-label for="nameInput" value="Nome completo" />
            <x-input wire:model.defer="state.name" type="text" id="nameInput" class="mt-1.5 block w-full" />
            <x-input-error for="state.name" class="mt-2" />
        </div>

        <div>
            <x-input-label for="emailInput" value="E-mail" />
            <x-input wire:model.defer="state.email" type="text" id="emailInput" class="mt-1.5 block w-full" />
            <x-input-error for="state.email" class="mt-2" />
        </div>

        <div class="flex gap-3">
            <div class="w-28 flex-shrink-0">
                <x-input-label for="phone_country" value="País" />
                <x-select wire:model="state.phone_country" id="phone_country" class="mt-1.5 block w-full !h-[42px] text-sm">
                    <option value="BR">🇧🇷 +55</option>
                </x-select>
            </div>
            <div class="flex-1">
                <x-input-label for="phoneInput" value="WhatsApp" />
                <x-input wire:model.defer="state.phone" type="text" id="phoneInput" x-mask="(99) 99999-9999" class="mt-1.5 block w-full" placeholder="(11) 99999-9999" />
                <x-input-error for="state.phone" class="mt-2" />
            </div>
        </div>
        <p class="!mt-2 text-xs text-slate-400">Usamos pra te avisar sobre o status do seu pedido — sem spam.</p>

        <button type="submit" class="rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white hover:bg-primary">
            Salvar Alterações
        </button>
    </form>
</div>
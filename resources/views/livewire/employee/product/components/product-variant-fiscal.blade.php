<div>
    <form wire:submit.prevent="save">
        <x-card class="relative overflow-hidden">
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                        {{ __('Classificação Fiscal') }}
                    </h3>
                    <button type="submit" class="btn btn-link">{{ __('Save') }}</button>
                </div>
            </x-slot:header>
            <x-slot:content>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                    {{ __('Guardado para uso futuro em relatórios e eventual emissão de nota fiscal — não emite nada agora.') }}
                </p>
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-1">
                        <x-input-label for="ncm" :value="__('NCM')" />
                        <x-input wire:model.defer="variant.ncm" type="text" id="ncm" maxlength="8" class="mt-1 block w-full sm:text-sm" placeholder="00000000" />
                        <x-input-error for="variant.ncm" class="mt-2" />
                    </div>
                    <div class="col-span-1">
                        <x-input-label for="cfop" :value="__('CFOP padrão de venda')" />
                        <x-input wire:model.defer="variant.cfop" type="text" id="cfop" maxlength="4" class="mt-1 block w-full sm:text-sm" placeholder="5102" />
                        <p class="mt-1 text-xs text-slate-400">{{ __('Referência — a contabilidade pode ajustar por operação (dentro/fora do estado).') }}</p>
                        <x-input-error for="variant.cfop" class="mt-2" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="origin" :value="__('Origem da mercadoria')" />
                        <x-select wire:model.defer="variant.origin" id="origin" class="mt-1">
                            <option value="">{{ __('Não definida') }}</option>
                            <option value="0">0 - {{ __('Nacional') }}</option>
                            <option value="1">1 - {{ __('Estrangeira - Importação direta') }}</option>
                            <option value="2">2 - {{ __('Estrangeira - Adquirida no mercado interno') }}</option>
                            <option value="3">3 - {{ __('Nacional, Conteúdo de Importação > 40%') }}</option>
                            <option value="4">4 - {{ __('Nacional, produção conforme processos básicos') }}</option>
                            <option value="5">5 - {{ __('Nacional, Conteúdo de Importação ≤ 40%') }}</option>
                            <option value="6">6 - {{ __('Estrangeira - Importação direta, sem similar nacional') }}</option>
                            <option value="7">7 - {{ __('Estrangeira - Mercado interno, sem similar nacional') }}</option>
                            <option value="8">8 - {{ __('Nacional, Conteúdo de Importação > 70%') }}</option>
                        </x-select>
                        <x-input-error for="variant.origin" class="mt-2" />
                    </div>
                </div>
            </x-slot:content>
        </x-card>
    </form>
</div>

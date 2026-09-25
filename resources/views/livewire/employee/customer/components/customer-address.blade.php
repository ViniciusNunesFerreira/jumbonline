<div>
    <x-card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                    {{ __('Visitante Cadastrado') }}
                </h2>
                <button
                    wire:click.prevent="manageVisitantes"
                    type="button"
                    class="btn btn-link"
                >
                    {{ __('Gerenciar') }}
                </button>
            </div>
        </x-slot:header>
        <x-slot:content>
            @unless($customer->visitantes->count() > 0)
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Nenhum Visitante definido.') }}
                </p>
            @else
                <address class="not-italic text-sm text-slate-600 dark:text-slate-300 space-y-0.5">
                    <p class="font-semibold text-primary dark:text-slate-200">{{ optional($visitante)->nome }}</p>
                    <p>{{ optional($visitante)->logradouro }}, {{ optional($visitante)->numero }}</p>
                    <p>{{ optional($visitante)->bairro }}</p>
                    <p>{{ optional($visitante)->cidade }} / {{ optional($visitante)->uf }} — {{ optional($visitante)->cep }}</p>
                </address>
            @endunless
        </x-slot:content>
    </x-card>

    <x-modal-dialog wire:model.defer="showAddressForm">
        <x-slot:title>
            {{ __('Carteirinha do Visitante Anexada') }}
        </x-slot:title>
        <x-slot:content>
            <fieldset wire:target="save" wire:loading.attr="disabled">
                @if(method_exists((object) $visitante, 'hasMedia'))
                    <div @class(['grid grid-cols-2 gap-4 auto-rows-fr' => $visitante->hasMedia()])>
                        @forelse($visitante->getMedia('gallery') as $medium)
                            <div class="relative overflow-hidden border border-slate-200 group rounded-xl flex items-center justify-center dark:border-white/10">
                                <img
                                    src="{{ $medium->getUrl() }}"
                                    alt="{{ $medium->name }}"
                                    class="h-full w-full object-contain object-center transition group-hover:scale-125"
                                />
                                <div class="absolute inset-0 rounded-xl bg-primary-900/0 transition-colors group-hover:bg-primary-900/40"></div>
                                <x-input
                                    wire:model="selected"
                                    type="checkbox"
                                    class="absolute top-2 left-2 !rounded !shadow-none text-accent-500 focus:ring-accent-500 dark:!bg-slate-900"
                                    x-bind:class="{ 'opacity-0 group-hover:opacity-100': !selected.length }"
                                    value="{{ $medium->id }}"
                                />
                            </div>
                        @empty
                            <div class="relative overflow-hidden border border-slate-200 rounded-xl flex items-center justify-center py-6 text-sm text-slate-400 dark:border-white/10">
                                {{ __('Sem imagens') }}
                            </div>
                        @endforelse
                    </div>
                @endif
            </fieldset>
        </x-slot:content>
        <x-slot:footer>
            <button
                wire:click.prevent="save"
                wire:target="save"
                wire:loading.attr="disabled"
                type="submit"
                class="btn btn-primary w-full sm:ml-3 sm:w-auto"
            >
                {{ __('Baixar') }}
            </button>
            <button
                wire:click="$set('showAddressForm', false)"
                wire:target="save"
                wire:loading.attr="disabled"
                type="button"
                class="btn btn-invisible mt-3 w-full sm:mt-0 sm:w-auto"
            >
                {{ __('Cancelar') }}
            </button>
        </x-slot:footer>
    </x-modal-dialog>

    <x-modal-dialog wire:model.defer="showAddressesManageModal">
        <x-slot:title>
            {{ __('Gerenciar Visitantes') }}
        </x-slot:title>
        <x-slot:content>
            @unless($customer->visitantes->count() > 0)
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Nenhum Visitante definido.') }}
                </p>
            @else
                <address class="not-italic text-sm text-slate-600 dark:text-slate-300 space-y-0.5">
                    <p class="font-semibold text-primary dark:text-slate-200">{{ optional($visitante)->nome }}</p>
                    @if(optional($visitante->prison_unit())->name)
                        <p>{{ $visitante->prison_unit->name }}</p>
                    @endif
                    <p>{{ optional($visitante)->logradouro }}, {{ optional($visitante)->numero }}</p>
                    <p>{{ optional($visitante)->bairro }}</p>
                    <p>{{ optional($visitante)->cidade }} / {{ optional($visitante)->uf }} — {{ optional($visitante)->cep }}</p>
                </address>

                <div class="mt-4">
                    <button wire:click.prevent="view()" type="button" class="btn btn-link">
                        {{ __('Visualizar Carteirinha') }}
                    </button>
                </div>
            @endunless
        </x-slot:content>
    </x-modal-dialog>
</div>
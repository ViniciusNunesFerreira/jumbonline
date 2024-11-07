<div>
    <x-card>
        <x-slot:header>
            <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                <div class="ml-4 mt-2">
                    <h2 class="text-base font-medium text-slate-900 dark:text-slate-100">
                        {{ __('Visitante Cadastrado') }}
                    </h2>
                </div>
                <div class="ml-4 mt-2 flex-shrink-0">
                    <button
                        wire:click.prevent="manageVisitantes"
                        type="button"
                        class="btn btn-link"
                    >
                        {{ __('Gerenciar') }}
                    </button>
                </div>
            </div>
        </x-slot:header>
        <x-slot:content class="-mt-5">
            @unless($customer->visitantes->count() > 0)
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Nenhum Visitante definido.') }}
                </div>
            @else
                <address class="not-italic text-sm">
                    {{ optional($visitante)->nome }}<br>
                
                    {{ optional($visitante)->logradouro }}, {{optional($visitante)->numero}}<br>
                    
                    {{ optional($visitante)->bairro }}<br>
                
                    {{ optional($visitante)->cidade }} / {{ optional($visitante)->uf }}
                               
                    {{ optional($visitante)->cep }}<br>

                </address>
            @endunless
            
        </x-slot:content>
    </x-card>

    <x-modal-dialog wire:model.defer="showAddressForm">
        <x-slot:title>
            Carteirinha do Visitante Anexada
        </x-slot:title>
        <x-slot:content>

            <fieldset
                wire:target="save"
                wire:loading.attr="disabled"
                class="mt-5 grid gap-4"
            >

                <div @class(['grid  grid-cols-2 gap-4 auto-rows-fr' => $visitante->hasMedia('gallery')])>

                    @foreach($visitante->getMedia('gallery') as $medium)
                        <div @class(['relative overflow-hidden border  border-slate-200 group rounded-md flex items-center justify-center dark:border-slate-200/20'])>
                            <img
                                src="{{ $medium->getUrl() }}"
                                alt="{{ $medium->name }}"
                                class="h-full w-full object-contain object-center transition group-hover:scale-125"
                            />
                            <div class="absolute inset-0 group-hover:bg-opacity-50 group-hover:bg-slate-600 rounded-md transition-all"></div>
                            <x-input
                                wire:model="selected"
                                type="checkbox"
                                class="absolute top-2 left-2 !rounded !shadow-none dark:!bg-slate-900 dark:checked:!bg-sky-500"
                                x-bind:class="{ 'opacity-0 group-hover:opacity-100': !selected.length }"
                                value="{{ $medium->id }}"
                            />
                        </div>
                    @endforeach

                </div>

        

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
                {{ __('Download') }}
            </button>
            <button
                wire:click="$set('showAddressForm', false)"
                wire:target="save"
                wire:loading.attr="disabled"
                type="button"
                class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
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
            <div class="divide-y divide-slate-200 dark:divide-slate-200/10">

                @unless($customer->visitantes->count() > 0)
                    <div class="text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Nenhum Visitante definido.') }}
                    </div>
                @else

                    <address class="not-italic text-sm">
                        {{ optional($visitante)->nome }}<br>

                        @if(optional($visitante->prison_unit())->name)
                            {{ $visitante->prison_unit->name }}<br>
                        @endif

                        {{ optional($visitante)->logradouro }}, {{optional($visitante)->numero}}<br>
                
                        {{ optional($visitante)->bairro }}<br>
            
                        {{ optional($visitante)->cidade }} / {{ optional($visitante)->uf }}
                        
                        {{ optional($visitante)->cep }}<br>
                        
                    </address>

                    <div class="mt-3 flex items-center justify-between">
                        <button
                            wire:click.prevent="view()"
                            class="btn btn-link"
                        >
                            {{ __('Visualizar Carteirinha') }}
                        </button>
                    </div>
                    
                @endunless
            </div>
        </x-slot:content>
    </x-modal-dialog>
</div>

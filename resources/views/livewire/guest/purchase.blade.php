<div x-data>
    <div class="bg-complement-500 w-full ">

        <div class="mx-auto max-w-7xl">

            <ul class=" flex list-none flex-row flex-nowrap ps-0 " role="tablist" data-nav-ref>

                <li role="presentation" class="flex-auto text-center">
                    <fieldset
                   
                    class="cursor-pointer block px-2 sm:px-7 pb-3.5 pt-4 text-lg font-semibold uppercase leading-tight text-primary hover:isolate focus:isolate focus:border-transparent data-[nav-active]:bg-accent data-[nav-active]:text-complement-500"
                    data-toggle="pill"
                    data-target="#tabs-entrega"
                    @if('tabs-entrega' == $currentTab && $step == 1) data-nav-active aria-selected="true" @else aria-selected="false" @endif
                    role="tab"
                    aria-controls="tabs-entrega"
                    
                    @if($step < 1) disabled @else wire:click.prevent="changeTab('tabs-entrega')" @endif
                    >  Entrega </fieldset>
                </li>

                <li role="presentation" class="flex-auto text-center">
                    <fieldset
                    class="cursor-pointer block px-2 sm:px-7 pb-3.5 pt-4 text-lg font-semibold uppercase leading-tight text-primary hover:isolate focus:isolate focus:border-transparent data-[nav-active]:bg-accent data-[nav-active]:text-complement-500"
                    data-toggle="pill"
                    data-target="#tabs-detento"
                    role="tab"
                    aria-controls="tabs-detento"
                    @if('tabs-detento' == $currentTab && $step == 2) data-nav-active aria-selected="true" @else aria-selected="false" @endif
                    @if($step < 2) disabled @else wire:click.prevent="changeTab('tabs-detento')" @endif
                    >  DETENTO </fieldset>
                </li>

                <li role="presentation" class="flex-auto text-center">
                    <fieldset
                   
                    class=" cursor-pointer block px-2 sm:px-7 pb-3.5 pt-4 text-lg font-semibold uppercase leading-tight text-primary hover:isolate focus:isolate focus:border-transparent data-[nav-active]:bg-accent data-[nav-active]:text-complement-500"
                    data-toggle="pill"
                    data-target="#tabs-pagamento"
                    role="tab"
                    aria-controls="tabs-pagamento"
                    @if('tabs-pagamento' == $currentTab && $step == 3 || 'tabs-pagamento' == $currentTab && $step == 0) data-nav-active aria-selected="true" @else aria-selected="false" @endif
                   
                    @if($step < 3 && $step > 0) disabled @endif
                    >  PAGAMENTO </fieldset>
                </li>

            </ul>

        </div>

        <!--Tabs content-->
        <div>

            
        
                <div
                    class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[tab-active]:block"
                    id="tabs-entrega"
                    role="tabpanel"
                    aria-labelledby="tabs-entrega-tab01"
                    
                    @if('tabs-entrega' == $currentTab) data-tab-active  @endif
                    >
                    
                    <div class="bg-white min-h-52 py-8 text-center text-2xl font-semibold font-urbanist text-primary">
                        Confira os dados, onde o jumbo será entregue.
                    </div>

                    <div class="mx-auto max-w-7xl py-8 space-y-4">
                        <x-card>
                            <x-slot:content class="!py-8 sm:!px-10">

                                <div class="flex flex-row justify-between">
                                    <h2 class="text-lg font-semibold text-purple tracking-tight flex items-center">
                                        <x-heroicon-s-check-circle class="h-5 w-5 flex-shrink-0 text-accent" />  &nbsp; Entregar na Unidade
                                    </h2>
                                    <a href="#" wire:click.prevent="limpaSession" class=" flex space-x-2">   Trocar unidade &nbsp; <x-heroicon-s-arrow-path class="h-5 w-5 flex-shrink-0 text-accent" /></a> 
                                </div>

                                @if( $prison )
                                    <div class="max-w-7xl mt-4 p-4">

                                        <h1 class="my-2 text-xl font-bold tracking-tight sm:text-2xl text-primary">
                                            {{ optional($prisonUnit)->name }}
                                        </h1>

                                        <ul class="mt-4 py-4 text-sm md:text-lg text-slate-500 leading-loose font-bold  space-y-2">

                                            <li class=" flex items-center leading-5">
                                                <x-heroicon-s-map-pin class="h-5 w-5 flex-shrink-0 text-accent m-2" />           
                                                {{ $prisonUnit->logradouro }} , {{ $prisonUnit->numero }}, {{$prisonUnit->bairro}} - {{ $prisonUnit->cidade }} / {{ $prisonUnit->uf }} - CEP: {{ $prisonUnit->cep }}
                                            </li>
                                            <li class=" flex items-center leading-5">
                                                <x-heroicon-s-phone class="h-5 w-5 flex-shrink-0 text-accent m-2" /> 
                                                Tel:{{ phone( optional($prisonUnit)->phone, 'BR' ); }}
                                            </li>
                                            
                                        </ul>

                                        

                                    </div>
                                @else

                                        <div>
                                           
                                            <x-select
                                                wire:model.change="prison"
                                                wire:key="prisonSession"
                                                id="prison"
                                                class="block mt-1 w-full text-xl text-gray font-urbanist"
                                                
                                            >
                                                <option disabled value="" selected >Selecione uma Unidade Prisional</option>

                                                @forelse($this->prison_categories as $category)

                                                    @if($category->prisonUnits()->count() > 0)
                                                        <option disabled class="text-lg font-bold tracking-widest py-2"><strong>----{{$category->name}}----</strong></option>
                                                    @endif

                                                    @forelse($category->prisonUnits as $unit )
                                                        <option value="{{ $unit->slug }}">{{ $unit->name }}</option>
                                                    @empty
                                                        
                                                    @endforelse

                                                @empty
                                                    <option> Sem Categorias Prisionais Cadastradas </option>
                                                @endforelse

                                            </x-select>
                                            
                                            <x-input-error
                                                for="prison"
                                                class="mt-2"
                                            />
                                        </div>

                                 
                                @endif

                            </x-slot:content>
                        </x-card>

                        <x-card>
                            <x-slot:content class="!py-8 sm:!px-10">
                                
                                    <!-- Informações de contato do consumidor -->
                                    
                                    <h2 class="text-lg font-semibold text-purple tracking-tight flex items-center">
                                        <x-heroicon-s-check-circle class="h-5 w-5 flex-shrink-0 text-accent" /> &nbsp; {{ __('Informações de Contato') }}
                                    </h2>
                                    <div class="md:grid md:grid-cols-3 md:gap-6 mb-4 pb-8 border-b border-gray">

                                        <div class="md:col-span-3">
                                            <p class="mt-2 text-sm text-slate-500">
                                                * Precisamos que mantenha seu cadastro atualizado com informações válidas, para entrarmos em contato caso ocorra algum problema.
                                            </p>
                                        </div>
                                        
                                       
                                        <div class="mt-5 md:col-span-3 md:mt-0">

                                            <form wire:submit.prevent="saveCustomer" >
                                                <div class="grid grid-cols-6 gap-6">

                                                    <div class="col-span-6">
                                                        <x-input-label
                                                            class="!text-sm !font-bold !text-primary"
                                                            for="full-name"
                                                            value="Nome <span class='text-warning'>*</span>"
                                                        />
                                                        <x-input
                                                            wire:model.defer="state.name"
                                                            type="text"
                                                            id="full-name"
                                                            class="mt-1 block w-full sm:text-sm"
                                                            placeholder="Nome completo para contato"
                                                        />
                                                        <x-input-error
                                                            for="state.name"
                                                            class="mt-2"
                                                        />
                                                    </div>

                                                    <div class="col-span-6">
                                                        <x-input-label
                                                            class="!text-sm !font-bold !text-primary"
                                                            for="email"
                                                            value="Email <span class='text-warning'>*</span>"
                                                        />
                                                        <x-input
                                                            wire:model.defer="state.email"
                                                            type="text"
                                                            id="email"
                                                            class="mt-1 block w-full sm:text-sm"
                                                            placeholder="Email"
                                                        />
                                                        <x-input-error
                                                            for="state.email"
                                                            class="mt-2"
                                                        />
                                                    </div>

                                                    <div class="col-span-3 md:col-span-2">
                                                        <x-input-label
                                                            class="!text-sm !font-bold !text-primary"
                                                            for="phone_country"
                                                            value="Cod. País <span class='text-warning'>*</span>"
                                                        />
                                                    

                                                        <x-select
                                                            wire:model.change="state.phone_country"
                                                        
                                                            id="state.phone_country"
                                                            name="state.phone_country"
                                                            autocomplete="country-name"
                                                            class="mt-1 block w-full sm:text-sm !h-10"
                                                        >

                                                            @foreach($this->availableCountries as $country)
                                                                <option value="{{ $country->iso2 }}">
                                                                    {{ $country->name }} ( +{{ $country->phonecode }} )
                                                                </option>
                                                            @endforeach

                                                        </x-select>
                                                        <x-input-error
                                                            for="state.phone_country"
                                                            class="mt-2"
                                                        />
                                                    </div>

                                                    <div class="col-span-3 md:col-span-4">
                                                        <x-input-label
                                                            class="!text-sm !font-bold !text-primary"
                                                            for="phone"
                                                            value="Telefone (WhatsApp) <span class='text-warning'>*</span>"
                                                        />
                                                        <x-input
                                                            wire:model.defer="state.phone"
                                                            type="text"
                                                            id="phone"
                                                            class="mt-1 block w-full sm:text-sm"
                                                            
                                                                x-mask="(99) 99999-9999"
                                                           
                                                            placeholder="(11) 99999-9999"
                                                        />
                                                        <x-input-error
                                                            for="state.phone"
                                                            class="mt-2"
                                                        />
                                                    </div>

                                                    <div class="col-span-3">

                                                    </div>

                                                    <div class="col-span-3">
                                                        <button type="submit" class="btn btn bg-primary text-white text-center block w-full mt-2 flex">
                                                            Continuar &nbsp; <x-heroicon-s-chevron-double-right class="h-5 w-5 flex-shrink-0 text-white" />
                                                        </button>
                                                    </div>

                                                </div>
                                            </form>

                                        </div>

                                        
                                    </div>
                            </x-slot:content>
                        </x-card>
                    </div>

                </div>

                <div
                    class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[tab-active]:block"
                    id="tabs-detento"
                    role="tabpanel"
                    aria-labelledby="tabs-detento-tab01"
                    @if('tabs-detento' == $currentTab) data-tab-active  @endif
                    >

                    <div class="bg-white min-h-52 py-8 text-center text-2xl font-semibold font-urbanist text-primary">
                        Informe os dados do detento que irá receber o jumbo. 
                    </div>

                    <livewire:guest.purchase-components.cadastro-detento :prison="$this->prisonUnit"/>

                </div>

                <div
                    class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[tab-active]:block"
                    id="tabs-pagamento"
                    role="tabpanel"
                    aria-labelledby="tabs-pagamento-tab01"
                    @if('tabs-pagamento' == $currentTab) data-tab-active  @endif
                    >

                    <div class="bg-white min-h-52 py-8 text-center text-2xl font-semibold font-urbanist text-primary">
                        Falta pouco para concluir e finalizar seu pedido.
                    </div>

                    <div class="mx-auto max-w-7xl py-8" >

                        <x-card>

                            <x-slot:content class="!py-8 sm:!px-10">

                                <div class="mt-10 lg:mt-0">
                                    <div class="sticky top-4">
                                        <h2 class="text-lg font-bold text-primary mt-5">Resumo do Pedido</h2>

                                        <div class="mt-4 rounded-lg border border-slate-200 bg-white shadow-sm">
                                            <h3 class="sr-only">Lista do Jumbo</h3>

                                            <ul
                                                role="list"
                                                class="divide-y divide-gray-200 text-sm text-gray-900"
                                                >

                                                @foreach($cartItems as $index => $item)
                                                    <li class="flex items-center space-x-4 px-4 py-6 sm:px-6">
                                                        <div class="relative flex flex-shrink-0 border border-slate-200 rounded-md">
                                                            @if($item->variant->hasMedia('image'))
                                                                {{ $item->variant->getFirstMedia('image')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-20 w-20 rounded-md']) }}
                                                            @elseif($item->product->hasMedia('gallery'))
                                                                {{ $item->product->getFirstMedia('gallery')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-20 w-20 rounded-md']) }}
                                                            @else
                                                                <div class="relative h-20 w-20 rounded-md bg-slate-100">
                                                                    <x-heroicon-o-camera class="h-full w-12 absolute inset-0 mx-auto text-slate-400 sm:w-16" />
                                                                </div>
                                                            @endif
                                                            <span class="absolute -top-3 -right-2 whitespace-nowrap rounded-full bg-slate-400 px-2 py-0.5 text-center text-xs font-medium leading-5 text-white ring-1 ring-inset ring-slate-400 tabular-nums">{{ $item->quantity }}</span>
                                                        </div>
                                                        <div class="ml-6 flex-auto space-y-1">
                                                            <h4 class="line-clamp-2">
                                                                <p class="font-medium text-slate-700 hover:text-slate-800" >
                                                                    {{ $item->product->name }}
                                                                <p>
                                                            </h4>
                                                            @if($item->variant->variantAttributes->count())
                                                                <ul class="space-x-2 divide-x divide-slate-200 text-sm text-slate-500">
                                                                    @foreach($item->variant->variantAttributes as $attribute)
                                                                        <li @class(['inline', 'pl-2' => !$loop->first])>{{ $attribute->optionValue->label }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif

                                                            <p class="font-medium flex flex-col text-right space-y-1">
                                                                @if($item->discount)
                                                                    <span class="line-through text-slate-500 text-xs">
                                                                        <x-money
                                                                            :amount="$item->subtotal"
                                                                            :currency="config('app.currency')"
                                                                        />
                                                                    </span>
                                                                    <span>
                                                                        <x-money
                                                                            :amount="$item->discountedPrice"
                                                                            :currency="config('app.currency')"
                                                                        />
                                                                    </span>
                                                                @else
                                                                    <x-money
                                                                        :amount="$item->subtotal"
                                                                        :currency="config('app.currency')"
                                                                    />
                                                                @endif
                                                            </p>

                                                            
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <dl class="space-y-6 border-t border-slate-200 py-6 px-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <dt class="text-sm">{{ __('Subtotal') }}</dt>
                                                    <dd class="text-sm font-medium text-slate-900">
                                                        <x-money
                                                            :amount="$cart->subtotal"
                                                            :currency="config('app.currency')"
                                                        />
                                                    </dd>
                                                </div>
                                                

                                                <div class="flex items-center justify-between">
                                                    <dt class="text-sm">{{ __('Frete') }}</dt>
                                                    <dd class="text-sm font-medium text-slate-900">
                                                        <x-money
                                                            :amount="optional($this->order)->shipping_price"
                                                            :currency="config('app.currency')"
                                                        />
                                                    </dd>
                                                </div>
                                                
                                                <div class="flex items-center justify-between border-t border-slate-200 pt-6">
                                                    <dt class="text-base font-bold">{{ __('Total') }}</dt>
                                                    <dd class="text-base font-medium text-slate-900">
                                                        <x-money
                                                            :amount="optional($this->order)->total"
                                                            :currency="config('app.currency')"
                                                        />
                                                    </dd>
                                                </div>
                                            </dl>

                                        </div>

                                    </div>
                                </div>

                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-3">
                                        <a class="btn bg-accent text-white text-center block w-full mt-2 flex">
                                            <x-heroicon-s-chevron-double-left class="h-5 w-5 flex-shrink-0 text-white" /> &nbsp; Cancelar Pedido
                                        </a>
                                    </div>
                                    <div class="col-span-3">
                                        <button wire:click.prevent="preparePayment()" class="btn bg-primary text-white text-center block w-full mt-2 flex">
                                            Confirmar &nbsp; <x-heroicon-s-chevron-double-right class="h-5 w-5 flex-shrink-0 text-white" />
                                        </button>
                                    </div>
                                </div>

                            </x-slot:content>

                        </x-card>
                        
                        
                    </div>

                </div>

            
        </div>

        
            
    </div>



</div>











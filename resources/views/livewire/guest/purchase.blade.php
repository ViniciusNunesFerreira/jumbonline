<div>
    <div class="bg-complement-500 w-full ">

        <div class="mx-auto max-w-7xl">

            <ul class=" flex list-none flex-row flex-nowrap ps-0 " role="tablist" data-nav-ref>

                <li role="presentation" class="flex-auto text-center">
                    <a
                    href="#tabs-entrega"
                    class="block px-2 sm:px-7 pb-3.5 pt-4 text-lg font-semibold uppercase leading-tight text-primary hover:isolate focus:isolate focus:border-transparent data-[nav-active]:bg-accent data-[nav-active]:text-complement-500"
                    data-toggle="pill"
                    data-target="#tabs-entrega"
                    @if('tabs-entrega' == $currentTab) data-nav-active aria-selected="true" @else aria-selected="false" @endif
                    role="tab"
                    aria-controls="tabs-entrega"
                    wire:click.prevent="changeTab('tabs-entrega')"
                    >  Entrega </a>
                </li>

                <li role="presentation" class="flex-auto text-center">
                    <a
                    href="#tabs-detento"
                    class=" block px-2 sm:px-7 pb-3.5 pt-4 text-lg font-semibold uppercase leading-tight text-primary hover:isolate focus:isolate focus:border-transparent data-[nav-active]:bg-accent data-[nav-active]:text-complement-500"
                    data-toggle="pill"
                    data-target="#tabs-detento"
                    role="tab"
                    aria-controls="tabs-detento"
                    @if('tabs-detento' == $currentTab) data-nav-active aria-selected="true" @else aria-selected="false" @endif
                    wire:click.prevent="changeTab('tabs-detento')"
                    >  DETENTO </a>
                </li>

                <li role="presentation" class="flex-auto text-center">
                    <a
                    href="#tabs-pagamento"
                    class=" block px-2 sm:px-7 pb-3.5 pt-4 text-lg font-semibold uppercase leading-tight text-primary hover:isolate focus:isolate focus:border-transparent data-[nav-active]:bg-accent data-[nav-active]:text-complement-500"
                    data-toggle="pill"
                    data-target="#tabs-pagamento"
                    role="tab"
                    aria-controls="tabs-pagamento"
                    @if('tabs-pagamento' == $currentTab) data-nav-active aria-selected="true" @else aria-selected="false" @endif
                    wire:click.prevent="changeTab('tabs-pagamento')"
                    >  PAGAMENTO </a>
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
                            Entregar na Unidade
                        </x-slot:content>
                    </x-card>

                    <x-card>
                        <x-slot:content class="!py-8 sm:!px-10">
                           <p class="text-center font-semibold text-2xl font-urbanist text-primary">FRETE</p>
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

                <div class="mx-auto max-w-7xl">
                    
                </div>

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

                <div class="mx-auto max-w-7xl">
                    
                </div>

            </div>

        </div>
            
    </div>
</div>

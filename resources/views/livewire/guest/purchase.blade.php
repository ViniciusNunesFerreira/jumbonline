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
                            <h2 class="text-lg font-semibold text-purple tracking-tight flex items-center">
                                 <x-heroicon-s-check-circle class="h-5 w-5 flex-shrink-0 text-accent" />  &nbsp; Entregar na Unidade
                            </h2>
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

                <livewire:guest.purchase-components.detento />

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

                <div class="mx-auto max-w-7xl py-8 space-y-4" >

                    <x-card>
                        <x-slot:content class="!py-8 sm:!px-10">
                            <h2 class="text-lg font-semibold text-purple tracking-tight flex items-center"> 
                               <x-heroicon-s-check-circle class="h-5 w-5 flex-shrink-0 text-accent" /> &nbsp; Selecione a forma de pagamento
                                
                            </h2>

                            <div id="paymentBrick_container"></div>
                            <div id="statusScreenBrick_container"></div>

                        </x-slot:content>
                    </x-card>
                    
                </div>

            </div>

        </div>
            
    </div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

@if('tabs-pagamento' == $currentTab)
<script>

    const mp = new MercadoPago('{{ $this->mercadopago->meta['public_key'] }}', {
          locale: 'pt-BR'
        } );
    const bricksBuilder = mp.bricks();

    const renderPaymentBrick = async (bricksBuilder) => {
        const settings = {
        initialization: {
            /*
            "amount" é o valor total a ser pago por todos os meios de pagamento
        com exceção da Conta Mercado Pago e Parcelamento sem cartão de crédito, que tem seu valor de processamento determinado no backend através do "preferenceId"
            */
            amount: parseFloat(1000),
            
        },
        customization: {

            paymentMethods: {
                creditCard: "all",
                ticket:'bolbradesco',
                bankTransfer:'pix',
                types: {
                    excluded: ['debit_card', 'mercadoPago']
                }, 
                maxInstallments: 3,
            },
            visual: {
                hideFormTitle: true,
                style: {
                  customVariables: {
                    theme: 'default', // | 'dark' | 'bootstrap' | 'flat'
                  }
                }
            }
        },
        callbacks: {
            onReady: () => {
            /*
            Callback chamado quando o Brick estiver pronto.
            Aqui você pode ocultar loadings do seu site, por exemplo.
            */
            },
            onSubmit: ({ selectedPaymentMethod, formData }) => {
            // callback chamado ao clicar no botão de submissão dos dados
            return new Promise((resolve, reject) => {
                fetch("", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(formData),
                })
                .then((response) => response.json())
                .then((response) => {
                    // receber o resultado do pagamento

                    const renderStatusScreenBrick = async (bricksBuilder) => {
                        const settings = {
                        initialization: {
                            paymentId: response.id, // id do pagamento a ser mostrado
                        },
                        callbacks: {
                            onReady: () => {
                                $("#paymentBrick_container").hide();
                            },
                            onError: (error) => {
                                // callback chamado para todos os casos de erro do Brick
                                console.error(error);
                            },
                        },
                        };
                        window.statusScreenBrickController = await bricksBuilder.create(
                        'statusScreen',
                        'statusScreenBrick_container',
                        settings,
                        );  
                    };
                    renderStatusScreenBrick(bricksBuilder);

                    resolve();
                })
                .catch((error) => {
                    // lidar com a resposta de erro ao tentar criar o pagamento
                   // console.log(JSON.stringify(error));

                    console.error(error);

                    reject();
                });
            });
            },
            onError: (error) => {
            // callback chamado para todos os casos de erro do Brick
            console.error(error);
            },
        },
        };
        window.paymentBrickController = await bricksBuilder.create(
        "payment",
        "paymentBrick_container",
        settings
        );
    };

    renderPaymentBrick(bricksBuilder);

</script>
@endif

</div>



@push('script_header')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
@endpush







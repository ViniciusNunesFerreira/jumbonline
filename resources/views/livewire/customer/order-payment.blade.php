<div>

    <x-slot:title>
        {{ __('Pedido - :orderId', ['orderId' => $order->id]) }}
    </x-slot:title>


    <div class="bg-complement-500 w-full ">
        <div class="mx-auto max-w-7xl p-5">
            <x-card>

                <x-slot:header>
                    

                    <dl class="space-y-6 border-b border-primary p-5 text-md mt-5">

                        <p class="text-lg text-primary text-center ">
                            @if($order->payment_status === \App\Enums\PaymentStatus::UNPAID)
                                {{ __('Para finalizar o seu pedido, pedimos a gentileza de efetuar o pagamento.
                                    Por favor, escolha uma das opções disponível abaixo para concluir sua compra.') }}
                            @elseif($order->payment_status === \App\Enums\PaymentStatus::PENDING)
                                {{ __('Estamos aguardando a confirmação do seu pagamento. Enviaremos um e-mail de confirmação assim que seu pagamento for confirmado.') }}
                            @elseif($order->payment_status === \App\Enums\PaymentStatus::PAID && $order->shipping_status === \App\Enums\ShippingStatus::UNSHIPPED)
                                {{ __('Agradecemos seu pedido, estamos processando-o no momento. Então aguarde e enviaremos a confirmação em breve!') }}
                            @elseif($order->payment_status === \App\Enums\PaymentStatus::PAID && $order->shipping_status === \App\Enums\ShippingStatus::SHIPPED)
                                {{ __('Agradecemos seu pedido, estamos processando-o no momento. Então aguarde e enviaremos a confirmação em breve!') }}
                            @else
                                {{ __('Agradecemos seu pedido, estamos processando-o no momento. Então aguarde e enviaremos a confirmação em breve!') }}
                            @endif
                        </p>
                        
                        <div class="flex justify-between">
                            <dt class="font-semibold text-primary-900">{{ __('Subtotal') }}</dt>
                            <dd class="text-primary">
                                <x-money
                                    :amount="$order->subtotal"
                                    :currency="config('app.currency')"
                                />
                            </dd>
                        </div>

                        <div class="flex justify-between">
                            <dt class="font-semibold text-primary-900">{{ __('Envio') }}</dt>
                            <dd class="text-primary">
                                <x-money
                                    :amount="$order->shipping_price"
                                    :currency="config('app.currency')"
                                />
                            </dd>
                        </div>

                        <div class="flex justify-between">
                            <dt class="font-semibold text-primary-900">{{ __('Total') }}</dt>
                            <dd class="text-primary font-bold ">
                                <x-money
                                    :amount="$order->total - $order->total_refunded"
                                    :currency="config('app.currency')"
                                />
                            </dd>
                        </div>
                    </dl>
                </x-slot:header>

                <x-slot:content class="!py-5 sm:!px-10">

        
                    <div id="paymentBrick_container"></div>
                    <div id="statusScreenBrick_container"></div>

                </x-slot:content>
            </x-card>
        </div>
    </div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


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
            amount: parseFloat('{{$order->total}}'),
            preferenceId: '{{$order->id}}',
            
        },
        customization: {

            paymentMethods: {
                creditCard: "all",
                ticket:'bolbradesco',
                bankTransfer:'pix',
                types: {
                    excluded: ['debit_card', 'mercadoPago']
                }, 
                maxInstallments: 12,
            },
            visual: {
                style: {
                    customVariables: {
                        textPrimaryColor: "#1B1850",
                        baseColor: "#1B1850"
                    },
                },
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
                    'Access-Control-Allow-Origin': '*',
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
                        customization: {
                            visual: {
                                style:{
                                    textPrimaryColor: "#1B1850",
                                    baseColor:"#1B1850"
                                }
                            },
                            backUrls: {
                                'return': '{{route("customer.orders.list")}}',
                            }
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
                    console.error("erro "+ error);
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


</div>

@push('script_header')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
@endpush
<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Pedidos - :orderId', ['orderId' => $order->id]) }}
    </x-slot:title>

    <div class="bg-white">
        <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
            <div>
                <h1 class="mt-2 text-4xl font-bold tracking-tight sm:text-5xl">
                    {{ __('Obrigado pelo seu pedido!') }}
                </h1>
                <p class="mt-2 text-base text-slate-500">
                    @if($order->payment_status === \App\Enums\PaymentStatus::UNPAID)
                        {{ __('Para finalizar o seu pedido, pedimos a gentileza de efetuar o pagamento. Por favor, prossiga para a seção de pagamento abaixo para concluir sua compra.') }}
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
                <dl class="mt-12 grid flex-1 grid-cols-2 gap-6 text-sm sm:col-span-4 sm:grid-cols-4 lg:col-span-2">
                    <div>
                        <dt class="font-medium text-gray-900">Nº Pedido </dt>
                        <dd class="mt-1 font-medium text-sky-600">{{ $order->id }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-900">{{ __('Data do Pedido') }}</dt>
                        <dd class="mt-1 font-medium text-sky-600">{{ $order->created_at->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-900">{{ __('Status do Pagamento') }}</dt>
                        <dd class="mt-1 font-medium text-sky-600">{{ $order->payment_status->label() }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-900">{{ __('Status do Envio') }}</dt>
                        <dd class="mt-1 font-medium text-sky-600">{{ $order->shipping_status->label() }}</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-10 border-t border-slate-200">
                <h2 class="sr-only">{{ __('Seu Pedido') }}</h2>

                <h3 class="sr-only">{{ __('Itens') }}</h3>

                <ul
                    role="list"
                    class="divide-y divide-slate-200 border-b border-slate-200"
                >
                    @foreach($order->orderItems as $item)
                        <li class="py-4 sm:py-6">
                            <div class="flex items-center sm:items-stretch">
                                <div class="relative h-20 w-20 flex-shrink-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-200 sm:h-40 sm:w-40">
                                    @if($item->variant->hasMedia('image'))
                                        {{ $item->variant->getFirstMedia('image')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-full w-full object-cover object-center']) }}
                                    @elseif($item->product->hasMedia('gallery'))
                                        {{ $item->product->getFirstMedia('gallery')('thumb_large')->attributes(['alt' => $item->product->name, 'class' => 'h-full w-full object-cover object-center']) }}
                                    @else
                                        <x-heroicon-o-camera class="h-full w-16 absolute inset-0 mx-auto text-slate-400 sm:w-24" />
                                    @endif
                                </div>
                                <div class="ml-6 flex flex-col flex-1 justify-between text-sm">
                                    <div>
                                        <div class="font-medium text-slate-900 sm:flex sm:justify-between">
                                            <h4>
                                                {{ $item->quantity }}x
                                                {{ $item->product->name }}
                                            </h4>
                                            <p class="mt-2 sm:mt-0">
                                                <x-money
                                                    :amount="$item->price"
                                                    :currency="config('app.currency')"
                                                />
                                            </p>
                                        </div>
                                        @if($item->variant->variantAttributes->count())
                                            <ul class="mt-2 space-x-2 divide-x divide-slate-200 text-slate-700">
                                                @foreach($item->variant->variantAttributes as $attribute)
                                                    <li @class(['inline', 'pl-2' => !$loop->first])>{{ $attribute->optionValue->label }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="hidden mt-2 sm:flex">
                                        <div class="flex items-center space-x-4 divide-x divide-slate-200 text-sm font-medium">
                                           

                                            @if($order->shipping_status != \App\Enums\ShippingStatus::UNSHIPPED)
                                                <div class="flex flex-1 justify-center pl-4">
                                                    <button
                                                        wire:click="writeReviewForProduct({{ $item->product->id }})"
                                                        type="button"
                                                        class="btn btn-link whitespace-nowrap"
                                                    >
                                                        {{ $item->product->reviews->isEmpty() ? __('Avaliar') : __('Editar avaliação') }}
                                                    </button>
                                                </div>
                                            @endif

                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 sm:hidden">
                                <div class="mt-6 flex items-center space-x-4 divide-x divide-slate-200 border-t border-slate-200 pt-4 text-sm font-medium">
                                    
                                    <div class="flex flex-1 justify-center pl-4">
                                        <button
                                            wire:click="writeReviewForProduct({{ $item->product->id }})"
                                            type="button"
                                            class="btn btn-link whitespace-nowrap"
                                        >
                                            {{ $item->product->reviews->isEmpty() ? __('Avaliar') : __('Editar Avaliação') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="sm:ml-40 sm:pl-6">
                    <h3 class="sr-only">{{ __('Suas Informações') }}</h3>

                    <h4 class="sr-only">{{ __('Endereços') }}</h4>
                    <dl class="grid grid-cols-2 gap-x-6 py-10 text-sm">
                        <div>
                            <dt class="font-medium text-slate-900">{{ __('Endereço de Envio') }}</dt>
                            <dd class="mt-2 text-slate-700">
                                <address class="not-italic">
                                    {{ $this->shippingAddress->name }}<br>

                                    

                                    @if($this->shippingAddress->logradouro)
                                        {{ $this->shippingAddress->logradouro }}<br>
                                    @endif

                                    @if($this->shippingAddress->bairro)
                                        {{ $this->shippingAddress->bairro }}<br>
                                    @endif

                                    @if($this->shippingAddress->cidade)
                                        {{ $this->shippingAddress->cidade }}
                                    @endif

                                    @if($this->shippingAddress->uf)
                                        {{ $this->shippingAddress->uf }}<br>
                                    @endif

                                    

                                    @if($this->shippingAddress->phone)
                                        {{ $this->shippingAddress->phone }}<br>
                                    @endif
                                </address>
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-slate-900">{{ __('Endereço do Visitante') }}</dt>
                            <dd class="mt-2 text-slate-700">
                                <address class="not-italic">
                                    {{ $this->billingAddress->nome }}<br>

                                

                                    @if($this->billingAddress->logradouro)
                                        {{ $this->billingAddress->logradouro }} , {{ optional($this->billingAddress)->numero }}<br>
                                    @endif

                                    @if($this->billingAddress->bairro)
                                        {{ $this->billingAddress->bairro }}<br>
                                    @endif

                                    @if($this->billingAddress->cidade)
                                        {{ $this->billingAddress->cidade }}
                                    @endif

                                    @if($this->billingAddress->uf)
                                        {{ $this->billingAddress->uf }}<br>
                                    @endif

                                   

                                    @if($this->billingAddress->phone)
                                        {{ $this->billingAddress->phone }}<br>
                                    @endif
                                </address>
                            </dd>
                        </div>
                    </dl>

                    <h4 class="sr-only">{{ __('Pagamento') }}</h4>
                    <dl class="grid grid-cols-2 gap-x-6 border-t border-slate-200 py-10 text-sm">
                        <div>
                            <dt class="font-medium text-slate-900">{{ __('Forma de Pagamento') }}</dt>
                            <dd class="mt-2 text-slate-700">
                                <p>{{ $order->paymentMethod->name }}</p>
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-slate-900">{{ __('Forma de Envio') }}</dt>
                            <dd class="mt-2 text-slate-700">
                                <p>{{ $order->shipping_rate }}</p>
                            </dd>
                        </div>
                    </dl>

                    <h3 class="sr-only">{{ __('Resumo') }}</h3>

                    <dl class="space-y-6 border-t border-slate-200 pt-10 text-sm">
                        <div class="flex justify-between">
                            <dt class="font-medium text-slate-900">{{ __('Subtotal') }}</dt>
                            <dd class="text-slate-700">
                                <x-money
                                    :amount="$order->subtotal"
                                    :currency="config('app.currency')"
                                />
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="flex font-medium text-slate-900">{{ __('Desconto') }}</dt>
                            <dd class="text-slate-700">
                                <x-money
                                    :amount="$order->discount_total"
                                    :currency="config('app.currency')"
                                />
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="font-medium text-slate-900">{{ __('Envio') }}</dt>
                            <dd class="text-slate-700">
                                <x-money
                                    :amount="$order->shipping_price"
                                    :currency="config('app.currency')"
                                />
                            </dd>
                        </div>
                        
                        
                        <div class="flex justify-between">
                            <dt class="font-medium text-slate-900">{{ __('Total') }}</dt>
                            <dd class="text-slate-900">
                                <x-money
                                    :amount="$order->total - $order->total_refunded"
                                    :currency="config('app.currency')"
                                />
                            </dd>
                        </div>
                        @if($order->payment_status == \App\Enums\PaymentStatus::UNPAID)
                            @if($order->paymentMethod->identifier == 'stripe')
                                <div class="flex">
                                    <button
                                        wire:click="processStripePayment"
                                        type="button"
                                        class="btn btn-primary btn-lg block w-full"
                                    >
                                        {{ __('Proceed to payment') }}
                                    </button>
                                </div>
                            @elseif($order->paymentMethod->identifier == 'razorpay')
                                <div class="flex">
                                    <button
                                        id="payNow"
                                        type="button"
                                        class="btn btn-primary btn-lg block w-full"
                                    >
                                        {{ __('Proceed to payment') }}
                                    </button>
                                </div>
                                @push('scripts')
                                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                                    <script>
                                        document.addEventListener('livewire:load', function () {
                                            const options = {
                                                "key": "rzp_test_gohOSyptUwoHXa",
                                                "amount": "{{ $order->total * 100 }}",
                                                "currency": "{{ config('app.currency') }}",
                                                "name": "{{ $generalSettings->store_name }}",
                                                "order_id": "{{ $order->meta['razorpay_order_id'] }}",
                                                "handler": function (response) {
                                                    @this.
                                                    verifyRazorpayPayment(response.razorpay_payment_id, response.razorpay_signature);
                                                },
                                                "prefill": {
                                                    "name": "{{ $order->billingAddress->name }}",
                                                    "email": "{{ $order->customer_email }}",
                                                    "contact": "{{ $order->billingAddress->phone }}"
                                                },
                                            };

                                            const razorpay = new Razorpay(options);

                                            document.getElementById('payNow').onclick = function (e) {
                                                razorpay.open();

                                                e.preventDefault();
                                            }
                                        });
                                    </script>
                                @endpush
                            @endif
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="saveReview">
        <x-modal-dialog wire:model="showReviewForm">
            <x-slot:title>
                {{ __('Write a review') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label
                            for="review.rating"
                            :value="__('Rating')"
                        />
                        <x-select
                            wire:model.defer="review.rating"
                            id="rating"
                            class="block w-full mt-1 sm:text-sm"
                        >
                            <option value="">{{ __('Select a rating') }}</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </x-select>
                        <x-input-error
                            for="review.rating"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="review.title"
                            :value="__('Review summary')"
                        />
                        <x-input
                            wire:model.defer="review.title"
                            id="title"
                            type="text"
                            class="block w-full mt-1 sm:text-sm"
                        />
                        <x-input-error
                            for="review.title"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <x-input-label
                            for="review.content"
                            :value="__('Share your thoughts')"
                        />
                        <x-textarea
                            wire:model="review.content"
                            id="comment"
                            class="block w-full mt-1 sm:text-sm"
                        />
                        <x-input-error
                            for="review.content"
                            class="mt-2"
                        />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button
                    wire:target="save"
                    wire:loading.attr="disabled"
                    type="submit"
                    class="btn btn-primary w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Save') }}
                </button>
                <button
                    x-on:click="show = false"
                    wire:target="save"
                    wire:loading.attr="disabled"
                    type="button"
                    class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancel') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>

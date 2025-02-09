<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Pedidos - :orderId', ['orderId' => $order->id]) }}
    </x-slot:title>

    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 ">
            <div>
                <h1 class="mt-2 text-4xl font-bold tracking-tight sm:text-5xl">
                    {{ __('Obrigado pelo seu pedido!') }}
                </h1>
                <p class="my-4 text-base text-slate-500">
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
                
                

                <table class="table-auto w-full mt-8 text-center border-collapse ">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="border border-gray-300 "> Nº Pedido </th>
                            <th class="border border-gray-300 ">{{ __('Data do Pedido') }} </th>
                            <th class="border border-gray-300 ">{{ __('Status do Pagamento') }}</th>
                            <th class="border border-gray-300 ">{{ __('Status do Envio') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="p-4">{{ $order->id }}</td>
                            <td class="p-4">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="p-4  relative  font-bold @if($order->payment_status === \App\Enums\PaymentStatus::PAID) bg-success text-white  @else bg-warning text-white  @endif">
                                {{ $order->payment_status->label() }} 
                                @if( $order->payment_status === \App\Enums\PaymentStatus::UNPAID ) 
                                    <a href="{{ route('customer.order.payment', $order->id) }}" class="btn btn-link px-5 absolute border rounded-full top-0 right-0 m-2 text-white border-white"> Pagar </a>
                                @endif
                            </td>
                            <td class="p-4 font-bold @if( $order->shipping_status === \App\Enums\ShippingStatus::SHIPPED ) text-success @else text-warning @endif">
                                {{ $order->shipping_status->label() }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                
            </div>

            <div class="my-10 py-2 relative overflow-auto " x-data="{ current: 0 }">
                
                <span class="p-2 text-white bg-primary w-auto rounded-full text-sm">
                    {{ __('Seu Pedido') }} {{ $order->orderItems->count() }}
                </span>

                

                <ul
                    role="list"
                    x-ref="slider"
                    class=" py-10 flex flex-1 scroll-smooth scroll-no-bar snap-mandatory snap-x overflow-x-auto overflow-y-hidden"
                >
                
                    @foreach($order->orderItems as $item)
                        <li class="snap-center shrink-0 w-full"  x-intersect.threshold.90="$nextTick(() => current = {{ $loop->index }})">

                            <div class="relative ">
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
                                    <div class="ml-6 flex flex-col flex-1 justify-between  self-center ">
                                        <div>
                                            <div class="font-bold text-slate-900 sm:flex sm:justify-between">
                                                
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
                                        
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                           
                        </li>
                    @endforeach
                </ul>


                <div class="absolute bottom-10 inset-x-0 flex justify-center space-x-3">
                    @foreach($order->orderItems as $slide)
                        <button x-on:click="$refs.slider.scrollTo({ left: $refs.slider.offsetWidth * {{ $loop->index }}, behavior: 'smooth' })">
                            <span class="sr-only">
                                {{ __('Slide :count', ['count' => $loop->index + 1]) }}
                            </span>
                            <span
                                class="block h-2 w-2 rounded-full border-primary ring-2 ring-primary ring-opacity-50 hover:ring-opacity-100"
                                :class="{ 'bg-primary ring-opacity-100': current === {{ $loop->index }} }"
                            ></span>
                        </button>
                    @endforeach
                </div>


            </div>

            <div class="">
                
                <h4 class="p-2 text-white bg-primary font-semibold">{{ __('Suas Informações') }}</h4>

                    <dl class="grid grid-cols-2 gap-x-6 py-10 text-sm">
                        <div>
                            <dt class="font-bold text-primary">{{ __('Endereço de Envio') }}</dt>
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
                            <dt class="font-bold text-primary">{{ __('Endereço do Visitante') }}</dt>
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

                    <h4  class="p-2 text-white bg-primary font-semibold">{{ __('Pagamento / Envio') }}</h4>

                    <dl class="grid grid-cols-2 gap-x-6 border-t border-slate-200 py-10 text-sm">
                        <div>
                            <dt class="font-bold text-primary">{{ __('Forma de Pagamento') }}</dt>
                            <dd class="mt-2 text-slate-700">
                                <p>{{ $order->paymentMethod->name }}</p>
                            </dd>
                        </div>
                        <div>
                            <dt class="font-bold text-primary">{{ __('Forma de Envio') }}</dt>
                            <dd class="mt-2 text-slate-700">
                                <p>{{ $order->shipping_rate }}</p>
                            </dd>
                        </div>
                    </dl>

                   
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
                            <dt class="flex font-medium text-warning">{{ __('Desconto') }}</dt>
                            <dd class="text-slate-700">
                                - <x-money
                                    :amount="$order->discount_total"
                                    :currency="config('app.currency')"
                                />
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="font-medium text-slate-900">{{ __('Envio') }}</dt>
                            <dd class="text-slate-700 font-bold">
                                <x-money
                                    :amount="$order->shipping_price"
                                    :currency="config('app.currency')"
                                />
                            </dd>
                        </div>
                        
                        
                        <div class="flex justify-between">
                            <dt class="font-bold text-slate-900 text-lg">{{ __('Total') }}</dt>
                            <dd class="text-slate-900 font-bold text-lg">
                                <x-money
                                    :amount="$order->total - $order->total_refunded"
                                    :currency="config('app.currency')"
                                />
                            </dd>
                        </div>
                        @if($order->payment_status == \App\Enums\PaymentStatus::UNPAID)
                            @if( $order->paymentMethod->identifier == 'mercadopago')
                                <div class="flex  justify-end">
                                    <a class="btn btn-primary btn-lg " href="{{ route('customer.order.payment', $order->id) }}">
                                        {{ __('Efetuar Pagamento') }}
                                    </a>
                                </div>                     
                            @endif
                        @endif
                    </dl>
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










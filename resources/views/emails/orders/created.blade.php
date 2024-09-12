@component('mail::message')
# {{ __('Obrigado pelo seu pedido') }}

{{ __('Temos o prazer de informar que recebemos seu pedido. Confira abaixo os detalhes do seu Jumbo.') }}

@component('mail::button', ['url' => $url])
    {{ __('Ver pedido') }}
@endcomponent

@component('mail::table')
    | {{ __('Resumo do Pedido') }} |   |   |
    | :----------- | :------------ | -----------: |
    @foreach($order->orderItems as $item)
        | **{{ $item->name }} x {{ $item->quantity }}** |  | **{{ money($item->subtotal, config('app.currency')) }}** |
    @endforeach
    |  | {{ __('Subtotal') }}  | **{{ money($order->subtotal, config('app.currency')) }}** |
    |  | {{ __('Frete') }}  | **{{ money($order->shipping_price, config('app.currency')) }}** |
    |  | {{ __('Total') }}     | **{{ money($order->total, config('app.currency')) }}** |
@endcomponent

@component('mail::table')
    | {{ __('Informações do Cliente') }} |   |   |
    | :---------- | :----------: | :---------- |
    | **{{ __('Endereço para envio') }}**<br>{{ $order->prison_unit->name }}<br>{{ $order->prison_unit->logradouro }}<br>{{ $order->prison_unit->cidade }} {{ $order->prison_unit->uf }} {{ $order->prison_unit->cep }}<br>  | **{{ __('Endereço do Visitante') }}**<br>{{ $order->visitante->name }}<br>{{ $order->visitante->logradouro }}<br>{{ $order->visitante->cidade }} {{ $order->visitante->uf }} {{ $order->visitante->cep }} |
    | **{{ __('Forma de Envio') }}**<br>{{ $order->shipping_rate }} |  | **{{ __('Meio de pagamento') }}**<br>{{ $order->paymentMethod->name }} |
@endcomponent

{{ __('Grato,') }}<br>
{{ $generalSettings->store_name ?: config('app.name') }}
@endcomponent

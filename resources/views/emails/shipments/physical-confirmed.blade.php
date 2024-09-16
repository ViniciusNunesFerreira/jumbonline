@component('mail::message')
# {{ trans_choice('{1} Um item do seu pedido está a caminho|{2,*} Alguns itens do seu pedido estão a caminho', $shipment->shipmentItems->count()) }}

{{ trans_choice('{1} Um item do seu pedido está a caminho.|{2,*} Alguns itens do seu pedido estão a caminho.', $shipment->shipmentItems->count()) }} {{ __('Acompanhe sua remessa para ver o status da entrega.') }}

@component('mail::button', ['url' => $url])
{{ __('Veja seu Pedido') }}
@endcomponent

@if($shipment->tracking_number)
@component('mail::panel')
 {{ $shipment->shipping_carrier == \App\Enums\ShippingCarrier::OTHER ? __('Código de Rastreio: :tracking_number', ['tracking_number' => $shipment->tracking_number]) : __(':carrier tracking number: :tracking_number', ['carrier' => $shipment->shipping_carrier->label(), 'tracking_number' => $shipment->tracking_number]) }}
@endcomponent
@endif

@component('mail::table')
| {{ __('Itens do Jumbo') }} |   |
| :----------- | ------------: |
@foreach($shipment->shipmentItems as $shipmentItem)
| **{{ $shipmentItem->orderItem->name }}** | {{ trans(':shipmentQuantity of :orderQuantity', ['shipmentQuantity' => $shipmentItem->quantity, 'orderQuantity' => $shipmentItem->orderItem->quantity]) }} |
@endforeach
@endcomponent

{{ __('Obrigado,') }}<br>
{{ $generalSettings->store_name ?: config('app.name') }}
@endcomponent

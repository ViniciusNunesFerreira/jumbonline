<div>
    <x-slot:title>{{ __('Histórico de Pedidos') }}</x-slot:title>

    <x-account-layout active="orders" title="Meus Pedidos" subtitle="Acompanhe o status, avalie produtos e reveja os jumbos que você já enviou.">
        <div class="space-y-5">
            @forelse($orders as $order)
                <div class="overflow-hidden rounded-3xl border border-secondary bg-white">
                    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-secondary bg-complement-500/60 px-5 py-4 sm:px-6">
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-sm">
                            <div><span class="text-slate-500">Pedido</span> <span class="ml-1 font-bold text-primary">#{{ $order->id }}</span></div>
                            <div><span class="text-slate-500">Data</span> <span class="ml-1 font-medium text-primary">{{ $order->created_at->format('d/m/Y') }}</span></div>
                            <div><span class="text-slate-500">Total</span> <span class="ml-1 font-bold text-primary"><x-money :amount="$order->total" :currency="config('app.currency')" /></span></div>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($order->payment_status === \App\Enums\PaymentStatus::PAID && $order->shipping_status === \App\Enums\ShippingStatus::SHIPPED)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-success/10 px-3 py-1.5 text-xs font-semibold text-success"><x-heroicon-s-truck class="h-3.5 w-3.5" /> Enviado</span>
                            @elseif($order->payment_status === \App\Enums\PaymentStatus::PAID)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-accent/10 px-3 py-1.5 text-xs font-semibold text-accent"><x-heroicon-s-check-circle class="h-3.5 w-3.5" /> Em separação</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-warning/10 px-3 py-1.5 text-xs font-semibold text-warning"><x-heroicon-s-clock class="h-3.5 w-3.5" /> Aguardando pagamento</span>
                            @endif
                            <a href="{{ route('customer.orders.detail', $order) }}" class="flex items-center gap-1 text-sm font-semibold text-purple hover:text-accent">Ver detalhes <x-heroicon-s-chevron-right class="h-4 w-4" /></a>
                        </div>
                    </div>

                    <ul role="list" class="divide-y divide-secondary/60">
                        @foreach($order->orderItems as $item)
                            <li class="flex items-center gap-4 px-5 py-4 sm:px-6">
                                <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl border border-secondary bg-complement-500">
                                    <img src="{{ $item->variant->hasMedia('image') ? $item->variant->getFirstMediaUrl('image') : $item->product->getFirstMediaUrl('gallery') }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-primary">{{ $item->quantity }}x {{ $item->product->name }}</p>
                                    @if($item->variant->variantAttributes->count())
                                        <p class="text-xs text-slate-500">@foreach($item->variant->variantAttributes as $attribute){{ $attribute->optionValue->label }}{{ !$loop->last ? ' · ' : '' }}@endforeach</p>
                                    @endif
                                </div>
                                <button wire:click="writeReviewForProduct({{ $item->product->id }})" type="button" class="flex-shrink-0 rounded-full border border-secondary px-4 py-2 text-xs font-semibold text-primary hover:bg-complement-500">
                                    {{ $item->product->reviews->isEmpty() ? 'Avaliar' : 'Editar avaliação' }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <div class="rounded-3xl border border-secondary bg-white p-12 text-center">
                    <img src="{{ asset('img/maskote.png') }}" alt="" class="mx-auto h-28 w-auto opacity-90">
                    <h3 class="mt-4 font-urbanist text-lg font-semibold text-primary">Você ainda não tem pedidos</h3>
                    <p class="mt-1 text-sm text-slate-500">Assim que montar seu primeiro jumbo, ele aparece aqui.</p>
                    <a href="{{ route('guest.welcome') }}" class="mt-6 inline-flex items-center gap-2 rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white hover:bg-primary">Montar meu Jumbo <x-heroicon-s-arrow-right class="h-4 w-4" /></a>
                </div>
            @endforelse
        </div>

        @if($orders->hasPages())
            <div class="mt-8">{{ $orders->links() }}</div>
        @endif
    </x-account-layout>

    <form wire:submit.prevent="saveReview">
        <x-modal-dialog wire:model="showReviewForm">
            <x-slot:title>Escreva uma avaliação</x-slot:title>
            <x-slot:content>
                <div class="space-y-5">
                    <div>
                        <x-input-label for="review.rating" value="Avaliação" />
                        <x-select wire:model.defer="review.rating" id="rating" class="mt-1.5 block w-full">
                            <option value="">Classifique o produto</option>
                            @for($i = 1; $i <= 5; $i++)<option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'estrela' : 'estrelas' }}</option>@endfor
                        </x-select>
                        <x-input-error for="review.rating" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="review.title" value="Resumo" />
                        <x-input wire:model.defer="review.title" id="title" type="text" class="mt-1.5 block w-full" />
                        <x-input-error for="review.title" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="review.content" value="Sua opinião" />
                        <x-textarea wire:model="review.content" id="comment" class="mt-1.5 block w-full" />
                        <x-input-error for="review.content" class="mt-2" />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button wire:target="save" wire:loading.attr="disabled" type="submit" class="w-full rounded-full bg-accent py-3 text-sm font-semibold text-white hover:bg-primary sm:ml-3 sm:w-auto sm:px-8">Salvar</button>
                <button wire:click="$set('showReviewForm', false)" wire:target="save" wire:loading.attr="disabled" type="button" class="mt-3 w-full rounded-full border border-secondary py-3 text-sm font-semibold text-primary hover:bg-complement-500 sm:mt-0 sm:w-auto sm:px-8">Cancelar</button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>
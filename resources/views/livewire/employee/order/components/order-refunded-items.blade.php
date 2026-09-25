<div>
    <x-card>
        <x-slot:header>
            <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                {{ __('Itens Removidos') }}
            </h3>
        </x-slot:header>
        <x-slot:content class="-mx-4 -mt-5 sm:-mx-6">
            <div class="-mb-5">
                <div class="relative overflow-auto">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-white/5">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-white/5">
                                <th scope="col" class="px-3 py-3 sm:px-6"></th>
                                <th scope="col" class="px-3 py-3 sm:px-6 text-center text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    {{ __('Qtd.') }}
                                </th>
                                <th scope="col" class="px-3 py-3 sm:px-6 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    {{ __('Preço') }}
                                </th>
                                <th scope="col" class="px-3 py-3 sm:px-6 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    {{ __('Subtotal') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @foreach($refundedItems as $item)
                                <tr>
                                    <td class="px-3 py-4 sm:px-6 w-full max-w-sm text-sm text-slate-500 dark:text-slate-400">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img
                                                    class="h-10 w-10 rounded-lg object-center object-cover ring-1 ring-slate-100 dark:ring-white/10"
                                                    src="{{ $item->variant->hasMedia('image') ? $item->variant->getFirstMediaUrl('image', 'thumb') : $item->variant->product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                    alt="{{ $item->name }}"
                                                >
                                            </div>
                                            <div class="ml-3.5 max-w-xs flex flex-col">
                                                <div class="font-medium text-primary hover:text-accent-600 truncate dark:text-slate-200 dark:hover:text-accent-400">
                                                    <a href="{{ route('employee.products.detail', $item->variant->product) }}">{{ $item->name }}</a>
                                                </div>
                                                @if($item->variant->variantAttributes)
                                                    <ul class="space-x-2 divide-x divide-slate-200 text-slate-500 dark:divide-white/10 dark:text-slate-400">
                                                        @foreach($item->variant->variantAttributes as $attribute)
                                                            <li @class(['inline', 'pl-2' => !$loop->first])>{{ $attribute->optionValue->label }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                                @if($item->discount)
                                                    <ul class="list-disc list-inside">
                                                        <li>{{ __('Desconto :discountCode aplicado', ['discountCode' => $item->discount->code]) }}</li>
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-center text-sm text-slate-500 tabular-nums dark:text-slate-400">
                                        {{ $item->refund_items_sum_quantity }}
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-right text-sm text-slate-500 tabular-nums dark:text-slate-400">
                                        @if($item->discount)
                                            <span class="block text-xs line-through">
                                                <x-money :amount="$item->price" :currency="config('app.currency')" />
                                            </span>
                                            <x-money
                                                :amount="$item->discount->type === 'fixed' ? $item->price - $item->discount->amount : $item->price - ($item->price * $item->discount->amount / 100)"
                                                :currency="config('app.currency')"
                                            />
                                        @else
                                            <x-money :amount="$item->price" :currency="config('app.currency')" />
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-right text-sm font-semibold text-primary tabular-nums dark:text-slate-200">
                                        <x-money :amount="$item->price * $item->refund_items_sum_quantity - $item->discount?->discounted_amount" :currency="config('app.currency')" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot:content>
    </x-card>
</div>
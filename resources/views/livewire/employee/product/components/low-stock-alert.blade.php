<div>
    @if($this->variants->isNotEmpty())
        <x-card>
            <x-slot:header>
                <div class="flex items-center gap-2">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-amber-500" />
                    <h3 class="font-display font-medium text-base text-slate-900 dark:text-slate-200">
                        {{ __('Estoque baixo') }}
                    </h3>
                </div>
            </x-slot:header>
            <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                <ul class="divide-y divide-slate-100 dark:divide-white/5">
                    @foreach($this->variants as $variant)
                        <li class="flex items-center justify-between px-4 py-3 sm:px-6">
                            <a href="{{ route('employee.products.variants.detail', [$variant->product, $variant]) }}" class="text-sm text-slate-700 hover:text-sky-600 dark:text-slate-300">
                                {{ $variant->product->name }} {{ $variant->sku ? "({$variant->sku})" : '' }}
                            </a>
                            <x-badge type="danger" size="xs">{{ $variant->stock_value }} {{ __('un.') }}</x-badge>
                        </li>
                    @endforeach
                </ul>
            </x-slot:content>
        </x-card>
    @endif
</div>
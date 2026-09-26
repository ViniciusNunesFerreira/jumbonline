<div>
    <x-slot:title>
        {{ __('Promoções') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                {{ __('Promoções') }}
            </h1>
        </div>

        <div class="mt-6">
            <div class="flex w-fit rounded-xl bg-slate-100 p-1 dark:bg-white/5">
                <button wire:click="setTab('discounts')" type="button" @class(['rounded-lg px-4 py-2 text-sm font-semibold transition-colors', 'bg-white text-primary shadow-sm dark:bg-slate-800 dark:text-white' => $tab === 'discounts', 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white' => $tab !== 'discounts'])>
                    {{ __('Descontos') }}
                </button>
                <button wire:click="setTab('frete')" type="button" @class(['rounded-lg px-4 py-2 text-sm font-semibold transition-colors', 'bg-white text-primary shadow-sm dark:bg-slate-800 dark:text-white' => $tab === 'frete', 'text-slate-500 hover:text-primary dark:text-slate-400 dark:hover:text-white' => $tab !== 'frete'])>
                    {{ __('Frete Grátis') }}
                </button>
            </div>

            <div class="mt-6">
                @if($tab === 'discounts')
                    <livewire:employee.discount.discount-list />
                @else
                    <livewire:employee.promotion.promotion-manager />
                @endif
            </div>
        </div>
    </div>
</div>
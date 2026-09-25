<div>
    <x-slot:title>
        {{ __('Cliente - :name', ['name' => $customer->name]) }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('employee.customers.list') }}" class="btn btn-default btn-xs !rounded-xl">
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <div class="flex items-center gap-2.5">
                <img
                    src="{{ $customer->getFirstMediaUrl('avatar') }}"
                    alt="{{ $customer->name }}"
                    class="h-9 w-9 rounded-full bg-slate-100 object-cover ring-2 ring-white dark:bg-slate-800 dark:ring-slate-900"
                >
                <h1 class="text-2xl font-bold tracking-tight text-primary dark:text-white">
                    {{ $customer->name }}
                </h1>
                @if($customer->banned_at)
                    <x-badge type="danger" size="xs">{{ __('Banido') }}</x-badge>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-3 xl:col-span-2 space-y-6">
                    @if($customer->paid_orders_count > 0)
                        <livewire:employee.customer.components.customer-statistics :customer="$customer" />
                    @endif

                    <livewire:employee.customer.components.customer-notes :customer="$customer" />

                    <livewire:employee.customer.components.customer-interactions :customer="$customer" />

                    <livewire:employee.customer.components.customer-latest-order :customer="$customer" />
                </div>

                <div class="col-span-3 xl:col-span-1 space-y-6">
                    <livewire:employee.customer.components.customer-information :customer="$customer" />

                    <livewire:employee.customer.components.customer-password-reset :customer="$customer" />

                    <livewire:employee.customer.components.customer-address :customer="$customer" />

                    <livewire:employee.customer.components.customer-detento :customer="$customer" />
                </div>
            </div>
        </div>
    </div>
</div>
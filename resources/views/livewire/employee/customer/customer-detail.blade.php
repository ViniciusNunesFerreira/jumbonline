<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Customer - :name', ['name' => $customer->name]) }}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex flex-1 items-center space-x-2">
            <a
                href="{{ route('employee.customers.list') }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-medium leading-6 text-slate-900 dark:text-slate-100">
                {{ $customer->name }}
            </h1>
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 xl:col-span-2 space-y-6">
                @if($customer->orders_count)
                    <livewire:employee.customer.components.customer-statistics :customer="$customer" />
                @endif

                <livewire:employee.customer.components.customer-latest-order :customer="$customer" />
            </div>

            <div class="col-span-3 xl:col-span-1 space-y-6">
                <livewire:employee.customer.components.customer-notes :customer="$customer" />

                <livewire:employee.customer.components.customer-information :customer="$customer" />

                <livewire:employee.customer.components.customer-address :customer="$customer" />
            </div>
        </div>
    </div>
</div>

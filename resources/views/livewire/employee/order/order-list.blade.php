<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Orders') }}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-medium text-slate-900 dark:text-slate-100">
                {{ __('Orders') }}
            </h1>
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        @if(!$orders->count() && !$search)
            <x-card>
                <x-slot:content>
                    <div class="max-w-lg mx-auto text-center">
                        <x-heroicon-o-inbox-arrow-down class="mx-auto h-12 w-12 text-slate-400" />

                        <h3 class="mt-2 text-lg font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Your orders will show here') }}
                        </h3>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('This is where you’ll fulfill orders, collect payments and track order progress.') }}
                        </p>
                    </div>
                </x-slot:content>
            </x-card>
        @else
            <x-card class="overflow-hidden">
                <x-slot:header>
                    <div
                        x-data="{ search: @entangle('search')}"
                        class="relative max-w-sm text-slate-400 focus-within:text-slate-600 dark:focus-within:text-slate-200"
                    >
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                        </div>
                        <x-input
                            wire:model.debounce.500ms="search"
                            type="text"
                            class="placeholder-slate-500 w-full pl-10 sm:text-sm focus:placeholder-slate-400 dark:focus:placeholder-slate-600"
                            ::class="{ 'pr-10' : search }"
                            placeholder="{{ __('Filter orders') }}"
                        />
                        <button
                            x-show="search.length"
                            x-on:click="search = ''"
                            type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3"
                        >
                            <x-heroicon-s-x-circle class="w-5 h-5 text-slate-500 hover:text-slate-600 dark:hover:text-slate-400" />
                        </button>
                    </div>
                </x-slot:header>
                <x-slot:content>
                    <div class="-mx-4 -my-5 overflow-x-auto sm:-mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="relative overflow-hidden">
                                <div
                                    wire:loading.delay
                                    class="absolute inset-0 z-10 bg-slate-100/50 dark:bg-slate-800/50"
                                >
                                    <div
                                        wire:loading.flex
                                        class="h-full w-screen items-center justify-center sm:w-auto"
                                    >
                                        <div class="m-auto flex items-center space-x-2">
                                            <p class="text-sm dark:text-slate-200">{{ 'Loading orders...' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-200/10">
                                    <thead class="border-t border-slate-200 bg-slate-50 dark:border-slate-200/10 dark:bg-slate-800/75">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="relative w-12 px-6 sm:w-16 sm:px-8"
                                            >
                                                <x-input
                                                    wire:model="selectPage"
                                                    type="checkbox"
                                                    class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6"
                                                />
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                            >
                                                {{ __('ID') }}
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                            >
                                                {{ __('Customer') }}
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                            >
                                                {{ __('Payment Status') }}
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                            >
                                                {{ __('Shipping Status') }}
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                            >
                                                {{ __('Items') }}
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-4 text-right text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                            >
                                                {{ __('Total') }}
                                            </th>
                                            <th
                                                scope="col"
                                                class="pl-3 pr-4 py-4 text-right text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap sm:pr-6 dark:text-slate-200"
                                            >
                                                {{ __('Date') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-200/10">
                                        @forelse($orders as $order)
                                            <tr
                                                wire:loading.class.delay="opacity-50"
                                                class="relative hover:bg-slate-50 dark:hover:bg-slate-800/75"
                                            >
                                                <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                    @if(in_array($order->id, $selected))
                                                        <div class="absolute inset-y-0 left-0 w-0.5 bg-sky-500 dark:bg-sky-400"></div>
                                                    @endif
                                                    <x-input
                                                        wire:model="selected"
                                                        wire:key="checkbox-{{ $order->id }}"
                                                        type="checkbox"
                                                        value="{{ $order->id }}"
                                                        class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6"
                                                    />
                                                </td>
                                                <td class="relative px-3 py-4 font-medium text-sm text-slate-900 text-left whitespace-nowrap tabular-nums dark:text-slate-200">
                                                    <a
                                                        href="{{ route('employee.orders.detail', $order) }}"
                                                        class="hover:text-sky-600 dark:hover:text-sky-400"
                                                    >
                                                        {{ $order->id }}
                                                    </a>
                                                </td>
                                                <td class="relative px-3 py-4 text-sm text-slate-900 text-left whitespace-nowrap dark:text-slate-200">
                                                    @if($order->customer)
                                                        <a
                                                            href="{{ route('employee.customers.detail', $order->customer) }}"
                                                            class="hover:text-sky-600 dark:hover:text-sky-400"
                                                        >
                                                            {{ $order->customer->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-gray-500">{{ __('No customer') }}</span>
                                                    @endif
                                                </td>
                                                <td class="relative px-3 py-4 text-sm text-slate-500 text-left whitespace-nowrap dark:text-slate-400">
                                                    <div class="flex items-center space-x-1">
                                                        <span
                                                            class="block w-2 h-2 rounded-full"
                                                            style="background-color: {{ $order->payment_status->color() }}"
                                                        ></span>
                                                        <span>{{ $order->payment_status->label() }}</span>
                                                    </div>
                                                </td>
                                                <td class="relative px-3 py-4 text-sm text-slate-500 text-left whitespace-nowrap dark:text-slate-400">
                                                    <div class="flex items-center space-x-1">
                                                        <span
                                                            class="block w-2 h-2 rounded-full"
                                                            style="background-color: {{ $order->shipping_status->color() }}"
                                                        ></span>
                                                        <span>{{ $order->shipping_status->label() }}</span>
                                                    </div>
                                                </td>
                                                <td class="relative px-3 py-4 text-sm text-slate-500 text-left whitespace-nowrap tabular-nums dark:text-slate-400">
                                                    {{ trans_choice(':count item|:count items', $order->order_items_sum_quantity) }}
                                                </td>
                                                <td class="relative px-3 py-4 text-sm text-slate-500 text-right whitespace-nowrap tabular-nums dark:text-slate-400">
                                                    <x-money :amount="$order->total" />
                                                </td>
                                                <td class="pl-3 pr-4 py-4 text-right text-sm text-slate-500 whitespace-nowrap tabular-nums sm:pr-6 dark:text-slate-400">
                                                    {{ $order->created_at }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td
                                                    class="px-3 py-4 text-sm text-slate-500 text-center whitespace-nowrap dark:text-slate-400"
                                                    colspan="8"
                                                >
                                                    <div class="max-w-lg mx-auto text-center">
                                                        <x-heroicon-o-magnifying-glass class="inline-block w-10 h-10 text-slate-400 dark:text-slate-300" />
                                                        <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-200">
                                                            {{ __('No orders found') }}
                                                        </h3>
                                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                            {{ __('Try changing the filters or search term') }}
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-slot:content>
            </x-card>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

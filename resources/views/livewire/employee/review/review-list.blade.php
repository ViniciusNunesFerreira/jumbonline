<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Reviews') }}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex flex-1 items-center space-x-2">
            <h1 class="text-2xl font-medium text-slate-900 dark:text-slate-100">
                {{ __('Reviews') }}
            </h1>
        </div>
        <div class="justify-stretch mt-6 flex flex-col-reverse space-y-4 space-y-reverse sm:ml-4 sm:flex-row-reverse sm:justify-end sm:space-y-0 sm:space-x-3 sm:space-x-reverse md:mt-0 md:flex-row md:space-x-3">
            <a
                href="{{ route('employee.reviews.create') }}"
                class="btn btn-primary w-full"
            >
                {{ __('Write a review') }}
            </a>
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
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
                        placeholder="{{ __('Filter reviews') }}"
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
            <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                <div class="overflow-x-auto">
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
                                        <p class="text-sm dark:text-slate-200">{{ 'Loading reviews...' }}</p>
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
                                            class="w-36 px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                        >
                                            {{ __('Rating') }}
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                        >
                                            {{ __('Review') }}
                                        </th>
                                        <th
                                            scope="col"
                                            class="pl-3 pr-4 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap sm:pr-6 dark:text-slate-200"
                                        >
                                            {{ __('Status') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-200/10">
                                    @forelse($reviews as $review)
                                        <tr
                                            wire:loading.class.delay="opacity-50"
                                            class="relative hover:bg-slate-50 dark:hover:bg-slate-800/75"
                                        >
                                            <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                @if(in_array($review->id, $selected))
                                                    <div class="absolute inset-y-0 left-0 w-0.5 bg-sky-500 dark:bg-sky-400"></div>
                                                @endif
                                                <x-input
                                                    wire:model="selected"
                                                    wire:key="checkbox-{{ $review->id }}"
                                                    type="checkbox"
                                                    value="{{ $review->id }}"
                                                    class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6"
                                                />
                                            </td>
                                            <td class="relative px-3 py-4 font-medium text-sm text-slate-900 text-left whitespace-nowrap dark:text-slate-200">
                                                <div class="flex items-center">
                                                    <x-star-rating :rating="$review->rating" />
                                                </div>
                                            </td>
                                            <td class="relative px-3 py-4 text-sm text-slate-500 text-left max-w-sm dark:text-slate-400">
                                                <a
                                                    href="{{ route('employee.reviews.detail', $review) }}"
                                                    class="btn btn-link line-clamp-1"
                                                >
                                                    {{ $review->title }}
                                                </a>
                                                <p class="line-clamp-2">
                                                    {{ strip_tags($review->content) }}
                                                </p>
                                                <p>&mdash; {{ $review->customer->name }} {{ __('on') }}
                                                    <a
                                                        href="{{ route('employee.products.detail', $review->product) }}"
                                                        class="btn btn-link"
                                                    >
                                                        {{ $review->product->name }}
                                                    </a>
                                                </p>
                                            </td>
                                            <td class="pl-3 pr-4 py-4 text-left text-sm text-slate-500 whitespace-nowrap sm:pr-6 dark:text-slate-400">
                                                @if($review->is_published)
                                                    <x-badge type="success">
                                                        {{ $review->status }}
                                                    </x-badge>
                                                @else
                                                    <x-badge type="warning">
                                                        {{ $review->status }}
                                                    </x-badge>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td
                                                class="px-3 py-4 text-sm text-slate-500 text-center whitespace-nowrap dark:text-slate-400"
                                                colspan="4"
                                            >
                                                <div class="max-w-lg mx-auto text-center">
                                                    <x-heroicon-o-magnifying-glass class="inline-block w-10 h-10 text-slate-400 dark:text-slate-300" />
                                                    <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-200">
                                                        {{ __('No reviews found') }}
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
            {{ $reviews->links() }}
        </div>
    </div>
</div>

<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Reviews') }}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex flex-1 items-center space-x-2">
            <a
                href="{{ route('employee.reviews.list') }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-medium leading-6 text-slate-900 dark:text-slate-100">
                {{ $review->title }}
            </h1>
        </div>
        <div class="justify-stretch mt-6 flex flex-col-reverse space-y-4 space-y-reverse sm:ml-4 sm:flex-row-reverse sm:justify-end sm:space-y-0 sm:space-x-3 sm:space-x-reverse md:mt-0 md:flex-row md:space-x-3">
            <button
                wire:click="confirmReviewDeletion"
                type="button"
                class="btn btn-outline-danger w-full"
            >
                {{ __('Delete') }}
            </button>
            @if($review->is_published)
                <button
                    wire:click="unpublishReview"
                    type="button"
                    class="btn btn-outline-warning w-full"
                >
                    {{ __('Unpublish') }}
                </button>
            @else
                <button
                    wire:click="publishReview"
                    type="button"
                    class="btn btn-primary w-full"
                >
                    {{ __('Publish') }}
                </button>
            @endif
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 xl:col-span-2 space-y-6">
                <x-card>
                    <x-slot:header>
                        <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                            <div class="ml-4 mt-2">
                                <div class="flex">
                                    <x-star-rating :rating="$review->rating" />
                                </div>
                            </div>
                            <div class="ml-4 mt-2 flex-shrink-0">
                                @if($review->is_published)
                                    <x-badge type="success">
                                        {{ $review->status }}
                                    </x-badge>
                                @else
                                    <x-badge type="warning">
                                        {{ $review->status }}
                                    </x-badge>
                                @endif
                            </div>
                        </div>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="-mt-5 prose prose-sm prose-slate max-w-none dark:prose-invert">
                            {!! $review->content !!}
                        </div>
                    </x-slot:content>
                    <x-slot:footer class="border-t border-slate-200 dark:border-slate-200/10">
                        <p class="text-sm">
                            &mdash; {{ $review->customer->name }} (<a
                                href="mailto:{{ $review->customer->email }}"
                                class="btn btn-link"
                            >{{ $review->customer->email }}</a>)
                        </p>
                    </x-slot:footer>
                </x-card>
            </div>
            <div class="col-span-3 xl:col-span-1 space-y-6">
                <x-card>
                    <x-slot:header>
                        <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Product details') }}
                        </h3>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="flex -mt-5">
                            <div class="mr-4 flex-shrink-0">
                                <img
                                    class="h-10 w-10 rounded object-center object-cover"
                                    src="{{ $review->product->getFirstMediaUrl('gallery', 'thumb') }}"
                                    alt="{{ $review->product->name }}"
                                >
                            </div>
                            <div>
                                <h4 class="text-md font-medium line-clamp-2">
                                    <a
                                        href="{{ route('employee.products.detail', $review->product) }}"
                                        class="text-slate-900 dark:text-slate-200 hover:text-slate-600 dark:hover:text-slate-300"
                                    >
                                        {{ $review->product->name }}
                                    </a>
                                </h4>
                                <div class="mt-1 flex">
                                    <x-star-rating :rating="$review->product->reviews->avg('rating')" />
                                    <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                                        {{ trans_choice(':count review|:count reviews', $review->product->reviews->count()) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="delete">
        <x-modal-alert wire:model.defer="confirmingReviewDeletion">
            <x-slot:title>
                {{ __('Delete review') }}
            </x-slot:title>
            <x-slot:content>
                <p class="text-sm text-slate-700 dark:text-slate-200">
                    {{ __('Are you sure you want to delete this review? This action cannot be undone!') }}
                </p>
            </x-slot:content>
            <x-slot:footer>
                <button
                    type="submit"
                    class="btn btn-danger w-full sm:ml-3 sm:w-auto"
                >
                    {{ __('Delete') }}
                </button>
                <button
                    wire:click="$set('confirmingReviewDeletion', false)"
                    type="button"
                    class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                >
                    {{ __('Cancel') }}
                </button>
            </x-slot:footer>
        </x-modal-alert>
    </form>
</div>

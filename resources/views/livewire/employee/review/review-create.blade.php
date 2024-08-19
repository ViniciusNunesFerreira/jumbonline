<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Reviews') }}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 max-w-3xl mx-auto sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex flex-1 items-center space-x-2">
            <a
                href="{{ route('employee.reviews.list') }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-medium leading-6 text-slate-900 dark:text-slate-100">
                {{ __('Write a review') }}
            </h1>
        </div>
    </div>

    <!-- Page content -->
    <div class="p-4 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <form
            wire:submit.prevent="save"
            wire:target="save"
            wire:loading.attr="disabled"
        >
            <x-card>
                <x-slot:content>
                    <div class="space-y-6">
                        <div>
                            <x-input-label
                                for="customer_id"
                                :value="__('Customer')"
                            />
                            <x-select
                                id="customer_id"
                                class="block w-full mt-1 sm:text-sm"
                                wire:model.debounce="review.customer_id"
                            >
                                <option value="">{{ __('Select a customer') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error
                                for="review.customer_id"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <x-input-label
                                for="order_id"
                                :value="__('Order')"
                            />
                            <x-select
                                id="order_id"
                                class="block w-full mt-1 sm:text-sm"
                                wire:model.debounce="review.order_id"
                            >
                                <option value="">{{ __('Select an order') }}</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}">
                                        #{{ $order->id }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error
                                for="review.order_id"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <x-input-label
                                for="product_id"
                                :value="__('Product')"
                            />
                            <x-select
                                id="product_id"
                                class="block w-full mt-1 sm:text-sm"
                                wire:model.debounce="review.product_id"
                            >
                                <option value="">{{ __('Select a product') }}</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error
                                for="review.product_id"
                                class="mt-2"
                            />
                        </div>

                        <hr class="-mx-4 sm:-mx-6 border-slate-200 dark:border-slate-200/20" />

                        @if($isReviewExists)
                            <x-alert
                                type="warning"
                                :message="__('You are editing an existing review.')"
                            />
                        @endif

                        <div>
                            <x-input-label
                                for="rating"
                                :value="__('Rating')"
                            />
                            <x-select
                                id="rating"
                                class="block w-full mt-1 sm:text-sm"
                                wire:model.defer="review.rating"
                            >
                                <option value="">{{ __('Select a rating') }}</option>
                                @foreach(range(1, 5) as $rating)
                                    <option value="{{ $rating }}">
                                        {{ $rating }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error
                                for="review.rating"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <x-input-label
                                for="title"
                                :value="__('Title')"
                            />
                            <x-input
                                id="title"
                                type="text"
                                class="block w-full mt-1 sm:text-sm"
                                wire:model.defer="review.title"
                                placeholder="{{ __('Title') }}"
                            />
                            <x-input-error
                                for="review.title"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <x-input-label
                                for="content"
                                :value="__('Body')"
                            />
                            <div class="block w-full mt-1 shadow-sm sm:text-sm">
                                <x-quill
                                    id="content"
                                    wire:model.defer="review.content"
                                />
                            </div>
                            <x-input-error
                                for="review.content"
                                class="mt-2"
                            />
                        </div>
                    </div>
                </x-slot:content>
                <x-slot:footer>
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            {{ __('Save') }}
                        </button>
                    </div>
                </x-slot:footer>
            </x-card>
        </form>
    </div>
</div>

<div>
    <x-slot:title>
        {{ $discount->exists ? __('Desconto :code', ['code' => $discount->code ?? '#' . $discount->id]) : __('Criar desconto') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8 mx-auto">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex flex-1 items-center gap-2.5">
                
                <a   href="{{ route('employee.promotions.list') }}"
                    class="btn btn-default btn-xs !rounded-xl"
                >
                    <x-heroicon-m-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-primary truncate dark:text-white">
                    {{ $discount->exists ? ($discount->code ?? __('Promoção automática')) : __('Criar desconto') }}
                </h1>
            </div>
        </div>

        <div class="mt-6 space-y-6">
            @if($message = session('success'))
                <x-alert type="success" :message="$message" />
            @endif

            <x-card>
                <x-slot:header>
                    <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                        {{ __('Ativação') }}
                    </h2>
                </x-slot:header>
                <x-slot:content>
                    <div
                        x-data="{
                            activation: @entangle('activationType'),
                            code: @entangle('discount.code').defer,
                            generateCode() {
                                const characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ0123456789';
                                let code = '';
                                for (let i = 0; i < 12; i++) {
                                   code += characters.charAt(Math.floor(Math.random() * characters.length));
                                }
                                return this.code = code;
                            }
                        }"
                    >
                        <div class="grid grid-cols-2 gap-3">
                            <label
                                class="relative flex cursor-pointer rounded-xl border p-4"
                                x-bind:class="activation === 'automatic' ? 'border-accent-500 ring-2 ring-accent-500' : 'border-slate-200 dark:border-white/10'"
                            >
                                <input x-model="activation" type="radio" name="activation-type" value="automatic" class="sr-only" />
                                <div>
                                    <span class="block text-sm font-semibold text-primary dark:text-slate-200">{{ __('Automático') }}</span>
                                    <span class="block text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Aplica sozinho, sem o cliente digitar nada.') }}</span>
                                </div>
                            </label>
                            <label class="relative flex cursor-pointer rounded-xl border p-4"
                                x-bind:class="activation === 'code' ? 'border-accent-500 ring-2 ring-accent-500' : 'border-slate-200 dark:border-white/10'"
                            >
                                <input x-model="activation" type="radio" name="activation-type" value="code" class="sr-only" />
                                <div>
                                    <span class="block text-sm font-semibold text-primary dark:text-slate-200">{{ __('Código promocional') }}</span>
                                    <span class="block text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Cliente digita um código no carrinho.') }}</span>
                                </div>
                            </label>
                        </div>

                        <div x-show="activation === 'code'" x-cloak class="mt-5">
                            <x-input-label for="code" :value="__('Código do desconto')" />
                            <div class="mt-1 flex">
                                <div class="relative flex flex-grow items-stretch focus-within:z-10">
                                    <x-input
                                        x-model="code"
                                        id="code"
                                        type="text"
                                        class="block w-full !rounded-none !rounded-l-xl sm:text-sm"
                                        placeholder="{{ __('Digite o código do desconto') }}"
                                    />
                                </div>
                                <button
                                    x-on:click="generateCode()"
                                    type="button"
                                    class="relative -ml-px btn btn-default !rounded-none !rounded-r-xl"
                                >
                                    {{ __('Gerar código') }}
                                </button>
                            </div>
                            <x-input-error for="discount.code" class="mt-2" />
                        </div>
                    </div>
                </x-slot:content>
            </x-card>

            <x-card>
                <x-slot:header>
                    <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                        {{ __('Valor do desconto') }}
                    </h2>
                </x-slot:header>
                <x-slot:content>
                    <div class="grid sm:grid-cols-2 gap-4" x-data="{ type: @entangle('discount.type').defer }">
                        <div>
                            <x-input-label for="type" :value="__('Tipo')" />
                            <x-select x-model="type" id="type" class="mt-1 !h-10 block w-full rounded-xl sm:text-sm">
                                <option value="percentage">{{ __('Porcentagem') }}</option>
                                <option value="fixed">{{ __('Valor fixo') }}</option>
                            </x-select>
                        </div>

                        <div>
                            <x-input-label for="value" :value="__('Valor')" />
                            <div class="relative mt-1">
                                <x-input
                                    wire:model.defer="discount.value"
                                    id="value"
                                    type="number"
                                    step="any"
                                    class="no-spinners block w-full rounded-xl pr-12 sm:text-sm"
                                    placeholder="{{ __('Digite o valor do desconto') }}"
                                />
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span x-show="type === 'fixed'" class="text-slate-400 sm:text-sm">{{ config('app.currency') }}</span>
                                    <span x-show="type === 'percentage'" class="text-slate-400 sm:text-sm">%</span>
                                </div>
                            </div>
                            <x-input-error for="discount.value" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="usage_limit" :value="__('Limite de uso (opcional)')" />
                        <x-input
                            wire:model.defer="discount.usage_limit"
                            id="usage_limit"
                            type="number"
                            min="1"
                            class="mt-1 block w-full max-w-xs rounded-xl sm:text-sm"
                            placeholder="{{ __('Sem limite se vazio') }}"
                        />
                        <x-input-error for="discount.usage_limit" class="mt-2" />
                        @if($discount->exists && $discount->usage_count > 0)
                            <p class="mt-1 text-xs text-slate-400">{{ __('Já usado :count vez(es).', ['count' => $discount->usage_count]) }}</p>
                        @endif
                    </div>
                </x-slot:content>
            </x-card>

            <x-card>
                <x-slot:header>
                    <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                        {{ __('Aplica-se a') }}
                    </h2>
                </x-slot:header>
                <x-slot:content>
                    <div
                        x-data="{ applies: @entangle('discount.applies_to'), search: '' }"
                        x-init="$watch('search', value => {
                            if (applies === 'collections') {
                                $wire.searchCollections(value)
                            } else if (applies === 'products') {
                                $wire.searchProducts(value)
                            }
                            search = ''
                        })"
                    >
                        <div class="space-y-3 sm:flex sm:items-center sm:space-y-0 sm:space-x-8">
                            <label class="flex items-center gap-2">
                                <x-input x-model="applies" wire:model.defer="discount.applies_to" type="radio" name="applies-to" value="orders" class="!rounded-full text-accent-500 focus:ring-accent-500" />
                                {{ __('Pedido inteiro') }}
                            </label>
                            <label class="flex items-center gap-2">
                                <x-input x-model="applies" wire:model.defer="discount.applies_to" type="radio" name="applies-to" value="collections" class="!rounded-full text-accent-500 focus:ring-accent-500" />
                                {{ __('Grupos específicos') }}
                            </label>
                            <label class="flex items-center gap-2">
                                <x-input x-model="applies" wire:model.defer="discount.applies_to" type="radio" name="applies-to" value="products" class="!rounded-full text-accent-500 focus:ring-accent-500" />
                                {{ __('Kits específicos') }}
                            </label>
                        </div>

                        <div x-show="applies !== 'orders'" x-cloak class="mt-4 flex">
                            <div class="relative flex flex-grow items-stretch focus-within:z-10">
                                <x-input
                                    x-model="search"
                                    type="text"
                                    class="block w-full !rounded-none !rounded-l-xl sm:text-sm"
                                    ::placeholder="applies === 'collections' ? '{{ __('Buscar grupo') }}' : '{{ __('Buscar kit') }}'"
                                />
                            </div>
                            <button
                                x-on:click="applies === 'collections' ? $wire.searchCollections() : $wire.searchProducts()"
                                type="button"
                                class="relative -ml-px btn btn-default !rounded-none !rounded-r-xl"
                            >
                                {{ __('Buscar') }}
                            </button>
                        </div>

                        <x-input-error for="selectedCollections" class="mt-2" />
                        <x-input-error for="selectedProducts" class="mt-2" />

                        @if($this->currentCollections->count())
                            <div x-show="applies === 'collections'" x-cloak class="mt-4">
                                <ul class="-mx-4 divide-y divide-slate-100 sm:-mx-6 dark:divide-white/5">
                                    @foreach($this->currentCollections as $currentCollection)
                                        <li class="flex items-center justify-between p-4 sm:px-6">
                                            <div class="flex items-center min-w-0">
                                                <img
                                                    class="h-10 w-10 rounded-lg object-center object-cover ring-1 ring-slate-100 dark:ring-white/10 flex-shrink-0"
                                                    src="{{ $currentCollection->getFirstMediaUrl('cover', 'thumb') }}"
                                                    alt="{{ $currentCollection->title }}"
                                                >
                                                <p class="ml-3.5 truncate font-medium text-sm text-primary dark:text-slate-200">
                                                    {{ $currentCollection->title }}
                                                </p>
                                            </div>
                                            <button wire:click.prevent="removeCollections({{ $currentCollection->id }})" type="button" class="ml-3 text-slate-400 hover:text-red-500 flex-shrink-0">
                                                <x-heroicon-o-x-mark class="h-5 w-5" />
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($this->currentProducts->count())
                            <div x-show="applies === 'products'" x-cloak class="mt-4">
                                <ul class="-mx-4 divide-y divide-slate-100 sm:-mx-6 dark:divide-white/5">
                                    @foreach($this->currentProducts as $product)
                                        <li class="flex items-center justify-between p-4 sm:px-6">
                                            <div class="flex items-center min-w-0">
                                                <img
                                                    class="h-10 w-10 rounded-lg object-center object-cover ring-1 ring-slate-100 dark:ring-white/10 flex-shrink-0"
                                                    src="{{ $product->getFirstMediaUrl('gallery', 'thumb') }}"
                                                    alt="{{ $product->name }}"
                                                >
                                                <p class="ml-3.5 truncate font-medium text-sm text-primary dark:text-slate-200">
                                                    {{ $product->name }}
                                                </p>
                                            </div>
                                            <button wire:click.prevent="removeProducts({{ $product->id }})" type="button" class="ml-3 text-slate-400 hover:text-red-500 flex-shrink-0">
                                                <x-heroicon-o-x-mark class="h-5 w-5" />
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </x-slot:content>
            </x-card>

            <x-card>
                <x-slot:header>
                    <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                        {{ __('Datas de validade') }}
                    </h2>
                </x-slot:header>
                <x-slot:content>
                    <div
                        x-data="{
                            startDate: @entangle('startDate').defer,
                            startTime: @entangle('startTime').defer,
                            endDate: @entangle('endDate').defer,
                            endTime: @entangle('endTime').defer,
                            hasEnd: @entangle('hasEnd').defer,
                        }"
                        x-init="
                            flatpickr('.start-date-input', { dateFormat: 'Z', defaultDate: startDate, disableMobile: true, altInput: true, onChange: (d, s) => startDate = s });
                            flatpickr('.start-time-input', { enableTime: true, noCalendar: true, dateFormat: 'Z', defaultDate: startTime, disableMobile: true, time_24hr: true, altInput: true, onChange: (d, s) => startTime = s });
                            flatpickr('.end-date-input', { dateFormat: 'Z', defaultDate: endDate, disableMobile: true, altInput: true, onChange: (d, s) => endDate = s });
                            flatpickr('.end-time-input', { enableTime: true, noCalendar: true, dateFormat: 'Z', defaultDate: endTime, disableMobile: true, time_24hr: true, altInput: true, onChange: (d, s) => endTime = s });
                        "
                        class="grid sm:grid-cols-2 gap-4"
                    >
                        <div wire:ignore>
                            <x-input-label for="starts_at_date" :value="__('Data de início')" />
                            <x-input wire:ignore id="starts_at_date" type="text" class="start-date-input mt-1 block w-full rounded-xl sm:text-sm" />
                            <x-input-error for="startDate" class="mt-2" />
                        </div>

                        <div wire:ignore>
                            <x-input-label for="starts_at_time" :value="__('Hora de início')" />
                            <x-input id="starts_at_time" type="text" class="start-time-input mt-1 block w-full rounded-xl sm:text-sm" />
                            <x-input-error for="startTime" class="mt-2" />
                        </div>

                        <div class="col-span-2 flex items-center">
                            <x-input x-model="hasEnd" type="checkbox" id="set-ends-at" class="mr-2 !rounded !shadow-none text-accent-500 focus:ring-accent-500" />
                            <x-input-label for="set-ends-at" :value="__('Definir data de término')" />
                        </div>

                        <div x-show="hasEnd" x-cloak wire:ignore>
                            <x-input-label for="ends_at_date" :value="__('Data de término')" />
                            <x-input id="ends_at_date" type="text" class="end-date-input mt-1 block w-full rounded-xl sm:text-sm" />
                            <x-input-error for="endDate" class="mt-2" />
                        </div>

                        <div x-show="hasEnd" x-cloak wire:ignore>
                            <x-input-label for="ends_at_time" :value="__('Hora de término')" />
                            <x-input id="ends_at_time" type="text" class="end-time-input mt-1 block w-full rounded-xl sm:text-sm" />
                            <x-input-error for="endTime" class="mt-2" />
                        </div>
                    </div>
                </x-slot:content>
            </x-card>

            <div class="flex justify-end">
                <button wire:click="save" type="submit" class="btn btn-primary">
                    {{ __('Salvar desconto') }}
                </button>
            </div>
        </div>
    </div>

    <form x-data="{ selectedCollections: @entangle('selectedCollections').defer }" wire:submit.prevent="addCollections">
        <x-modal-dialog wire:model="showCollectionModal">
            <x-slot:title>{{ __('Adicionar grupos') }}</x-slot:title>
            <x-slot:content>
                <div x-init="$watch('show', value => value && $wire.loadCollections())" class="-mx-4 sm:-mx-6">
                    <div class="p-4 sm:px-6">
                        <x-input-label for="search-collections" :value="__('Buscar')" class="sr-only" />
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <x-heroicon-m-magnifying-glass class="h-5 w-5 text-slate-400" />
                            </div>
                            <x-input wire:model.debounce.500ms="filterCollectionTitle" type="search" id="search-collections" class="block pl-10 w-full rounded-xl sm:text-sm" autofocus />
                        </div>
                    </div>
                    <div wire:target="loadCollections, filterCollectionTitle" wire:loading class="p-4 bg-white w-full border-y border-slate-100 sm:px-6 dark:bg-slate-900 dark:border-white/5">
                        <div class="flex space-x-4 animate-pulse">
                            <div class="rounded-full bg-slate-100 dark:bg-white/10 h-10 w-10"></div>
                            <div class="flex-1 space-y-6 py-1">
                                <div class="space-y-3">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="h-2 bg-slate-100 dark:bg-white/10 rounded col-span-2"></div>
                                        <div class="h-2 bg-slate-100 dark:bg-white/10 rounded col-span-1"></div>
                                    </div>
                                    <div class="h-2 bg-slate-100 dark:bg-white/10 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div wire:target="loadCollections, filterCollectionTitle" wire:loading.remove class="border-y border-slate-100 dark:border-white/5">
                        <ul class="overflow-y-auto divide-y divide-slate-100 max-h-[30rem] sm:max-h-[40rem] dark:divide-white/5">
                            @forelse($collections as $collection)
                                <li>
                                    <x-input-label class="p-4 cursor-pointer sm:px-6 hover:bg-slate-50/80 dark:hover:bg-white/[0.03] flex items-center">
                                        <x-input x-model.number="selectedCollections" type="checkbox" value="{{ $collection->id }}" class="mr-4 !rounded !shadow-none text-accent-500 focus:ring-accent-500" />
                                        <img class="h-10 w-10 rounded-lg object-center object-cover mr-3" src="{{ $collection->getFirstMediaUrl('cover', 'thumb') }}" alt="{{ $collection->title }}">
                                        <span class="text-primary dark:text-slate-200">{{ $collection->title }}</span>
                                    </x-input-label>
                                </li>
                            @empty
                                <li><p class="p-4 text-sm text-slate-500 sm:px-6">{{ __('Nenhum grupo encontrado.') }}</p></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button x-bind:disabled="selectedCollections.length === 0" type="submit" class="btn btn-primary w-full sm:ml-3 sm:w-auto">{{ __('Adicionar') }}</button>
                <button x-on:click.prevent="show = false" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">{{ __('Cancelar') }}</button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>

    <form x-data="{ selectedProducts: @entangle('selectedProducts').defer }" wire:submit.prevent="addProducts">
        <x-modal-dialog wire:model="showProductModal" max-width="2xl">
            <x-slot:title>{{ __('Adicionar kits') }}</x-slot:title>
            <x-slot:content>
                <div x-init="$watch('show', value => value && $wire.loadProducts())" class="-mx-4 sm:-mx-6">
                    <div class="p-4 border-y border-slate-100 sm:px-6 dark:border-white/5">
                        <x-input-label for="search-products" :value="__('Buscar')" class="sr-only" />
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <x-heroicon-m-magnifying-glass class="h-5 w-5 text-slate-400" />
                            </div>
                            <x-input wire:model.debounce.500ms="filterProductName" type="search" id="search-products" class="block pl-10 w-full rounded-xl sm:text-sm" autofocus />
                        </div>
                    </div>
                    <div wire:target="loadProducts, filterProductName" wire:loading class="p-4 bg-white w-full border-y border-slate-100 sm:px-6 dark:bg-slate-900 dark:border-white/5">
                        <div class="flex space-x-4 animate-pulse">
                            <div class="rounded-full bg-slate-100 dark:bg-white/10 h-10 w-10"></div>
                            <div class="flex-1 space-y-6 py-1">
                                <div class="space-y-3">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="h-2 bg-slate-100 dark:bg-white/10 rounded col-span-2"></div>
                                        <div class="h-2 bg-slate-100 dark:bg-white/10 rounded col-span-1"></div>
                                    </div>
                                    <div class="h-2 bg-slate-100 dark:bg-white/10 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div wire:target="loadProducts, filterProductName" wire:loading.remove class="border-b border-slate-100 dark:border-white/5">
                        <ul class="overflow-y-auto divide-y divide-slate-100 max-h-[30rem] sm:max-h-[40rem] dark:divide-white/5">
                            @forelse($products as $product)
                                <li>
                                    <x-input-label class="p-4 cursor-pointer sm:px-6 hover:bg-slate-50/80 dark:hover:bg-white/[0.03] flex items-center">
                                        <x-input x-model.number="selectedProducts" type="checkbox" value="{{ $product->id }}" class="mr-4 !rounded !shadow-none text-accent-500 focus:ring-accent-500" />
                                        <img class="h-10 w-10 rounded-lg object-center object-cover mr-3" src="{{ $product->getFirstMediaUrl('gallery', 'thumb') }}" alt="{{ $product->name }}">
                                        <span class="text-primary dark:text-slate-200">{{ $product->name }}</span>
                                    </x-input-label>
                                </li>
                            @empty
                                <li><p class="p-4 text-sm text-slate-500 sm:px-6">{{ __('Nenhum kit encontrado.') }}</p></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button x-bind:disabled="selectedProducts.length === 0" type="submit" class="btn btn-primary w-full sm:ml-3 sm:w-auto">{{ __('Adicionar') }}</button>
                <button x-on:click.prevent="show = false" type="button" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">{{ __('Cancelar') }}</button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>
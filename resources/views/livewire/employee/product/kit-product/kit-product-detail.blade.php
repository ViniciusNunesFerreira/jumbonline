<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Montar Kit de Produtos') }}
    </x-slot:title>    

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex flex-1 items-center space-x-2">
            <a
                href="{{ route('employee.kits.list') }}"
                class="btn btn-default btn-xs"
            >
                <x-heroicon-m-arrow-left class="w-5 h-5" />
            </a>
            <h1 class="text-2xl font-medium text-slate-900 truncate dark:text-slate-100">
                {{ $kit->title }}
            </h1>

            <x-badge :type="$kit->is_active ? 'success' : 'default'">
                {{ $kit->status->label() }}
            </x-badge>
            
        </div>
        <div class="mt-4 flex sm:mt-0 sm:ml-4">
            <a
                href="#"
                target="_blank"
                class="btn btn-outline-primary w-full"
            >
                {{ __('Visualizar') }}
            </a>
        </div>
    </div>

    <div class="p-4 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-6">
            

                <div class="col-span-3 xl:col-span-2 space-y-6">
                    <livewire:employee.product.kit-product.components.kit-product-information :kit="$kit" />
                    <livewire:employee.product.kit-product.components.kit-produts-items :kit="$kit" />
                    <livewire:employee.product.kit-product.components.kit-product-gallery :product="$kit" />
                </div>

                <div class="col-span-3 xl:col-span-1 space-y-6">
                    <livewire:employee.product.kit-product.components.kit-product-status :kit="$kit" />
                </div>
                
            
        </div>
    </div>

</div>

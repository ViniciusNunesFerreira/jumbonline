<div>
    <form
        wire:submit.prevent="save"
    >
        <x-card>
            <x-slot:header>
                <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                    <div class="ml-4 mt-2">
                        <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Status do Produto') }}
                        </h3>
                    </div>
                    <div
                        
                        class="ml-4 mt-2 flex-shrink-0"
                    >
                        <button
                            type="submit"
                            class="btn btn-link"
                        >
                            {{ __('Salvar') }}
                        </button>
                    </div>
                </div>
            </x-slot:header>
            <x-slot:content class="-mt-5">
                <x-select
                    wire:model="kit.status"
                    class="sm:text-sm"
                >
                    @foreach(\App\Enums\KitProductStatus::cases() as $status)
                        <option value="{{ $status->name }}">{{ $status->label() }}</option>
                    @endforeach
                </x-select>
                
            </x-slot:content>
        </x-card>
    </form>
</div>

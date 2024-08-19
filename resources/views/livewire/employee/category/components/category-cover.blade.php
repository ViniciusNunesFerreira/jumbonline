<div>
    <x-card class="relative overflow-hidden">
        <x-slot:header>
            <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
                <div class="ml-4 mt-2">
                    <h3 class="text-base font-medium text-slate-900 dark:text-slate-200">
                        Imagem
                    </h3>
                </div>
                @if($category->hasMedia('cover'))
                    <div class="ml-4 mt-2 flex-shrink-0">
                        <button
                            x-on:click.prevent="if(confirm('{{ __('Tem certeza de que deseja apagar esta imagem?') }}')) $wire.delete();"
                            type="button"
                            class="btn p-0 text-red-500 hover:text-red-600 dark:hover:text-red-400"
                        >
                            {{ __('Deletar') }}
                        </button>
                    </div>
                @endif
            </div>
        </x-slot:header>
        <x-slot:content class="-mt-5">
            <div class="relative aspect-[16/9]">
                @if($category->hasMedia('cover'))
                    <img
                        src="{{ $category->getFirstMediaUrl('cover') }}"
                        alt="{{ $category->title }}"
                        class="absolute inset-0 h-full mx-auto rounded-lg object-cover"
                    >
                @else
                    <x-upload-widget wire:model="image" />
                @endif
            </div>
        </x-slot:content>
    </x-card>
</div>

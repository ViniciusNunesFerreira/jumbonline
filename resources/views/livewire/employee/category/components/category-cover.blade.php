<div>
    <x-card class="relative overflow-hidden">
        <x-slot:header>
            <div class="flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap">
                <h3 class="text-base font-semibold text-primary dark:text-slate-200">
                    {{ __('Imagem') }}
                </h3>
                @if($category->hasMedia('cover'))
                    <button
                        x-on:click.prevent="if(confirm('{{ __('Tem certeza de que deseja excluir esta imagem?') }}')) $wire.delete();"
                        type="button"
                        class="btn p-0 text-red-500 hover:text-red-600 dark:hover:text-red-400"
                    >
                        {{ __('Excluir') }}
                    </button>
                @endif
            </div>
        </x-slot:header>
        <x-slot:content>
            <div class="relative aspect-[16/9]">
                @if($category->hasMedia('cover'))
                    <img
                        src="{{ $category->getFirstMediaUrl('cover') }}"
                        alt="{{ $category->title }}"
                        class="absolute inset-0 h-full w-full mx-auto rounded-xl object-cover"
                    >
                @else
                    <x-upload-widget wire:model="image" />
                @endif
            </div>
        </x-slot:content>
    </x-card>
</div>
<div>
    
    <livewire:guest.components.guest-carousel />

    <div class="bg-white relative">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">

            <div class="max-w-7xl">

                <h1 class="my-2 text-2xl font-bold tracking-tight sm:text-5xl text-center text-primary">
                    {{ $prison->name }}
                </h1>

                <a href="/" class=" flex space-x-2">   Trocar unidade &nbsp; <x-heroicon-s-arrow-path class="h-5 w-5 flex-shrink-0 text-accent" /></a>

                <ul class="mt-4 py-4 text-sm md:text-lg text-slate-500 leading-loose font-bold border-b border-indigo-500 space-y-2">

                    <li class=" flex items-center leading-5">
                        <x-heroicon-s-map-pin class="h-5 w-5 flex-shrink-0 text-accent m-2" />           
                        {{ $prison->logradouro }} , {{ $prison->numero }}, {{$prison->bairro}} - {{ $prison->cidade }} / {{ $prison->uf }} - CEP: {{ $prison->cep }}
                    </li>
                    <li class=" flex items-center leading-5">
                        <x-heroicon-s-phone class="h-5 w-5 flex-shrink-0 text-accent m-2" /> 
                        Tel:{{ phone( optional($prison)->phone, 'BR' ); }}
                    </li>
                    <li class="flex items-center leading-5">
                        <x-heroicon-s-truck class="h-5 w-5 flex-shrink-0 text-accent m-2" /> 
                        Enviamos o Jumbo nesta Unidade, com rapidez, segurança e economia para você.
                    </li>
                </ul>

                <p class=" p-5 text-center font-bold text-accent font-urbanist text-xl tracking-tight">
                    Monte o Jumbo escolhendo os produtos que deseja enviar. 
                </p>

            </div>

        
            <section class="grid grid-cols-1 gap-y-3">


                @forelse($collections as $collection)

                <details open class="group py-1 text-lg">

                    <summary class="flex cursor-pointer flex-row items-center justify-between py-2 font-bold text-gray-800 marker:[font-size:0px] text-xl">

                        {{ $collection->title }}
                    
                        <svg class="h-6 w-6 rotate-0 transform text-gray-400 group-open:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>

                    </summary>

                    
                    <section class="grid grid-cols-1 gap-y-3 divide-y">

                        @forelse( $collection->categoriesPublished as $cat)
                            <livewire:guest.components.category-products :category="$cat" :wire:key="time().$cat->id" :selectedOptionValues="$selectedOptions" />
                        @empty
                            sem categorias
                        @endforelse

                    </section>

                </details>

                @empty
                    'sem grupos cadastrados'
                @endforelse

            </section>

        </div>

        <div class="fixed bottom-0 right-0">
            <x:card >           
                <x-slot:content>{{ $weight_max - $weight }}Kg restantes</x-slot:content>
            </x:card>
        </div>

    </div>

    
</div>

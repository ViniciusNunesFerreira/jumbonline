<div>
    <details close class="group group-2 py-2 text-lg" @if($showProducts) open @endif >

        <summary class="relative flex cursor-pointer flex-row items-center space-x-2 p-1 font-semibold text-gray-800 marker:[font-size:0px]">
            
            <div   class="flex flex-row items-center grow"  wire:click.prevent="selectCategory" >
                @if($category->hasMedia('cover'))
                    <img
                        class="h-14 w-14 rounded-md object-center m-2"
                        src="{{ $category->getFirstMediaUrl('cover', 'thumb') }}"
                        alt="{{ $category->title }}"
                    >
                @else
                    <x-heroicon-o-camera class="absolute inset-0 h-full w-6 mx-auto text-slate-400 dark:text-slate-500" />
                @endif
                
                <span class="ml-3 font-semibold line-clamp-2 text-lg p-2">
                    {{ $category->title }}
                </span>  
            </div>

            <div class="flex-col flex  {{$quantity > 0 ? '' : 'hidden' }} py-2">

                <div class="relative flex items-center max-w-[8rem]">
                    <button type="button" @if( $quantity <= 0 ) disabled @endif id="decrement-button" wire:click.prevent="decrementQuantity" class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-s-lg p-2 h-8 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                        <svg class="w-2 h-2 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                        </svg>
                    </button>
                    
                    <input type="number"  min="1" max="{{$category->quantity}}" wire:model.live="quantity" class="input-number bg-gray-50 border-x-0 border-gray-300 h-8 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                    
                    <button type="button" wire:click.prevent="incrementQuantity" id="increment-button"  class="bg-accent   hover:bg-primary border border-gray-300 rounded-e-lg p-2 h-8 focus:ring-accent-100 focus:ring-2 focus:outline-none">
                        <svg class="w-2 h-2 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                        </svg>
                    </button>
                </div>
                <p id="helper-text-explanation" class="text-xs text-gray-500 ">máx. {{$category->quantity}} unid</p>

            </div>
            
            
        </summary>

          
        <div x-data="{ current: 0 }" class="relative overflow-auto">
                
            <ul x-ref="slider" class="scroll-smooth scroll-no-bar snap-mandatory snap-x overflow-x-auto overflow-y-hidden space-x-4 flex flex-nowrap">
                
            
                @forelse( $category->products as $prod)

                    

                        <li wire:key="{{ $prod->id }}" class="  @if(isset($selectedOptionValues) && sizeof($selectedOptionValues) > 0 && in_array($prod->id, $selectedOptionValues)  ) border-primary @else border-slate-200 @endif snap-center z-0 shrink-0 w-44 group relative flex flex-col overflow-hidden rounded-lg border  hover:border-sky-300 hover:shadow-lg hover:shadow-sky-300/50 transition duration-150">
                            <div class="aspect-w-3 aspect-h-4 group-hover:opacity-75 sm:aspect-none">
                                @if($prod->hasMedia('gallery'))
                                    {{ $prod->getFirstMedia('gallery')('responsive')->attributes(['alt' => $prod->name, 'class' => 'h-full w-full object-cover object-center sm:h-full sm:w-full p-2']) }}
                                @else
                                    <img
                                        src="{{ $prod->getFirstMediaUrl('gallery') }}"
                                        alt="{{ $prod->name }}"
                                        class="h-full w-full object-cover object-center sm:h-full sm:w-full"
                                    >
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col items-center text-center space-y-2 p-4">
                                <h3 class="text-lg font-bold text-slate-900 line-clamp-2">
                                    
                                        <span
                                            aria-hidden="true"
                                            class="absolute inset-0"
                                        ></span>
                                        {{ $prod->name }}
                                    
                                </h3>
                                
                                <div class="pt-1 flex flex-1 flex-col justify-end">
                                    <p class="text-base font-semibold text-slate-900">
                                        <x-money
                                            :amount="$prod->price"
                                            :currency="config('app.currency')"
                                        />
                                    </p>
                                </div>

                                <div class="flex w-full">
                                    
                                
                                    <button 
                                        wire:click.prevent="addToCart({{$prod->id}})" 
                                        class="z-10 btn btn-primary btn-xl w-full" 
                                        @disabled( isset($selectedOptionValues) && sizeof($selectedOptionValues) > 0 && in_array($prod->id, $selectedOptionValues) )>
                                        
                                        @if(isset($selectedOptionValues) && sizeof($selectedOptionValues) > 0 && in_array($prod->id, $selectedOptionValues)  )
                                             <x-heroicon-m-check class="h-5 w-5 text-white font-semibold" /> 
                                        @else
                                             {{ __('escolher') }}
                                        @endif

                                    </button>
                                
                                </div>

                            </div>
                        </li>
                    

                @empty
                    sem produtos
                @endforelse


            </ul>
       

        </div>                                        

    
    </details>
</div>

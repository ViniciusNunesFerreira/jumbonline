<div>
@if($this->hero_carousel)
        <div
            x-data="{ current: 0 }"
            class="relative overflow-auto"
        >
            <ul
                x-ref="slider"
                class="flex flex-1 scroll-smooth scroll-no-bar snap-mandatory snap-x overflow-x-auto overflow-y-hidden"
            >
                @foreach($this->hero_carousel->slides as $slide)
                    <li
                        x-intersect.threshold.90="$nextTick(() => current = {{ $loop->index }})"
                        class="snap-center shrink-0 w-full"
                    >
                        <div class="relative bg-slate-900">
                            <div
                                aria-hidden="true"
                                class="w-full h-auto sm:max-h-96"
                            >
                                @if($slide->hasMedia('image'))
                                    {{ $slide->getFirstMedia('image')('responsive')->attributes(['class' => 'h-full w-full object-cover object-center']) }}
                                @else
                                    <img
                                        src="{{ asset('img/placeholder-wide.png') }}"
                                        alt="{{ $slide->title }}"
                                        class="h-full w-full object-contain object-center"
                                    >
                                @endif
                            </div>
                            
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="absolute bottom-10 inset-x-0 flex justify-center space-x-3">
                @foreach($this->hero_carousel->slides as $slide)
                    <button x-on:click="$refs.slider.scrollTo({ left: $refs.slider.offsetWidth * {{ $loop->index }}, behavior: 'smooth' })">
                        <span class="sr-only">
                            {{ __('Slide :count', ['count' => $loop->index + 1]) }}
                        </span>
                        <span
                            class="block h-2 w-2 rounded-full ring-2 ring-white ring-opacity-50 hover:ring-opacity-100"
                            :class="{ 'bg-white ring-opacity-100': current === {{ $loop->index }} }"
                        ></span>
                    </button>
                @endforeach
            </div>
        </div>
    @else
        <div class="relative bg-slate-900">
            <div
                aria-hidden="true"
                class="absolute inset-0"
            >
                <img
                    src="{{ asset('img/placeholder-wide.png') }}"
                    alt="{{ __('Hero carousel placeholder') }}"
                    class="h-full w-full object-cover object-center"
                >
            </div>
            <div class="relative px-6 py-32 bg-slate-900 bg-opacity-50 sm:px-12 sm:py-40 lg:px-16">
                <div class="relative mx-auto flex max-w-3xl flex-col items-center text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        {{ __('Atendemos todas as regras SAP') }}
                    </h2>
                    <p class="mt-3 text-xl text-white line-clamp-2">
                        {{ __('Um modelo de banner slide que você pode aplicar aqui') }}
                    </p>
                    <a
                        href="javascript:void(0);"
                        class="mt-8 block w-full rounded-md border border-transparent bg-white px-8 py-3 text-base font-medium text-slate-900 cursor-not-allowed hover:bg-slate-100 sm:w-auto"
                    >
                        {{ __('Aproveite') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

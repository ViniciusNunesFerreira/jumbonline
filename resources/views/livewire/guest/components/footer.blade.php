<footer class="bg-primary" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">{{ __('Footer') }}</h2>

    <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8 lg:py-14">
        <div class="grid grid-cols-2 gap-x-8 gap-y-10 sm:grid-cols-4 lg:grid-cols-4">
            <div class="col-span-2 sm:col-span-4 lg:col-span-2">
                <x-site-logo :brand-settings="$brandSettings" size="md" variant="light" />
                <p class="mt-3 max-w-xs text-sm leading-relaxed text-slate-300">
                    {{ $brandSettings->slogan }}
                </p>

                <div class="mt-5 space-y-2.5 text-sm text-slate-300">
                    <div class="flex items-start gap-2">
                        <x-heroicon-s-map-pin class="mt-0.5 h-4 w-4 flex-shrink-0 text-accent" />
                        <span>Rua Alice Garcia Vega, 82 — Itaberaba, São Paulo/SP — CEP 02737-050</span>
                    </div>
                    <a href="tel:+5511957923791" class="flex items-center gap-2 transition-colors hover:text-white">
                        <x-heroicon-s-phone class="h-4 w-4 flex-shrink-0 text-accent" />
                        (11) 95792-3791
                    </a>
                    <a href="mailto:contato@jumbonline.com.br" class="flex items-center gap-2 transition-colors hover:text-white">
                        <x-heroicon-s-envelope class="h-4 w-4 flex-shrink-0 text-accent" />
                        contato@jumbonline.com.br
                    </a>
                </div>

                <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-accent/10 px-3 py-1.5 text-xs font-semibold text-accent">
                    <x-heroicon-s-map-pin class="h-3.5 w-3.5" />
                    Única com atendimento presencial em todo o Estado de SP
                </div>
            </div>


            <div>
                <h3 class="font-urbanist text-xs font-semibold uppercase tracking-wide text-accent">
                    Unidades Atendidas
                </h3>
                <ul role="list" class="mt-3 space-y-2.5">
                    @foreach($this->featuredPrisonUnits as $unit)
                        <li>
                            <a href="{{ route('guest.products.list', $unit->slug) }}" class="text-sm text-slate-300 transition-colors hover:text-white">
                                {{ $unit->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

        <div class="mt-10 flex flex-col items-center gap-4 border-t border-white/10 pt-6 sm:flex-row sm:justify-between">
            <p class="text-xs text-slate-400">
                {!! $layoutSettings->footer_bottom_bar_message !!}
            </p>
            <div class="flex items-center gap-4">
                @foreach($brandSettings->social_links as $socialLink)
                    @if($socialLink['url'])
                        <a href="{{ $socialLink['url'] }}" class="text-slate-400 transition-colors hover:text-accent">
                            <span class="sr-only">{{ $socialLink['name'] }}</span>
                            <x-icon name="simpleicon-{{ Str::lower($socialLink['name']) }}" class="h-5 w-5" />
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</footer>
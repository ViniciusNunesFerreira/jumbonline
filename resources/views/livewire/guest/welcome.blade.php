<main>

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-secondary/40 via-white to-white">
        <img src="{{ asset('img/estrelas.png') }}" alt="" class="pointer-events-none absolute right-10 top-10 hidden w-16 opacity-80 lg:block">

        <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-10 px-6 pb-16 pt-16 lg:grid-cols-2 lg:pt-24">

            <div class="space-y-8">
                <div class="inline-flex items-center gap-2 rounded-full bg-accent/10 px-4 py-2 text-sm font-semibold uppercase tracking-widest text-accent">
                    <x-heroicon-s-gift class="h-4 w-4" /> Serviço de Jumbo Online
                </div>

                <h1 class="font-urbanist text-4xl font-extrabold leading-tight tracking-tight text-primary sm:text-5xl lg:text-6xl">
                    Enviar o jumbo pra quem você ama, <span class="text-accent">sem complicação.</span>
                </h1>

                <p class="max-w-lg font-sans text-lg text-slate-600">
                    Escolha a unidade, monte a lista dentro das normas e a gente cuida do resto — do pagamento até a entrega na porta do detento.
                </p>

                <form wire:submit.prevent="getPrisonProducts" class="max-w-lg space-y-4">
                    <x-prison-unit-select
                        :categories="$prison_categories"
                        model="prison"
                        :selected="$prison"
                        placeholder="Digite o nome da unidade prisional..."
                    />

                    <button type="submit" class="btn inline-flex w-full items-center justify-center gap-2 rounded-full bg-accent px-8 py-4 text-lg font-semibold text-white shadow-lg shadow-accent/30 transition-transform hover:scale-[1.02] hover:bg-accent-700 sm:w-auto">
                        Montar meu Jumbo <x-heroicon-s-arrow-right class="h-5 w-5" />
                    </button>
                </form>

                <div class="flex items-center gap-3 pt-2 text-sm text-slate-500">
                    <div class="flex">
                        <x-heroicon-s-star class="h-4 w-4 text-warning" />
                        <x-heroicon-s-star class="h-4 w-4 text-warning" />
                        <x-heroicon-s-star class="h-4 w-4 text-warning" />
                        <x-heroicon-s-star class="h-4 w-4 text-warning" />
                        <x-heroicon-s-star class="h-4 w-4 text-warning" />
                    </div>
                    <span><strong class="text-primary">4.4/5</strong> em 4.841 avaliações · aprovado por 4 mil+ clientes</span>
                </div>
            </div>

            <div class="relative flex justify-center lg:justify-end">
                <div class="bg-mask absolute inset-0 -z-10 bg-contain bg-center bg-no-repeat opacity-60"></div>
                <img src="{{ asset('img/mascote-logo-mark.png') }}" alt="Jumbonline CDP Penitenciarias" class="max-h-[28rem] w-auto object-contain drop-shadow-xl">
            </div>
        </div>
    </section>

    {{-- COMO FUNCIONA --}}
    <section class="mx-auto max-w-7xl px-6 py-20 lg:py-28" id="como-funciona-o-jumbo">
        <div class="mb-14 max-w-2xl">
            <div class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-widest text-purple">
                <x-heroicon-s-gift class="h-4 w-4 text-accent" /> Nossos Serviços
            </div>
            <h2 class="mt-3 font-urbanist text-3xl font-extrabold tracking-tight text-primary sm:text-4xl">
                Como funciona o Jumbo Online?
            </h2>
            <p class="mt-3 text-slate-600">
                Guiamos o processo de escolha e padronização do jumbo de acordo com as normas estabelecidas pela unidade prisional.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-5">
            @foreach([
                ['Escolha a unidade prisional', 'Basta selecionar a unidade prisional na lista disponível no início do nosso site.'],
                ['Selecione os produtos da lista', 'Nossa lista segue rigorosamente os padrões das unidades prisionais.'],
                ['Informe os dados', 'Informe os dados do detento e do visitante com carteirinha.'],
                ['Forma de pagamento', 'Selecione a forma de pagamento disponível: cartão, boleto ou PIX.'],
                ['Pronto! Processo finalizado', 'Agora é só acompanhar o envio do jumbo.'],
            ] as $index => [$title, $description])
                <div class="relative">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-accent font-urbanist text-lg font-bold text-white">
                        {{ $index + 1 }}
                    </div>
                    <h3 class="mt-4 font-urbanist text-lg font-semibold text-primary">{{ $title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $description }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- QUEM SOMOS --}}
    <section class="bg-complement-500 py-20 lg:py-28" id="o-que-significa-jumbo">
        <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-6 lg:grid-cols-2">
            <div class="relative">
                <img src="{{ asset('img/estrelas.png') }}" alt="" class="pointer-events-none absolute -top-6 -left-6 w-14">
                <div class="overflow-hidden rounded-3xl shadow-xl">
                    <img src="{{ asset('img/jumbo-online-cdp-penitenciarias.png') }}" alt="Jumbo montado pela Jumbonline" class="h-80 w-full object-cover lg:h-[26rem]">
                </div>
            </div>

            <div>
                <div class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-widest text-purple">
                    <x-heroicon-s-gift class="h-4 w-4 text-accent" /> Sobre Nós
                </div>
                <h2 class="mt-3 font-urbanist text-3xl font-extrabold tracking-tight text-primary sm:text-4xl">Quem somos?</h2>

                <div class="mt-5 space-y-4 text-slate-600">
                    <p>Com sede na cidade de São Paulo, nossa empresa surgiu com o intuito de amenizar o drama vivido por familiares e amigos de pessoas que foram presas.</p>
                    <p>Trabalhando já algum tempo, alcançamos total confiança junto aos familiares graças ao comprometimento e dedicação com que produzimos um trabalho de qualidade e seguro — reconhecido e indicado por diversas unidades prisionais e advogados.</p>
                    <p>Enviamos os produtos reembalados ou em embalagens originais, conforme a regra da unidade, protegidos em caixas especiais — direto para o detento em penitenciárias, CDPs, CPPs, CRs, hospitais, ou também na casa do familiar.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- DEPOIMENTOS --}}
    <section class="mx-auto max-w-7xl px-6 py-20 lg:py-28" id="testimonial">
        <div class="mx-auto mb-14 max-w-2xl text-center">
            <div class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-widest text-purple">
                <x-heroicon-s-gift class="h-4 w-4 text-accent" /> Depoimentos
            </div>
            <h2 class="mt-3 font-urbanist text-3xl font-extrabold tracking-tight text-primary sm:text-4xl">O que nossos clientes falam?</h2>
            <p class="mt-3 text-slate-600">
                Trabalhamos sempre comprometidos com a qualidade e segurança demandada pelos nossos clientes — por isso ganhamos reconhecimento e confiança de quem busca nossos serviços.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            @foreach([
                ['Raquel Santos', 'Praia Grande / SP', 'O serviço de jumbo online me ajudou muito, fiquei muito satisfeita. Entrega foi rápida e os produtos são de muita qualidade. Amei e recomendo.'],
                ['Marlene Pedroso', 'Corumbá / MS', 'Gratidão pela agilidade na entrega. Para mim foi a resolução de um grande problema, pois moro longe e não sabia como enviar o jumbo ao meu filho.'],
                ['Marcos V Gomes', 'São Paulo / SP', 'Sem palavras! Vocês estão de parabéns pela qualidade e pela agilidade no atendimento. Saiu muito mais barato e evitei problemas na montagem do jumbo.'],
            ] as [$name, $city, $quote])
                <div class="flex flex-col justify-between rounded-2xl border border-secondary bg-white p-8 shadow-sm">
                    <div>
                        <div class="flex gap-0.5">
                            @for($i = 0; $i < 5; $i++)
                                <x-heroicon-s-star class="h-4 w-4 text-warning" />
                            @endfor
                        </div>
                        <p class="mt-5 text-slate-600">&ldquo;{{ $quote }}&rdquo;</p>
                    </div>
                    <div class="mt-6 border-t border-secondary pt-4">
                        <h4 class="font-urbanist font-semibold text-primary">{{ $name }}</h4>
                        <span class="text-sm text-slate-500">{{ $city }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex flex-col items-center gap-2 text-center">
            <p class="font-urbanist text-lg font-semibold text-primary">Aprovado por 4 mil+ clientes</p>
            <div class="flex items-center gap-2">
                <div class="flex gap-0.5">
                    <x-heroicon-s-star class="h-5 w-5 text-warning" />
                    <x-heroicon-s-star class="h-5 w-5 text-warning" />
                    <x-heroicon-s-star class="h-5 w-5 text-warning" />
                    <x-heroicon-s-star class="h-5 w-5 text-warning" />
                    <x-heroicon-s-star class="h-5 w-5 text-warning/40" />
                </div>
                <span class="font-semibold text-primary">4.4/5</span>
                <span class="text-slate-500">· 4.841 avaliações</span>
            </div>
        </div>
    </section>

    {{-- KITS PRONTOS --}}
    <section @class(['bg-complement-500 px-6 py-20 lg:py-28', 'pb-32' => !empty($this->randomProducts)])>
        <div class="mx-auto max-w-7xl">
            <div class="mx-auto mb-12 max-w-2xl text-center">
                <div class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-widest text-purple">
                    <x-heroicon-s-gift class="h-4 w-4 text-accent" /> Nossos Kits de Jumbo Montados
                </div>
                <h2 class="mt-3 font-urbanist text-3xl font-extrabold tracking-tight text-primary sm:text-4xl">
                    Qual a diferença nos kits de jumbo pronto?
                </h2>
                <p class="mt-3 text-slate-600">
                    Kits montados e padronizados de acordo com as normas da unidade prisional, com produtos de melhor qualidade selecionados para atender todas as necessidades do detento. Você evita gastos desnecessários, economiza tempo e garante uma entrega bem-sucedida dentro do prazo.
                </p>
            </div>

            <livewire:components.product-section :items="$this->randomProducts" />
        </div>
    </section>

</main>
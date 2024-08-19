<main>
    

       
    <div class=" max-w-7xl mx-auto grid xs:grid-rows-3 md:grid-cols-3 gap-2 pt-12 lg:mt-10 block h-full">

        <div class="flex flex-col  justify-center md:col-span-2 p-8  space-y-6 size-full">
            <div class="text-base text-purple antialiased  uppercase font-semibold tracking-widest align-middle inline-block flex">
                <x-heroicon-s-gift class="h-5 w-5 flex-shrink-0 text-accent" /> &nbsp; Serviços de Jumbo Online 
            </div>

            <div class="text-center py-8  text-primary font-extrabold font-urbanist sm:text-2xl md:text-4xl tracking-tight space-y-12">
                <p>Enviar o jumbo para o detento ficou rápido e fácil</p>

                <div class="flex-1 w-full">
                    <form class="space-y-10" wire:submit.prevent="getPrisonProducts"> 
                        <div>
                            <x-input-label
                                for="prison"
                                :value="__('Selecione a Unidade Prisional para enviar o jumbo')"
                            />
                            <x-select
                                wire:model.blur="prison"
                                id="prison"
                                class="block mt-1 w-full text-xl text-gray font-urbanist"
                                
                            >
                                <option disabled value="" selected >Selecione uma Unidade Prisional</option>

                                @forelse($prison_categories as $category)

                                    @if($category->prisonUnits()->count() > 0)
                                        <option disabled class="text-lg font-bold tracking-widest py-2"><strong>----{{$category->name}}----</strong></option>
                                    @endif

                                    @forelse($category->prisonUnits as $unit )
                                        <option value="{{ $unit->slug }}">{{ $unit->name }}</option>
                                    @empty
                                        
                                    @endforelse

                                @empty
                                    <option> Sem Categorias Prisionais Cadastradas </option>
                                @endforelse

                            </x-select>
                            
                            <x-input-error
                                for="prison"
                                class="mt-2"
                            />
                        </div>

                        <div class="flex w-full space-x-8 items-center pt-5">
                            <button class="btn bg-secondary text-primary hover:bg-primary hover:text-accent btn-lg rounded-full" type="submit">
                                Enviar o Jumbo 
                            </button>

                            <a href="#como-funciona-o-jumbo" class="text-sm text-primary hover:text-accent tracking-widest font-medium font-urbanist">Como funciona?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div aria-hidden="true" class="items-end bg-mask bg-no-repeat bg-cover  flex content-end">
                <img src="{{ asset('img/maskote.png') }}" class="object-contain object-end sm:max-h-full xs:h-2/6 focus:transition-all duration-150 focus:ease-in">
        </div>

    </div>

    <div class="bg-complement-500 w-full min-h-60 py-8 " id="o-que-significa-jumbo">
        <div class="max-w-7xl mx-auto grid xs:grid-rows-2 md:grid-cols-2 gap-2 px-8">
            <iframe width="100%" height="260" src="https://www.youtube.com/embed/diDgD89FXEo" title="Enviar um jumbo é rápido, fácil e seguro - Jumbo Online" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            
            <div class="px-8">

                <h1 class="text-primary text-3xl text-center p-5 font-urbanist font-semibold">O que é um Jumbo?</h1>
                <p class="tracking-wide font-light text-gray-700 leading-relaxed">
                    O <span class="font-bold"> “jumbo” </span> são os itens que os presos podem receber de seus familiares, como por exemplo alimentos, produtos de higiene pessoal, produtos de limpeza, roupas e cigarros. É chamado de “jumbo” por conta do tamanho das sacolas em que é carregado, que geralmente são muito grandes.
                </p>
                
            </div>
        </div>
    </div>

    <section class="bg-white px-4 py-24  sm:px-6 sm:pt-32 xl:mx-auto xl:max-w-7xl lg:px-8 " id="como-funciona-o-jumbo">

        <div class="sm:flex sm:justify-between flex-col space-y-5">

            <div class="text-base text-purple antialiased  uppercase font-semibold tracking-widest align-middle inline-block flex">
                <x-heroicon-s-gift class="h-5 w-5 flex-shrink-0 text-accent" /> &nbsp; Nossos Serviços 
            </div>

            <div class="py-4">
               <p class="sm:text-2xl md:text-4xl text-primary font-extrabold font-urbanist  tracking-tight"> Como funciona o Jumbo Online? </p>
               <div class=" sm:w-2/3 md:w-1/2 ">
                    <p class="text-wrap py-2 tracking-tight text-gray-700 text-sm">
                        Guiamos no processo de escolha e padronização do jumbo de acordo com as normas estabelecidas pela unidade prisional.
                    </p>
               </div>
            </div>

            <div class="grid xs:grid-rows-5 md:grid-cols-5 gap-2 md:gap-6 justify-items-center content-center text-center">

                <div class="flex flex-col items-center font-urbanist">
                    <div class="bg-secondary text-primary w-10 h-10 flex justify-center items-center rounded-full font-bold">1</div>
                    <div class="text-primary sm:text-lg md:text-xl text-center sm:h-4 md:h-14 font-semibold">
                        Escolha a unidade prisional
                    </div>
                    <div class="text-gray py-4 text-base font-normal px-8 md:px-0 text-center md:text-start ">
                        Basta selecionar a unidade prisional na lista disponivel no inicio do nosso site    
                    </div>
                </div>
                <div class="flex flex-col items-center font-urbanist">
                    <div class="bg-secondary text-primary w-10 h-10 flex justify-center items-center rounded-full font-bold">2</div>
                    <div class="text-primary sm:text-lg md:text-xl text-center sm:h-4 md:h-14 font-semibold">
                        Selecione os Produtos da lista
                    </div>
                    <div class="text-gray py-4 text-base font-normal px-8 md:px-0 text-center md:text-start">
                        Nossa lista segue rigorosamente os padrões das unidades prisionais
                    </div>
                </div>
                <div class="flex flex-col items-center font-urbanist">
                    <div class="bg-secondary text-primary w-10 h-10 flex justify-center items-center rounded-full font-bold">3</div>
                    <div class="text-primary sm:text-lg md:text-xl text-center sm:h-4 md:h-14 font-semibold">
                        Informe os Dados
                    </div>
                    <div class="text-gray py-4 text-base font-normal px-8 md:px-0 text-center md:text-start">
                        Informe os dados do Detento e Visitante com carteirinha
                    </div>
                </div>
                <div class="flex flex-col items-center font-urbanist">
                    <div class="bg-secondary text-primary w-10 h-10 flex justify-center items-center rounded-full font-bold">4</div>
                    <div class="text-primary sm:text-lg md:text-xl text-center sm:h-4 md:h-14 font-semibold">
                        Forma de Pagamento
                    </div>
                    <div class="text-gray py-4 text-base font-normal px-8 md:px-0 text-center md:text-start">
                        Selecione a forma de pagamento disponível (cartão, boleto, depósito ou pix)
                    </div>
                </div>
                <div class="flex flex-col items-center font-urbanist">
                    <div class="bg-secondary text-primary w-10 h-10 flex justify-center items-center rounded-full font-bold">5</div>
                    <div class="text-primary sm:text-lg md:text-xl text-center sm:h-4 md:h-14 font-semibold">
                        Pronto! Processo Finalizado
                    </div>
                    <div class="text-gray py-4 text-base font-normal px-8 md:px-0 text-center md:text-start">
                        Agora é só acompanhar o envio do Jumbo
                    </div>

                </div>

            </div>

        </div>

    </section>

    <div class="bg-complement-500 w-full min-h-60 py-12 md:py-24 ">

        <div class="max-w-7xl mx-auto grid xs:grid-rows-2 md:grid-cols-2 gap-6 px-8 relative content-center">
                
                <img src="{{ asset('img/estrelas.png')}}" alt="" class="absolute -top-4 z-10 ">

                <div class="w-full h-72 md:h-96 bg-neutral-400 rounded-lg  text-center origin-center rotate-6"> 
                    <img src="{{ asset('img/empresa-jumbo-online.png')}}" alt="" class="rounded-lg object-fill object-center w-full mx-auto h-full origin-center -rotate-12">     
                </div>

                <div class="px-8">

                    <div class="text-base text-purple antialiased  uppercase font-semibold tracking-widest align-middle inline-block flex">
                        <x-heroicon-s-gift class="h-5 w-5 flex-shrink-0 text-accent" /> &nbsp; Sobre Nós 
                    </div>
                
                    <h1 class="text-primary text-3xl text-center p-5 font-urbanist font-extrabold tracking-tight">Quem Somos?</h1>

                    <p class="tracking-wide font-light text-gray-700 leading-6 py-2">
                        Com sede na cidade de São Paulo, nossa empresa surgiu com o intuito de amenizar o drama vivido por familiares e amigos de pessoas que foram presas.
                    </p>
                    <p class="tracking-wide font-light text-gray-700 leading-6 py-2">
                        Trabalhando já algum tempo podemos alcaçar uma total confiança junto aos familiares graças ao comprometimento e dedicação o qual produzimos um trabalho de qualidade e seguro, reconhecido e indicado por diversas unidades prisionais e advogados.
                    </p>
                    <p class="tracking-wide font-light text-gray-700 leading-6 py-2">
                        Enviamos os produtos reembalados ou em embalagens originais conforme a regra da unidade prisional, protegidos em caixas especiais, direto para o detento nas penitenciárias, centros de detenção provisória (CDP), centros de progressão penitenciária (CPP), centros de ressocialização (CR), hospitais e ou também na casa do familiar.
                    </p>
                </div>

        </div>



    </div>


    <section class="bg-white px-4 py-24  sm:px-6 sm:pt-32 xl:mx-auto xl:max-w-7xl lg:px-8 " id="testimonial">

        <div class="sm:flex sm:justify-center flex-col space-y-5 ">

            <div class="text-base text-center text-purple antialiased  uppercase font-semibold tracking-widest align-middle inline-block flex justify-center w-full">
                <x-heroicon-s-gift class="h-5 w-5 flex-shrink-0 text-accent" /> &nbsp; Depoimentos 
            </div>

            <div class="text-center ">
               <p class="sm:text-2xl md:text-4xl text-primary font-extrabold font-urbanist  tracking-tight"> O que nossos clientes falam? </p>
               
                <p class="text-wrap py-4 tracking-tight text-gray-700 text-sm w-2/3  mx-auto">
                    Trabalhamos sempre comprometidos com a qualidade e segurança demandada pelos nossos clientes, por este motivo, ganhamos reconhecimento e confiança de todos que buscam nossos serviços.
                </p>
               
            </div>

            <div class="grid xs:grid-rows-3 md:grid-cols-3 gap-6 justify-items-center content-center">

                <div class=" border border-primary  rounded-lg w-full h-72 p-8 flex flex-col justify-between">

                    <div class="max-w-28">
                        <div class="flex " title="5/5" itemtype="http://schema.org/Rating" itemscope="" itemprop="reviewRating">
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                        </div>
                    </div>

                    <p class="py-5">
                        O serviço de jumbo online me ajudou muito, fiquei muito satisfeita. Entrega foi rápida e os produtos são de muita qualidade. Amei e recomendo
                    </p>

                    <hr>

                    <div class="footer">
                        <h4 class="text-primary font-medium">Raquel Santos</h4>
                        <small>Praia Grande / SP</small>
                    </div>

                </div>

                <div class=" border border-primary  rounded-lg w-full h-72 p-8 flex flex-col justify-between">
                    <div class="max-w-28">
                        <div class="flex " title="5/5" itemtype="http://schema.org/Rating" itemscope="" itemprop="reviewRating">
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                        </div>
                    </div>

                    <p class="py-5">
                        Gratidão pela agilidade na entrega. Para mim foi a resolução de um grande problema, pois moro longe e não sabia como enviar o jumbo ao meu filho
                    </p>

                    <hr>

                    <div class="footer">
                        <h4 class="text-primary font-medium">Marlene Pedroso</h4>
                        <small>Corumbá / MS</small>
                    </div>
                </div>

                <div class=" border border-primary  rounded-lg w-full h-72 p-8 flex flex-col justify-between">
                    <div class="max-w-28">
                        <div class="flex " title="5/5" itemtype="http://schema.org/Rating" itemscope="" itemprop="reviewRating">
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                            <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                        </div>
                    </div>

                    <p class="py-5">
                        Sem palavras!! Vocês estão de parabéns pela qualidade e pela agilidade no atendimento. Saiu muito mais barato e evitei problemas na montagem do jumbo
                    </p>

                    <hr>

                    <div class="footer">
               
                        <h4 class="text-primary font-medium" >Marcos V Gomes</h4>
                        <small>São Paulo / SP</small>

                    </div>
                </div>
                
            </div>

            <div class="text-center flex flex-col m-auto pt-4">

                <p class="text-primary font-semibold tracking-wide text-lg">Aprovado por 4k+ clientes</p>

                <div class="flex justify-center" title="5/5" itemtype="http://schema.org/Rating" itemscope="" itemprop="reviewRating">
                    <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                    <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                    <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                    <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-500" /> 
                    <x-heroicon-s-star class="h-5 w-5 flex-shrink-0 text-warning-100" /> 
                    
                    <h4 class="text-bold text-complement-700 font-medium"> &nbsp; 4.4/5 &nbsp; . &nbsp; 4.841 Reviews </h4>
                </div>

            </div>

        </div>

    </section>




    @foreach(collect($this->template_settings->home_page_sections)->sortBy('order') as $section)
        <section @class(['bg-white px-4 pt-24 space-y-5 sm:px-6 sm:pt-32 xl:mx-auto xl:max-w-7xl lg:px-8', 'pb-24 sm:pb-32' => $loop->last])>
            <div class="sm:flex sm:items-center sm:justify-between">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ $section['title'] }}
                </h2>
                @if($section['link'] && $section['link_text'])
                    <a
                        href="{{ $section['link'] }}"
                        class="hidden text-sm font-semibold text-sky-700 hover:text-sky-600 sm:block"
                    >
                        {!! $section['link_text'] !!}
                        <span aria-hidden="true"> &rarr;</span>
                    </a>
                @endif
            </div>
            @if($section['banner_path'])
                <img
                    src="{{ Storage::url($section['banner_path']) }}"
                    alt="{{ $section['title'] }}"
                    class="w-full h-auto object-cover object-center rounded-lg"
                />
            @endif
            @if($section['type'] === 'collection')
                <livewire:components.collection-section
                    :handle="$section['carousel_handle']"
                    :items="$section['items']"
                />
            @elseif($section['type'] === 'product')
                <livewire:components.product-section
                    :handle="$section['carousel_handle']"
                    :items="$section['items']"
                />
            @elseif($section['type'] === 'blog')
                <livewire:components.blog-section />
            @endif
        </section>
    @endforeach
</main>

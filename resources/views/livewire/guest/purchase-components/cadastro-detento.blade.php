<div>
    <div class="mx-auto max-w-7xl py-8  space-y-4">

    <form
        wire:submit.prevent="saveData"
        class="space-y-6"
    >
        <x-card>
            <x-slot:content class="!py-8 sm:!px-10">
                <h2 class="text-lg font-semibold text-purple tracking-tight flex items-center"> 
                    <x-heroicon-s-check-circle class="h-5 w-5 flex-shrink-0 text-accent" /> &nbsp; Cadastrar novo detento 
                </h2>

                <div class="md:grid md:grid-cols-3 md:gap-6 mb-4 pb-8 border-b border-gray">

                    <div class="md:col-span-2">
                        
                        <p class="mt-2 text-sm text-slate-500">
                           * Os dados precisam ser <strong>IDÊNTICOS</strong> aos cadastrados na unidade
                        </p>
                    </div>

                    <div class="mt-5 md:col-span-3 md:mt-0">
                        <div class="grid grid-cols-6 gap-6">

                            <div class="col-span-6">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="full-name"
                                    value="Nome <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.blur="data.detento.name"
                                    type="text"
                                    id="full-name"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Nome Completo do Detento"
                                />
                                <x-input-error
                                    for="data.detento.name"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-2">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="matricula"
                                    value="Matrícula <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.blur="data.detento.matricula"
                                    type="text"
                                    id="matricula"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Matrícula (incluindo números, espaços e digitos)"
                                />
                                <x-input-error
                                    for="data.detento.matricula"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-2">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="raio"
                                    value="Raio <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.blur="data.detento.raio"
                                    type="text"
                                    id="raio"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Raio"
                                />
                                <x-input-error
                                    for="data.detento.raio"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-2">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="cela"
                                    value="Cela <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.blur="data.detento.cela"
                                    type="text"
                                    id="cela"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Cela"
                                />
                                <x-input-error
                                    for="data.detento.cela"
                                    class="mt-2"
                                />
                            </div>

                        </div>
                    </div>

                </div>

                <h2 class="text-lg font-semibold text-purple tracking-tight flex items-center"> 
                    <x-heroicon-s-check-circle class="h-5 w-5 flex-shrink-0 text-accent" /> &nbsp; Visitante 
                </h2>

                <div class="md:grid md:grid-cols-3 md:gap-6 border-b border-gray pb-8 mb-4">

                    <div class="md:col-span-2">
                        
                        <p class="mt-2 text-sm text-slate-500">
                           * Informe o nome do visitante e o seu endereço cadastrado no rol de visitas na unidade prisional( endereço igual ao comprovante de residência enviado).
                        </p>
                    </div>

                    <div class="mt-5 md:col-span-3 md:mt-0">

                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="nome-visitante"
                                    value="Nome <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.blur="data.visitante.nome"
                                    type="text"
                                    id="nome-visitante"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Nome Completo do Visitante"
                                />
                                <x-input-error
                                    for="data.visitante.nome"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-6 md:col-span-1">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="cep"
                                    value="CEP <span class='text-warning'>*</span>"
                                />
                                <x-input x-mask="99.999-999"
                                    wire:model.defer="data.visitante.cep"
                                    wire:loading.attr="disabled"
                                    type="text"
                                    id="cep"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="CEP"
                                    x-on:blur.prevent="$wire.changeDataVisitanteCep"
                                />
                                <x-input-error
                                    for="data.visitante.cep"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-4">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="logradouro"
                                    value="Endereço <span class='text-warning'>*</span>"
                                />
                               
                                <x-input
                                    wire:model.blur="data.visitante.logradouro"
                                    type="text"
                                    id="logradouro"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Endereço"
                                    wire:loading.attr="disabled"
                                />
                                <x-input-error
                                    for="data.visitante.logradouro"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-2">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="numero"
                                    value="Número <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.defer="data.visitante.numero"
                                    type="text"
                                    id="numero"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Número"
                                />
                                <x-input-error
                                    for="data.visitante.numero"
                                    class="mt-2"
                                />
                            </div>



                            <div class="col-span-2">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="bairro"
                                    value="Bairro <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.blur="data.visitante.bairro"
                                    type="text"
                                    id="bairro"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Bairro"
                                    wire:loading.attr="disabled"
                                />
                                <x-input-error
                                    for="data.visitante.bairro"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-2">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="cidade"
                                    value="Cidade <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.blur="data.visitante.cidade"
                                    type="text"
                                    id="cidade"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Cidade"
                                    wire:loading.attr="disabled"
                                />
                                <x-input-error
                                    for="data.visitante.cidade"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-2">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="uf"
                                    value="Estado <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.blur="data.visitante.uf"
                                    type="text"
                                    id="uf"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Estado"
                                    wire:loading.attr="disabled"
                                />
                                <x-input-error
                                    for="data.visitante.uf"
                                    class="mt-2"
                                />
                            </div>
                        </div>

                    </div>

                </div>

                <div class="md:grid md:grid-cols-3 md:gap-6 border-b border-gray pb-8 mb-4">

                    <div class="md:col-span-3">
                    
                        <p class="mt-2 text-base text-warning flex">
                            <x-heroicon-s-exclamation-triangle class="h-5 w-5 flex-shrink-0 text-accent" /> &nbsp;
                           * Envie uma imagem com boa qualidade da carteirinha de visitante, que seja legível e esteja válido. Mostrando todo o documento, sem desfoques ou pouca luz.
                        </p>
                    </div>

                    <div class="col-span-3 relative">
                        @if(optional($visitante)->hasMedia('cover') )

                        
                            <div class="ml-4 mt-2 absolute top-5">
                                <button
                                    x-on:click.prevent="if(confirm('{{ __('Tem certeza de que deseja apagar esta imagem?') }}')) $wire.deleteImage();"
                                    type="button"
                                    class="btn p-2 text-white hover:text-primary bg-accent rounded-lg text-xs"
                                >
                                    {{ __('Deletar') }}
                                </button>
                            </div>
               
                            <div class="p-4 flex justify-start max-h-64 overflow-hidden border border-accent">
                                <img
                                    src="{{ $visitante->getFirstMediaUrl('cover', 'thumb') }}"
                                    class=" size-72 object-contain"
                                >
                            </div>
                        @else
                            <x-upload-widget wire:model="image" />
                        @endif
                        
                    </div>

                </div>

                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-3">
                        <a class="btn bg-accent text-white text-center block w-full mt-2 flex">
                            <x-heroicon-s-chevron-double-left class="h-5 w-5 flex-shrink-0 text-white" /> &nbsp; voltar
                        </a>
                    </div>
                    <div class="col-span-3">
                        <button type="submit" class="btn bg-primary text-white text-center block w-full mt-2 flex">
                            Continuar &nbsp; <x-heroicon-s-chevron-double-right class="h-5 w-5 flex-shrink-0 text-white" />
                        </button>
                    </div>
                </div>

            </x-slot:content>
        </x-card>

    </form>

    </div>
</div>

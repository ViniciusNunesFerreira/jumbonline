<div>
    <div class="mx-auto max-w-7xl py-8  space-y-4">

    <form
        wire:submit.prevent="save"
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
                                    wire:model.defer="detento.name"
                                    type="text"
                                    id="full-name"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Nome Completo do Detento"
                                />
                                <x-input-error
                                    for="detento.name"
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
                                    wire:model.defer="detento.name"
                                    type="text"
                                    id="matricula"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Matrícula"
                                />
                                <x-input-error
                                    for="detento.matricula"
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
                                    wire:model.defer="detento.name"
                                    type="text"
                                    id="raio"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Raio"
                                />
                                <x-input-error
                                    for="detento.raio"
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
                                    wire:model.defer="detento.name"
                                    type="text"
                                    id="cela"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Cela"
                                />
                                <x-input-error
                                    for="detento.cela"
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
                                    wire:model.defer="visitante.nome"
                                    type="text"
                                    id="nome-visitante"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Nome Completo do Visitante"
                                />
                                <x-input-error
                                    for="visitante.nome"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-3">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="cep"
                                    value="CEP <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.defer="visitante.cep"
                                    type="text"
                                    id="cep"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="CEP"
                                />
                                <x-input-error
                                    for="visitante.cep"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-3">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="logradouro"
                                    value="Endereço <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.defer="visitante.logradouro"
                                    type="text"
                                    id="logradouro"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Endereço"
                                />
                                <x-input-error
                                    for="visitante.nome"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-3">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="numero"
                                    value="Número <span class='text-warning'>*</span>"
                                />
                                <x-input
                                    wire:model.defer="visitante.numero"
                                    type="text"
                                    id="numero"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Número"
                                />
                                <x-input-error
                                    for="visitante.numero"
                                    class="mt-2"
                                />
                            </div>

                            <div class="col-span-3">
                                <x-input-label
                                    class="!text-sm !font-bold !text-primary"
                                    for="complemento"
                                    value="Complemento"
                                />
                                <x-input
                                    wire:model.defer="visitante.complemento"
                                    type="text"
                                    id="complemento"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Ex: Apartamento 02 "
                                />
                                <x-input-error
                                    for="visitante.complemento"
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
                                    wire:model.defer="visitante.bairro"
                                    type="text"
                                    id="bairro"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Bairro"
                                />
                                <x-input-error
                                    for="visitante.bairro"
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
                                    wire:model.defer="visitante.cidade"
                                    type="text"
                                    id="cidade"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Cidade"
                                />
                                <x-input-error
                                    for="visitante.cidade"
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
                                    wire:model.defer="visitante.uf"
                                    type="text"
                                    id="uf"
                                    class="mt-1 block w-full sm:text-sm"
                                    placeholder="Estado"
                                />
                                <x-input-error
                                    for="visitante.uf"
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

                    <div class="col-span-3">
                        <x-upload-widget />
                    </div>

                </div>

                <div class="col-span-full">
                    <button class="btn btn-primary block">
                        Continuar
                    </button>
                </div>

            </x-slot:content>
        </x-card>

    </form>

    </div>
</div>

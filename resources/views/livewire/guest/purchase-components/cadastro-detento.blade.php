<div>
    <form wire:submit.prevent="saveData" class="space-y-6">

        <div class="rounded-3xl border border-secondary bg-white p-6 sm:p-8">
            <h2 class="flex items-center gap-2 font-urbanist text-lg font-semibold text-primary">
                <x-heroicon-s-user class="h-5 w-5 text-accent" /> Dados do Detento
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Os dados precisam ser <strong class="text-primary">idênticos</strong> aos cadastrados na unidade.
            </p>

            <div class="mt-6 grid grid-cols-6 gap-5">
                <div class="col-span-6">
                    <x-input-label class="!text-sm !font-semibold !text-primary" for="full-name" value="Nome completo" />
                    <x-input wire:model.blur="data.detento.name" type="text" id="full-name" class="mt-1.5 block w-full" placeholder="Nome completo do detento" />
                    <x-input-error for="data.detento.name" class="mt-2" />
                </div>

                <div class="col-span-3 sm:col-span-2">
                    <x-input-label class="!text-sm !font-semibold !text-primary" for="matricula" value="Matrícula" />
                    <x-input wire:model.blur="data.detento.matricula" type="text" id="matricula" class="mt-1.5 block w-full" placeholder="Matrícula" />
                    <x-input-error for="data.detento.matricula" class="mt-2" />
                </div>

                <div class="col-span-3 sm:col-span-2">
                    <x-input-label class="!text-sm !font-semibold !text-primary" for="raio" value="Raio" />
                    <x-input wire:model.blur="data.detento.raio" type="text" id="raio" class="mt-1.5 block w-full" placeholder="Raio" />
                    <x-input-error for="data.detento.raio" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-2">
                    <x-input-label class="!text-sm !font-semibold !text-primary" for="cela" value="Cela" />
                    <x-input wire:model.blur="data.detento.cela" type="text" id="cela" class="mt-1.5 block w-full" placeholder="Cela" />
                    <x-input-error for="data.detento.cela" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-secondary bg-white p-6 sm:p-8">
            <h2 class="flex items-center gap-2 font-urbanist text-lg font-semibold text-primary">
                <x-heroicon-s-map-pin class="h-5 w-5 text-accent" /> Visitante
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Nome e endereço cadastrados no rol de visitas — igual ao comprovante de residência enviado à unidade.
            </p>

            <div class="mt-6 grid grid-cols-6 gap-5">
                <div class="col-span-6">
                    <x-input-label class="!text-sm !font-semibold !text-primary" for="nome-visitante" value="Nome completo" />
                    <x-input wire:model.blur="data.visitante.nome" type="text" id="nome-visitante" class="mt-1.5 block w-full" placeholder="Nome completo do visitante" />
                    <x-input-error for="data.visitante.nome" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-2">
                    <x-input-label class="!text-sm !font-semibold !text-primary" for="cep" value="CEP" />
                    <x-input
                        x-mask="99.999-999"
                        wire:model.defer="data.visitante.cep"
                        wire:loading.attr="disabled"
                        type="text"
                        id="cep"
                        class="mt-1.5 block w-full"
                        placeholder="00.000-000"
                        x-on:blur.prevent="$wire.changeDataVisitanteCep"
                    />
                    <x-input-error for="data.visitante.cep" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-input-label class="!text-sm !font-semibold !text-primary" for="logradouro" value="Endereço" />
                    <x-input wire:model.blur="data.visitante.logradouro" type="text" id="logradouro" class="mt-1.5 block w-full" placeholder="Endereço" wire:loading.attr="disabled" />
                    <x-input-error for="data.visitante.logradouro" class="mt-2" />
                </div>

                <div class="col-span-3 sm:col-span-2">
                    <x-input-label class="!text-sm !font-semibold !text-primary" for="numero" value="Número" />
                    <x-input wire:model.defer="data.visitante.numero" type="text" id="numero" class="mt-1.5 block w-full" placeholder="Número" />
                    <x-input-error for="data.visitante.numero" class="mt-2" />
                </div>

                <div class="col-span-3 sm:col-span-2">
                    <x-input-label class="!text-sm !font-semibold !text-primary" for="bairro" value="Bairro" />
                    <x-input wire:model.blur="data.visitante.bairro" type="text" id="bairro" class="mt-1.5 block w-full" placeholder="Bairro" wire:loading.attr="disabled" />
                    <x-input-error for="data.visitante.bairro" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-2">
                    <x-input-label class="!text-sm !font-semibold !text-primary" for="cidade" value="Cidade" />
                    <x-input wire:model.blur="data.visitante.cidade" type="text" id="cidade" class="mt-1.5 block w-full" placeholder="Cidade" wire:loading.attr="disabled" />
                    <x-input-error for="data.visitante.cidade" class="mt-2" />
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <x-input-label class="!text-sm !font-semibold !text-primary" for="uf" value="UF" />
                    <x-input wire:model.blur="data.visitante.uf" type="text" id="uf" class="mt-1.5 block w-full" placeholder="UF" wire:loading.attr="disabled" />
                    <x-input-error for="data.visitante.uf" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-secondary bg-white p-6 sm:p-8">
            <h2 class="flex items-center gap-2 font-urbanist text-lg font-semibold text-primary">
                <x-heroicon-s-camera class="h-5 w-5 text-accent" /> Carteirinha de Visitante
            </h2>
            <p class="mt-2 flex items-start gap-2 text-sm text-warning">
                <x-heroicon-s-exclamation-triangle class="h-5 w-5 flex-shrink-0" />
                Envie uma foto legível, frente e verso, sem desfoque ou pouca luz.
            </p>

            <div class="mt-4">
                <livewire:guest.purchase-components.visitante-gallery :visitante="$visitante" />
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row">
            <button type="button" wire:click="$emitUp('changeTab', 'tabs-entrega')" class="flex flex-1 items-center justify-center gap-2 rounded-full border border-secondary py-3.5 text-sm font-semibold text-primary hover:bg-complement-500">
                <x-heroicon-s-chevron-left class="h-4 w-4" /> Voltar
            </button>
            <button type="submit" class="flex flex-1 items-center justify-center gap-2 rounded-full bg-accent py-3.5 text-sm font-semibold text-white shadow-lg shadow-accent/30 hover:bg-primary">
                Continuar <x-heroicon-s-arrow-right class="h-4 w-4" />
            </button>
        </div>
    </form>
</div>
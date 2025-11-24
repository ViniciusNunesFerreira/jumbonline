<div>
    <x-slot:title>
        {{ __('Gerador de Etiqueta + Declaração') }}
    </x-slot:title>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between px-4 sm:px-6 lg:px-8 mb-6">
        <div>
            <h1 class="text-2xl font-medium leading-none text-slate-900 dark:text-slate-100">
                {{ __('Etiqueta e Declaração Manual') }}
            </h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Preencha remetente, destinatário e os itens do pacote.
            </p>
        </div>
    </div>

    <!-- Mensagens Flash -->
    <div class="px-4 sm:px-6 lg:px-8 mb-4">
        @if (session()->has('success'))
            <div class="rounded-md bg-green-50 p-4 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-check-circle class="h-5 w-5 text-green-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Erros gerais de validação (ex: lista vazia) -->
        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 border border-red-200 mb-4">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Atenção:</h3>
                    <ul class="mt-2 list-disc pl-5 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
        
        <!-- GRID DE ENDEREÇOS (2 COLUNAS) -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            
            <!-- REMETENTE -->
            <x-card>
                <x-slot:header>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-user class="w-5 h-5 text-sky-600"/>
                        <h3 class="font-medium text-slate-900 dark:text-slate-200">Remetente</h3>
                    </div>
                </x-slot:header>
                <x-slot:content>
                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-span-6">
                            <x-input-label for="remetente.nome" value="Nome Completo" />
                            <x-input wire:model.defer="state.remetente.nome" class="mt-1 block w-full" placeholder="Ex: Sua Empresa Ltda" />
                            <x-input-error for="state.remetente.nome" class="mt-1"/>
                        </div>
                        <div class="col-span-3">
                            <x-input-label for="remetente.cep" value="CEP" />
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <x-input wire:model.defer="state.remetente.endereco.cep" class="block w-full rounded-none rounded-l-md" placeholder="00000-000" />
                                <button type="button" wire:click="searchCep('remetente')" class="relative -ml-px inline-flex items-center space-x-2 rounded-r-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                    <x-heroicon-o-magnifying-glass class="h-5 w-5" wire:loading.remove wire:target="searchCep('remetente')" />
                                    <span wire:loading wire:target="searchCep('remetente')" class="loading loading-spinner loading-xs">...</span>
                                </button>
                            </div>
                            <x-input-error for="state.remetente.endereco.cep" class="mt-1"/>
                        </div>
                         <!-- Campos restantes de endereço (simplificados para poupar espaço, igual ao anterior) -->
                         <div class="col-span-3">
                            <x-input-label for="remetente.numero" value="Número" />
                            <x-input wire:model.defer="state.remetente.endereco.numero" class="mt-1 block w-full" />
                        </div>
                        <div class="col-span-4">
                            <x-input-label for="remetente.logradouro" value="Logradouro" />
                            <x-input wire:model.defer="state.remetente.endereco.logradouro" class="mt-1 block w-full" />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="remetente.bairro" value="Bairro" />
                            <x-input wire:model.defer="state.remetente.endereco.bairro" class="mt-1 block w-full" />
                        </div>
                         <div class="col-span-4">
                            <x-input-label for="remetente.cidade" value="Cidade" />
                            <x-input wire:model.defer="state.remetente.endereco.cidade" class="mt-1 block w-full bg-gray-50" />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="remetente.uf" value="UF" />
                            <x-input wire:model.defer="state.remetente.endereco.uf" class="mt-1 block w-full bg-gray-50" maxlength="2" />
                        </div>
                    </div>
                </x-slot:content>
            </x-card>

            <!-- DESTINATÁRIO -->
            <x-card>
                <x-slot:header>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-map-pin class="w-5 h-5 text-sky-600"/>
                        <h3 class="font-medium text-slate-900 dark:text-slate-200">Destinatário</h3>
                    </div>
                </x-slot:header>
                <x-slot:content>
                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-span-6">
                            <x-input-label for="destinatario.nome" value="Nome (Detento/Cliente)" />
                            <x-input wire:model.defer="state.destinatario.nome" class="mt-1 block w-full" />
                            <x-input-error for="state.destinatario.nome" class="mt-1"/>
                        </div>
                        <div class="col-span-3">
                            <x-input-label for="destinatario.cep" value="CEP" />
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <x-input wire:model.defer="state.destinatario.endereco.cep" class="block w-full rounded-none rounded-l-md" placeholder="00000-000" />
                                <button type="button" wire:click="searchCep('destinatario')" class="relative -ml-px inline-flex items-center space-x-2 rounded-r-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
                                    <x-heroicon-o-magnifying-glass class="h-5 w-5" wire:loading.remove wire:target="searchCep('destinatario')" />
                                    <span wire:loading wire:target="searchCep('destinatario')" class="loading loading-spinner loading-xs">...</span>
                                </button>
                            </div>
                            <x-input-error for="state.destinatario.endereco.cep" class="mt-1"/>
                        </div>
                         <div class="col-span-3">
                            <x-input-label for="destinatario.numero" value="Número" />
                            <x-input wire:model.defer="state.destinatario.endereco.numero" class="mt-1 block w-full" />
                        </div>
                         <div class="col-span-4">
                            <x-input-label for="destinatario.logradouro" value="Logradouro" />
                            <x-input wire:model.defer="state.destinatario.endereco.logradouro" class="mt-1 block w-full" />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="destinatario.bairro" value="Bairro" />
                            <x-input wire:model.defer="state.destinatario.endereco.bairro" class="mt-1 block w-full" />
                        </div>
                        <div class="col-span-4">
                            <x-input-label for="destinatario.cidade" value="Cidade" />
                            <x-input wire:model.defer="state.destinatario.endereco.cidade" class="mt-1 block w-full bg-gray-50" />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="destinatario.uf" value="UF" />
                            <x-input wire:model.defer="state.destinatario.endereco.uf" class="mt-1 block w-full bg-gray-50" maxlength="2" />
                        </div>
                         <div class="col-span-6">
                            <x-input-label for="destinatario.obs" value="Observação (Cela/Raio/Matrícula) - Etiqueta" />
                            <x-input wire:model.defer="state.destinatario.obs" class="mt-1 block w-full" maxlength="60" />
                        </div>
                    </div>
                </x-slot:content>
            </x-card>
        </div>

        <!-- SEÇÃO DE PRODUTOS -->
        <x-card>
            <x-slot:header>
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-cube class="w-5 h-5 text-sky-600"/>
                    <h3 class="font-medium text-slate-900 dark:text-slate-200">
                        Produtos (Declaração de Conteúdo)
                    </h3>
                </div>
            </x-slot:header>

            <x-slot:content>
                <div class="space-y-6">
                    <p class="text-sm text-gray-500">
                        Adicione os itens para compor a declaração de conteúdo.
                    </p>

                    <!-- FORMULÁRIO DE INSERÇÃO (Fundo cinza para destacar) -->
                    <div class="bg-gray-50 dark:bg-slate-800/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            
                            <div class="md:col-span-4">
                                <x-input-label for="prod_name" value="Nome do Produto" />
                                <x-input wire:model.defer="tempProduct.name" id="prod_name" type="text" class="mt-1 block w-full" placeholder="Ex: Camiseta" />
                                <x-input-error for="tempProduct.name" class="mt-1" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="prod_qtd" value="Qtd" />
                                <x-input wire:model.defer="tempProduct.qtd" id="prod_qtd" type="number" min="1" class="mt-1 block w-full" placeholder="1" />
                                <x-input-error for="tempProduct.qtd" class="mt-1" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="prod_weight" value="Peso (Kg)" />
                                <x-input wire:model.defer="tempProduct.weight" id="prod_weight" type="number" step="0.001" min="0" class="mt-1 block w-full" placeholder="0.500" />
                                <x-input-error for="tempProduct.weight" class="mt-1" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="prod_val" value="Valor Unit (R$)" />
                                <x-input wire:model.defer="tempProduct.value" id="prod_val" type="number" step="0.01" min="0" class="mt-1 block w-full" placeholder="0.00" />
                                <x-input-error for="tempProduct.value" class="mt-1" />
                            </div>

                            <div class="md:col-span-2">
                                <button wire:click="addProduct" class="btn btn-primary w-full justify-center">
                                    Inserir
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- TABELA DE ITENS ADICIONADOS -->
                    @if(count($products) > 0)
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-slate-800">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Item</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Produto</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Qtd</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Peso</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Valor Total</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Ações</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-slate-900">
                                    @foreach($products as $index => $product)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $product['name'] }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $product['qtd'] }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ number_format($product['weight'] * $product['qtd'], 3, ',', '.') }} kg
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                R$ {{ number_format($product['value'] * $product['qtd'], 2, ',', '.') }}
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <button wire:click="removeProduct({{ $index }})" class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                                    Remover
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-slate-800/50">
                                    <tr>
                                        <td colspan="6" class="px-6 py-3 text-right text-sm font-medium text-gray-500">
                                            Total de Itens: {{ count($products) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6 bg-white dark:bg-slate-900 rounded border border-dashed border-gray-300 dark:border-gray-700">
                            <x-heroicon-o-shopping-bag class="mx-auto h-10 w-10 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum produto</h3>
                            <p class="mt-1 text-sm text-gray-500">Adicione itens acima para gerar a declaração.</p>
                        </div>
                    @endif

                </div>
            </x-slot:content>
        </x-card>

        <!-- FOOTER DE AÇÃO -->
        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div wire:loading wire:target="geraEtiqueta" class="text-sm text-sky-600 font-medium animate-pulse">
                    Gerando PDF (Etiqueta + Declaração)...
                </div>
                
                <button
                    wire:click.prevent="geraEtiqueta"
                    class="btn btn-primary flex items-center gap-2"
                    wire:loading.attr="disabled"
                    @if(count($products) == 0) disabled @endif
                >
                    <x-heroicon-o-printer class="w-5 h-5" />
                    {{ __('Gerar Documentos PDF') }}
                </button>
            </div>
        </div>
    </div>
</div>
<div>
    <x-slot:title>
        {{ $expense->exists ? __('Conta - :description', ['description' => $expense->description]) : __('Nova conta a pagar') }}
    </x-slot:title>

    <div class="px-4 sm:px-6 lg:px-8 mx-auto">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="min-w-0 flex flex-1 items-center gap-2.5">
                <a href="{{ route('employee.expenses.list') }}" class="btn btn-default btn-xs !rounded-xl">
                    <x-heroicon-m-arrow-left class="w-5 h-5" />
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-primary truncate dark:text-white">
                    {{ $expense->exists ? $expense->description : __('Nova conta a pagar') }}
                </h1>
                @if($expense->exists)
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium flex-shrink-0" style="background-color: {{ $expense->status->color() }}1A; color: {{ $expense->status->color() }};">
                        <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $expense->status->color() }}"></span>
                        {{ $expense->status->label() }}
                    </span>
                @endif
            </div>

            @if($expense->exists)
                <div class="mt-4 flex sm:mt-0 sm:ml-4">
                    @if($expense->payment_date)
                        <button x-on:click.prevent="if(confirm('{{ __('Desfazer o pagamento desta conta?') }}')) $wire.undoPayment()" type="button" class="btn btn-default btn-xs !rounded-xl">
                            {{ __('Desfazer pagamento') }}
                        </button>
                    @else
                        <button wire:click="confirmMarkAsPaid" type="button" class="btn btn-primary block w-full order-0 sm:order-1 sm:ml-3">
                            {{ __('Marcar como pago') }}
                        </button>
                    @endif
                </div>
            @endif
        </div>

        @if($expense->exists && $expense->payment_date)
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Pago em :date via :method', ['date' => $expense->payment_date->format('d/m/Y'), 'method' => $expense->payment_method?->label() ?? __('não informado')]) }}
            </p>
        @endif

        <div class="mt-6 space-y-6">
            <form wire:submit.prevent="save" class="space-y-6">
                <x-card>
                    <x-slot:header>
                        <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                            {{ __('Favorecido') }}
                        </h2>
                    </x-slot:header>
                    <x-slot:content>
                        <div x-data="{ payeeMode: @entangle('payeeMode') }">
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <label
                                    class="relative flex cursor-pointer rounded-xl border p-3 transition-colors hover:border-accent-300 dark:hover:border-accent-500/40"
                                    x-bind:class="payeeMode === 'existing' ? 'border-accent-500 ring-2 ring-accent-500' : 'border-slate-200 dark:border-white/10'"
                                >
                                    <input x-model="payeeMode" type="radio" name="payee-mode" value="existing" class="sr-only" />
                                    <span class="text-sm font-medium text-primary dark:text-slate-200">{{ __('Favorecido existente') }}</span>
                                </label>
                                <label
                                    class="relative flex cursor-pointer rounded-xl border p-3 transition-colors hover:border-accent-300 dark:hover:border-accent-500/40"
                                    x-bind:class="payeeMode === 'employee' ? 'border-accent-500 ring-2 ring-accent-500' : 'border-slate-200 dark:border-white/10'"
                                >
                                    <input x-model="payeeMode" type="radio" name="payee-mode" value="employee" class="sr-only" />
                                    <span class="text-sm font-medium text-primary dark:text-slate-200">{{ __('Funcionário (folha)') }}</span>
                                </label>
                                <label
                                    class="relative flex cursor-pointer rounded-xl border p-3 transition-colors hover:border-accent-300 dark:hover:border-accent-500/40"
                                    x-bind:class="payeeMode === 'new' ? 'border-accent-500 ring-2 ring-accent-500' : 'border-slate-200 dark:border-white/10'"
                                >
                                    <input x-model="payeeMode" type="radio" name="payee-mode" value="new" class="sr-only" />
                                    <span class="text-sm font-medium text-primary dark:text-slate-200">{{ __('Novo (fornecedor, conta etc.)') }}</span>
                                </label>
                            </div>

                            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('"Favorecido existente" reaproveita alguém já cadastrado em outra conta (fornecedor, concessionária ou funcionário). "Funcionário (folha)" vincula a um funcionário do sistema — se ele já tiver um favorecido de uma conta anterior, ele é reaproveitado. "Novo" cadastra um favorecido a partir de um nome digitado na hora.') }}
                            </p>

                            <div class="mt-4">
                                <div x-show="payeeMode === 'existing'">
                                    <x-input-label for="payee_id" :value="__('Favorecido')" />
                                    <x-searchable-select
                                        wire:model="payee_id"
                                        id="payee_id"
                                        :options="$payees"
                                        :placeholder="__('Buscar favorecido...')"
                                        class="mt-1"
                                    />
                                    <x-input-error for="payee_id" class="mt-2" />
                                </div>
                                <div x-show="payeeMode === 'employee'" x-cloak>
                                    <x-input-label for="newPayeeEmployeeId" :value="__('Funcionário')" />
                                    <x-searchable-select
                                        wire:model="newPayeeEmployeeId"
                                        id="newPayeeEmployeeId"
                                        :options="$employees"
                                        :placeholder="__('Buscar funcionário...')"
                                        class="mt-1"
                                    />
                                    <x-input-error for="newPayeeEmployeeId" class="mt-2" />
                                </div>
                                <div x-show="payeeMode === 'new'" x-cloak>
                                    <x-input-label for="newPayeeName" :value="__('Nome do favorecido')" />
                                    <x-input wire:model.defer="newPayeeName" id="newPayeeName" type="text" class="block w-full mt-1 rounded-xl sm:text-sm" placeholder="{{ __('Ex.: Sabesp, Fornecedor XYZ...') }}" />
                                    <x-input-error for="newPayeeName" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                            {{ __('Detalhes da conta') }}
                        </h2>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <x-input-label for="description" :value="__('Descrição')" />
                                <x-input wire:model.defer="expense.description" id="description" type="text" class="block w-full mt-1 rounded-xl sm:text-sm" required autofocus />
                                <x-input-error for="expense.description" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="amount" :value="__('Valor')" />
                                <x-money-input wire:model="amountInput" :value="$expense->amount" id="amount" class="mt-1" />
                                <x-input-error for="expense.amount" class="mt-2" />
                            </div>

                            <div wire:ignore x-data="{ dueDate: @entangle('dueDate').defer }" x-init="flatpickr($refs.dueDate, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd/m/Y', disableMobile: true, defaultDate: dueDate, onChange: (d, s) => dueDate = s })">
                                <x-input-label for="due_date" :value="__('Vencimento')" />
                                <x-input x-ref="dueDate" id="due_date" type="text" class="block w-full mt-1 rounded-xl sm:text-sm" />
                                <x-input-error for="dueDate" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="expense_category_id" :value="__('Categoria')" />
                                <x-select wire:model="expense.expense_category_id" id="expense_category_id" class="mt-1 !h-10 w-full rounded-xl text-sm">
                                    <option value="">{{ __('Sem categoria') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="expense.expense_category_id" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="recurrence_type" :value="__('Recorrência')" />
                                <x-select wire:model="expense.recurrence_type" id="recurrence_type" class="mt-1 !h-10 w-full rounded-xl text-sm">
                                    @foreach($recurrenceOptions as $option)
                                        <option value="{{ $option->value }}">{{ $option->label() }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-description class="mt-1">
                                    {{ __('A geração automática da próxima ocorrência ainda não está disponível — por enquanto, este campo só fica registrado.') }}
                                </x-input-description>
                                <x-input-error for="expense.recurrence_type" class="mt-2" />
                            </div>

                            <div class="sm:col-span-2">
                                <x-input-label for="notes" :value="__('Observações')" />
                                <x-textarea wire:model.defer="expense.notes" id="notes" class="block w-full mt-1 rounded-xl sm:text-sm" rows="3" />
                                <x-input-error for="expense.notes" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h2 class="text-base font-semibold text-primary dark:text-slate-200">
                            {{ __('Boleto / Nota fiscal') }}
                        </h2>
                    </x-slot:header>
                    <x-slot:content>
                        @if($expense->exists && $expense->hasMedia('boleto'))
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 p-3 dark:border-white/10">
                                <div class="flex min-w-0 items-center gap-2">
                                    <x-heroicon-o-paper-clip class="h-5 w-5 flex-shrink-0 text-slate-400" />
                                    <a href="{{ $expense->getFirstMediaUrl('boleto') }}" target="_blank" class="truncate text-sm font-medium text-accent-600 hover:text-accent-700 dark:text-accent-400">
                                        {{ $expense->getFirstMedia('boleto')->file_name }}
                                    </a>
                                </div>
                                <button x-on:click.prevent="if(confirm('{{ __('Remover o anexo atual?') }}')) $wire.removeBoleto()" type="button" class="flex-shrink-0 text-sm text-red-600 hover:text-red-700 dark:text-red-400">
                                    {{ __('Remover') }}
                                </button>
                            </div>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Selecionar outro arquivo abaixo substitui o anexo atual ao salvar.') }}
                            </p>
                        @endif

                        <div class="mt-3" wire:loading.class="opacity-50" wire:target="boleto">
                            <label for="boleto" class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-dashed border-slate-300 px-4 py-6 text-sm text-slate-500 hover:border-accent-400 hover:text-accent-600 dark:border-white/10 dark:text-slate-400">
                                <x-heroicon-o-arrow-up-tray class="h-5 w-5" />
                                <span>{{ $boleto ? $boleto->getClientOriginalName() : __('Selecionar arquivo (PDF, JPG ou PNG, até 10MB)') }}</span>
                            </label>
                            <input wire:model="boleto" id="boleto" type="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" />
                        </div>
                        <x-input-error for="boleto" class="mt-2" />
                    </x-slot:content>
                </x-card>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        {{ $expense->exists ? __('Salvar alterações') : __('Criar conta') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <form wire:submit.prevent="markAsPaid">
        <x-modal-dialog wire:model="showMarkAsPaidModal">
            <x-slot:title>
                {{ __('Marcar como pago') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-4">
                    <div wire:ignore x-data="{ paymentDate: @entangle('paymentDate').defer }" x-init="flatpickr($refs.paymentDate, { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd/m/Y', disableMobile: true, defaultDate: paymentDate, onChange: (d, s) => paymentDate = s }); $watch('paymentDate', value => { if ($refs.paymentDate._flatpickr && $refs.paymentDate._flatpickr.input.value !== value) $refs.paymentDate._flatpickr.setDate(value) })">
                        <x-input-label for="payment_date" :value="__('Data do pagamento')" />
                        <x-input x-ref="paymentDate" id="payment_date" type="text" class="block w-full mt-1 rounded-xl sm:text-sm" />
                        <x-input-error for="paymentDate" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="payment_method" :value="__('Forma de pagamento')" />
                        <x-select wire:model="paymentMethod" id="payment_method" class="mt-1 !h-10 w-full rounded-xl text-sm">
                            <option value="">{{ __('Selecione...') }}</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->value }}">{{ $method->label() }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="paymentMethod" class="mt-2" />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <button type="submit" class="btn btn-primary w-full sm:ml-3 sm:w-auto">
                    {{ __('Confirmar pagamento') }}
                </button>
                <button x-on:click.prevent="show = false" class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto">
                    {{ __('Cancelar') }}
                </button>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>
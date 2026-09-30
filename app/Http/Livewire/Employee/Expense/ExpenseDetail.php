<?php

namespace App\Http\Livewire\Employee\Expense;

use App\Enums\ExpensePaymentMethod;
use App\Enums\ExpenseRecurrence;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Payee;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Criar/editar conta a pagar (Screen 2 do roadmap de Contas a Pagar).
 * Mesmo padrão de DiscountDetail: um único componente atende tanto
 * /expenses/create quanto /expenses/{expense}, decidindo pelo nome da rota
 * no mount() — ver DiscountDetail::mount() como referência direta.
 */
class ExpenseDetail extends Component
{
    use WithFileUploads;

    public Expense $expense;

    /**
     * 'existing' = escolher um favorecido já cadastrado.
     * 'employee' = vincular a um funcionário já cadastrado (folha de
     *              pagamento) — reaproveita o Payee dele se já existir um.
     * 'new'      = nome livre (fornecedor, concessionária etc.).
     */
    public string $payeeMode = 'existing';

    public $payee_id = null;

    public $newPayeeEmployeeId = null;

    public $newPayeeName = '';

    /**
     * Valor final sincronizado pelo <x-money-input> (ver o componente),
     * sempre no formato "1500.50" (ponto decimal, sem separador de
     * milhar) — o componente já garante isso no navegador, então aqui só
     * falta validar e atribuir a expense.amount em save().
     */
    public $amountInput = '';

    /**
     * Datas como propriedades próprias (não `expense.due_date` diretamente)
     * pra combinar com flatpickr — mesmo padrão de DiscountDetail::$startDate.
     */
    public $dueDate = null;

    public $boleto = null;

    public $showMarkAsPaidModal = false;

    public $paymentDate = null;

    public $paymentMethod = null;

    protected function rules()
    {
        $rules = [
            'expense.description' => ['required', 'string', 'max:255'],
            'expense.amount' => ['required', 'numeric', 'min:0.01'],
            'expense.expense_category_id' => ['nullable', 'exists:expense_categories,id'],
            'expense.recurrence_type' => ['required'],
            'expense.notes' => ['nullable', 'string'],
            'dueDate' => ['required', 'date'],
            'boleto' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];

        return match ($this->payeeMode) {
            'existing' => $rules + ['payee_id' => ['required', 'exists:payees,id']],
            'employee' => $rules + ['newPayeeEmployeeId' => ['required', 'exists:employees,id']],
            'new' => $rules + ['newPayeeName' => ['required', 'string', 'max:255']],
        };
    }

    protected $messages = [
        'payee_id.required' => 'Selecione um favorecido.',
        'newPayeeEmployeeId.required' => 'Selecione um funcionário.',
        'newPayeeName.required' => 'Informe o nome do favorecido.',
        'expense.amount.required' => 'Informe o valor da conta.',
        'expense.amount.numeric' => 'Informe um valor válido (ex.: 1500,00).',
    ];

    /**
     * Interpreta o texto digitado em "Valor" (aceita "1500", "1500,00" ou
     * "1.500,00") sem depender da máscara JS pra isso — o cast automático
     * do Eloquent (float) truncaria um valor como "1.500,00" no primeiro
     * separador que não reconhece, virando 1.0 em vez de 1500.0. Aqui a
     * interpretação é explícita: quando vírgula E ponto aparecem, o que
     * vier por último é o separador decimal; o outro é só agrupamento de
     * milhar e é descartado. Funciona como rede de segurança do servidor,
     * mesmo o <x-money-input> já enviando sempre um formato limpo.
     */
    public static function parseMoneyInput(?string $raw): ?float
    {
        $raw = trim((string) $raw);

        if ($raw === '') {
            return null;
        }

        $raw = preg_replace('/[^\d,.]/', '', $raw);

        $lastComma = strrpos($raw, ',');
        $lastDot = strrpos($raw, '.');

        if ($lastComma !== false && $lastDot !== false) {
            if ($lastComma > $lastDot) {
                $raw = str_replace('.', '', $raw);
                $raw = str_replace(',', '.', $raw);
            } else {
                $raw = str_replace(',', '', $raw);
            }
        } elseif ($lastComma !== false) {
            $raw = str_replace(',', '.', $raw);
        }

        return is_numeric($raw) ? (float) $raw : null;
    }

    public function mount()
    {
        if (Route::currentRouteName() === 'employee.expenses.create') {
            $this->expense = new Expense([
                'recurrence_type' => ExpenseRecurrence::NONE->value,
            ]);

            $this->dueDate = now()->toDateString();

            return;
        }

        $this->expense->load(['payee', 'category', 'media']);

        $this->payee_id = $this->expense->payee_id;

        $this->dueDate = $this->expense->due_date->toDateString();
    }

    public function updatedBoleto()
    {
        $this->validateOnly('boleto');
    }

    /**
     * Resolve o favorecido da despesa a partir do modo escolhido. Só é
     * chamado dentro de save(), depois da validação — nunca cria um Payee
     * "órfão" se o resto do formulário for inválido.
     */
    protected function resolvePayeeId(): int
    {
        if ($this->payeeMode === 'existing') {
            return (int) $this->payee_id;
        }

        if ($this->payeeMode === 'employee') {
            $employee = Employee::findOrFail($this->newPayeeEmployeeId);

            // firstOrCreate por employee_id: se este funcionário já tem um
            // favorecido vinculado (de uma despesa anterior), reaproveita —
            // não cria um Payee duplicado a cada nova conta da mesma pessoa.
            return Payee::firstOrCreate(
                ['employee_id' => $employee->id],
                ['name' => $employee->name]
            )->id;
        }

        return Payee::create(['name' => $this->newPayeeName])->id;
    }

    public function save()
    {
        // O <select> de categoria envia string vazia para "Sem categoria" —
        // sem isso, a validação (nullable|exists) rejeitaria '' como um id
        // inválido, em vez de tratar como "nenhuma categoria".
        if ($this->expense->expense_category_id === '') {
            $this->expense->expense_category_id = null;
        }

        $this->expense->amount = self::parseMoneyInput($this->amountInput);

        $this->validate();

        $this->expense->payee_id = $this->resolvePayeeId();

        $this->expense->due_date = $this->dueDate;

        $this->expense->save();

        if ($this->boleto) {
            $this->expense
                ->addMedia($this->boleto->getRealPath())
                ->usingFileName($this->boleto->getClientOriginalName())
                ->toMediaCollection('boleto');

            $this->boleto = null;
        }

        if ($this->expense->wasRecentlyCreated) {
            session()->flash('success', trans('Conta criada com sucesso!'));

            $this->redirect(route('employee.expenses.detail', $this->expense));

            return;
        }

        $this->expense->load(['payee', 'category', 'media']);

        $this->notify(trans('Conta salva com sucesso!'));
    }

    public function removeBoleto()
    {
        $this->expense->clearMediaCollection('boleto');

        $this->notify(trans('Anexo removido.'));
    }

    public function confirmMarkAsPaid()
    {
        $this->paymentDate = now()->toDateString();

        $this->paymentMethod = null;

        $this->resetErrorBag();

        $this->showMarkAsPaidModal = true;
    }

    public function markAsPaid()
    {
        $this->validate([
            'paymentDate' => ['required', 'date'],
            'paymentMethod' => ['required'],
        ]);

        $this->expense->payment_date = $this->paymentDate;

        $this->expense->payment_method = $this->paymentMethod;

        $this->expense->save();

        $this->showMarkAsPaidModal = false;

        $this->notify(trans('Conta marcada como paga.'));
    }

    /**
     * Desfaz um pagamento marcado por engano. Não é um "estorno" financeiro
     * de verdade — só limpa payment_date/payment_method, voltando a conta
     * para Pendente/Atrasado (o que já era, antes de ser paga).
     */
    public function undoPayment()
    {
        $this->expense->payment_date = null;

        $this->expense->payment_method = null;

        $this->expense->save();

        $this->notify(trans('Pagamento desfeito.'));
    }

    public function getPayeesProperty()
    {
        return Payee::orderBy('name')->get(['id', 'name'])->map(fn ($payee) => [
            'id' => $payee->id,
            'label' => $payee->name,
        ]);
    }

    public function getEmployeesProperty()
    {
        return Employee::orderBy('name')->get(['id', 'name'])->map(fn ($employee) => [
            'id' => $employee->id,
            'label' => $employee->name,
        ]);
    }

    public function getCategoriesProperty()
    {
        return ExpenseCategory::orderBy('name')->get(['id', 'name']);
    }

    public function render()
    {
        return view('livewire.employee.expense.expense-detail', [
            'payees' => $this->payees,
            'employees' => $this->employees,
            'categories' => $this->categories,
            'recurrenceOptions' => ExpenseRecurrence::cases(),
            'paymentMethods' => ExpensePaymentMethod::cases(),
        ])->layout('layouts.admin');
    }
}
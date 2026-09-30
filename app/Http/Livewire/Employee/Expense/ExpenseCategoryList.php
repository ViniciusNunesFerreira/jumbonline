<?php

namespace App\Http\Livewire\Employee\Expense;

use App\Http\Livewire\Traits\WithBulkActions;
use App\Models\ExpenseCategory;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Lista de Categorias de Despesa. Mesmo padrão visual e estrutural de
 * CategoryList (busca, seleção em massa, exclusão confirmada, modal de
 * criação) — ver categoria-list.blade.php como referência.
 *
 * Diferença deliberada: aqui um único modal atende tanto criação quanto
 * edição (via $editingId), em vez de criação por modal + edição por página
 * de detalhe separada como em Categoria de Produto. Categoria de despesa
 * tem um campo só (nome) — uma página de detalhe dedicada só para isso
 * seria complexidade sem ganho nenhum pro atendente.
 */
class ExpenseCategoryList extends Component
{
    use WithBulkActions;
    use WithPagination;

    public $perPage = 10;

    public $search = '';

    public $showModal = false;

    public $showDeleteConfirmationModal = false;

    /**
     * Null enquanto está criando; id da categoria enquanto está editando.
     */
    public $editingId = null;

    public $name = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('expense_categories', 'name')->ignore($this->editingId)],
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->clearSelection();
    }

    public function create()
    {
        $this->reset('editingId', 'name');

        $this->resetErrorBag();

        $this->showModal = true;
    }

    public function edit(ExpenseCategory $category)
    {
        $this->editingId = $category->id;

        $this->name = $category->name;

        $this->resetErrorBag();

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        ExpenseCategory::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $this->name]
        );

        $this->showModal = false;

        $this->notify($this->editingId ? trans('Categoria atualizada.') : trans('Categoria criada.'));
    }

    /**
     * Bloqueado pela FK restrictOnDelete quando a categoria tem despesas —
     * a mensagem aqui só explica o que a constraint do banco já impede,
     * em vez de deixar o atendente ver um erro de SQL cru.
     */
    public function deleteSelected()
    {
        try {
            ExpenseCategory::query()->whereIn('id', $this->selected)->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            $this->showDeleteConfirmationModal = false;

            $this->notify(trans('Uma ou mais categorias selecionadas têm despesas vinculadas e não podem ser excluídas. Reclassifique as despesas antes de excluir a categoria.'));

            return;
        }

        $this->clearSelection();

        $this->showDeleteConfirmationModal = false;

        $this->notify(trans('Categoria(s) excluída(s).'));
    }

    public function getRowsQueryProperty()
    {
        return ExpenseCategory::query()
            ->withCount('expenses')
            ->when($this->search, fn ($query, $search) => $query->where('name', 'like', '%' . $search . '%'))
            ->orderBy('name');
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.employee.expense.expense-category-list', [
            'categories' => $this->rows,
        ])->layout('layouts.admin');
    }
}
<?php

namespace App\Http\Livewire\Employee\Article;

use App\Http\Livewire\Traits\WithBulkActions;
use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleList extends Component
{
    use WithBulkActions;
    use WithPagination;

    public Article $newArticle;

    public $addingNewArticle = false;

    public $showDeleteConfirmationModal = false;

    public $perPage = 10;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'newArticle.title' => 'required',
    ];

    public function addNewArticle()
    {
        $this->newArticle = new Article();

        $this->addingNewArticle = true;
    }

    public function saveNewArticle()
    {
        $this->validate([
            'newArticle.title' => 'required',
        ]);

        $this->newArticle->author_id = auth()->user()->id;

        $this->newArticle->save();

        $this->addingNewArticle = false;

        $this->redirect(route('employee.articles.detail', $this->newArticle));
    }

    public function deleteSelected()
    {
        Article::query()->whereIn('id', $this->selected)->delete();

        $this->reset('selectAll', 'selectPage', 'selected');

        $this->showDeleteConfirmationModal = false;
    }

    public function getRowsQueryProperty()
    {
        return Article::query()
            ->when($this->search, fn($query, $search) => $query->where('title', 'like', '%' . $search . '%'))
            ->latest();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.employee.article.article-list', [
            'articles' => $this->rows,
        ])->layout('layouts.admin');
    }
}

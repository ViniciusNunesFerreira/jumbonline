<?php

namespace App\Http\Livewire\Employee\Article\Components;

use App\Models\Article;
use App\Models\Employee;
use Livewire\Component;

class ArticleAuthorManager extends Component
{
    public Article $article;

    public $authors = [];

    public $filterAuthorName = '';

    protected $listeners = ['refresh' => '$refresh'];

    public function loadAuthors()
    {
        $this->authors = Employee::query()
            ->select('id', 'name')
            ->with('media')
            ->when($this->filterAuthorName, fn($query) => $query->where('name', 'like', '%' . $this->filterAuthorName . '%'))
            ->get();
    }

    public function updatedFilterAuthorName()
    {
        $this->loadAuthors();
    }

    public function setAuthor($authorId)
    {
        $this->article->author_id = $authorId;

        $this->article->save();

        $this->emit('refresh')->self();
    }

    public function render()
    {
        return view('livewire.employee.article.components.article-author-manager');
    }
}

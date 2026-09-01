<?php

namespace App\Http\Livewire\Employee\Article\Components;

use App\Models\Article;
use App\Models\Tag;
use Livewire\Component;

class ArticleCategoryManager extends Component
{
    public Article $article;

    public $categories = [];

    public $filterCategoryName = '';

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Tag::query()
            ->select('id', 'name')
            ->where('type', 'category')
            ->when($this->filterCategoryName, fn($query) => $query->where('name', 'like', '%' . $this->filterCategoryName . '%'))
            ->get();
    }

    public function updatedFilterCategoryName()
    {
        $this->loadCategories();
    }

    public function getCurrentCategoryProperty()
    {
        return $this->article->tags()->where('type', 'category')->first();
    }

    public function setCategory($categoryName)
    {
        $category = Tag::firstOrCreate([
            'name' => $categoryName,
            'type' => 'category',
        ]);

        // Categoria é única por artigo — remove qualquer outra antes de atribuir a nova.
        $this->article->tags()->wherePivotIn('tag_id', function ($query) {
            $query->select('id')->from('tags')->where('type', 'category');
        })->detach();

        $this->article->tags()->attach($category->id);

        $this->reset('filterCategoryName');
        $this->emit('refresh')->self();
    }

    public function removeCategory()
    {
        $category = $this->currentCategory;

        if (! $category) {
            return;
        }

        $this->article->tags()->detach($category->id);

        $category->loadCount('articles');
        if ($category->articles_count === 0) {
            $category->delete();
        }

        $this->emit('refresh')->self();
    }

    public function render()
    {
        return view('livewire.employee.article.components.article-category-manager');
    }
}
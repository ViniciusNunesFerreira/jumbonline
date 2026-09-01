<?php

namespace App\Http\Livewire\Guest\Blog;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class ArticleList extends Component
{
    use SEOTools;

    public $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function mount()
    {

        $this->seo()->jsonLd()->setType('Blog');
        $this->seo()->jsonLd()->setTitle(trans('Blog'));
        $this->seo()->jsonLd()->addValue('publisher', [
            '@type' => 'Organization',
            'name' => 'Jumbonline',
            'logo' => ['@type' => 'ImageObject', 'url' => asset('img/mascote-logo-mark.png')],
        ]);

        $this->seo()->setTitle(trans('Blog Jumbonline CDP, CPP, CR e Penitenciárias'));

        $this->seo()->setDescription(trans('Blog Jumbonline - Facilitando a entrega de jumbos nas unidades penitenciárias de SP'));
    }

    public function getCategoriesProperty()
    {
        return \App\Models\Tag::where('type', 'category')->withCount('articles')->having('articles_count', '>', 0)->get();
    }

    public function getRowsQueryProperty()
    {
        return \App\Models\Article::with(['media', 'tags'])
        ->published()
        ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
        ->latest();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->simplePaginate(10);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.guest.blog.article-list', [
            'articles' => $this->rows,
            'categories' => $this->categories
        ]);
    }
}

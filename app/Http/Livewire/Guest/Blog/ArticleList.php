<?php

namespace App\Http\Livewire\Guest\Blog;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class ArticleList extends Component
{
    use SEOTools;

    public function mount()
    {
        $this->seo()->setTitle(trans('Blog'));

        $this->seo()->setDescription(trans('Stay up to date with our latest news and blog posts.'));
    }

    public function getRowsQueryProperty()
    {
        return \App\Models\Article::with(['media', 'tags'])->published()->latest();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->simplePaginate(10);
    }

    public function render()
    {
        return view('livewire.guest.blog.article-list', [
            'articles' => $this->rows,
        ]);
    }
}

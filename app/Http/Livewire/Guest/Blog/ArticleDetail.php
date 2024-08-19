<?php

namespace App\Http\Livewire\Guest\Blog;

use App\Models\Article;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class ArticleDetail extends Component
{
    use SEOTools;

    public Article $article;

    public function mount()
    {
        $this->article->load('media');

        $this->seo()->setTitle($this->article->seo_title ?? $this->article->title);

        $this->seo()->setDescription($this->article->seo_description ?? strip_tags($this->article->excerpt));

        $this->seo()->opengraph()->setType('article');

        $this->seo()->opengraph()->addImage($this->article->getFirstMediaUrl('cover'), [
            'height' => 1260,
            'width' => 2400,
            'type' => 'image/jpeg'
        ]);

        $this->seo()->twitter()->addValue('card', 'summary_large_image');
    }

    public function render()
    {
        return view('livewire.guest.blog.article-detail');
    }
}

<?php

namespace App\Http\Livewire\Guest\Blog;

use App\Models\Article;
use App\Models\Tag;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class TagDetail extends Component
{
    use SEOTools;

    public Tag $tag;

    public function mount()
    {
        $this->seo()->setTitle(trans('Tag: :tagName', ['tagName' => $this->tag->name]));

        $this->seo()->setDescription(trans('Articles tagged with :tagName', ['tagName' => $this->tag->name]));
    }

    public function getArticlesProperty()
    {
        return Article::query()
            ->with(['media', 'tags'])
            ->published()
            ->whereHas('tags', function ($query) {
                $query->where('tag_id', $this->tag->id);
            })
            ->latest()
            ->simplePaginate(10);
    }

    public function render()
    {
        return view('livewire.guest.blog.tag-detail', [
            'articles' => $this->articles,
        ]);
    }
}

<?php

namespace App\Http\Livewire\Employee\Article\Components;

use App\Models\Article;
use App\Models\Tag;
use Livewire\Component;

class ArticleTagManager extends Component
{
    public Article $article;

    public $tags = [];

    public $filterTagName = '';

    protected $listeners = ['refresh' => '$refresh'];

    public function loadTags()
    {
        $this->tags = Tag::query()
            ->select('id', 'name')
            ->where('type', 'tag')
            ->when($this->filterTagName, fn($query) => $query->where('name', 'like', '%' . $this->filterTagName . '%'))
            ->get();
    }

    public function updatedFilterTagName()
    {
        $this->loadTags();
    }

    public function setTag($tagName)
    {
        $tag = Tag::firstOrCreate([
            'name' => $tagName,
            'type' => 'tag',
        ]);

        $this->article->tags()->syncWithoutDetaching($tag->id);

        $this->reset('filterTagName');

        $this->emit('refresh')->self();
    }

    public function removeTag($tagId)
    {
        $tag = Tag::query()
            ->where('type', 'tag')
            ->find($tagId);

        if (! $tag) {
            return;
        }

        $this->article->tags()->detach($tag->id);

        $tag->loadCount('articles');

        if ($tag->articles_count === 0) {
            $tag->delete();
        }

        $this->emit('refresh')->self();
    }

    public function toggleTag($tagId)
    {
        $tag = Tag::query()
            ->where('type', 'tag')
            ->find($tagId);
        
        if (! $tag) {
            return;
        }

        $this->article->tags()->toggle($tag->id);

        $tag->loadCount('articles');

        if ($tag->articles_count === 0) {
            $tag->delete();
        }

        $this->emit('refresh')->self();
    }

    public function render()
    {
        return view('livewire.employee.article.components.article-tag-manager');
    }
}

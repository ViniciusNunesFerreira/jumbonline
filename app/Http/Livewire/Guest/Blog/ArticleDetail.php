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
        $this->article->load(['media', 'tags', 'author']);

        $title = $this->article->seo_title ?? $this->article->title;
        $description = $this->article->seo_description ?? strip_tags($this->article->excerpt);
        $image = $this->article->getFirstMediaUrl('cover');

        $this->seo()->setTitle($title);
        $this->seo()->setDescription($description);
        $this->seo()->metatags()->setCanonical(route('guest.blog.articles.detail', $this->article));

        $this->seo()->opengraph()->setType('article');
        $this->seo()->opengraph()->setTitle($title);
        $this->seo()->opengraph()->setDescription($description);
        $this->seo()->opengraph()->addImage($image, ['height' => 1260, 'width' => 2400, 'type' => 'image/jpeg']);
        $this->seo()->opengraph()->addProperty('article:published_time', $this->article->published_at?->toIso8601String());
        $this->seo()->opengraph()->addProperty('article:author', $this->article->author?->name);

        $this->seo()->twitter()->addValue('card', 'summary_large_image');

        $this->seo()->jsonLd()->setType('BlogPosting');
        $this->seo()->jsonLd()->setTitle($title);
        $this->seo()->jsonLd()->setDescription($description);
        $this->seo()->jsonLd()->addImage($image);
        $this->seo()->jsonLd()->addValue('datePublished', $this->article->published_at?->toIso8601String());
        $this->seo()->jsonLd()->addValue('dateModified', $this->article->updated_at->toIso8601String());
        $this->seo()->jsonLd()->addValue('author', [
            '@type' => 'Person',
            'name' => $this->article->author?->name ?? 'Jumbonline',
        ]);
        $this->seo()->jsonLd()->addValue('publisher', [
            '@type' => 'Organization',
            'name' => 'Jumbonline',
            'logo' => ['@type' => 'ImageObject', 'url' => asset('img/mascote-logo-mark.png')],
        ]);
        $this->seo()->jsonLd()->addValue('mainEntityOfPage', [
            '@type' => 'WebPage',
            '@id' => route('guest.blog.articles.detail', $this->article),
        ]);
    }

    public function getCategoryProperty()
    {
        return $this->article->tags->firstWhere('type', 'category');
    }

    public function getTopicsProperty()
    {
        return $this->article->tags->where('type', '!=', 'category');
    }

    public function getReadingTimeProperty()
    {
        $words = str_word_count(strip_tags($this->article->content));
        return max(1, (int) ceil($words / 200));
    }

    public function getRelatedArticlesProperty()
    {
        $tagIds = $this->article->tags->pluck('id');

        if ($tagIds->isEmpty()) {
            return collect();
        }

        return Article::query()
            ->published()
            ->where('id', '!=', $this->article->id)
            ->whereHas('tags', fn($q) => $q->whereIn('tags.id', $tagIds))
            ->with('media')
            ->latest('published_at')
            ->limit(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.guest.blog.article-detail');
    }
}
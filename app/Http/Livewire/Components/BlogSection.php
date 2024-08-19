<?php

namespace App\Http\Livewire\Components;

use App\Models\Article;
use Livewire\Component;

class BlogSection extends Component
{
    public function getArticlesProperty()
    {
        return Article::query()
            ->with(['media'])
            ->limit(3)
            ->published()
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.components.blog-section');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Article;

class BlogFeedController extends Controller
{
    public function index()
    {
        $articles = Article::query()->published()->with('media')->latest('published_at')->limit(20)->get();

        return response()
            ->view('feeds.blog', compact('articles'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
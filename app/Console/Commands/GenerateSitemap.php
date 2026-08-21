<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\PrisonUnit;
use App\Models\Article;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Gera o sitemap.xml a partir dos dados reais do catálogo';

    public function handle(): void
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('guest.welcome'))->setPriority(1.0));

        PrisonUnit::query()->select('slug', 'updated_at')->each(function (PrisonUnit $unit) use ($sitemap) {
            $sitemap->add(
                Url::create(route('guest.products.list', $unit->slug))
                    ->setLastModificationDate($unit->updated_at)
                    ->setPriority(0.9)
            );
        });

        Product::query()->active()->select('slug', 'updated_at')->each(function (Product $product) use ($sitemap) {
            $sitemap->add(
                Url::create(route('guest.products.detail', $product->slug))
                    ->setLastModificationDate($product->updated_at)
                    ->setPriority(0.7)
            );
        });

        Article::query()->select('slug', 'updated_at')->each(function (Article $article) use ($sitemap) {
            $sitemap->add(
                Url::create(route('guest.blog.articles.detail', $article->slug))
                    ->setLastModificationDate($article->updated_at)
                    ->setPriority(0.5)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
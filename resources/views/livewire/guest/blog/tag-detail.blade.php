<div>
    <x-slot:title>{{ $tag->type === 'category' ? $tag->name : "#{$tag->name}" }}</x-slot:title>

    <div class="bg-complement-500 py-14 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="flex items-center gap-1.5 text-sm text-slate-500">
                <a href="{{ route('guest.blog.articles.list') }}" class="hover:text-accent">Blog</a>
                <x-heroicon-s-chevron-right class="h-3.5 w-3.5" />
                <span class="text-primary">{{ $tag->name }}</span>
            </nav>

            <h1 class="mt-3 font-urbanist text-3xl font-bold text-primary sm:text-4xl">
                {{ $tag->type === 'category' ? $tag->name : "Assunto: {$tag->name}" }}
            </h1>

            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($articles as $article)
                    <a href="{{ route('guest.blog.articles.detail', $article) }}" class="group overflow-hidden rounded-3xl border border-secondary bg-white transition-shadow hover:shadow-lg">
                        <div class="aspect-[16/9] overflow-hidden bg-complement-500">
                            @if($article->hasMedia('cover'))
                                <img loading="lazy" src="{{ $article->getFirstMediaUrl('cover') }}" alt="{{ $article->title }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="line-clamp-2 font-urbanist text-base font-semibold text-primary group-hover:text-accent">{{ $article->title }}</h3>
                            <time class="mt-3 block text-xs text-slate-400">{{ $article->published_at->format('d/m/Y') }}</time>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-slate-500">Nenhum artigo encontrado.</p>
                @endforelse
            </div>

            <div class="mt-10">{{ $articles->onEachSide(1)->links('pagination::tailwind') }}</div>
        </div>
    </div>
</div>
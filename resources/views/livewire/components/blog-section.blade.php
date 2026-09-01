<div>
    @if($this->articles->isNotEmpty())
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            @foreach($this->articles as $article)
                <a href="{{ route('guest.blog.articles.detail', $article) }}" class="group overflow-hidden rounded-3xl border border-secondary bg-white transition-shadow hover:shadow-lg">
                    <div class="aspect-[16/9] overflow-hidden bg-complement-500">
                        @if($article->hasMedia('cover'))
                            <img src="{{ $article->getFirstMediaUrl('cover') }}" alt="{{ $article->title }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="line-clamp-2 font-urbanist text-base font-semibold text-primary group-hover:text-accent">{{ $article->title }}</h3>
                        <time class="mt-3 block text-xs text-slate-400">{{ $article->published_at->format('d/m/Y') }}</time>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
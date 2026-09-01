<div>
    <div class="bg-complement-500 py-10 sm:py-14">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">

            <!-- Trilha de navegação -->
            <nav class="flex items-center gap-1.5 text-sm text-slate-500" aria-label="Breadcrumb">
                <a href="{{ route('guest.welcome') }}" class="hover:text-accent">Início</a>
                <x-heroicon-s-chevron-right class="h-3.5 w-3.5" />
                <a href="{{ route('guest.blog.articles.list') }}" class="hover:text-accent">Blog</a>
                @if($this->category)
                    <x-heroicon-s-chevron-right class="h-3.5 w-3.5" />
                    <a href="{{ route('guest.blog.tags.detail', $this->category) }}" class="hover:text-accent">{{ $this->category->name }}</a>
                @endif
            </nav>

            @if($this->category)
                <span class="mt-6 inline-block rounded-full bg-accent/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-accent">
                    {{ $this->category->name }}
                </span>
            @endif

            <h1 class="mt-3 font-urbanist text-3xl font-bold leading-tight text-primary sm:text-4xl">
                {{ $article->title }}
            </h1>

            <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-500">
                @if($article->author)
                    <span class="flex items-center gap-1.5">
                        <x-heroicon-s-user-circle class="h-4 w-4 text-accent" />
                        {{ $article->author->name }}
                    </span>
                @endif
                <time datetime="{{ $article->published_at->format('Y-m-d') }}" class="flex items-center gap-1.5">
                    <x-heroicon-s-calendar class="h-4 w-4 text-accent" />
                    {{ $article->published_at->translatedFormat('d \d\e F \d\e Y') }}
                </time>
                <span class="flex items-center gap-1.5">
                    <x-heroicon-s-clock class="h-4 w-4 text-accent" />
                    {{ $this->readingTime }} min de leitura
                </span>
            </div>

            @if($article->hasMedia('cover'))
                <div class="mt-8 overflow-hidden rounded-3xl border border-secondary">
                    <img
                        loading="lazy"
                        src="{{ $article->getFirstMediaUrl('cover') }}"
                        alt="{{ $article->title }}"
                        class="aspect-[16/9] w-full object-cover"
                    >
                </div>
            @endif

            <article class="prose prose-slate mt-8 max-w-none prose-headings:font-urbanist prose-headings:text-primary prose-a:text-purple prose-a:no-underline hover:prose-a:text-accent prose-strong:text-primary">
                {!! $article->content !!}
            </article>

            @if($this->topics->isNotEmpty())
                <div class="mt-8 flex flex-wrap gap-2 border-t border-secondary pt-6">
                    @foreach($this->topics as $tag)
                        <a href="{{ route('guest.blog.tags.detail', $tag) }}" class="rounded-full border border-secondary px-3 py-1.5 text-xs font-medium text-slate-600 hover:border-accent hover:text-accent">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Compartilhar -->
            <div class="mt-6 flex items-center gap-3 border-t border-secondary pt-6">
                <span class="text-sm font-semibold text-primary">Compartilhar:</span>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . route('guest.blog.articles.detail', $article)) }}" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-complement-500 text-slate-500 hover:bg-accent hover:text-white">
                    <x-heroicon-s-chat-bubble-left-right class="h-4 w-4" />
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('guest.blog.articles.detail', $article)) }}" target="_blank" class="flex h-8 w-8 items-center justify-center rounded-full bg-complement-500 text-slate-500 hover:bg-accent hover:text-white">
                    <x-heroicon-s-share class="h-4 w-4" />
                </a>
            </div>
        </div>
    </div>

    @if($this->relatedArticles->isNotEmpty())
        <div class="bg-white py-12 sm:py-16">
            <div class="mx-auto max-w-5xl px-4 sm:px-6">
                <h2 class="font-urbanist text-xl font-bold text-primary">Continue lendo</h2>
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
                    @foreach($this->relatedArticles as $related)
                        <a href="{{ route('guest.blog.articles.detail', $related) }}" class="group overflow-hidden rounded-2xl border border-secondary bg-white">
                            <div class="aspect-[16/9] overflow-hidden bg-complement-500">
                                <img loading="lazy" src="{{ $related->getFirstMediaUrl('cover') }}" alt="{{ $related->title }}" class="h-full w-full object-cover transition-transform group-hover:scale-105">
                            </div>
                            <div class="p-4">
                                <h3 class="line-clamp-2 font-urbanist text-sm font-semibold text-primary group-hover:text-accent">{{ $related->title }}</h3>
                                <time class="mt-1 block text-xs text-slate-400">{{ $related->published_at->format('d/m/Y') }}</time>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
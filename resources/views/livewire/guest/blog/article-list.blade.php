<div>
    <x-slot:title>{{ __('Blog') }}</x-slot:title>

    <div class="bg-complement-500 py-14 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="text-center">
                <span class="inline-block rounded-full bg-accent/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-accent">Blog Jumbonline</span>
                <h1 class="mt-3 font-urbanist text-3xl font-bold text-primary sm:text-4xl">
                    Dicas, novidades e orientações
                </h1>
                <p class="mx-auto mt-3 max-w-xl text-slate-500">
                    Tudo o que você precisa saber pra facilitar o envio de jumbos e cuidar de quem você ama.
                </p>

                <div class="mx-auto mt-8 max-w-md">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                        </div>
                        <x-input
                            wire:model.debounce.400ms="search"
                            type="text"
                            placeholder="Buscar no blog..."
                            class="w-full !rounded-full !pl-11"
                        />
                    </div>
                </div>

            </div>

            @if($categories->isNotEmpty())
                <div class="mt-8 flex flex-wrap justify-center gap-2">
                    <a href="{{ route('guest.blog.articles.list') }}" @class(['rounded-full px-4 py-2 text-sm font-semibold', 'bg-accent text-white' => !request('categoria'), 'bg-white text-slate-600 border border-secondary hover:border-accent' => request('categoria')])>
                        Tudo
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('guest.blog.tags.detail', $category) }}" class="rounded-full border border-secondary bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:border-accent hover:text-accent">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($articles as $article)
                    <a href="{{ route('guest.blog.articles.detail', $article) }}" class="group overflow-hidden rounded-3xl border border-secondary bg-white transition-shadow hover:shadow-lg">
                        <div class="aspect-[16/9] overflow-hidden bg-complement-500">
                            @if($article->hasMedia('cover'))
                                <img loading="lazy" src="{{ $article->getFirstMediaUrl('cover') }}" alt="{{ $article->title }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @endif
                        </div>
                        <div class="p-5">
                            @php $category = $article->tags->firstWhere('type', 'category'); @endphp
                            @if($category)
                                <span class="text-xs font-bold uppercase tracking-wide text-accent">{{ $category->name }}</span>
                            @endif
                            <h3 class="mt-1.5 line-clamp-2 font-urbanist text-base font-semibold text-primary group-hover:text-accent">
                                {{ $article->title }}
                            </h3>
                            @if($article->displayExcerpt)
                                <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ strip_tags($article->displayExcerpt) }}</p>
                            @endif
                            <time class="mt-3 block text-xs text-slate-400">{{ $article->published_at->format('d/m/Y') }}</time>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10">{{ $articles->onEachSide(1)->links('pagination::tailwind') }}</div>
        </div>
    </div>
</div>
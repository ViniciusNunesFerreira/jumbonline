<div>
    <div class="bg-white py-16 px-6 sm:py-24 sm:px-8">
        <div class="mx-auto max-w-3xl">
            <a
                href="{{ route('guest.blog.articles.list') }}"
                class="btn btn-link"
            >
                <x-heroicon-m-arrow-long-left class="w-5 h-5 -ml-1 mr-2" />
                {{ __('Back to blog') }}
            </a>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                {{ __('Tag: :tagName', ['tagName' => $tag->name]) }}
            </h1>
            <div class="mt-10 space-y-16 border-t border-slate-200 pt-10 sm:mt-16 sm:pt-16">
                @foreach($articles as $article)
                    <article class="flex flex-col items-start justify-between">
                        @if($article->hasMedia('cover'))
                            <div class="relative w-full">
                                <img
                                    src="{{ $article->getFirstMediaUrl('cover') }}"
                                    alt="{{ $article->title }}"
                                    class="aspect-[16/9] w-full rounded-2xl bg-slate-100 object-cover"
                                >
                            </div>
                        @endif
                        <div class="mt-8 flex items-center gap-x-4 text-xs">
                            <time
                                datetime="{{ $article->published_at->format('Y-m-d') }}"
                                class="text-slate-500"
                            >
                                {{ $article->published_at->format('M d, Y') }}
                            </time>
                        </div>
                        <div class="group relative">
                            <h3 class="mt-3 text-lg font-semibold leading-6 text-slate-900 group-hover:underline">
                                <a href="{{ route('guest.blog.articles.detail', $article->slug) }}">
                                    <span class="absolute inset-0"></span>
                                    {{ $article->title }}
                                </a>
                            </h3>
                            @if($article->excerpt)
                                <div class="mt-5 max-w-none prose prose-slate line-clamp-2 dark:prose-dark">
                                    {!! $article->excerpt !!}
                                </div>
                            @endif
                        </div>
                        <div class="mt-8">
                            @foreach($article->tags as $tag)
                                <a href="{{ route('guest.blog.tags.detail', $tag->slug) }}">
                                    <x-badge
                                        size="sm"
                                        class="!rounded-full !ring-0 hover:bg-slate-100"
                                    >
                                        {{ $tag->name }}
                                    </x-badge>
                                </a>
                            @endforeach
                        </div>
                    </article>
                @endforeach

                {{ $articles->links() }}
            </div>
        </div>
    </div>
</div>

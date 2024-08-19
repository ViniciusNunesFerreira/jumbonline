<div>
    <div class="bg-white py-16 px-6 sm:py-24 sm:px-8">
        <div class="mx-auto max-w-3xl text-base leading-7 text-gray-700">
            <a
                href="{{ route('guest.blog.articles.list') }}"
                class="btn btn-link"
            >
                <x-heroicon-m-arrow-long-left class="w-5 h-5 -ml-1 mr-2" />
                {{ __('Back to blog') }}
            </a>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                {{ $article->title }}
            </h1>
            <div class="mt-5 flex items-center gap-x-4 text-xs">
                <time
                    datetime="{{ $article->published_at->format('Y-m-d') }}"
                    class="text-slate-500"
                >
                    {{ $article->published_at->format('M d, Y') }}
                </time>
            </div>
            @if($article->hasMedia('cover'))
                <div class="mt-6 relative w-full">
                    <img
                        src="{{ $article->getFirstMediaUrl('cover') }}"
                        alt="{{ $article->title }}"
                        class="aspect-[16/9] w-full rounded-2xl bg-slate-100 object-cover"
                    >
                </div>
            @endif
            <div class="mt-6 prose prose-slate max-w-none dark:prose-dark">
                {!! $article->content !!}
            </div>
        </div>
    </div>
</div>

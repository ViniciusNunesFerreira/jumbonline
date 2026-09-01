{{-- resources/views/feeds/blog.blade.php --}}
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>Blog Jumbonline</title>
    <link>{{ route('guest.blog.articles.list') }}</link>
    <atom:link href="{{ route('guest.blog.feed') }}" rel="self" type="application/rss+xml" />
    <description>Dicas, novidades e orientações da Jumbonline</description>
    <language>pt-BR</language>
    <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
    @foreach($articles as $article)
    <item>
        <title>{{ $article->title }}</title>
        <link>{{ route('guest.blog.articles.detail', $article) }}</link>
        <guid isPermaLink="true">{{ route('guest.blog.articles.detail', $article) }}</guid>
        <pubDate>{{ $article->published_at->toRssString() }}</pubDate>
        <description><![CDATA[{{ strip_tags($article->excerpt ?? '') }}]]></description>
    </item>
    @endforeach
</channel>
</rss>
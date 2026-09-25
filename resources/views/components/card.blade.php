<div {{ $attributes->merge(['class' => 'bg-white shadow-sm ring-1 ring-slate-200/70 rounded-2xl dark:bg-slate-900 dark:ring-white/10 dark:shadow-inner dark:shadow-xl']) }}>
    @isset($header)
        <div {{ $header->attributes->class(['px-4 py-5 rounded-t-2xl sm:px-6 border-b border-slate-100 dark:border-white/5']) }}>
            {{ $header }}
        </div>
    @endisset
    <div {{ $content->attributes->class(['px-4 py-5 sm:px-6']) }}>
        {{ $content }}
    </div>
    @isset($footer)
        <div {{ $footer->attributes->class(['px-4 py-3 rounded-b-2xl sm:px-6 bg-slate-50/60 dark:bg-white/5']) }}>
            {{ $footer }}
        </div>
    @endisset
</div>
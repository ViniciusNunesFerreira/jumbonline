<div>
    <x-slot:title>
        {!! $page->title !!}
    </x-slot:title>

    <div class="bg-white py-16 px-6 sm:py-24 sm:px-8">
        <div class="mx-auto max-w-3xl text-base leading-7 text-gray-700">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                {{ $page->title }}
            </h1>
            <div class="mt-6 prose prose-slate max-w-none dark:prose-dark">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>

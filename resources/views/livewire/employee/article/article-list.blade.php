<div>
    <!-- Meta title & description -->
    <x-slot:title>
        {{ __('Blog posts') }}
    </x-slot:title>

    <!-- Page title & actions -->
    <div class="px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-medium text-slate-900 sm:truncate dark:text-slate-100">
                {{ __('Blog posts') }}
            </h1>
        </div>
        @if($articles->count())
            <div class="mt-4 flex sm:mt-0 sm:ml-4">
                <button
                    wire:click.prevent="addNewArticle"
                    class="btn btn-primary w-full order-0 sm:order-1 sm:ml-3"
                >
                    {{ __('Create blog post') }}
                </button>
            </div>
        @endif
    </div>

    <!-- Page content -->
    <div class="p-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        @if(!$articles->count() && !$search)
            <x-card>
                <x-slot:content>
                    <div class="max-w-lg mx-auto text-center">
                        <x-heroicon-o-document-text class="mx-auto h-12 w-12 text-slate-400" />

                        <h3 class="mt-2 text-lg font-medium text-slate-900 dark:text-slate-200">
                            {{ __('Write a blog post') }}
                        </h3>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Blog posts are a great way to build a community around your products and your brand.') }}
                        </p>

                        <div class="mt-6">
                            <button
                                wire:click.prevent="addNewArticle"
                                class="btn btn-primary"
                            >
                                {{ __('Create blog post') }}
                            </button>
                        </div>
                    </div>
                </x-slot:content>
            </x-card>
        @else
            <x-card class="overflow-hidden">
                <x-slot:header>
                    <div
                        x-data="{ search: @entangle('search')}"
                        class="relative max-w-sm text-slate-400 focus-within:text-slate-600 dark:focus-within:text-slate-200"
                    >
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                        </div>
                        <x-input
                            wire:model.debounce.500ms="search"
                            type="text"
                            class="placeholder-slate-500 w-full pl-10 sm:text-sm focus:placeholder-slate-400 dark:focus:placeholder-slate-600"
                            ::class="{ 'pr-10' : search }"
                            placeholder="{{ __('Filter blog posts') }}"
                        />
                        <button
                            x-show="search.length"
                            x-on:click="search = ''"
                            type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3"
                        >
                            <x-heroicon-s-x-circle class="w-5 h-5 text-slate-500 hover:text-slate-600 dark:hover:text-slate-400" />
                        </button>
                    </div>
                </x-slot:header>
                <x-slot:content class="-mx-4 -my-5 sm:-mx-6">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <div class="relative overflow-hidden">
                                <div
                                    wire:loading.delay
                                    class="absolute inset-0 z-10 bg-slate-100/50 dark:bg-slate-800/50"
                                >
                                    <div
                                        wire:loading.flex
                                        class="h-full w-screen items-center justify-center sm:w-auto"
                                    >
                                        <div class="m-auto flex items-center space-x-2">
                                            <p class="text-sm dark:text-slate-200">{{ __('Loading blog posts...') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-200/10">
                                    <thead class="border-t border-slate-200 bg-slate-50 dark:border-slate-200/10 dark:bg-slate-800/75">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="relative w-12 px-6 sm:w-16 sm:px-8"
                                            >
                                                <x-input
                                                    wire:model="selectPage"
                                                    type="checkbox"
                                                    class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6"
                                                />
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-4 text-left text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                            >
                                                @unless(count($selected))
                                                    {{ __('Title') }}
                                                @else
                                                    <div class="space-x-0.5">
                                                        <span>{{ trans(':count selected', ['count' => count($selected)]) }}</span>
                                                        <button
                                                            wire:click="$set('showDeleteConfirmationModal', true)"
                                                            class="btn btn-default btn-xs -my-1.5"
                                                        >
                                                            {{ __('Delete') }}
                                                        </button>
                                                        @if($articles->total() > $articles->count())
                                                            <button
                                                                wire:click="$toggle('selectAll')"
                                                                class="btn btn-link"
                                                            >
                                                                {{ $selectAll ? __('Clear selection') : __('Select all :count blog posts', ['count' => $articles->total()]) }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endunless
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-4 text-center text-sm font-semibold tracking-wide text-slate-900 whitespace-nowrap dark:text-slate-200"
                                            >
                                                {{ __('Status') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-200/10">
                                        @forelse($articles as $article)
                                            <tr
                                                wire:loading.class.delay="opacity-50"
                                                class="relative hover:bg-slate-50 dark:hover:bg-slate-800/75"
                                            >
                                                <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                                    @if(in_array($article->id, $selected))
                                                        <div class="absolute inset-y-0 left-0 w-0.5 bg-sky-500 dark:bg-sky-400"></div>
                                                    @endif
                                                    <x-input
                                                        wire:model="selected"
                                                        wire:key="checkbox-{{ $article->id }}"
                                                        type="checkbox"
                                                        value="{{ $article->id }}"
                                                        class="absolute left-4 top-1/2 -mt-2 h-4 w-4 !rounded !shadow-none sm:left-6"
                                                    />
                                                </td>
                                                <td class="relative px-3 py-4 font-medium text-sm text-slate-900 text-left whitespace-nowrap dark:text-slate-200">
                                                    <a
                                                        href="{{ route('employee.articles.detail', $article) }}"
                                                        class="inline-flex items-center truncate hover:text-sky-600 dark:hover:text-sky-400"
                                                    >
                                                        {{ $article->title }}
                                                    </a>
                                                </td>
                                                <td class="relative px-3 py-4 text-sm text-slate-500 text-center whitespace-nowrap dark:text-slate-400">
                                                    @if($article->published_at)
                                                        <x-badge type="success">
                                                            {{ __('Published') }}
                                                        </x-badge>
                                                    @else
                                                        <x-badge type="warning">
                                                            {{ __('Draft') }}
                                                        </x-badge>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td
                                                    class="px-3 py-4 text-sm text-slate-500 text-center whitespace-nowrap dark:text-slate-400"
                                                    colspan="4"
                                                >
                                                    <div class="max-w-lg mx-auto text-center">
                                                        <x-heroicon-o-magnifying-glass class="inline-block w-10 h-10 text-slate-400 dark:text-slate-300" />
                                                        <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-200">
                                                            {{ __('No blog posts found') }}
                                                        </h3>
                                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                            {{ __('Try changing the filters or search term') }}
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-slot:content>
            </x-card>

            <div class="mt-6">
                {{ $articles->links() }}
            </div>

            <x-modal-alert wire:model="showDeleteConfirmationModal">
                <x-slot:title>
                    {{ __('Please confirm your action!') }}
                </x-slot:title>
                <x-slot:content>
                    {{ trans_choice('Are you sure you want to delete :count blog post?|Are you sure you want to delete :count blog posts?', count($selected)) }}
                    {{ __('This action cannot be undone!') }}
                </x-slot:content>
                <x-slot:footer>
                    <button
                        wire:click.prevent="deleteSelected"
                        class="btn btn-danger w-full sm:ml-3 sm:w-auto"
                    >
                        {{ __('Delete') }}
                    </button>
                    <button
                        x-on:click.prevent="show = false"
                        class="mt-3 btn btn-invisible w-full sm:mt-0 sm:w-auto"
                    >
                        {{ __('Cancel') }}
                    </button>
                </x-slot:footer>
            </x-modal-alert>
        @endif
    </div>

    <form wire:submit.prevent="saveNewArticle">
        <x-modal-dialog wire:model="addingNewArticle">
            <x-slot:title>
                {{ __('Add new blog post') }}
            </x-slot:title>
            <x-slot:content>
                <div class="space-y-6">
                    <div>
                        <x-input-label
                            for="newArticleTitleInput"
                            :value="__('Title')"
                        />
                        <x-input
                            wire:model.defer="newArticle.title"
                            type="text"
                            id="newArticleTitleInput"
                            class="block w-full mt-1 sm:text-sm"
                            placeholder="{{ __('Eg: Blog about your latest products or deals') }}"
                            autofocus
                        />
                        <x-input-error
                            for="newArticle.title"
                            class="mt-2"
                        />
                    </div>
                </div>
            </x-slot:content>
            <x-slot:footer>
                <div class="flex flex-shrink-0 justify-end">
                    <button
                        x-on:click="show = false"
                        type="button"
                        class="btn btn-invisible"
                    >
                        {{ __('Cancel') }}
                    </button>
                    <button
                        type="submit"
                        class="ml-4 btn btn-primary gap-x-2"
                    >
                        {{ __('Continue') }}
                        <x-heroicon-o-arrow-small-right class="-mr-0.5 w-5 h-5" />
                    </button>
                </div>
            </x-slot:footer>
        </x-modal-dialog>
    </form>
</div>

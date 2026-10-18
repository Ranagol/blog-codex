<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('My Posts') }}</h2>
            <a href="{{ route('posts.create') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white">{{ __('Write a post') }}</a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @include('posts.partials.list', ['showOwnerActions' => true, 'emptyMessage' => __('You have not written any posts yet.')])
            <div>{{ $posts->links() }}</div>
        </div>
    </div>
</x-app-layout>

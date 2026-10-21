<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div><p class="section-kicker">{{ __('Your writing desk') }}</p><h2>{{ __('My Posts') }}</h2></div>
            <a href="{{ route('posts.create') }}" class="button-primary">{{ __('Write a post') }} <span aria-hidden="true">↗</span></a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8 page-container journal-page">
            <div class="posts-list">@include('posts.partials.list', ['showOwnerActions' => true, 'emptyMessage' => __('You have not written any posts yet.')])</div>
            <div>{{ $posts->links() }}</div>
        </div>
    </div>
</x-app-layout>

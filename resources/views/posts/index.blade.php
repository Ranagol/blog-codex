<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4 posts-index-heading">
            <div><p class="section-kicker">{{ __('Ideas in good company') }}</p><h2>{{ __('The Journal') }}</h2></div>
            @auth
                <a href="{{ route('posts.create') }}" class="button-primary">{{ __('Write a post') }} <span aria-hidden="true">↗</span></a>
            @endauth
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8 page-container journal-page">
            @if (session('status'))
                <p class="status-message">{{ session('status') }}</p>
            @endif

            <div class="posts-list">@include('posts.partials.list', ['showOwnerActions' => false])</div>

            <div>{{ $posts->links() }}</div>
        </div>
    </div>
</x-app-layout>

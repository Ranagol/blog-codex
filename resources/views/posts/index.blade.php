<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Posts') }}</h2>
            @auth
                <a href="{{ route('posts.create') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white">{{ __('Write a post') }}</a>
            @endauth
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <p class="rounded-md bg-green-100 p-4 text-sm text-green-800 dark:bg-green-900 dark:text-green-100">{{ session('status') }}</p>
            @endif

            @include('posts.partials.list', ['showOwnerActions' => false])

            <div>{{ $posts->links() }}</div>
        </div>
    </div>
</x-app-layout>

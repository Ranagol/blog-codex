<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('posts.index') }}" class="text-sm text-gray-600 underline dark:text-gray-400">{{ __('All posts') }}</a>
    </x-slot>

    <div class="py-8 sm:py-12">
        <article class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <p class="rounded-md bg-green-100 p-4 text-sm text-green-800 dark:bg-green-900 dark:text-green-100">{{ session('status') }}</p>
            @endif

            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800 sm:p-10">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="space-y-2">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $post->title }}</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('By :author · :date', ['author' => $post->author->name, 'date' => $post->created_at->format('M j, Y')]) }}
                        </p>
                    </div>

                    @can('update', $post)
                        <a href="{{ route('posts.edit', $post) }}" class="text-sm font-medium text-indigo-700 underline dark:text-indigo-300">{{ __('Edit post') }}</a>
                    @endcan
                </div>

                <div class="mt-8 whitespace-pre-wrap break-words text-gray-800 dark:text-gray-200">{{ $post->content }}</div>

                @can('delete', $post)
                    <form method="POST" action="{{ route('posts.destroy', $post) }}" class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-700" onsubmit="return confirm('{{ __('Delete this post?') }}')">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>{{ __('Delete post') }}</x-danger-button>
                    </form>
                @endcan
            </div>
        </article>
    </div>
</x-app-layout>

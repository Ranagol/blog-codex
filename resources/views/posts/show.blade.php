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

            <section class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800 sm:p-10" aria-labelledby="comments-heading">
                <h2 id="comments-heading" class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Comments') }} ({{ $post->comments->count() }})
                </h2>

                <div class="mt-6 space-y-6">
                    @forelse ($post->comments as $comment)
                        <article id="comment-{{ $comment->id }}" class="border-t border-gray-200 pt-6 first:border-0 first:pt-0 dark:border-gray-700">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('By :author · :date', ['author' => $comment->author->name, 'date' => $comment->created_at->format('M j, Y')]) }}
                                </p>

                                @can('update', $comment)
                                    <a href="{{ route('posts.comments.edit', [$post, $comment]) }}" class="text-sm font-medium text-indigo-700 underline dark:text-indigo-300">{{ __('Edit comment') }}</a>
                                @endcan
                            </div>

                            <p class="mt-3 whitespace-pre-wrap break-words text-gray-800 dark:text-gray-200">{{ $comment->content }}</p>

                            @can('delete', $comment)
                                <form method="POST" action="{{ route('posts.comments.destroy', [$post, $comment]) }}" class="mt-4" onsubmit="return confirm('{{ __('Delete this comment?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button>{{ __('Delete comment') }}</x-danger-button>
                                </form>
                            @endcan
                        </article>
                    @empty
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">{{ __('No comments yet.') }}</p>
                    @endforelse
                </div>

                @auth
                    <form method="POST" action="{{ route('posts.comments.store', $post) }}" class="mt-8 space-y-4 border-t border-gray-200 pt-6 dark:border-gray-700">
                        @csrf
                        <div>
                            <x-input-label for="content" :value="__('Add a comment')" />
                            <textarea id="content" name="content" rows="4" maxlength="500" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" required>{{ old('content') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                        </div>
                        <x-primary-button>{{ __('Post comment') }}</x-primary-button>
                    </form>
                @else
                    <p class="mt-8 border-t border-gray-200 pt-6 text-sm text-gray-600 dark:border-gray-700 dark:text-gray-400">
                        <a href="{{ route('login') }}" class="underline">{{ __('Log in') }}</a> {{ __('to leave a comment.') }}
                    </p>
                @endauth
            </section>
        </article>
    </div>
</x-app-layout>

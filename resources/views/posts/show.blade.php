<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('posts.index') }}" class="back-link">← {{ __('All posts') }}</a>
    </x-slot>

    <div class="py-8 sm:py-12">
        <article class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8 page-container post-detail">
            @if (session('status'))
                <p class="status-message">{{ session('status') }}</p>
            @endif

            <div class="post-sheet">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="post-heading-copy">
                        <p class="section-kicker">{{ __('From the journal') }}</p>
                        <h1>{{ $post->title }}</h1>
                        <p class="post-byline">
                            {{ __('By :author · :date', ['author' => $post->author->name, 'date' => $post->created_at->format('M j, Y')]) }}
                        </p>
                    </div>

                    @can('update', $post)
                        <a href="{{ route('posts.edit', $post) }}" class="text-sm font-medium text-accent-link">{{ __('Edit post') }}</a>
                    @endcan
                </div>

                <div class="post-content">{{ $post->content }}</div>

                @can('delete', $post)
                    <form method="POST" action="{{ route('posts.destroy', $post) }}" class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-700" onsubmit="return confirm('{{ __('Delete this post?') }}')">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>{{ __('Delete post') }}</x-danger-button>
                    </form>
                @endcan
            </div>

            <section class="comments-sheet" aria-labelledby="comments-heading">
                <p class="section-kicker">{{ __('Join the conversation') }}</p>
                <h2 id="comments-heading">
                    {{ __('Comments') }} ({{ $post->comments->count() }})
                </h2>

                <div class="mt-6 space-y-6">
                    @forelse ($post->comments as $comment)
                        <article id="comment-{{ $comment->id }}" class="comment-entry">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <p class="post-byline">
                                    {{ __('By :author · :date', ['author' => $comment->author->name, 'date' => $comment->created_at->format('M j, Y')]) }}
                                </p>

                                @can('update', $comment)
                                    <a href="{{ route('posts.comments.edit', [$post, $comment]) }}" class="text-sm font-medium text-accent-link">{{ __('Edit comment') }}</a>
                                @endcan
                            </div>

                            <p class="comment-content">{{ $comment->content }}</p>

                            @can('delete', $comment)
                                <form method="POST" action="{{ route('posts.comments.destroy', [$post, $comment]) }}" class="mt-4" onsubmit="return confirm('{{ __('Delete this comment?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button>{{ __('Delete comment') }}</x-danger-button>
                                </form>
                            @endcan
                        </article>
                    @empty
                        <p class="empty-state">{{ __('No comments yet.') }}</p>
                    @endforelse
                </div>

                @auth
                    <form method="POST" action="{{ route('posts.comments.store', $post) }}" class="comment-form">
                        @csrf
                        <div>
                            <x-input-label for="content" :value="__('Add a comment')" />
                            <textarea id="content" name="content" rows="4" maxlength="500" class="field-control" required>{{ old('content') }}</textarea>
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

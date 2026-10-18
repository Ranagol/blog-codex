@forelse ($posts as $post)
    <article class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0 flex-1">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    <a class="hover:underline" href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('By :author · :date', ['author' => $post->author->name, 'date' => $post->created_at->format('M j, Y')]) }}
                </p>
                <p class="mt-4 whitespace-pre-wrap break-words text-gray-700 dark:text-gray-300">{{ Illuminate\Support\Str::limit($post->content, 240) }}</p>
            </div>

            @if ($showOwnerActions)
                <div class="flex shrink-0 items-center gap-4">
                    <a href="{{ route('posts.edit', $post) }}" class="text-sm font-medium text-indigo-700 underline dark:text-indigo-300">{{ __('Edit') }}</a>
                    <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('{{ __('Delete this post?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm font-medium text-red-700 underline dark:text-red-300">{{ __('Delete') }}</button>
                    </form>
                </div>
            @endif
        </div>
    </article>
@empty
    <div class="rounded-lg bg-white p-6 text-gray-700 shadow-sm dark:bg-gray-800 dark:text-gray-300">
        {{ $emptyMessage ?? __('No posts have been published yet.') }}
    </div>
@endforelse

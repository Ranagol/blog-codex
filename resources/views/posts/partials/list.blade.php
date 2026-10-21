@forelse ($posts as $post)
    <article class="post-card">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0 flex-1">
                <h3 class="post-card-title">
                    <a href="{{ route('posts.show', $post) }}">{{ $post->title }} <span class="post-card-arrow" aria-hidden="true">↗</span></a>
                </h3>
                <p class="post-byline">
                    {{ __('By :author · :date', ['author' => $post->author->name, 'date' => $post->created_at->format('M j, Y')]) }}
                </p>
                <p class="post-excerpt">{{ Illuminate\Support\Str::limit($post->content, 240) }}</p>
            </div>

            @if ($showOwnerActions)
                <div class="post-actions">
                    <a href="{{ route('posts.edit', $post) }}" class="text-sm font-medium">{{ __('Edit') }}</a>
                    <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('{{ __('Delete this post?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm font-medium action-delete">{{ __('Delete') }}</button>
                    </form>
                </div>
            @endif
        </div>
    </article>
@empty
    <div class="empty-state">
        {{ $emptyMessage ?? __('No posts have been published yet.') }}
    </div>
@endforelse

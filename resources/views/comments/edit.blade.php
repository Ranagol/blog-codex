<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('posts.show', $post) }}" class="back-link">← {{ __('Back to post') }}</a>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 page-container form-page">
            <div class="form-sheet">
                <p class="section-kicker">{{ __('A note in the conversation') }}</p><h1>{{ __('Edit comment') }}</h1>

                <form method="POST" action="{{ route('posts.comments.update', [$post, $comment]) }}" class="mt-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="content" :value="__('Comment')" />
                        <textarea id="content" name="content" rows="6" maxlength="500" class="field-control" required>{{ old('content', $comment->content) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('content')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Save changes') }}</x-primary-button>
                        <a href="{{ route('posts.show', $post) }}" class="text-sm text-gray-600 underline dark:text-gray-400">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

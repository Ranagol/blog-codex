<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('posts.show', $post) }}" class="text-sm text-gray-600 underline dark:text-gray-400">{{ __('Back to post') }}</a>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800 sm:p-8">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Edit comment') }}</h1>

                <form method="POST" action="{{ route('posts.comments.update', [$post, $comment]) }}" class="mt-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="content" :value="__('Comment')" />
                        <textarea id="content" name="content" rows="6" maxlength="500" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" required>{{ old('content', $comment->content) }}</textarea>
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

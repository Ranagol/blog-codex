<form method="POST" action="{{ $post ? route('posts.update', $post) : route('posts.store') }}" class="space-y-6">
    @csrf
    @if ($post)
        @method('PUT')
    @endif

    <div>
        <x-input-label for="title" :value="__('Title')" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $post?->title)" required maxlength="255" autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="content" :value="__('Content')" />
        <textarea id="content" name="content" rows="14" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" required>{{ old('content', $post?->content) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('content')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ $post ? __('Save changes') : __('Publish post') }}</x-primary-button>
        <a href="{{ $post ? route('posts.show', $post) : route('posts.mine') }}" class="text-sm text-gray-600 underline dark:text-gray-400">{{ __('Cancel') }}</a>
    </div>
</form>

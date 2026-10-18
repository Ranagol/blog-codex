<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Edit post') }}</h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800 sm:p-8">
                @include('posts.partials.form', ['post' => $post])
            </div>
        </div>
    </div>
</x-app-layout>

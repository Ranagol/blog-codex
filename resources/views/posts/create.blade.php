<x-app-layout>
    <x-slot name="header">
        <div><p class="section-kicker">{{ __('Make a little room for an idea') }}</p><h2>{{ __('Write a post') }}</h2></div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 page-container form-page">
            <div class="form-sheet">
                @include('posts.partials.form', ['post' => null])
            </div>
        </div>
    </div>
</x-app-layout>

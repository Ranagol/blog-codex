<x-app-layout>
    <x-slot name="header">
        <div><p class="section-kicker">{{ __('Your corner of the journal') }}</p><h2>
            {{ __('Dashboard') }}
        </h2></div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 page-container dashboard-page">
            <div class="form-sheet dashboard-sheet">
                <div class="dashboard-copy">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

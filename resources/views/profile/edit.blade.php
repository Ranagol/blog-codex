<x-app-layout>
    <x-slot name="header">
        <div><p class="section-kicker">{{ __('Your account') }}</p><h2>
            {{ __('Profile') }}
        </h2></div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 page-container profile-page">
            <div class="form-sheet profile-sheet">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="form-sheet profile-sheet">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="form-sheet profile-sheet account-danger-sheet">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

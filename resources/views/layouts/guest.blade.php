<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased site-body">
        <div class="min-h-screen guest-shell">
            <aside class="guest-brand-panel">
                <a href="{{ route('posts.index') }}" class="guest-wordmark"><x-application-logo class="guest-mark" /><span>The Margin</span></a>
                <div class="guest-brand-copy">
                    <p class="guest-kicker">AN OPEN JOURNAL</p>
                    <p class="guest-manifesto">Ideas worth<br><em>staying with.</em></p>
                    <p class="guest-caption">Stories, notes, and conversations from our community of writers.</p>
                </div>
                <span class="guest-index">INDEPENDENT WRITING &nbsp; / &nbsp; VOL. 01</span>
            </aside>

            <main class="guest-content">
                <div class="guest-card">
                    <div class="guest-heading">
                        <p class="guest-kicker">MEMBER ACCESS</p>
                        <h1 class="auth-title">
                            @if (request()->routeIs('login')) {{ __('Welcome back') }}
                            @elseif (request()->routeIs('register')) {{ __('Join the journal') }}
                            @elseif (request()->routeIs('password.request')) {{ __('Reset your password') }}
                            @elseif (request()->routeIs('password.reset')) {{ __('Choose a new password') }}
                            @elseif (request()->routeIs('password.confirm')) {{ __('Confirm your password') }}
                            @elseif (request()->routeIs('verification.notice')) {{ __('Verify your email') }}
                            @else {{ __('Your account') }}
                            @endif
                        </h1>
                    </div>
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>

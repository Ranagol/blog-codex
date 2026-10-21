<nav x-data="{ open: false }" class="site-nav">
    <div class="site-nav-inner">
        <a href="{{ route('posts.index') }}" class="site-wordmark">
            <x-application-logo class="site-mark" />
            <span>The Margin<span class="wordmark-period">.</span></span>
        </a>

        <div class="site-nav-links">
            <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.index', 'posts.show')">{{ __('Journal') }}</x-nav-link>
            @auth
                <x-nav-link :href="route('posts.mine')" :active="request()->routeIs('posts.mine')">{{ __('My Posts') }}</x-nav-link>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-nav-link>
            @endauth
        </div>

        <div class="site-nav-account">
            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="account-trigger" type="button" aria-label="{{ __('Open account menu') }}">
                            <span class="account-avatar">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg viewBox="0 0 20 20" aria-hidden="true"><path d="m5 7.5 5 5 5-5" /></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <a class="account-login" href="{{ route('login') }}">{{ __('Log in') }}</a>
                <a class="account-join" href="{{ route('register') }}">{{ __('Join the journal') }} <span aria-hidden="true">↗</span></a>
            @endauth
        </div>

        <button class="mobile-menu-toggle" type="button" @click="open = ! open" :aria-expanded="open.toString()" aria-label="{{ __('Toggle navigation') }}">
            <span></span><span></span>
        </button>
    </div>

    <div x-cloak x-show="open" x-transition class="mobile-menu">
        <x-responsive-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.index', 'posts.show')">{{ __('Journal') }}</x-responsive-nav-link>
        @auth
            <x-responsive-nav-link :href="route('posts.mine')" :active="request()->routeIs('posts.mine')">{{ __('My Posts') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
            </form>
        @else
            <x-responsive-nav-link :href="route('login')">{{ __('Log in') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register')">{{ __('Register') }}</x-responsive-nav-link>
        @endauth
    </div>
</nav>

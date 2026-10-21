@props(['active'])

@php
$classes = ($active ?? false) ? 'site-nav-link is-active' : 'site-nav-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

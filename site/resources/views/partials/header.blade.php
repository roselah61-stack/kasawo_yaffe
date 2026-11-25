@php
    $links = [
        ['home', 'Home', 'home'],
        ['story', 'Our Story', 'story'],
        ['explore', 'Explore', 'explore'],
        ['marketplace.index', 'Marketplace', 'marketplace.*'],
        ['community.index', 'Community & Advice', 'community.*'],
    ];
@endphp
<header class="site-header @hasSection('overlay_header') overlay @endif">
    <div class="wrap nav">
        <x-logo :animate="request()->routeIs('home')" />
        <ul class="nav-links" id="navLinks">
            @foreach ($links as [$route, $label, $match])
                <li><a href="{{ route($route) }}" class="{{ request()->routeIs($match) ? 'active' : '' }}">{{ $label }}</a></li>
            @endforeach
        </ul>
        <div class="nav-cta">
            <a href="{{ route('donate.create') }}" class="btn btn-gold btn-sm">♥ Donate</a>
            <a href="{{ route('marketplace.create') }}" class="btn btn-outline btn-sm">List your business</a>
            <button class="menu-btn" aria-label="Open menu" aria-controls="navLinks">☰</button>
        </div>
    </div>
</header>

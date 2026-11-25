<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('title', 'Dashboard') · Kasawo Yaffe Admin</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:400,600,700,700i|inter:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
@php
    $pendingBiz = \App\Models\Business::where('status', 'pending')->count();
    $pendingDon = \App\Models\Donation::where('status', 'pending')->count();
    $nav = [
        ['Overview', [
            ['admin.dashboard', '📊', 'Dashboard', 'admin.dashboard', 0],
        ]],
        ['Community', [
            ['admin.businesses.index', '🏪', 'Businesses', 'admin.businesses.*', $pendingBiz],
            ['admin.donations.index', '💛', 'Donations', 'admin.donations.*', $pendingDon],
            ['admin.subscribers', '✉️', 'Subscribers', 'admin.subscribers*', 0],
        ]],
        ['Content', [
            ['admin.articles.index', '📝', 'Advice articles', 'admin.articles.*', 0],
            ['admin.places.index', '🧭', 'Places to visit', 'admin.places.*', 0],
            ['admin.events', '📅', 'Events', 'admin.events*', 0],
            ['admin.notices', '📌', 'Notice board', 'admin.notices*', 0],
        ]],
    ];
@endphp
<div class="admin-shell">
    <aside class="admin-side" id="adminSide">
        <x-logo :animate="true" />
        @foreach ($nav as [$group, $links])
            <div class="side-label">{{ $group }}</div>
            @foreach ($links as [$route, $icon, $label, $match, $count])
                <a href="{{ route($route) }}" class="side-link {{ request()->routeIs($match) ? 'on' : '' }}">
                    <span class="ic">{{ $icon }}</span>{{ $label }}
                    @if ($count)<span class="count" title="{{ $count }} waiting">{{ $count }}</span>@endif
                </a>
            @endforeach
        @endforeach
        <div class="side-label">Site</div>
        <a href="{{ route('home') }}" target="_blank" class="side-link"><span class="ic">🌍</span>View website ↗</a>

        <div class="side-foot">
            <div class="side-user">
                <span class="avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                <div><b style="color:#fff">{{ auth()->user()->name }}</b><small>{{ auth()->user()->email }}</small></div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="side-link" style="width:100%;background:none;border:0;cursor:pointer;font:inherit"><span class="ic">↩</span>Sign out</button></form>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-top">
            <div style="display:flex;gap:14px;align-items:center">
                <button class="icon-btn admin-menu" aria-label="Menu" onclick="document.getElementById('adminSide').classList.toggle('open')">☰</button>
                <div>
                    <div class="crumb">Kasawo Yaffe admin</div>
                    <h1>@yield('title', 'Dashboard')</h1>
                </div>
            </div>
            <div style="display:flex;gap:10px;align-items:center">@yield('actions')</div>
        </header>
        <div class="admin-content">
            @yield('content')
        </div>
    </div>
</div>

@if (session('status'))
    <div class="toast" role="status"><span style="font-size:1.3rem">✅</span><div>{!! session('status') !!}</div></div>
@endif
</body>
</html>

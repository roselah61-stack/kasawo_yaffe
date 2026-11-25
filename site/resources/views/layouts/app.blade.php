<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') · @endif{{ config('kasawo.name') }}</title>
    <meta name="description" content="@yield('description', 'Kasawo Yaffe is the home of Kasawo online: our history and culture, places to visit, and the local businesses that keep our community going.')">
    <meta property="og:title" content="@yield('title', config('kasawo.name'))">
    <meta property="og:image" content="{{ asset('images/hero-sezibwa.jpg') }}">
    <meta name="theme-color" content="#1f3a2b">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:400,400i,600,700,700i|inter:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@hasSection('overlay_header') has-overlay-header @endif">
    <div class="scroll-progress" aria-hidden="true"></div>
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @if (session('status'))
        <div class="toast" role="status"><span style="font-size:1.4rem">✨</span><div>{!! session('status') !!}</div></div>
    @endif
    <button class="to-top" aria-label="Back to top">↑</button>
</body>
</html>

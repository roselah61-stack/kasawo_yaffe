<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Sign in · Kasawo Yaffe Admin</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://fonts.bunny.net/css?family=fraunces:400,600,700,700i|inter:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
</head>
<body>
<div class="login-wrap">
    <div class="login-art">
        <div class="hero-bg" style="background-image:url('{{ asset('images/hero-sezibwa.jpg') }}')"></div>
        <div class="hero-in">
            <x-logo light :animate="true" style="margin-bottom:22px" />
            <h2>Looking after Kasawo’s home online</h2>
            <p style="color:#e7dfd0">Approve businesses, confirm donations and keep the stories, places and notices up to date.</p>
        </div>
    </div>
    <div class="login-form">
        <form class="login-card form-card hero-in" method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <span class="eyebrow">Admin</span>
            <h1 style="font-size:2rem">Welcome back</h1>
            <p class="muted">Sign in to manage Kasawo Yaffe.</p>
            @if (session('status'))<div class="notice" style="margin:16px 0">{{ session('status') }}</div>@endif
            <div class="field" style="margin-top:18px">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" autofocus class="@error('email') invalid @enderror">
                @error('email')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" autocomplete="current-password" class="@error('password') invalid @enderror">
                @error('password')<span class="error">{{ $message }}</span>@enderror
            </div>
            <label class="check" style="margin-bottom:18px"><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
            <button class="btn btn-primary" style="width:100%;justify-content:center">Sign in →</button>
            <p style="margin:18px 0 0;text-align:center"><a href="{{ route('home') }}" class="muted" style="font-size:.88rem">← Back to the website</a></p>
        </form>
    </div>
</div>
</body>
</html>

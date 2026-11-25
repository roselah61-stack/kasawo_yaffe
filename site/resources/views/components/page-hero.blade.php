@props(['image', 'title', 'crumb' => null, 'height' => null, 'position' => 'center'])
<section class="page-hero" @if ($height) style="min-height:{{ $height }}" @endif>
    <div class="hero-bg" style="background-image:url('{{ asset($image) }}');background-position:{{ $position }}"></div>
    <div class="wrap hero-in">
        <div class="crumbs"><a href="{{ route('home') }}">Home</a> / {{ $crumb ?? $title }}</div>
        <h1>{{ $title }}</h1>
        @if ($slot->isNotEmpty())<p>{{ $slot }}</p>@endif
    </div>
</section>

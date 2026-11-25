@extends('layouts.app')
@section('title', 'Explore')
@section('overlay_header', true)

@section('content')
<x-page-hero image="images/sezibwa-top.jpg" title="Explore Kasawo & around" crumb="Explore" height="540px">Waterfalls and forests, farms you can walk through, and food cooked the way it always has been. All within easy reach of Kampala.</x-page-hero>

<section class="section-sm">
    <div class="wrap">
        <div class="grid g4" data-stagger=".1">
            <div class="info-card"><div class="ico">🚗</div><h3>Getting here</h3><p>About 62 km northeast of Kampala, through Mukono. Taxis leave from Kampala and Mukono.</p></div>
            <div class="info-card"><div class="ico">☀️</div><h3>When to visit</h3><p>Dry seasons (Dec–Feb, Jun–Aug) are best for falls and farm walks.</p></div>
            <div class="info-card"><div class="ico">🛏️</div><h3>Where to stay</h3><p>Guesthouses and village homestays. <a href="{{ route('marketplace.index', ['category' => 'lodging']) }}" style="color:var(--bark);font-weight:600">See lodging →</a></p></div>
            <div class="info-card"><div class="ico">🧭</div><h3>Local guides</h3><p>Book a trusted local guide for cultural and nature tours.</p></div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:24px" id="places">
    <div class="wrap">
        <div class="sec-head" data-reveal>
            <div><span class="eyebrow">Places</span><h2>Things to see &amp; do</h2></div>
            <div class="chips">
                <a href="{{ route('explore') }}#places" class="chip {{ $type ? '' : 'on' }}">All</a>
                @foreach ($types as $t)
                    <a href="{{ route('explore', ['type' => $t]) }}#places" class="chip {{ $type === $t ? 'on' : '' }}">{{ $t }}</a>
                @endforeach
            </div>
        </div>

        @if ($featured && ! $type)
            <div class="feature-ad" style="background:var(--bark)" data-reveal="zoom">
                <div><img src="{{ $featured->imageUrl() }}" alt="{{ $featured->name }}"></div>
                <div>
                    <span class="eyebrow" style="color:#f3d48a">Top pick · Mukono District</span>
                    <h3>{{ $featured->name }}</h3>
                    <p style="color:#f1dcc9">{{ $featured->description ?: $featured->summary }}</p>
                    <div style="display:flex;gap:20px;font-size:.9rem;margin:6px 0 22px;color:#f7e6d4;flex-wrap:wrap">
                        @if ($featured->duration)<span>⏱ {{ $featured->duration }}</span>@endif
                        @if ($featured->meta)<span>{{ $featured->meta }}</span>@endif
                    </div>
                    <div><a href="{{ route('marketplace.index', ['q' => 'guide']) }}" class="btn btn-light btn-sm">Find a local guide</a></div>
                </div>
            </div>
        @endif

        @if ($places->isEmpty())
            <div class="empty"><div class="big">🧭</div><h3>No places here yet</h3><p class="muted">Try another category.</p></div>
        @else
            <div class="grid g3" data-stagger=".1">
                @foreach ($places as $place)
                    <div class="card">
                        <div class="card-img"><img src="{{ $place->imageUrl() }}" alt="{{ $place->name }}" loading="lazy"><span class="badge">{{ $place->type }}</span></div>
                        <div class="card-body"><h3>{{ $place->name }}</h3><p>{{ $place->summary }}</p><div class="card-meta">@if ($place->duration)<span>⏱ {{ $place->duration }}</span>@endif @if ($place->meta)<span>{{ $place->meta }}</span>@endif</div></div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="section" id="events" style="background:var(--sand)">
    <div class="wrap split">
        <div data-reveal="left">
            <span class="eyebrow">What's on</span>
            <h2>Upcoming events</h2>
            <p class="lead">Market days, cultural celebrations and community gatherings. Organisers can post their events for free.</p>
            <a href="mailto:{{ config('kasawo.email') }}?subject=Event%20for%20Kasawo%20Yaffe" class="btn btn-leaf">Submit an event</a>
        </div>
        <div style="display:grid;gap:14px" data-stagger=".1">
            @forelse ($events as $event)
                <div class="event">
                    <div class="date"><b>{{ $event->starts_at->format('d') }}</b><span>{{ $event->starts_at->format('M') }}</span></div>
                    <div><h3 style="font-size:1.1rem;margin:0">{{ $event->title }}</h3><small class="muted">{{ $event->location }} · {{ $event->starts_at->format('g:i A') }}</small></div>
                </div>
            @empty
                <div class="empty" style="padding:32px"><p class="muted" style="margin:0">No events listed yet. Check back soon.</p></div>
            @endforelse
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="center" style="margin-bottom:32px" data-reveal><span class="eyebrow">Map</span><h2>Find your way</h2></div>
        <div data-reveal="zoom" style="border-radius:20px;overflow:hidden;box-shadow:var(--shadow-lg);height:420px">
            <iframe title="Map of Kasawo" width="100%" height="100%" style="border:0" loading="lazy"
                src="https://www.openstreetmap.org/export/embed.html?bbox=32.75%2C0.38%2C32.92%2C0.50&amp;layer=mapnik"></iframe>
        </div>
    </div>
</section>

@include('partials.cta-band', [
    'title' => 'Help bring more visitors to Kasawo',
    'text' => 'Our Tourism fund trains local guides and puts up signs at our sites, so more visitors come and spend money here.',
    'href' => route('donate.create', ['fund' => 'tourism']), 'button' => 'Give to Tourism',
])
@endsection

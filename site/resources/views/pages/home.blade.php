@extends('layouts.app')
@section('overlay_header', true)

@section('content')
@php
    $slides = [
        ['images/hero-sezibwa.jpg', 'Sezibwa Falls, Mukono District'],
        ['images/culture-intro.jpg', 'Kwanjula ceremony, Buganda'],
        ['images/pineapple.jpg', 'Pineapple gardens, Central Uganda'],
        ['images/food-leaves.jpg', 'Food steamed in banana leaves'],
    ];
@endphp

<section class="hero">
    @foreach ($slides as $n => [$img, $credit])
        <div class="hero-slide {{ $n === 0 ? 'on' : '' }}" style="background-image:url('{{ asset($img) }}')" data-credit="{{ $credit }}"></div>
    @endforeach
    <div class="wrap hero-in">
        <div class="greet">Tukwaniriza: you are welcome</div>
        <h1>Discover Kasawo. Support Kasawo.</h1>
        <p>Our history and culture, places worth the trip, and the local businesses, farmers and makers who keep our community going.</p>
        <form class="hero-search" method="GET" action="{{ route('marketplace.index') }}">
            <select aria-label="Search in" onchange="this.form.action=this.value">
                <option value="{{ route('marketplace.index') }}">Businesses</option>
                <option value="{{ route('explore') }}">Places</option>
            </select>
            <input name="q" placeholder="Try “tailor”, “pineapples” or “boda”" aria-label="Search">
            <button class="btn btn-primary">Search</button>
        </form>
        <div class="hero-tags">
            <a href="{{ route('marketplace.index', ['category' => 'farm-produce']) }}">🍍 Farm produce</a>
            <a href="{{ route('marketplace.index', ['category' => 'fashion-tailoring']) }}">✂️ Tailoring</a>
            <a href="{{ route('marketplace.index', ['category' => 'transport']) }}">🏍️ Transport</a>
            <a href="{{ route('explore') }}">🌊 Sezibwa Falls</a>
            <a href="{{ route('story') }}">📜 Our history</a>
        </div>
        <div class="hero-dots">
            @foreach ($slides as $n => $s)<button class="{{ $n === 0 ? 'on' : '' }}" aria-label="Show photo {{ $n + 1 }}"></button>@endforeach
        </div>
    </div>
    <span class="photo-credit">{{ $slides[0][1] }}</span>
    <span class="scroll-cue" aria-hidden="true"></span>
</section>

<section class="stats">
    <div class="wrap" data-stagger=".12">
        <div class="stat"><b data-count="{{ $businessCount }}" data-suffix="+">0</b><span>Local businesses listed</span></div>
        <div class="stat"><b data-count="{{ $placeCount }}">0</b><span>Places to visit nearby</span></div>
        <div class="stat"><b data-count="62" data-suffix=" km">0</b><span>From Kampala by road</span></div>
        <div class="stat"><b>1 home</b><span>Kasawo Yaffe: our Kasawo</span></div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="center" style="margin-bottom:48px" data-reveal>
            <span class="eyebrow">Three ways in</span>
            <h2>Everything Kasawo, in one place</h2>
            <p class="lead">Whether you're visiting for the first time, coming home, or looking for someone local to buy from, start here.</p>
        </div>
        <div class="pillars" data-stagger=".14">
            <a href="{{ route('story') }}" class="pillar">
                <img src="{{ asset('images/culture-intro.jpg') }}" alt="Kwanjula introduction ceremony" loading="lazy">
                <div class="pillar-body"><div class="num">01</div><h3>Our Story</h3><p>History, clans and Buganda traditions that shape life in Kasawo today.</p><span class="link-arrow" style="color:#f3d48a">Read the story</span></div>
            </a>
            <a href="{{ route('explore') }}" class="pillar">
                <img src="{{ asset('images/sezibwa-front.jpg') }}" alt="Sezibwa Falls" loading="lazy">
                <div class="pillar-body"><div class="num">02</div><h3>Explore</h3><p>Waterfalls, forests, farms and food. Plan a day trip or a weekend.</p><span class="link-arrow" style="color:#f3d48a">Plan a visit</span></div>
            </a>
            <a href="{{ route('marketplace.index') }}" class="pillar">
                <img src="{{ asset('images/tailor.jpg') }}" alt="A tailor at work" loading="lazy">
                <div class="pillar-body"><div class="num">03</div><h3>Marketplace</h3><p>Find and contact local businesses, services and products directly.</p><span class="link-arrow" style="color:#f3d48a">Browse businesses</span></div>
            </a>
        </div>
    </div>
</section>

<section class="section" style="background:var(--sand)">
    <div class="wrap">
        <div class="sec-head" data-reveal>
            <div><span class="eyebrow">Explore</span><h2>Places worth the trip</h2></div>
            <a href="{{ route('explore') }}" class="link-arrow">See all places</a>
        </div>
        <div class="grid g4" data-stagger=".1">
            @foreach ($places as $place)
                <a class="card" href="{{ route('explore', ['type' => $place->type]) }}">
                    <div class="card-img"><img src="{{ $place->imageUrl() }}" alt="{{ $place->name }}" loading="lazy">@if ($place->is_featured)<span class="badge gold">★ Must see</span>@endif</div>
                    <div class="card-body"><h3>{{ $place->name }}</h3><p>{{ $place->summary }}</p><div class="card-meta"><span>{{ $place->type }}</span>@if ($place->duration)<span>⏱ {{ $place->duration }}</span>@endif</div></div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="sec-head" data-reveal>
            <div><span class="eyebrow">Marketplace</span><h2>Buy local. Hire local.</h2></div>
            <a href="{{ route('marketplace.index') }}" class="link-arrow">Browse all businesses</a>
        </div>
        <div class="grid g3" data-stagger=".12">
            @foreach ($businesses as $business)
                <x-business-card :business="$business" />
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="background:var(--white);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
    <div class="wrap split">
        <div class="split-media" data-reveal="left">
            <img class="main" src="{{ asset('images/gonja.jpg') }}" alt="Roasting gonja" loading="lazy">
            <img class="float" src="{{ asset('images/baskets.jpg') }}" alt="Woven baskets of gifts" loading="lazy">
        </div>
        <div data-reveal="right">
            <span class="eyebrow">Our Story</span>
            <h2>A place shaped by land, family and tradition</h2>
            <p class="lead">Kasawo sits in Kyaggwe, in the heart of Buganda. Its story is told through its clans, its farms, its markets and the elders who remember how it began.</p>
            <ul class="checks">
                <li>How Kasawo got its name, told by our elders</li>
                <li>Clans, totems and Buganda customs</li>
                <li>Then and now: a photo archive of our community</li>
            </ul>
            <a href="{{ route('story') }}" class="btn btn-leaf">Read our story</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="sec-head" data-reveal>
            <div><span class="eyebrow">Community &amp; Advice</span><h2>Learn, share, grow together</h2></div>
            <a href="{{ route('community.index') }}" class="link-arrow">All articles</a>
        </div>
        <div class="grid g2" style="gap:48px">
            <div data-stagger=".12">
                @foreach ($articles as $article)
                    <a class="article" href="{{ route('community.show', $article) }}">
                        <div class="article-thumb"><img src="{{ $article->imageUrl() }}" alt="" loading="lazy"></div>
                        <div><span class="tag {{ $article->topicClass() }}">{{ $article->topic }}</span><h3 style="margin-top:8px">{{ $article->title }}</h3><p>{{ $article->excerpt }}</p><small class="muted">{{ $article->read_minutes }} min read</small></div>
                    </a>
                @endforeach
            </div>
            <div data-stagger=".1">
                @foreach ($notices as $notice)
                    <div class="notice" @if ($notice->is_urgent) style="border-color:var(--bark)" @endif><b>{{ $notice->icon }} {{ $notice->title }}</b><br><small>{{ $notice->detail }}</small></div>
                @endforeach
                <div><a href="{{ route('community.index') }}" class="btn btn-outline btn-sm" style="margin-top:10px">Visit the notice board</a></div>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:var(--leaf);color:var(--cream);position:relative;overflow:hidden">
    <div class="wrap split">
        <div data-reveal="left">
            <span class="eyebrow" style="color:#f3d48a">Support Kasawo</span>
            <h2 style="color:#fff">Help us keep Kasawo’s story alive, and its future growing</h2>
            <p class="lead" style="color:#c9d6cc">Your gift records elders’ stories, trains local guides and gives young traders a start. People and companies can give once or every month, by Mobile Money, card or bank.</p>
            <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:24px">
                <a href="{{ route('donate.create') }}" class="btn btn-gold">♥ Donate now</a>
                <a href="{{ route('donate.create') }}#partners" class="btn btn-ghost-light">Partner as a company</a>
            </div>
        </div>
        <div class="grid g2" data-stagger=".1">
            @foreach (config('kasawo.donations.funds') as $fund)
                <div class="info-card" style="background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.12);color:var(--cream)"><div class="ico" style="background:rgba(255,255,255,.1)">{{ $fund['icon'] }}</div><h3 style="color:#fff">{{ $fund['title'] }}</h3><p style="color:#c9d6cc">{{ $fund['text'] }}</p></div>
            @endforeach
        </div>
    </div>
</section>

@include('partials.cta-band', [
    'title' => 'Do you run a business in Kasawo?',
    'text' => 'Get found by visitors and neighbours. Listing is free, and takes 5 minutes on your phone.',
    'href' => route('marketplace.create'), 'button' => 'List your business free',
    'href2' => route('marketplace.index'), 'button2' => 'See the Marketplace',
])
@endsection

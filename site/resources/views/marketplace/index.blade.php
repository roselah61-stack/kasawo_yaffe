@extends('layouts.app')
@section('title', $category ? $category->name.' · Marketplace' : 'Marketplace')
@section('overlay_header', true)

@section('content')
<x-page-hero image="images/market.jpg" title="Kasawo Marketplace" crumb="Marketplace" height="400px">Local businesses, services and products. Contact them directly by phone or WhatsApp.</x-page-hero>

<section class="section-sm" id="results">
    <form class="wrap dir" method="GET" action="{{ route('marketplace.index') }}#results">
        <aside class="sidebar" data-reveal="left">
            <h4>Categories</h4>
            <ul class="cat-list">
                <li><a href="{{ route('marketplace.index', request()->except(['category', 'page'])) }}#results" class="{{ $category ? '' : 'on' }}">All businesses <small>{{ $total }}</small></a></li>
                @foreach ($categories as $cat)
                    <li><a href="{{ route('marketplace.index', [...request()->except(['category', 'page']), 'category' => $cat->slug]) }}#results" class="{{ $category?->is($cat) ? 'on' : '' }}">{{ $cat->icon }} {{ $cat->name }} <small>{{ $cat->businesses_count }}</small></a></li>
                @endforeach
            </ul>
            @if ($category)<input type="hidden" name="category" value="{{ $category->slug }}">@endif
            <h4>Show only</h4>
            <label class="check"><input type="checkbox" name="verified" value="1" @checked(request()->boolean('verified')) onchange="this.form.submit()"> Verified businesses</label>
            <h4>Type</h4>
            <select class="select" name="type" style="width:100%" onchange="this.form.submit()">
                <option value="">Products &amp; services</option>
                <option value="products" @selected(request('type') === 'products')>Products</option>
                <option value="services" @selected(request('type') === 'services')>Services</option>
            </select>
        </aside>

        <div>
            <div class="toolbar" data-reveal>
                <label class="search-box">🔍<input name="q" value="{{ $search }}" placeholder="Search businesses, products or services"></label>
                <select class="select" name="sort" onchange="this.form.submit()" aria-label="Sort">
                    <option value="">Sort: Recommended</option>
                    <option value="rating" @selected(request('sort') === 'rating')>Top rated</option>
                    <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                </select>
            </div>

            @if ($sponsored)
                <div class="feature-ad" data-reveal="zoom">
                    <a href="{{ route('marketplace.show', $sponsored) }}"><img src="{{ $sponsored->imageUrl() }}" alt="{{ $sponsored->name }}"></a>
                    <div>
                        <span class="eyebrow" style="color:#f3d48a">Sponsored</span>
                        <h3>{{ $sponsored->name }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($sponsored->description, 160) }}</p>
                        <div style="display:flex;gap:10px;flex-wrap:wrap">
                            <a href="{{ route('marketplace.show', $sponsored) }}" class="btn btn-light btn-sm">View business</a>
                            @if ($wa = $sponsored->whatsappUrl())<a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-wa btn-sm">WhatsApp</a>@endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($search !== '' || $category)
                <p class="muted" style="margin-bottom:18px">{{ $businesses->total() }} {{ \Illuminate\Support\Str::plural('result', $businesses->total()) }}@if ($search !== '') for “<b style="color:var(--ink)">{{ $search }}</b>”@endif @if ($category) in <b style="color:var(--ink)">{{ $category->name }}</b>@endif · <a href="{{ route('marketplace.index') }}#results" style="color:var(--bark);font-weight:600">Clear</a></p>
            @endif

            @if ($businesses->isEmpty())
                <div class="empty" data-reveal>
                    <div class="big">🔎</div>
                    <h3>No businesses found</h3>
                    <p class="muted">Try another search, or be the first to list one here.</p>
                    <a href="{{ route('marketplace.create') }}" class="btn btn-primary btn-sm">List your business</a>
                </div>
            @else
                <div class="grid g3" data-stagger=".08">
                    @foreach ($businesses as $business)
                        <x-business-card :business="$business" />
                    @endforeach
                </div>
                {{ $businesses->fragment('results')->links('partials.pagination') }}
            @endif
        </div>
    </form>
</section>

@include('partials.cta-band', [
    'title' => 'Not listed yet?',
    'text' => 'Add your business free. Upgrade to Featured to appear at the top.',
    'href' => route('marketplace.create'), 'button' => 'List your business',
])
@endsection

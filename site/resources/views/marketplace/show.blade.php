@extends('layouts.app')
@section('title', $business->name)
@section('description', $business->tagline)

@section('content')
<div class="profile-cover" style="overflow:hidden">
    <img src="{{ $business->imageUrl() }}" alt="{{ $business->name }}" style="object-position:center 40%;animation:kenburns 20s ease-in-out infinite alternate">
</div>

<section class="wrap">
    <div class="profile-head hero-in">
        <div class="profile-logo">{{ $business->initial() }}</div>
        <div class="info">
            <div class="crumbs" style="color:var(--muted);margin-bottom:6px"><a href="{{ route('marketplace.index') }}">Marketplace</a> / <a href="{{ route('marketplace.index', ['category' => $business->category->slug]) }}">{{ $business->category->name }}</a></div>
            <h1>{{ $business->name }}</h1>
            <div class="card-meta" style="font-size:.92rem">
                @if ($business->reviews_count)<x-stars :rating="$business->rating" /><b>{{ number_format($business->rating, 1) }}</b><span>({{ $business->reviews_count }} reviews)</span>@endif
                @if ($business->is_verified)<span class="verified">Verified business</span>@endif
                @if ($business->location)<span>📍 {{ $business->location }}</span>@endif
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            @if ($wa = $business->whatsappUrl())<a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-wa">WhatsApp</a>@endif
            @if ($tel = $business->telUrl())<a href="{{ $tel }}" class="btn btn-outline">📞 Call</a>@endif
        </div>
    </div>

    <div class="profile">
        <div>
            <div class="panel" data-reveal>
                <h3>About</h3>
                <p>{{ $business->description }}</p>
                <div class="chips">
                    <span class="tag">{{ $business->category->name }}</span>
                    <span class="tag">{{ ucfirst($business->type === 'both' ? 'Products & services' : $business->type) }}</span>
                    @if ($business->payment_methods && str_contains($business->payment_methods, 'MTN'))<span class="tag green">Mobile Money accepted</span>@endif
                </div>
            </div>

            @if ($business->products->isNotEmpty())
                <div class="panel" data-reveal>
                    <h3>Products &amp; prices</h3>
                    @foreach ($business->products as $product)
                        <div class="product" @if ($loop->last) style="border:0" @endif>
                            <img src="{{ $product->imageUrl() }}" alt="" loading="lazy">
                            <div><b>{{ $product->name }}</b>@if ($product->note)<br><small class="muted">{{ $product->note }}</small>@endif</div>
                            @if ($product->price)<span class="price">{{ $product->price_prefix ? $product->price_prefix.' ' : '' }}UGX {{ number_format($product->price) }}</span>@endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if (count($business->gallery ?? []) > 0)
                <div class="panel" data-reveal>
                    <h3>Photos</h3>
                    <div class="gallery" style="grid-auto-rows:150px;grid-template-columns:repeat(3,1fr)">
                        @foreach ($business->gallery as $i => $img)
                            <img class="{{ $i === 0 ? 'wide' : '' }}" src="{{ \App\Support\Media::url($img) }}" alt="" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="panel" data-reveal>
                <h3>Reviews</h3>
                @forelse ($business->reviews as $review)
                    <div class="review" @if ($loop->last) style="border:0" @endif>
                        <div style="display:flex;gap:12px;align-items:center"><div class="avatar">{{ $review->initials() }}</div><div><b>{{ $review->author }}</b> <x-stars :rating="$review->rating" /><br><small class="muted">{{ $review->created_at->diffForHumans() }}</small></div></div>
                        <p style="margin:10px 0 0">{{ $review->body }}</p>
                    </div>
                @empty
                    <p class="muted">No reviews yet.</p>
                @endforelse
            </div>
        </div>

        <aside>
            <div class="panel" data-reveal="right">
                <h3>Contact</h3>
                @if ($business->owner_name)<div class="kv"><span>Owner</span><span>{{ $business->owner_name }}</span></div>@endif
                @if ($business->phone)<div class="kv"><span>Phone</span><a href="{{ $business->telUrl() }}">{{ $business->phone }}</a></div>@endif
                @if ($business->email)<div class="kv"><span>Email</span><a href="mailto:{{ $business->email }}">{{ $business->email }}</a></div>@endif
                @if ($business->payment_methods)<div class="kv"><span>Payment</span><span>{{ $business->payment_methods }}</span></div>@endif
                @if ($business->delivery)<div class="kv"><span>Delivery</span><span>{{ $business->delivery }}</span></div>@endif
                @if ($wa)<a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:16px">Send an enquiry</a>@endif
            </div>
            @if ($business->opening_hours)
                <div class="panel" data-reveal="right">
                    <h3>Opening hours</h3>
                    @foreach ($business->opening_hours as $days => $hours)
                        <div class="kv"><span>{{ $days }}</span><span>{{ $hours }}</span></div>
                    @endforeach
                </div>
            @endif
            @if ($business->location)
                <div class="panel" data-reveal="right">
                    <h3>Location</h3>
                    <div class="map"><div class="pin"></div></div>
                    <p class="muted" style="font-size:.88rem;margin:12px 0 0">{{ $business->location }}</p>
                </div>
            @endif
            <div class="panel" style="background:var(--sand)" data-reveal="right">
                <b>Is this your business?</b>
                <p class="muted" style="font-size:.9rem;margin:6px 0 12px">Contact us to update photos, prices and opening hours.</p>
                <a href="mailto:{{ config('kasawo.email') }}?subject={{ rawurlencode('Claim listing: '.$business->name) }}" class="btn btn-leaf btn-sm">Claim listing</a>
            </div>
        </aside>
    </div>
</section>

@if ($similar->isNotEmpty())
    <section class="section">
        <div class="wrap">
            <div class="sec-head" data-reveal><h2>Similar businesses</h2><a href="{{ route('marketplace.index', ['category' => $business->category->slug]) }}" class="link-arrow">See more</a></div>
            <div class="grid g3" data-stagger=".1">
                @foreach ($similar as $s)<x-business-card :business="$s" />@endforeach
            </div>
        </div>
    </section>
@endif
@endsection

@props(['business'])
<article class="card biz-card">
    <a href="{{ route('marketplace.show', $business) }}" class="card-img">
        <img src="{{ $business->imageUrl() }}" alt="{{ $business->name }}" loading="lazy">
        @if ($business->is_featured)<span class="badge gold">★ Featured</span>@endif
    </a>
    <div class="card-body">
        <div class="biz-top">
            <div>
                <h3><a href="{{ route('marketplace.show', $business) }}">{{ $business->name }}</a></h3>
                <span class="tag {{ $business->category->slug === 'farm-produce' ? 'green' : '' }}">{{ $business->category->name }}</span>
            </div>
        </div>
        <p style="margin-top:10px">{{ $business->tagline }}</p>
        <div class="card-meta">
            @if ($business->reviews_count)
                <x-stars :rating="$business->rating" /><span>({{ $business->reviews_count }})</span>
            @endif
            @if ($business->is_verified)<span class="verified">Verified</span>@endif
        </div>
        <div class="biz-actions">
            <a href="{{ route('marketplace.show', $business) }}" class="btn btn-outline btn-sm">View</a>
            @if ($wa = $business->whatsappUrl())
                <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-wa btn-sm">WhatsApp</a>
            @elseif ($tel = $business->telUrl())
                <a href="{{ $tel }}" class="btn btn-wa btn-sm">Call</a>
            @endif
        </div>
    </div>
</article>

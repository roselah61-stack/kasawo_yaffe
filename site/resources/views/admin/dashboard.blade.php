@extends('layouts.admin')
@section('title', 'Dashboard')
@section('actions')
    <a href="{{ route('admin.articles.create') }}" class="btn btn-outline btn-sm">+ New article</a>
    <a href="{{ route('admin.businesses.index', ['status' => 'pending']) }}" class="btn btn-primary btn-sm">Review businesses</a>
@endsection

@section('content')
@php $pct = $goal > 0 ? min(100, round($stats['raised'] / $goal * 100)) : 0; @endphp
<p class="muted" style="margin-top:-8px">Hello {{ \Illuminate\Support\Str::of(auth()->user()->name)->before(' ') }}, here’s what’s happening on Kasawo Yaffe.</p>

<div class="kpis" data-stagger=".08">
    <a href="{{ route('admin.businesses.index', ['status' => 'pending']) }}" class="kpi {{ $stats['pendingBusinesses'] ? 'alert' : '' }}" style="--kpi:#fbefd5">
        <span class="ico">🏪</span><div class="lbl">Awaiting review</div>
        <div class="val" data-count="{{ $stats['pendingBusinesses'] }}">0</div>
        <div class="sub">{{ $stats['liveBusinesses'] }} businesses live</div>
    </a>
    <a href="{{ route('admin.donations.index', ['status' => 'confirmed']) }}" class="kpi" style="--kpi:#e1efe4">
        <span class="ico">💛</span><div class="lbl">Raised this year</div>
        <div class="val" style="font-size:1.6rem">UGX <span data-count="{{ $stats['raised'] }}">0</span></div>
        <div class="progress" style="margin-top:8px;height:8px"><span data-width="{{ max($pct, 2) }}%"></span></div>
        <div class="sub" style="margin-top:6px">{{ $pct }}% of UGX {{ number_format($goal) }}</div>
    </a>
    <a href="{{ route('admin.donations.index', ['status' => 'pending']) }}" class="kpi {{ $stats['pendingDonations'] ? 'alert' : '' }}" style="--kpi:#f8e2df">
        <span class="ico">⏳</span><div class="lbl">Gifts to confirm</div>
        <div class="val" data-count="{{ $stats['pendingDonations'] }}">0</div>
        <div class="sub">UGX {{ number_format($stats['pendingAmount']) }} promised</div>
    </a>
    <a href="{{ route('admin.subscribers') }}" class="kpi" style="--kpi:#e6ecf6">
        <span class="ico">✉️</span><div class="lbl">Subscribers</div>
        <div class="val" data-count="{{ $stats['subscribers'] }}">0</div>
        <div class="sub">Monthly newsletter</div>
    </a>
</div>

<div class="a-grid">
    <div class="a-card" data-reveal>
        <div class="a-card-head"><h2>Businesses waiting for review</h2><a href="{{ route('admin.businesses.index', ['status' => 'pending']) }}" class="link-arrow" style="font-size:.88rem">See all</a></div>
        @forelse ($pending as $b)
            <div style="display:flex;gap:14px;align-items:center;padding:12px 0;border-bottom:1px solid var(--line)">
                <img src="{{ $b->imageUrl() }}" alt="" style="width:52px;height:52px;border-radius:12px;object-fit:cover">
                <div style="flex:1;min-width:0"><b>{{ $b->name }}</b><br><small class="muted">{{ $b->category->name }} · {{ $b->owner_name }} · {{ $b->created_at->diffForHumans() }}</small></div>
                <form method="POST" action="{{ route('admin.businesses.status', $b->id) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="approved"><button class="btn btn-approve btn-xs">✓ Approve</button></form>
                <a href="{{ route('admin.businesses.edit', $b->id) }}" class="icon-btn" title="Review details">✎</a>
            </div>
        @empty
            <div class="empty" style="padding:36px"><div class="big">🎉</div><b>All caught up</b><p class="muted" style="margin:4px 0 0">No businesses waiting for review.</p></div>
        @endforelse
    </div>

    <div class="a-card" data-reveal>
        <div class="a-card-head"><h2>Confirmed gifts by month</h2><span class="muted" style="font-size:.85rem">UGX</span></div>
        <div class="bars" data-bars>
            @foreach ($chart as $i => $m)
                <div class="bar"><i style="--d:{{ $i * .08 }}s" data-h="{{ max(2, round($m['total'] / $chartMax * 100)) }}%" data-v="UGX {{ number_format($m['total']) }}"></i><span>{{ $m['label'] }}</span></div>
            @endforeach
        </div>
    </div>
</div>

<div class="a-card" style="margin-top:22px" data-reveal>
    <div class="a-card-head"><h2>Latest donations</h2><a href="{{ route('admin.donations.index') }}" class="link-arrow" style="font-size:.88rem">All donations</a></div>
    @include('admin.donations._table', ['donations' => $donations, 'compact' => true])
</div>
@endsection

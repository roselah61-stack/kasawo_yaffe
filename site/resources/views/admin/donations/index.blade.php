@extends('layouts.admin')
@section('title', 'Donations')
@section('actions')
    <a href="{{ route('admin.donations.export', request()->query()) }}" class="btn btn-outline btn-sm">⬇ Export CSV</a>
@endsection

@section('content')
<div class="kpis" style="grid-template-columns:repeat(3,1fr)" data-stagger=".08">
    @foreach (['confirmed' => ['✅', 'Received', '#e1efe4'], 'pending' => ['⏳', 'Waiting for payment', '#fbefd5'], 'failed' => ['✕', 'Not received', '#f8e2df']] as $k => [$icon, $label, $bg])
        <a class="kpi" style="--kpi:{{ $bg }}" href="{{ route('admin.donations.index', ['status' => $k]) }}">
            <span class="ico">{{ $icon }}</span><div class="lbl">{{ $label }}</div>
            <div class="val" style="font-size:1.5rem">UGX <span data-count="{{ $counts[$k]->total ?? 0 }}">0</span></div>
            <div class="sub">{{ $counts[$k]->n ?? 0 }} {{ \Illuminate\Support\Str::plural('gift', $counts[$k]->n ?? 0) }}</div>
        </a>
    @endforeach
</div>

<div class="notice" style="margin-bottom:20px"><b>How to confirm a gift:</b> check that the money arrived in your MoMo, Airtel or bank statement with the matching reference, then click <b>✓ Received</b>. Only received gifts count towards the goal and appear on the Supporters wall.</div>

<div class="a-toolbar">
    <div class="tabs">
        @foreach (['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Received', 'failed' => 'Not received'] as $key => $label)
            <a href="{{ route('admin.donations.index', array_filter(['status' => $key, 'q' => $q])) }}" class="tab {{ ($status ?? '') === $key ? 'on' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
    <form class="a-search" method="GET">
        @if ($status)<input type="hidden" name="status" value="{{ $status }}">@endif
        🔍<input name="q" value="{{ $q }}" placeholder="Reference, name, phone or email">
    </form>
</div>

@include('admin.donations._table', ['donations' => $donations])
{{ $donations->links('partials.pagination') }}
@endsection

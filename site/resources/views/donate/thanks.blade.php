@extends('layouts.app')
@section('title', 'Thank you')

@section('content')
<section class="section" style="background:radial-gradient(ellipse at top, #efe2c8, var(--cream) 60%);min-height:80vh" data-confetti>
    <div class="wrap">
        <div class="receipt hero-in">
            <svg class="check-anim" viewBox="0 0 88 88" aria-hidden="true">
                <circle cx="44" cy="44" r="40" fill="none" stroke="var(--leaf-2)" stroke-width="5"/>
                <path d="M27 45 l12 12 l23 -25" fill="none" stroke="var(--leaf-2)" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="eyebrow">Webale nnyo, {{ \Illuminate\Support\Str::of($donation->name)->before(' ') }}!</span>
            <h1 style="font-size:clamp(2rem,4vw,2.8rem)">Thank you for supporting Kasawo</h1>
            <p class="muted">Your {{ $donation->frequency === 'monthly' ? 'monthly' : '' }} gift of <b style="color:var(--ink)">UGX {{ number_format($donation->amount) }}</b> to <b style="color:var(--ink)">{{ $donation->fundLabel() }}</b> is almost done. Please complete the payment below.</p>

            <div class="muted" style="font-size:.85rem;margin-top:18px">Your reference</div>
            <div class="ref">{{ $donation->reference }}</div>

            <div class="pay-steps">
                <h3 style="font-size:1.1rem;display:flex;gap:10px;align-items:center"><i class="method-dot" style="background:{{ $method['color'] }};{{ $donation->method === 'mtn' ? 'color:#222' : '' }};font-style:normal">{{ strtoupper(substr($donation->method, 0, 3)) }}</i> Pay with {{ $method['label'] }}</h3>
                <p style="margin:0">{{ $instructions }}</p>
                <p class="muted" style="font-size:.85rem;margin:12px 0 0">Amount: <b style="color:var(--ink)">UGX {{ number_format($donation->amount) }}</b> · Reference: <b style="color:var(--ink)">{{ $donation->reference }}</b></p>
            </div>

            <p class="muted" style="font-size:.9rem">Once we receive your payment we’ll confirm it{{ $donation->email ? ' by email' : '' }} and add you to our Supporters wall{{ $donation->is_anonymous ? ' as “A friend of Kasawo”' : '' }}.</p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:24px">
                <a href="{{ route('home') }}" class="btn btn-leaf">Back to home</a>
                <a href="https://wa.me/?text={{ rawurlencode('I just supported Kasawo Yaffe, our community site for Kasawo! Join me: '.route('donate.create')) }}" target="_blank" rel="noopener" class="btn btn-wa">Share on WhatsApp</a>
            </div>
        </div>
    </div>
</section>
@endsection

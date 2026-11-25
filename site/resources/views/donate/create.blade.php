@extends('layouts.app')
@section('title', 'Donate')
@section('description', 'Support Kasawo Yaffe: preserve our heritage, grow tourism and back young entrepreneurs in Kasawo.')
@section('overlay_header', true)

@php
    $fmt = fn ($n) => 'UGX '.number_format($n);
    $selectedFund = old('fund', array_key_exists((string) request('fund'), $cfg['funds']) ? request('fund') : 'general');
    $selectedAmount = old('amount', (string) $cfg['presets'][1]);
@endphp

@section('content')
<section class="page-hero donate-hero" style="min-height:600px">
    <div class="hero-bg" style="background-image:url('{{ asset('images/sezibwa-front.jpg') }}');background-position:center 35%"></div>
    <div class="wrap" style="display:flex;justify-content:space-between;align-items:flex-end;gap:40px;flex-wrap:wrap">
        <div class="hero-in" style="max-width:640px">
            <div class="crumbs"><a href="{{ route('home') }}">Home</a> / Donate</div>
            <h1>Give back to the place that <em>raised us</em></h1>
            <p>Every shilling goes into Kasawo: recording our history, training local guides and helping young people start businesses. Individuals and companies can give once or every month.</p>
        </div>
        <div class="goal-card" data-reveal="right">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:4px">
                <span class="muted" style="font-size:.85rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em">Raised this year</span>
                <span style="font-weight:700;color:var(--bark)">{{ $percent }}%</span>
            </div>
            <div class="raised">UGX <span data-count="{{ $raised }}">0</span></div>
            <div class="progress" style="margin:12px 0 10px"><span data-width="{{ max($percent, 2) }}%"></span></div>
            <div style="display:flex;justify-content:space-between;font-size:.85rem" class="muted">
                <span>Goal: {{ $fmt($cfg['goal']) }}</span>
                <span><b style="color:var(--ink)">{{ $donorCount }}</b> {{ \Illuminate\Support\Str::plural('supporter', $donorCount) }}</span>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap donate-grid">
        <form class="form-card" id="donate-form" method="POST" action="{{ route('donate.store') }}" data-reveal="left" novalidate>
            @csrf
            <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true">
            <h2 style="font-size:1.8rem;margin-bottom:6px">Make a gift</h2>
            <p class="muted" style="margin-bottom:24px">It takes about a minute. You’ll get a reference and simple payment steps.</p>

            @if ($errors->any())
                <div class="notice" style="border-color:#b3261e;margin-bottom:20px"><b>Please check the highlighted fields below.</b></div>
            @endif

            <div class="form-sec">
                <h3><span class="n">1</span> How much would you like to give?</h3>
                <div style="margin-bottom:14px" class="seg" role="radiogroup" aria-label="Frequency">
                    <label><input type="radio" name="frequency" value="once" @checked(old('frequency', 'once') === 'once')><span>One time</span></label>
                    <label><input type="radio" name="frequency" value="monthly" @checked(old('frequency') === 'monthly')><span>♻ Monthly</span></label>
                </div>
                <div class="amount-grid">
                    @foreach ($cfg['presets'] as $p)
                        <label class="pick"><input type="radio" name="amount" value="{{ $p }}" @checked($selectedAmount === (string) $p)><span>{{ number_format($p) }}<small>UGX</small></span></label>
                    @endforeach
                    <label class="pick"><input type="radio" name="amount" value="custom" @checked($selectedAmount === 'custom')><span>Other<small>Your amount</small></span></label>
                </div>
                <div class="field" style="margin:12px 0 0">
                    <div class="custom-amount"><b>UGX</b><input name="custom_amount" inputmode="numeric" value="{{ old('custom_amount') }}" placeholder="Enter another amount" aria-label="Custom amount" class="@error('custom_amount') invalid @enderror"></div>
                    @error('custom_amount')<span class="error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-sec">
                <h3><span class="n">2</span> Where should it go?</h3>
                <div class="fund-grid">
                    @foreach ($cfg['funds'] as $key => $fund)
                        <label class="pick"><input type="radio" name="fund" value="{{ $key }}" @checked($selectedFund === $key)><span style="font-weight:600">{{ $fund['icon'] }} {{ $fund['title'] }}<small>{{ \Illuminate\Support\Str::limit($fund['text'], 70) }}</small></span></label>
                    @endforeach
                </div>
            </div>

            <div class="form-sec">
                <h3><span class="n">3</span> How will you pay?</h3>
                <div class="method-grid">
                    @foreach ($cfg['methods'] as $key => $m)
                        <label class="pick"><input type="radio" name="method" value="{{ $key }}" @checked(old('method', 'mtn') === $key)><span><i class="method-dot" style="background:{{ $m['color'] }};{{ $key === 'mtn' ? 'color:#222' : '' }}">{{ ['mtn' => 'MTN', 'airtel' => 'AIR', 'card' => 'CARD', 'bank' => 'BANK'][$key] ?? '' }}</i>{{ $m['label'] }}</span></label>
                    @endforeach
                </div>
                @error('method')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-sec" style="border:0">
                <h3><span class="n">4</span> Your details</h3>
                <div class="seg" style="margin-bottom:16px" role="radiogroup" aria-label="Giving as">
                    <label><input type="radio" name="donor_type" value="individual" @checked(old('donor_type', 'individual') === 'individual')><span>👤 Individual</span></label>
                    <label><input type="radio" name="donor_type" value="company" @checked(old('donor_type') === 'company')><span>🏢 Company / organisation</span></label>
                </div>
                <div class="field" id="company-field" @if (old('donor_type') !== 'company') hidden @endif>
                    <label for="company_name">Company / organisation name</label>
                    <input id="company_name" name="company_name" value="{{ old('company_name') }}" class="@error('company_name') invalid @enderror">
                    @error('company_name')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="d-name">Full name</label>
                    <input id="d-name" name="name" value="{{ old('name') }}" autocomplete="name" class="@error('name') invalid @enderror">
                    @error('name')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="row2">
                    <div class="field">
                        <label for="d-phone">Phone <span class="muted" style="font-weight:400">(Mobile Money)</span></label>
                        <input id="d-phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="+256 7…" autocomplete="tel" class="@error('phone') invalid @enderror">
                        @error('phone')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="d-email">Email</label>
                        <input id="d-email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" class="@error('email') invalid @enderror">
                        @error('email')<span class="error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="field">
                    <label for="d-msg">A message for Kasawo <span class="muted" style="font-weight:400">(optional)</span></label>
                    <textarea id="d-msg" name="message" style="min-height:80px" placeholder="Why are you giving? We may share it on our Supporters wall.">{{ old('message') }}</textarea>
                </div>
                <label class="check"><input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous'))> Keep my name off the public Supporters wall</label>
            </div>

            <div class="summary-line">
                <div><small style="opacity:.8">You are giving</small><br><span id="donate-freq" style="font-size:.85rem">one-time gift</span></div>
                <b id="donate-total">UGX 0</b>
            </div>
            <button class="btn btn-gold" style="width:100%;justify-content:center;padding:16px;font-size:1.05rem">♥ Continue to payment</button>
            <div class="trust"><span>🔒 Your details stay private</span><span>🧾 Receipt for every gift</span><span>📊 Yearly report on spending</span></div>
        </form>

        <div>
            <div data-reveal="right">
                <span class="eyebrow">Your impact</span>
                <h2 style="font-size:2rem">What your gift can do</h2>
                <p class="muted">Examples of what gifts pay for in Kasawo.</p>
            </div>
            <div class="impact" data-stagger=".1" style="margin-top:20px">
                <div class="impact-item"><span class="amt">UGX 20,000</span><span>Records and transcribes one elder’s story for our history archive.</span></div>
                <div class="impact-item"><span class="amt">UGX 50,000</span><span>Pays for a photo day that gets 5 small businesses online with good pictures.</span></div>
                <div class="impact-item"><span class="amt">UGX 250,000</span><span>Puts up a sign and visitor information at a local heritage site.</span></div>
                <div class="impact-item"><span class="amt">UGX 1,000,000</span><span>Trains a group of young people as certified local tour guides.</span></div>
            </div>
            <div class="panel" style="margin-top:24px;background:var(--sand)" data-reveal>
                <h3>🧾 Where the money goes</h3>
                <p class="muted" style="font-size:.92rem;margin:0">We publish a yearly report showing what came in and what it paid for. Companies can ask for an invoice or a receipt for their records.</p>
            </div>
        </div>
    </div>
</section>

@if ($supporters->isNotEmpty())
    <section class="section-sm" style="background:var(--white);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
        <div class="wrap">
            <div class="center" style="margin-bottom:24px" data-reveal><span class="eyebrow">Supporters wall</span><h2 style="font-size:1.8rem">Webale nnyo to everyone who gives</h2></div>
            <div class="supporter-wall">
                @foreach ([1, 2] as $copy)
                    <div class="supporter-track" @if ($copy === 2) aria-hidden="true" @endif>
                        @foreach ($supporters as $s)
                            <div class="supporter"><span class="avatar" style="width:32px;height:32px;font-size:.8rem">{{ mb_substr($s->displayName(), 0, 1) }}</span><b>{{ $s->displayName() }}</b><span class="muted">· {{ $s->fundLabel() }}</span></div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<section class="section" id="partners" style="background:var(--sand)">
    <div class="wrap">
        <div class="center" style="margin-bottom:48px" data-reveal>
            <span class="eyebrow">For companies &amp; organisations</span>
            <h2>Partner with Kasawo Yaffe</h2>
            <p class="lead">Put your brand behind a community. Yearly partnerships come with visibility across the site and a report on what your support achieved.</p>
        </div>
        <div class="plans" data-stagger=".12">
            @foreach ($cfg['partner_tiers'] as $i => $tier)
                <div class="plan {{ $i === 1 ? 'pop' : '' }}">
                    @if ($i === 1)<span class="eyebrow" style="color:#f3d48a">Most chosen</span>@endif
                    <h3>{{ $tier['name'] }}</h3>
                    <div class="price-big">{{ number_format($tier['amount'] / 1_000_000) }}M <small>UGX / year</small></div>
                    <ul>@foreach ($tier['perks'] as $perk)<li>{{ $perk }}</li>@endforeach</ul>
                    <a href="mailto:{{ config('kasawo.email') }}?subject={{ rawurlencode($tier['name'].' partnership') }}" class="btn {{ $i === 1 ? 'btn-gold' : 'btn-outline' }}" style="justify-content:center">Talk to us</a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

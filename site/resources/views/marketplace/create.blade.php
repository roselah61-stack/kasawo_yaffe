@extends('layouts.app')
@section('title', 'List your business')

@section('content')
<section class="section" style="padding-top:64px">
    <div class="wrap split" style="align-items:start">
        <div style="position:sticky;top:110px" class="hero-in">
            <span class="eyebrow">For businesses</span>
            <h1 style="font-size:clamp(2.2rem,4vw,3.2rem)">Put your business in front of all of Kasawo</h1>
            <p class="lead">Visitors, neighbours and people from Kasawo living away from home search here for what they need. Make sure they find you.</p>
            <ul class="checks">
                <li>Free listing with your photos, prices and opening hours</li>
                <li>Customers contact you directly on WhatsApp or by phone</li>
                <li>A “Verified” badge once we’ve checked your business</li>
                <li>Fill it in on your phone in about 5 minutes</li>
            </ul>
            <div class="split-media" style="max-width:420px"><img class="main" src="{{ asset('images/tailor.jpg') }}" alt="" style="aspect-ratio:16/11"></div>
        </div>

        <form class="form-card" method="POST" action="{{ route('marketplace.store') }}" enctype="multipart/form-data" data-reveal="right" novalidate>
            @csrf
            <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true">
            <h2 style="font-size:1.6rem">Tell us about your business</h2>
            <p class="muted" style="font-size:.9rem">We check every listing before it goes live, usually within 48 hours.</p>

            @if ($errors->any())
                <div class="notice" style="border-color:#b3261e;margin-bottom:20px"><b>Please check the highlighted fields.</b></div>
            @endif

            <div class="field">
                <label for="name">Business name</label>
                <input id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Nakato Fashions" class="@error('name') invalid @enderror">
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="row2">
                <div class="field">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" class="@error('category_id') invalid @enderror">
                        <option value="">Choose…</option>
                        @foreach ($categories as $cat)<option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->icon }} {{ $cat->name }}</option>@endforeach
                    </select>
                    @error('category_id')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="type">You offer</label>
                    <select id="type" name="type">
                        <option value="products" @selected(old('type') === 'products')>Products</option>
                        <option value="services" @selected(old('type') === 'services')>Services</option>
                        <option value="both" @selected(old('type') === 'both')>Both</option>
                    </select>
                </div>
            </div>
            <div class="field">
                <label for="description">Describe what you offer</label>
                <textarea id="description" name="description" placeholder="What do you sell or do? What makes you different?" class="@error('description') invalid @enderror">{{ old('description') }}</textarea>
                @error('description')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="row2">
                <div class="field">
                    <label for="owner_name">Your name</label>
                    <input id="owner_name" name="owner_name" value="{{ old('owner_name') }}" class="@error('owner_name') invalid @enderror">
                    @error('owner_name')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="email">Email <span class="muted" style="font-weight:400">(optional)</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="@error('email') invalid @enderror">
                    @error('email')<span class="error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="row2">
                <div class="field">
                    <label for="phone">Phone</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="+256 7…" class="@error('phone') invalid @enderror">
                    @error('phone')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="whatsapp">WhatsApp <span class="muted" style="font-weight:400">(if different)</span></label>
                    <input id="whatsapp" type="tel" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="+256 7…" class="@error('whatsapp') invalid @enderror">
                    @error('whatsapp')<span class="error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="field">
                <label for="location">Location</label>
                <input id="location" name="location" value="{{ old('location') }}" placeholder="e.g. Opposite the taxi stage, Kasawo Trading Centre" class="@error('location') invalid @enderror">
                @error('location')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label>Photos</label>
                <div class="upload">
                    <input type="file" name="photos[]" accept="image/*" multiple aria-label="Upload photos">
                    📷<br><b>Tap to add up to 10 photos</b><br><small>Your shop, your products or your work · JPG/PNG, up to 5 MB each</small>
                    <div class="upload-preview"></div>
                </div>
                @error('photos')<span class="error">{{ $message }}</span>@enderror
                @error('photos.*')<span class="error">{{ $message }}</span>@enderror
            </div>
            <button class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">Submit my business →</button>
        </form>
    </div>
</section>

<section class="section" id="plans" style="background:var(--sand)">
    <div class="wrap">
        <div class="center" style="margin-bottom:48px" data-reveal>
            <span class="eyebrow">Plans</span>
            <h2>Start free. Grow when you're ready.</h2>
            <p class="lead">Pay by Mobile Money. Featured plans help keep the site running for the community.</p>
        </div>
        <div class="plans" data-stagger=".12">
            <div class="plan"><h3>Basic</h3><div class="price-big">Free</div><ul><li>Business listing</li><li>Up to 10 photos</li><li>Phone &amp; WhatsApp buttons</li><li>Customer reviews</li></ul><a href="#name" class="btn btn-outline" style="justify-content:center">Start free</a></div>
            <div class="plan pop"><span class="eyebrow" style="color:#f3d48a">Most popular</span><h3>Featured</h3><div class="price-big">UGX 20,000 <small>/ month</small></div><ul><li>Everything in Basic</li><li>Top of search &amp; category</li><li>“Featured” badge on your listing</li><li>Price list on your profile</li><li>Shown on the home page</li></ul><a href="mailto:{{ config('kasawo.email') }}?subject=Featured%20listing" class="btn btn-primary" style="justify-content:center">Go Featured</a></div>
            <div class="plan"><h3>Sponsor</h3><div class="price-big">UGX 100,000 <small>/ month</small></div><ul><li>Everything in Featured</li><li>Large sponsored banner</li><li>A story about your business</li><li>Promotion in the monthly newsletter</li></ul><a href="mailto:{{ config('kasawo.email') }}?subject=Sponsor%20listing" class="btn btn-outline" style="justify-content:center">Contact us</a></div>
        </div>
    </div>
</section>
@endsection

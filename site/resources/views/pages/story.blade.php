@extends('layouts.app')
@section('title', 'Our Story')
@section('overlay_header', true)

@section('content')
<x-page-hero image="images/culture-intro.jpg" title="Our Story" height="480px">Kasawo's land, people and traditions, told by those who know them best.</x-page-hero>

<section class="section">
    <div class="wrap split">
        <div data-reveal="left">
            <span class="eyebrow">Where we are</span>
            <h2>In the heart of Kyaggwe, Buganda</h2>
            <p class="lead">Kasawo is a town and sub-county in Mukono District, in Uganda's Central Region, about 62 km northeast of Kampala by road.</p>
            <p>Its life follows the land: banana groves, pineapple and coffee gardens, busy market days and close family ties. This page is where Kasawo's history is being gathered from elders, families and local records.</p>
            <p class="muted" style="font-size:.9rem;border-left:3px solid var(--gold);padding-left:14px"><i>We are still collecting this history. If you know Kasawo’s story, or have old photos, please share them so we can tell it properly.</i></p>
        </div>
        <div class="split-media" data-reveal="right">
            <img class="main" src="{{ asset('images/matooke-garden.jpg') }}" alt="Matooke garden" style="aspect-ratio:4/4.4" loading="lazy">
            <img class="float" src="{{ asset('images/pot.jpg') }}" alt="Traditional water pot" loading="lazy">
        </div>
    </div>
</section>

<section class="section" style="background:var(--white);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
    <div class="wrap">
        <div class="center" data-reveal>
            <span class="eyebrow">Timeline</span>
            <h2>How Kasawo came to be</h2>
            <p class="lead">Milestones to be completed with local elders and records.</p>
        </div>
        <div class="timeline">
            @foreach ([
                ['Long ago', 'The name and the land', 'The story of how Kasawo got its name, and the clans who first settled the hills, as remembered by our elders.', 'sezibwa-rocks.jpg'],
                ['1900s', 'Farms and trade routes', 'Coffee, bananas and later pineapples connect Kasawo to markets in Mukono, Kayunga and Kampala.', 'matooke-bike.jpg'],
                ['1962 →', 'Schools, churches and a growing town', 'Schools, places of worship and the trading centre grow up around the main road.', 'market.jpg'],
                ['Today', 'A community looking ahead', 'Young entrepreneurs, farmers and families building on what came before, and now online too.', 'tailor.jpg'],
            ] as $i => [$year, $title, $text, $img])
                <div class="tl-item">
                    <div class="tl-text" data-reveal="{{ $i % 2 ? 'right' : 'left' }}"><div class="tl-year">{{ $year }}</div><h3>{{ $title }}</h3><p class="muted">{{ $text }}</p></div>
                    <div data-reveal="zoom"><img src="{{ asset('images/'.$img) }}" alt="" loading="lazy"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="center" style="margin-bottom:48px" data-reveal>
            <span class="eyebrow">Culture</span>
            <h2>Buganda traditions we live by</h2>
        </div>
        <div class="grid g3" data-stagger=".12">
            <div class="card"><div class="card-img"><img src="{{ asset('images/baskets.jpg') }}" alt="" loading="lazy"></div><div class="card-body"><h3>Kwanjula</h3><p>The introduction ceremony that brings two families together, with gifts, speeches and celebration.</p></div></div>
            <div class="card"><div class="card-img"><img src="{{ asset('images/food-leaves.jpg') }}" alt="" loading="lazy"></div><div class="card-body"><h3>Food &amp; luwombo</h3><p>Meals steamed in banana leaves, shared together, and the stories told over them.</p></div></div>
            <div class="card"><div class="card-img"><img src="{{ asset('images/kasubi.jpg') }}" alt="" loading="lazy"></div><div class="card-body"><h3>Clans &amp; totems</h3><p>Each clan has its own totem, its own history and its own place in Buganda.</p></div></div>
        </div>
    </div>
</section>

<section class="section-sm" style="background:var(--sand)">
    <div class="wrap center" data-reveal="zoom">
        <p class="quote">“A saying from one of Kasawo’s elders will go here, in Luganda first, with the English beneath.”</p>
        <p class="quote-by">Share a saying from your elders and we’ll feature it here</p>
    </div>
</section>

<section class="section" id="gallery">
    <div class="wrap">
        <div class="sec-head" data-reveal>
            <div><span class="eyebrow">Gallery</span><h2>Then &amp; now</h2></div>
            <a href="mailto:{{ config('kasawo.email') }}?subject=Photos%20for%20Kasawo%20Yaffe" class="btn btn-primary btn-sm">Share your old photos</a>
        </div>
        <div class="gallery" data-stagger=".07">
            <img class="tall" src="{{ asset('images/sezibwa-front.jpg') }}" alt="Sezibwa Falls" loading="lazy">
            <img class="wide" src="{{ asset('images/culture-intro.jpg') }}" alt="Introduction ceremony" loading="lazy">
            <img src="{{ asset('images/gonja.jpg') }}" alt="Roasting gonja" loading="lazy">
            <img src="{{ asset('images/pot.jpg') }}" alt="Water pot" loading="lazy">
            <img src="{{ asset('images/pineapple.jpg') }}" alt="Pineapple farm" loading="lazy">
            <img class="wide" src="{{ asset('images/lake.jpg') }}" alt="Boats on Lake Victoria" loading="lazy">
        </div>
    </div>
</section>

@include('partials.cta-band', [
    'title' => 'Help us preserve Kasawo’s history',
    'text' => 'Share a story, or give to our Heritage fund so we can record elders’ memories and scan old photos.',
    'href' => 'mailto:'.config('kasawo.email').'?subject=A%20story%20about%20Kasawo', 'button' => 'Share a story',
    'href2' => route('donate.create', ['fund' => 'heritage']), 'button2' => 'Give to Heritage',
])
@endsection

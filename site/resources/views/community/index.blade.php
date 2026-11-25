@extends('layouts.app')
@section('title', 'Community & Advice')
@section('overlay_header', true)

@section('content')
<x-page-hero image="images/matooke-garden.jpg" title="Community & Advice" height="400px" position="center 60%">Practical advice on farming, business, health and more, plus the community notice board.</x-page-hero>

<section class="section-sm" id="topics">
    <div class="wrap">
        <div class="chips" data-reveal>
            <a href="{{ route('community.index') }}#topics" class="chip {{ $topic ? '' : 'on' }}">All topics</a>
            @foreach ($topics as $name => $icon)
                <a href="{{ route('community.index', ['topic' => $name]) }}#topics" class="chip {{ $topic === $name ? 'on' : '' }}">{{ $icon }} {{ $name }}</a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        @if ($featured)
            <a class="feature-ad" href="{{ route('community.show', $featured) }}" style="background:var(--leaf)" data-reveal="zoom">
                <div><img src="{{ $featured->imageUrl() }}" alt=""></div>
                <div>
                    <span class="eyebrow" style="color:#f3d48a">Featured guide · {{ $featured->topic }}</span>
                    <h3>{{ $featured->title }}</h3>
                    <p>{{ $featured->excerpt }}</p>
                    <small style="color:#c9d6cc">{{ $featured->read_minutes }} min read · By {{ $featured->author }}</small>
                </div>
            </a>
        @endif

        <div class="profile" style="margin-top:0">
            <div>
                <h2 style="font-size:1.8rem" data-reveal>{{ $topic ? $topic.' advice' : 'Latest advice' }}</h2>
                <div data-stagger=".08">
                    @forelse ($articles as $article)
                        <a class="article" href="{{ route('community.show', $article) }}">
                            <div class="article-thumb"><img src="{{ $article->imageUrl() }}" alt="" loading="lazy"></div>
                            <div><span class="tag {{ $article->topicClass() }}">{{ $article->topic }}</span><h3 style="margin-top:8px">{{ $article->title }}</h3><p>{{ $article->excerpt }}</p><small class="muted">{{ $article->read_minutes }} min read · {{ $article->published_at->format('j M Y') }}</small></div>
                        </a>
                    @empty
                        <div class="empty"><div class="big">📝</div><h3>No articles in this topic yet</h3><p class="muted">Have advice to share? We’d love to hear from you.</p></div>
                    @endforelse
                </div>
            </div>

            <aside>
                <div class="panel" data-reveal="right">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px"><h3 style="margin:0">📌 Notice board</h3><a href="mailto:{{ config('kasawo.email') }}?subject=Notice%20for%20Kasawo%20Yaffe" class="link-arrow" style="font-size:.85rem">Post</a></div>
                    @foreach ($notices as $notice)
                        <div class="notice" @if ($notice->is_urgent) style="border-color:var(--bark)" @endif><b>{{ $notice->icon }} {{ $notice->title }}</b><br><small>{{ $notice->detail }} · {{ $notice->created_at->diffForHumans() }}</small></div>
                    @endforeach
                </div>
                <div class="panel" data-reveal="right" style="background:var(--leaf);color:var(--cream);border-color:var(--leaf)">
                    <h3 style="color:#fff">🌱 Youth &amp; small business fund</h3>
                    <p style="color:#c9d6cc;font-size:.92rem">Your gift pays for free training days, photo shoots for listings and digital skills for young traders.</p>
                    <a href="{{ route('donate.create', ['fund' => 'enterprise']) }}" class="btn btn-gold btn-sm">♥ Support young traders</a>
                </div>
            </aside>
        </div>
    </div>
</section>

@include('partials.cta-band', [
    'title' => 'Do you have advice to share?',
    'text' => 'Teachers, farmers, health workers and business owners: write for Kasawo Yaffe and help your neighbours.',
    'href' => 'mailto:'.config('kasawo.email').'?subject=I%20want%20to%20contribute', 'button' => 'Become a contributor',
])
@endsection

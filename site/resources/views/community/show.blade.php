@extends('layouts.app')
@section('title', $article->title)
@section('description', $article->excerpt)
@section('overlay_header', true)

@section('content')
<x-page-hero :image="$article->image" :title="$article->title" crumb="Community / {{ $article->topic }}" height="480px">{{ $article->excerpt }}</x-page-hero>

<section class="section">
    <div class="wrap article-layout">
        <article class="prose" data-reveal>
            <p class="muted" style="font-size:.9rem">By <b style="color:var(--ink)">{{ $article->author }}</b> · {{ $article->published_at->format('j F Y') }} · {{ $article->read_minutes }} min read</p>
            {!! \Illuminate\Support\Str::markdown($article->body, ['html_input' => 'escape', 'allow_unsafe_links' => false]) !!}
        </article>
        <aside style="position:sticky;top:110px" data-reveal="right">
            <div class="panel">
                <h3>More advice</h3>
                @foreach ($more as $m)
                    <a href="{{ route('community.show', $m) }}" style="display:block;padding:12px 0;border-bottom:1px solid var(--line)">
                        <span class="tag {{ $m->topicClass() }}">{{ $m->topic }}</span>
                        <b style="display:block;margin-top:6px;font-family:var(--serif)">{{ $m->title }}</b>
                    </a>
                @endforeach
                <a href="{{ route('community.index') }}" class="link-arrow" style="display:inline-block;margin-top:14px">All articles</a>
            </div>
        </aside>
    </div>
</section>
@endsection

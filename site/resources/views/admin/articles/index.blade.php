@extends('layouts.admin')
@section('title', 'Advice articles')
@section('actions')<a href="{{ route('admin.articles.create') }}" class="btn btn-primary btn-sm">+ New article</a>@endsection

@section('content')
<div class="table-wrap">
    <table class="a-table">
        <thead><tr><th>Article</th><th>Topic</th><th>Status</th><th>Published</th><th></th></tr></thead>
        <tbody>
        @forelse ($articles as $a)
            <tr>
                <td><div class="who"><img class="thumb" src="{{ $a->imageUrl() }}" alt=""><div><b>{{ $a->title }}@if ($a->is_featured) <span title="Featured" style="color:var(--gold)">★</span>@endif</b><small>{{ \Illuminate\Support\Str::limit($a->excerpt, 70) }}</small></div></div></td>
                <td><span class="tag {{ $a->topicClass() }}">{{ $a->topic }}</span></td>
                <td>@if ($a->published_at)<span class="pill approved">published</span>@else<span class="pill pending">draft</span>@endif</td>
                <td><small class="muted">{{ $a->published_at?->format('j M Y') ?? '—' }}</small></td>
                <td><div class="row-actions">
                    @if ($a->published_at)<a href="{{ route('community.show', $a) }}" target="_blank" class="icon-btn" title="View">↗</a>@endif
                    <a href="{{ route('admin.articles.edit', $a->id) }}" class="icon-btn" title="Edit">✎</a>
                    <form method="POST" action="{{ route('admin.articles.destroy', $a->id) }}" data-confirm="Delete “{{ $a->title }}”?">@csrf @method('DELETE')<button class="icon-btn danger" title="Delete">🗑</button></form>
                </div></td>
            </tr>
        @empty
            <tr><td colspan="5" class="empty-row">No articles yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $articles->links('partials.pagination') }}
@endsection

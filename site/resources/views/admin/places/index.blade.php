@extends('layouts.admin')
@section('title', 'Places to visit')
@section('actions')<a href="{{ route('admin.places.create') }}" class="btn btn-primary btn-sm">+ Add place</a>@endsection

@section('content')
<div class="grid g3" data-stagger=".06">
    @foreach ($places as $p)
        <div class="card">
            <div class="card-img"><img src="{{ $p->imageUrl() }}" alt="" loading="lazy">@if ($p->is_featured)<span class="badge gold">★ Top pick</span>@endif<span class="badge right">{{ $p->type }}</span></div>
            <div class="card-body">
                <h3>{{ $p->name }}</h3><p>{{ $p->summary }}</p>
                <div class="row-actions" style="margin-top:auto;justify-content:space-between;align-items:center">
                    <small class="muted">Order: {{ $p->sort }}</small>
                    <div class="row-actions">
                        <a href="{{ route('admin.places.edit', $p->id) }}" class="btn btn-outline btn-xs">✎ Edit</a>
                        <form method="POST" action="{{ route('admin.places.destroy', $p->id) }}" data-confirm="Remove {{ $p->name }}?">@csrf @method('DELETE')<button class="icon-btn danger" title="Remove">🗑</button></form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

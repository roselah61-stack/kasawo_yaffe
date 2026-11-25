@extends('layouts.admin')
@section('title', 'Notice board')

@section('content')
<div class="form-grid">
    <div class="a-card" data-reveal>
        <div class="a-card-head"><h2>Current notices</h2><small class="muted">The newest 3 show on the home page</small></div>
        <div data-stagger=".06">
            @forelse ($notices as $n)
                <div class="notice" style="display:flex;gap:12px;align-items:center;box-shadow:none;border:1px solid var(--line);border-left:4px solid {{ $n->is_urgent ? 'var(--bark)' : 'var(--gold)' }}">
                    <div style="flex:1"><b>{{ $n->icon }} {{ $n->title }}</b>@if ($n->is_urgent) <span class="pill rejected" style="margin-left:6px">urgent</span>@endif<br><small>{{ $n->detail }} · {{ $n->created_at->diffForHumans() }}</small></div>
                    <a href="{{ route('admin.notices', ['edit' => $n->id]) }}" class="icon-btn" title="Edit">✎</a>
                    <form method="POST" action="{{ route('admin.notices.destroy', $n) }}" data-confirm="Remove this notice?">@csrf @method('DELETE')<button class="icon-btn danger">🗑</button></form>
                </div>
            @empty
                <p class="muted">The notice board is empty.</p>
            @endforelse
        </div>
    </div>

    <form class="a-card" data-reveal="right" method="POST" action="{{ $editing ? route('admin.notices.update', $editing) : route('admin.notices.store') }}" style="position:sticky;top:110px">
        @csrf @if ($editing) @method('PUT') @endif
        <h2 style="margin-bottom:14px">{{ $editing ? 'Edit notice' : 'Post a notice' }}</h2>
        <div class="row2" style="grid-template-columns:80px 1fr">
            <div class="field"><label>Icon</label><input name="icon" value="{{ old('icon', $editing?->icon ?? '📢') }}" style="text-align:center;font-size:1.2rem"></div>
            <div class="field"><label>Title</label><input name="title" value="{{ old('title', $editing?->title) }}" class="@error('title') invalid @enderror">@error('title')<span class="error">{{ $message }}</span>@enderror</div>
        </div>
        <div class="field"><label>Details</label><input name="detail" value="{{ old('detail', $editing?->detail) }}" placeholder="When, where, who to contact" class="@error('detail') invalid @enderror">@error('detail')<span class="error">{{ $message }}</span>@enderror</div>
        <label class="switch"><span>Urgent<small>Highlighted in terracotta</small></span><input type="checkbox" name="is_urgent" value="1" @checked(old('is_urgent', $editing?->is_urgent))></label>
        <div style="display:flex;gap:10px;margin-top:14px">
            @if ($editing)<a href="{{ route('admin.notices') }}" class="btn btn-outline">Cancel</a>@endif
            <button class="btn btn-primary" style="flex:1;justify-content:center">{{ $editing ? 'Save notice' : '📌 Post notice' }}</button>
        </div>
    </form>
</div>
@endsection

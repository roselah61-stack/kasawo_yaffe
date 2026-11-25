@extends('layouts.admin')
@section('title', 'Events')

@section('content')
<div class="form-grid">
    <div>
        <div class="a-card" data-reveal>
            <div class="a-card-head"><h2>Upcoming</h2><a href="{{ route('explore') }}#events" target="_blank" class="link-arrow" style="font-size:.88rem">View on site</a></div>
            <div style="display:grid;gap:12px" data-stagger=".06">
                @forelse ($upcoming as $e)
                    <div class="event" style="box-shadow:none">
                        <div class="date"><b>{{ $e->starts_at->format('d') }}</b><span>{{ $e->starts_at->format('M') }}</span></div>
                        <div style="flex:1"><b>{{ $e->title }}</b><br><small class="muted">{{ $e->location }} · {{ $e->starts_at->format('D, g:i A') }}</small></div>
                        <a href="{{ route('admin.events', ['edit' => $e->id]) }}" class="icon-btn" title="Edit">✎</a>
                        <form method="POST" action="{{ route('admin.events.destroy', $e) }}" data-confirm="Delete {{ $e->title }}?">@csrf @method('DELETE')<button class="icon-btn danger">🗑</button></form>
                    </div>
                @empty
                    <p class="muted">No upcoming events. Add one →</p>
                @endforelse
            </div>
        </div>
        @if ($past->isNotEmpty())
            <div class="a-card" style="margin-top:22px;opacity:.85" data-reveal>
                <h2 style="margin-bottom:12px">Past</h2>
                @foreach ($past as $e)<div class="kv"><span>{{ $e->title }}</span><span>{{ $e->starts_at->format('j M Y') }}</span></div>@endforeach
            </div>
        @endif
    </div>

    <form class="a-card" data-reveal="right" method="POST" action="{{ $editing ? route('admin.events.update', $editing) : route('admin.events.store') }}" style="position:sticky;top:110px">
        @csrf @if ($editing) @method('PUT') @endif
        <h2 style="margin-bottom:14px">{{ $editing ? 'Edit event' : 'Add an event' }}</h2>
        <div class="field"><label>Title</label><input name="title" value="{{ old('title', $editing?->title) }}" class="@error('title') invalid @enderror">@error('title')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label>Date &amp; time</label><input type="datetime-local" name="starts_at" value="{{ old('starts_at', $editing?->starts_at?->format('Y-m-d\TH:i')) }}" class="@error('starts_at') invalid @enderror">@error('starts_at')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label>Where</label><input name="location" value="{{ old('location', $editing?->location) }}" class="@error('location') invalid @enderror">@error('location')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label>Details <span class="muted" style="font-weight:400">(optional)</span></label><textarea name="description" style="min-height:80px">{{ old('description', $editing?->description) }}</textarea></div>
        <div style="display:flex;gap:10px">
            @if ($editing)<a href="{{ route('admin.events') }}" class="btn btn-outline">Cancel</a>@endif
            <button class="btn btn-primary" style="flex:1;justify-content:center">{{ $editing ? 'Save event' : '+ Add event' }}</button>
        </div>
    </form>
</div>
@endsection

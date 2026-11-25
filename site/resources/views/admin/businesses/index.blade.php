@extends('layouts.admin')
@section('title', 'Businesses')
@section('actions')
    <a href="{{ route('marketplace.index') }}" target="_blank" class="btn btn-outline btn-sm">View Marketplace ↗</a>
@endsection

@section('content')
<div class="a-toolbar">
    <div class="tabs">
        @foreach ([null => 'All', 'pending' => 'Pending', 'approved' => 'Live', 'rejected' => 'Rejected'] as $key => $label)
            <a href="{{ route('admin.businesses.index', array_filter(['status' => $key, 'q' => $q])) }}" class="tab {{ $status === ($key ?: null) ? 'on' : '' }}">{{ $label }}<b>{{ $key ? ($counts[$key] ?? 0) : $counts->sum() }}</b></a>
        @endforeach
    </div>
    <form class="a-search" method="GET">
        @if ($status)<input type="hidden" name="status" value="{{ $status }}">@endif
        🔍<input name="q" value="{{ $q }}" placeholder="Search name, owner or phone">
    </form>
</div>

<div class="table-wrap">
    <table class="a-table">
        <thead><tr><th>Business</th><th>Contact</th><th>Status</th><th>Badges</th><th>Added</th><th></th></tr></thead>
        <tbody>
        @forelse ($businesses as $b)
            <tr>
                <td><div class="who"><img class="thumb" src="{{ $b->imageUrl() }}" alt=""><div><b>{{ $b->name }}</b><small>{{ $b->category->icon }} {{ $b->category->name }}</small></div></div></td>
                <td><small>{{ $b->owner_name ?: '—' }}<br><span class="muted">{{ $b->phone }}</span></small></td>
                <td><span class="pill {{ $b->status }}">{{ $b->status === 'approved' ? 'live' : $b->status }}</span></td>
                <td>
                    <div class="row-actions" style="justify-content:flex-start">
                        @foreach (['is_verified' => ['✔', 'Verified'], 'is_featured' => ['★', 'Featured']] as $field => [$icon, $label])
                            <form method="POST" action="{{ route('admin.businesses.toggle', $b->id) }}">@csrf @method('PATCH')<input type="hidden" name="field" value="{{ $field }}"><button class="icon-btn {{ $b->$field ? 'on' : '' }}" title="{{ $b->$field ? 'Remove '.$label : 'Mark '.$label }}">{{ $icon }}</button></form>
                        @endforeach
                    </div>
                </td>
                <td><small class="muted">{{ $b->created_at->format('j M Y') }}</small></td>
                <td>
                    <div class="row-actions">
                        @if ($b->status !== 'approved')
                            <form method="POST" action="{{ route('admin.businesses.status', $b->id) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="approved"><button class="btn btn-approve btn-xs">✓ Approve</button></form>
                        @endif
                        @if ($b->status === 'pending')
                            <form method="POST" action="{{ route('admin.businesses.status', $b->id) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="rejected"><button class="btn btn-reject btn-xs">Reject</button></form>
                        @endif
                        @if ($b->status === 'approved')<a href="{{ route('marketplace.show', $b) }}" target="_blank" class="icon-btn" title="View on site">↗</a>@endif
                        <a href="{{ route('admin.businesses.edit', $b->id) }}" class="icon-btn" title="Edit">✎</a>
                        <form method="POST" action="{{ route('admin.businesses.destroy', $b->id) }}" data-confirm="Delete {{ $b->name }}? This can’t be undone.">@csrf @method('DELETE')<button class="icon-btn danger" title="Delete">🗑</button></form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="empty-row">No businesses here.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $businesses->links('partials.pagination') }}
@endsection

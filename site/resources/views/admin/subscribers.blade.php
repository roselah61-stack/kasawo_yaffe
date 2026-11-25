@extends('layouts.admin')
@section('title', 'Subscribers')
@section('actions')<a href="{{ route('admin.subscribers.export') }}" class="btn btn-outline btn-sm">⬇ Export CSV</a>@endsection

@section('content')
<div class="table-wrap">
    <table class="a-table">
        <thead><tr><th>Email or phone</th><th>Joined</th><th></th></tr></thead>
        <tbody>
        @forelse ($subscribers as $s)
            <tr>
                <td><div class="who"><span class="avatar">{{ str_contains($s->contact, '@') ? '✉' : '☎' }}</span><b>{{ $s->contact }}</b></div></td>
                <td><small class="muted">{{ $s->created_at->format('j M Y') }}</small></td>
                <td><div class="row-actions"><form method="POST" action="{{ route('admin.subscribers.destroy', $s) }}" data-confirm="Remove {{ $s->contact }}?">@csrf @method('DELETE')<button class="icon-btn danger" title="Remove">🗑</button></form></div></td>
            </tr>
        @empty
            <tr><td colspan="3" class="empty-row">No subscribers yet. They sign up in the website footer.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $subscribers->links('partials.pagination') }}
@endsection

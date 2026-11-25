<div class="table-wrap" @isset($compact) style="border:0" @endisset>
    <table class="a-table">
        <thead><tr><th>Donor</th><th>Amount</th><th>Fund</th><th>Method</th><th>Status</th>@unless (isset($compact))<th>Reference</th>@endunless<th></th></tr></thead>
        <tbody>
        @forelse ($donations as $d)
            <tr>
                <td><div class="who"><span class="avatar">{{ mb_substr($d->donor_type === 'company' && $d->company_name ? $d->company_name : $d->name, 0, 1) }}</span><div><b>{{ $d->donor_type === 'company' && $d->company_name ? $d->company_name : $d->name }}</b><small>@if ($d->donor_type === 'company'){{ $d->name }} · @endif{{ $d->phone ?: $d->email }}@if ($d->is_anonymous) · 🙈 anonymous @endif</small></div></div></td>
                <td><b>UGX {{ number_format($d->amount) }}</b>@if ($d->frequency === 'monthly')<br><small class="muted">♻ monthly</small>@endif</td>
                <td><small>{{ $d->fundLabel() }}</small></td>
                <td><small>{{ config("kasawo.donations.methods.{$d->method}.short", $d->method) }}</small></td>
                <td><span class="pill {{ $d->status }}">{{ $d->status }}</span><br><small class="muted">{{ $d->created_at->diffForHumans() }}</small></td>
                @unless (isset($compact))<td><code style="font-size:.82rem">{{ $d->reference }}</code>@if ($d->message)<br><small class="muted" title="{{ $d->message }}">💬 {{ \Illuminate\Support\Str::limit($d->message, 40) }}</small>@endif</td>@endunless
                <td>
                    <div class="row-actions">
                        @if ($d->status !== 'confirmed')
                            <form method="POST" action="{{ route('admin.donations.update', $d->id) }}" data-confirm="Confirm you received UGX {{ number_format($d->amount) }} for {{ $d->reference }}?">@csrf @method('PATCH')<input type="hidden" name="status" value="confirmed"><button class="btn btn-approve btn-xs">✓ Received</button></form>
                        @endif
                        @if ($d->status === 'pending')
                            <form method="POST" action="{{ route('admin.donations.update', $d->id) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="failed"><button class="btn btn-reject btn-xs">Not paid</button></form>
                        @elseif ($d->status !== 'pending')
                            <form method="POST" action="{{ route('admin.donations.update', $d->id) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="pending"><button class="icon-btn" title="Move back to pending">↺</button></form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty-row">No donations yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DonationController extends Controller
{
    use Concerns;

    private function filtered(Request $request): Builder
    {
        $status = in_array($request->query('status'), ['pending', 'confirmed', 'failed'], true) ? $request->query('status') : null;
        $q = trim((string) $request->query('q'));

        return Donation::query()
            ->when($status, fn ($d) => $d->where('status', $status))
            ->when($request->query('fund'), fn ($d, $f) => $d->where('fund', $f))
            ->when($q !== '', fn ($d) => $d->where(fn ($w) => $w->where('reference', 'like', "%{$q}%")
                ->orWhere('name', 'like', "%{$q}%")->orWhere('company_name', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")))
            ->latest();
    }

    public function index(Request $request): View
    {
        return view('admin.donations.index', [
            'donations' => $this->filtered($request)->paginate(20)->withQueryString(),
            'counts' => Donation::selectRaw('status, count(*) as n, sum(amount) as total')->groupBy('status')->get()->keyBy('status'),
            'status' => $request->query('status'),
            'q' => $request->query('q'),
        ]);
    }

    public function update(Request $request, Donation $donation): RedirectResponse
    {
        $status = $request->validate(['status' => ['required', 'in:pending,confirmed,failed']])['status'];
        $donation->update(['status' => $status, 'confirmed_at' => $status === 'confirmed' ? now() : null]);

        $msg = [
            'confirmed' => "Payment confirmed for <b>{$donation->reference}</b>. It now counts towards the goal.",
            'failed' => "<b>{$donation->reference}</b> marked as not received.",
            'pending' => "<b>{$donation->reference}</b> moved back to pending.",
        ][$status];

        return back()->with('status', $msg);
    }

    public function export(Request $request): StreamedResponse
    {
        return $this->csv('kasawo-donations-'.now()->format('Y-m-d').'.csv',
            ['Reference', 'Date', 'Status', 'Donor', 'Company', 'Email', 'Phone', 'Amount (UGX)', 'Frequency', 'Fund', 'Method', 'Anonymous', 'Message'],
            $this->filtered($request)->lazy()->map(fn (Donation $d) => [
                $d->reference, $d->created_at->format('Y-m-d H:i'), $d->status, $d->name, $d->company_name, $d->email, $d->phone,
                $d->amount, $d->frequency, $d->fundLabel(), $d->methodLabel(), $d->is_anonymous ? 'yes' : 'no', $d->message,
            ]));
    }
}

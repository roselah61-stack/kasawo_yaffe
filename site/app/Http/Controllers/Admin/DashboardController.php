<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Donation;
use App\Models\Subscriber;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $months = collect(range(5, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));
        $confirmed = Donation::counted()->where('confirmed_at', '>=', $months->first())->get(['amount', 'confirmed_at']);
        $chart = $months->map(fn ($m) => [
            'label' => $m->format('M'),
            'total' => $confirmed->filter(fn ($d) => $d->confirmed_at->isSameMonth($m))->sum('amount'),
        ]);

        return view('admin.dashboard', [
            'stats' => [
                'pendingBusinesses' => Business::where('status', 'pending')->count(),
                'liveBusinesses' => Business::approved()->count(),
                'raised' => Donation::counted()->sum('amount') + config('kasawo.donations.raised_offset'),
                'pendingDonations' => Donation::where('status', 'pending')->count(),
                'pendingAmount' => Donation::where('status', 'pending')->sum('amount'),
                'subscribers' => Subscriber::count(),
            ],
            'goal' => config('kasawo.donations.goal'),
            'chart' => $chart,
            'chartMax' => max(1, $chart->max('total')),
            'pending' => Business::where('status', 'pending')->with('category')->latest()->take(5)->get(),
            'donations' => Donation::latest()->take(6)->get(),
        ]);
    }
}

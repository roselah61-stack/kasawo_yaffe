<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function create(): View
    {
        $cfg = config('kasawo.donations');
        $raised = Donation::counted()->sum('amount') + $cfg['raised_offset'];

        return view('donate.create', [
            'cfg' => $cfg,
            'raised' => $raised,
            'percent' => $cfg['goal'] > 0 ? min(100, round($raised / $cfg['goal'] * 100)) : 0,
            'donorCount' => Donation::counted()->count(),
            'supporters' => Donation::counted()->latest('confirmed_at')->take(16)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $cfg = config('kasawo.donations');

        $amount = $request->input('amount') === 'custom'
            ? (int) preg_replace('/\D/', '', (string) $request->input('custom_amount', ''))
            : (int) $request->input('amount');

        $data = $request->validate([
            'amount' => ['required', Rule::in([...array_map('strval', $cfg['presets']), 'custom'])],
            'custom_amount' => ['nullable', 'string', 'max:20', function ($attr, $value, $fail) use ($amount) {
                if ($amount < 5_000) {
                    $fail('The smallest gift is UGX 5,000. Thank you!');
                } elseif ($amount > 500_000_000) {
                    $fail('For gifts this large, please contact us directly.');
                }
            }],
            'frequency' => ['required', 'in:once,monthly'],
            'fund' => ['required', Rule::in(array_keys($cfg['funds']))],
            'method' => ['required', Rule::in(array_keys($cfg['methods']))],
            'donor_type' => ['required', 'in:individual,company'],
            'name' => ['required', 'string', 'max:120'],
            'company_name' => ['nullable', 'required_if:donor_type,company', 'string', 'max:160'],
            'email' => ['nullable', 'required_if:method,card', 'required_if:method,bank', 'email', 'max:160'],
            'phone' => ['nullable', 'required_if:method,mtn', 'required_if:method,airtel', 'string', 'max:30', 'regex:/^[+\d\s()-]{9,}$/'],
            'message' => ['nullable', 'string', 'max:500'],
            'is_anonymous' => ['nullable', 'boolean'],
            'website' => ['prohibited'], // honeypot
        ], [
            'company_name.required_if' => 'Please tell us the company name.',
            'email.required_if' => 'We need an email to send your card link or bank invoice.',
            'phone.required_if' => 'Enter the Mobile Money number you will pay from.',
            'phone.regex' => 'Please enter a valid phone number, e.g. +256 772 123 456.',
        ]);

        $donation = Donation::create([
            ...collect($data)->except(['amount', 'custom_amount', 'website'])->all(),
            'amount' => $amount,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'status' => 'pending',
        ]);

        // Hook for an online gateway (Flutterwave / Pesapal / DPO) once merchant keys exist:
        // return redirect()->away(PaymentGateway::checkoutUrl($donation));

        return redirect()->route('donate.thanks', $donation);
    }

    public function thanks(Donation $donation): View
    {
        $method = config("kasawo.donations.methods.{$donation->method}");

        return view('donate.thanks', [
            'donation' => $donation,
            'method' => $method,
            'instructions' => str_replace(':code', (string) ($method['code'] ?? ''), $method['instructions'] ?? ''),
        ]);
    }
}

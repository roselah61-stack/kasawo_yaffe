<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['contact' => ['required', 'string', 'max:160']]);
        Subscriber::firstOrCreate(['contact' => trim($data['contact'])]);

        return back()->with('status', '<b>You’re in!</b> We’ll send Kasawo news once a month.');
    }
}

<?php

/*
|--------------------------------------------------------------------------
| Kasawo Yaffe site settings
|--------------------------------------------------------------------------
| Contact and payment details come from .env so they never live in code.
| Values shown as "set in .env" are placeholders until you fill them in.
*/

return [
    'name' => 'Kasawo Yaffe',
    'tagline' => 'Discover Kasawo. Support Kasawo.',
    'phone' => env('KASAWO_PHONE', '+256 700 000 000'),
    'email' => env('KASAWO_EMAIL', 'hello@kasawoyaffe.com'),
    'whatsapp' => env('KASAWO_WHATSAPP'),

    'donations' => [
        'goal' => (int) env('DONATION_GOAL', 25_000_000), // UGX, this year
        'raised_offset' => (int) env('DONATION_RAISED_OFFSET', 0), // gifts received offline before the site
        'presets' => [20_000, 50_000, 100_000, 250_000, 500_000],

        'funds' => [
            'general' => [
                'title' => 'Where it’s needed most',
                'icon' => '💛',
                'text' => 'Lets us put your gift wherever it helps Kasawo most right now.',
            ],
            'heritage' => [
                'title' => 'Heritage & history',
                'icon' => '📜',
                'text' => 'Recording elders’ stories, scanning old photos and keeping Kasawo’s history for the next generation.',
            ],
            'tourism' => [
                'title' => 'Tourism & guides',
                'icon' => '🧭',
                'text' => 'Training local guides, signposting sites and bringing visitors (and their spending) to Kasawo.',
            ],
            'enterprise' => [
                'title' => 'Youth & small business',
                'icon' => '🌱',
                'text' => 'Free listings, photo days and digital-skills training for young people and small traders.',
            ],
        ],

        'methods' => [
            'mtn' => [
                'label' => 'MTN Mobile Money',
                'short' => 'MTN MoMo',
                'color' => '#ffcb05',
                'instructions' => 'Dial *165*3#, choose “Pay with MoMo Pay”, enter merchant code :code, then enter the amount and use your reference as the reason.',
                'code' => env('DONATE_MTN_MERCHANT', 'set in .env'),
            ],
            'airtel' => [
                'label' => 'Airtel Money',
                'short' => 'Airtel Money',
                'color' => '#e40000',
                'instructions' => 'Dial *185*9#, choose “Pay merchant”, enter merchant ID :code, then enter the amount and use your reference.',
                'code' => env('DONATE_AIRTEL_MERCHANT', 'set in .env'),
            ],
            'card' => [
                'label' => 'Visa / Mastercard',
                'short' => 'Card',
                'color' => '#1a1f71',
                'instructions' => 'Card payments open on a secure payment page. Until online card payments are switched on, our team will email you a secure payment link within 24 hours.',
                'code' => null,
            ],
            'bank' => [
                'label' => 'Bank transfer',
                'short' => 'Bank',
                'color' => '#1f3a2b',
                'instructions' => 'Transfer to :code and put your reference in the transfer narration. Companies can ask us for an invoice.',
                'code' => env('DONATE_BANK_DETAILS', 'Bank details set in .env'),
            ],
        ],

        'partner_tiers' => [
            ['name' => 'Friend', 'amount' => 1_000_000, 'perks' => ['Logo on our Supporters wall', 'Thank-you post on our social media']],
            ['name' => 'Partner', 'amount' => 5_000_000, 'perks' => ['Everything in Friend', 'Featured Marketplace listing for 12 months', 'Your logo on the footer of every page']],
            ['name' => 'Patron', 'amount' => 15_000_000, 'perks' => ['Everything in Partner', 'Name a project (e.g. a guide-training cohort)', 'A story about your support, written by our team']],
        ],
    ],
];

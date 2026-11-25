<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExploreController extends Controller
{
    public const TYPES = ['Nature', 'Culture', 'Food', 'Farms'];

    public function index(Request $request): View
    {
        $type = in_array($request->query('type'), self::TYPES, true) ? $request->query('type') : null;

        return view('pages.explore', [
            'featured' => Place::where('is_featured', true)->orderBy('sort')->first(),
            'places' => Place::when($type, fn ($q) => $q->where('type', $type))
                ->where('is_featured', false)->orderBy('sort')->get(),
            'events' => Event::where('starts_at', '>=', now()->startOfDay())->orderBy('starts_at')->take(4)->get(),
            'types' => self::TYPES,
            'type' => $type,
        ]);
    }
}

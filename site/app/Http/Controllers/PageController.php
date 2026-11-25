<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Business;
use App\Models\Notice;
use App\Models\Place;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'places' => Place::orderByDesc('is_featured')->orderBy('sort')->take(4)->get(),
            'businesses' => Business::approved()->with('category')
                ->orderByDesc('is_featured')->orderByDesc('rating')->take(3)->get(),
            'articles' => Article::published()->latest('published_at')->take(2)->get(),
            'notices' => Notice::latest()->take(3)->get(),
            'businessCount' => Business::approved()->count(),
            'placeCount' => Place::count(),
        ]);
    }

    public function story(): View
    {
        return view('pages.story');
    }
}

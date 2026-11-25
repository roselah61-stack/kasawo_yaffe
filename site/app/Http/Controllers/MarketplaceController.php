<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::withCount(['businesses' => fn ($q) => $q->approved()])->orderBy('sort')->get();
        $category = $categories->firstWhere('slug', $request->query('category'));
        $search = trim((string) $request->query('q'));
        $type = in_array($request->query('type'), ['products', 'services'], true) ? $request->query('type') : null;

        $businesses = Business::approved()->with('category')
            ->when($category, fn ($q) => $q->where('category_id', $category->id))
            ->when($type, fn ($q) => $q->whereIn('type', [$type, 'both']))
            ->when($request->boolean('verified'), fn ($q) => $q->where('is_verified', true))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(fn ($w) => $w->where('name', 'like', "%{$search}%")
                    ->orWhere('tagline', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('products', fn ($p) => $p->where('name', 'like', "%{$search}%")));
            })
            ->when($request->query('sort') === 'rating', fn ($q) => $q->orderByDesc('rating'))
            ->when($request->query('sort') === 'newest', fn ($q) => $q->latest())
            ->orderByDesc('is_featured')->orderByDesc('rating')
            ->paginate(9)->withQueryString();

        $sponsored = Business::approved()->where('is_featured', true)->inRandomOrder()->first();

        return view('marketplace.index', [
            'categories' => $categories,
            'category' => $category,
            'businesses' => $businesses,
            'sponsored' => $search === '' && ! $category ? $sponsored : null,
            'search' => $search,
            'total' => $categories->sum('businesses_count'),
        ]);
    }

    public function show(Business $business): View
    {
        abort_unless($business->status === 'approved', 404);
        $business->load(['category', 'products', 'reviews']);

        return view('marketplace.show', [
            'business' => $business,
            'similar' => Business::approved()->where('category_id', $business->category_id)
                ->whereKeyNot($business->id)->take(3)->get(),
        ]);
    }

    public function create(): View
    {
        return view('marketplace.create', ['categories' => Category::orderBy('sort')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:products,services,both'],
            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'owner_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[+\d\s()-]{9,}$/'],
            'whatsapp' => ['nullable', 'string', 'max:30', 'regex:/^[+\d\s()-]{9,}$/'],
            'email' => ['nullable', 'email', 'max:160'],
            'location' => ['required', 'string', 'max:160'],
            'photos' => ['nullable', 'array', 'max:10'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'website' => ['prohibited'], // honeypot
        ], [
            'phone.regex' => 'Please enter a valid phone number, e.g. +256 772 123 456.',
            'whatsapp.regex' => 'Please enter a valid WhatsApp number.',
            'description.min' => 'Tell customers a little more, at least 20 characters.',
        ]);

        $paths = collect($request->file('photos', []))->map(fn ($f) => $f->store('businesses', 'public'))->all();

        Business::create([
            ...collect($data)->except(['photos', 'website'])->all(),
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(5)),
            'tagline' => Str::limit($data['description'], 80),
            'image' => $paths[0] ?? null,
            'gallery' => $paths,
            'status' => 'pending',
        ]);

        return redirect()->route('marketplace.index')->with('status', '<b>Webale!</b> Your business has been received. We’ll check it and publish it within 48 hours.');
    }
}

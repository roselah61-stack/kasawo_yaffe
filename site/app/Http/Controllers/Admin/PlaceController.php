<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ExploreController;
use App\Models\Place;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlaceController extends Controller
{
    use Concerns;

    public function index(): View
    {
        return view('admin.places.index', ['places' => Place::orderByDesc('is_featured')->orderBy('sort')->get()]);
    }

    public function create(): View
    {
        return view('admin.places.form', ['place' => new Place(['sort' => Place::max('sort') + 1]), 'types' => ExploreController::TYPES]);
    }

    public function edit(Place $place): View
    {
        return view('admin.places.form', ['place' => $place, 'types' => ExploreController::TYPES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $place = Place::create($this->data($request, new Place));

        return redirect()->route('admin.places.index')->with('status', "<b>{$place->name}</b> added.");
    }

    public function update(Request $request, Place $place): RedirectResponse
    {
        $place->update($this->data($request, $place));

        return redirect()->route('admin.places.index')->with('status', "<b>{$place->name}</b> saved.");
    }

    public function destroy(Place $place): RedirectResponse
    {
        $place->delete();

        return back()->with('status', "<b>{$place->name}</b> removed.");
    }

    private function data(Request $request, Place $place): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', Rule::in(ExploreController::TYPES)],
            'summary' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],
            'duration' => ['nullable', 'string', 'max:40'],
            'meta' => ['nullable', 'string', 'max:60'],
            'sort' => ['nullable', 'integer'],
            'image' => [$place->exists ? 'nullable' : 'required', 'image', 'max:5120'],
        ]);

        if ($request->boolean('is_featured')) {
            Place::whereKeyNot($place->id)->update(['is_featured' => false]); // only one top pick
        }

        return [
            ...collect($data)->except('image')->all(),
            'sort' => $data['sort'] ?? 0,
            'slug' => $place->slug ?: Str::slug($data['name']).'-'.Str::lower(Str::random(4)),
            'image' => $this->storeImage($request, 'image', 'places', $place->image),
            'is_featured' => $request->boolean('is_featured'),
        ];
    }
}

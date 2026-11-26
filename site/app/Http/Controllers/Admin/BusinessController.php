<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BusinessController extends Controller
{
    use Concerns;

    public function index(Request $request): View
    {
        $status = in_array($request->query('status'), ['pending', 'approved', 'rejected'], true) ? $request->query('status') : null;
        $q = trim((string) $request->query('q'));

        return view('admin.businesses.index', [
            'businesses' => Business::with('category')
                ->when($status, fn ($b) => $b->where('status', $status))
                ->when($q !== '', fn ($b) => $b->where(fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('owner_name', 'like', "%{$q}%")->orWhere('phone', 'like', "%{$q}%")))
                ->orderByRaw("case status when 'pending' then 0 else 1 end")->latest()
                ->paginate(15)->withQueryString(),
            'counts' => Business::selectRaw('status, count(*) as n')->groupBy('status')->pluck('n', 'status'),
            'status' => $status,
            'q' => $q,
        ]);
    }

    public function edit(Business $business): View
    {
        return view('admin.businesses.edit', ['business' => $business->load('products'), 'categories' => Category::orderBy('sort')->get()]);
    }

    public function update(Request $request, Business $business): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:products,services,both'],
            'tagline' => ['nullable', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:3000'],
            'owner_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:160'],
            'location' => ['nullable', 'string', 'max:160'],
            'payment_methods' => ['nullable', 'string', 'max:120'],
            'delivery' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:pending,approved,rejected'],
            'admin_note' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
            'products' => ['array'],
            'products.*.name' => ['nullable', 'string', 'max:120'],
            'products.*.note' => ['nullable', 'string', 'max:160'],
            'products.*.price' => ['nullable', 'integer', 'min:0'],
            'products.*.price_prefix' => ['nullable', 'string', 'max:10'],
        ]);

        $business->update([
            ...collect($data)->except(['image', 'products'])->all(),
            'is_verified' => $request->boolean('is_verified'),
            'is_featured' => $request->boolean('is_featured'),
            'image' => $this->storeImage($request, 'image', 'businesses', $business->image),
        ]);

        $business->products()->delete();
        foreach ($data['products'] ?? [] as $p) {
            if (filled($p['name'] ?? null)) {
                $business->products()->create($p);
            }
        }

        return redirect()->route('admin.businesses.index', ['status' => $business->status])
            ->with('status', "<b>".e($business->name)."</b> saved.");
    }

    public function status(Request $request, Business $business): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:pending,approved,rejected']]);
        $business->update($data);

        $verb = ['approved' => 'is now live', 'rejected' => 'was rejected', 'pending' => 'is back in review'][$data['status']];

        return back()->with('status', "<b>".e($business->name)."</b> {$verb}.");
    }

    public function toggle(Request $request, Business $business): RedirectResponse
    {
        $field = $request->validate(['field' => ['required', 'in:is_verified,is_featured']])['field'];
        $business->update([$field => ! $business->{$field}]);

        return back()->with('status', "<b>".e($business->name)."</b> updated.");
    }

    public function destroy(Business $business): RedirectResponse
    {
        foreach (array_filter([$business->image, ...($business->gallery ?? [])]) as $path) {
            if (! str_starts_with($path, 'images/')) {
                Storage::disk('public')->delete($path);
            }
        }
        $business->delete();

        return redirect()->route('admin.businesses.index')->with('status', "<b>".e($business->name)."</b> was deleted.");
    }
}

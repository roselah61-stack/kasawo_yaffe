@extends('layouts.admin')
@section('title', $business->name)
@section('actions')
    <a href="{{ route('admin.businesses.index') }}" class="btn btn-outline btn-sm">← All businesses</a>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.businesses.update', $business->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="form-grid">
        <div>
            <div class="a-card" data-reveal>
                <div class="a-card-head"><h2>Details</h2><span class="pill {{ $business->status }}">{{ $business->status }}</span></div>
                <div class="field"><label>Name</label><input name="name" value="{{ old('name', $business->name) }}" class="@error('name') invalid @enderror">@error('name')<span class="error">{{ $message }}</span>@enderror</div>
                <div class="row2">
                    <div class="field"><label>Category</label><select name="category_id">@foreach ($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $business->category_id) == $c->id)>{{ $c->icon }} {{ $c->name }}</option>@endforeach</select></div>
                    <div class="field"><label>Offers</label><select name="type">@foreach (['products' => 'Products', 'services' => 'Services', 'both' => 'Both'] as $k => $v)<option value="{{ $k }}" @selected(old('type', $business->type) === $k)>{{ $v }}</option>@endforeach</select></div>
                </div>
                <div class="field"><label>Tagline <span class="muted" style="font-weight:400">(shown on cards)</span></label><input name="tagline" value="{{ old('tagline', $business->tagline) }}"></div>
                <div class="field"><label>Description</label><textarea name="description" style="min-height:140px">{{ old('description', $business->description) }}</textarea></div>
                <div class="row2">
                    <div class="field"><label>Owner</label><input name="owner_name" value="{{ old('owner_name', $business->owner_name) }}"></div>
                    <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email', $business->email) }}"></div>
                </div>
                <div class="row2">
                    <div class="field"><label>Phone</label><input name="phone" value="{{ old('phone', $business->phone) }}"></div>
                    <div class="field"><label>WhatsApp</label><input name="whatsapp" value="{{ old('whatsapp', $business->whatsapp) }}"></div>
                </div>
                <div class="field"><label>Location</label><input name="location" value="{{ old('location', $business->location) }}"></div>
                <div class="row2">
                    <div class="field"><label>Payment methods</label><input name="payment_methods" value="{{ old('payment_methods', $business->payment_methods) }}" placeholder="Cash · MTN · Airtel"></div>
                    <div class="field"><label>Delivery</label><input name="delivery" value="{{ old('delivery', $business->delivery) }}" placeholder="Kasawo & Mukono"></div>
                </div>
            </div>

            <div class="a-card" style="margin-top:22px" data-reveal>
                <div class="a-card-head"><h2>Products &amp; prices</h2><button type="button" class="btn btn-outline btn-xs" data-add-row="#products">+ Add item</button></div>
                <div class="product-row muted" style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em"><span>Item</span><span>Note</span><span>Price (UGX)</span><span>Prefix</span></div>
                <div id="products">
                    @foreach (old('products', $business->products->toArray() ?: [[]]) as $i => $p)
                        <div class="product-row">
                            <input name="products[{{ $i }}][name]" value="{{ $p['name'] ?? '' }}" placeholder="e.g. Gomesi">
                            <input name="products[{{ $i }}][note]" value="{{ $p['note'] ?? '' }}" placeholder="Ready in 5 days">
                            <input name="products[{{ $i }}][price]" value="{{ $p['price'] ?? '' }}" inputmode="numeric" placeholder="150000">
                            <input name="products[{{ $i }}][price_prefix]" value="{{ $p['price_prefix'] ?? '' }}" placeholder="from">
                        </div>
                    @endforeach
                </div>
                <small class="hint">Leave a name empty to remove that item.</small>
            </div>
        </div>

        <div>
            <div class="a-card" data-reveal="right">
                <h2 style="margin-bottom:14px">Publishing</h2>
                <div class="field"><label>Status</label>
                    <select name="status">@foreach (['pending' => '⏳ Pending review', 'approved' => '✅ Live on the site', 'rejected' => '✕ Rejected'] as $k => $v)<option value="{{ $k }}" @selected(old('status', $business->status) === $k)>{{ $v }}</option>@endforeach</select>
                </div>
                <label class="switch"><span>Verified<small>We’ve checked this business is real</small></span><input type="checkbox" name="is_verified" value="1" @checked(old('is_verified', $business->is_verified))></label>
                <label class="switch"><span>Featured<small>Top of the Marketplace and home page</small></span><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $business->is_featured))></label>
                <div class="field" style="margin-top:14px"><label>Internal note</label><input name="admin_note" value="{{ old('admin_note', $business->admin_note) }}" placeholder="e.g. Called owner on 12 Dec, paid Featured"></div>
            </div>
            <div class="a-card" style="margin-top:22px" data-reveal="right">
                <h2 style="margin-bottom:14px">Main photo</h2>
                <img class="img-preview" src="{{ $business->imageUrl() }}" alt="" data-preview="image">
                <div class="upload" style="padding:16px"><input type="file" name="image" accept="image/*" data-preview-target="image">Replace photo</div>
                @error('image')<span class="error">{{ $message }}</span>@enderror
                @if (count($business->gallery ?? []) > 1)
                    <div class="upload-preview" style="justify-content:flex-start">@foreach ($business->gallery as $g)<img src="{{ \App\Support\Media::url($g) }}" alt="">@endforeach</div>
                @endif
            </div>
            <div class="a-card" style="margin-top:22px" data-reveal="right">
                <small class="muted">Submitted {{ $business->created_at->format('j M Y, g:i A') }}<br>Last updated {{ $business->updated_at->diffForHumans() }}</small>
            </div>
        </div>
    </div>
    <div class="sticky-save">
        <a href="{{ route('admin.businesses.index') }}" class="btn btn-outline">Cancel</a>
        <button class="btn btn-primary">Save changes</button>
    </div>
</form>
@endsection

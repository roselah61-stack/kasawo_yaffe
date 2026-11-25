@extends('layouts.admin')
@section('title', $place->exists ? 'Edit place' : 'Add a place')
@section('actions')<a href="{{ route('admin.places.index') }}" class="btn btn-outline btn-sm">← All places</a>@endsection

@section('content')
<form method="POST" action="{{ $place->exists ? route('admin.places.update', $place->id) : route('admin.places.store') }}" enctype="multipart/form-data">
    @csrf @if ($place->exists) @method('PUT') @endif
    <div class="form-grid">
        <div class="a-card" data-reveal>
            <div class="field"><label>Name</label><input name="name" value="{{ old('name', $place->name) }}" class="@error('name') invalid @enderror">@error('name')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field"><label>Short summary</label><input name="summary" value="{{ old('summary', $place->summary) }}" class="@error('summary') invalid @enderror">@error('summary')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field"><label>Longer description <span class="muted" style="font-weight:400">(used when it’s the top pick)</span></label><textarea name="description">{{ old('description', $place->description) }}</textarea></div>
            <div class="row2">
                <div class="field"><label>Time needed</label><input name="duration" value="{{ old('duration', $place->duration) }}" placeholder="2–3 hrs"></div>
                <div class="field"><label>Extra detail</label><input name="meta" value="{{ old('meta', $place->meta) }}" placeholder="🎟 Entry fee"></div>
            </div>
        </div>
        <div>
            <div class="a-card" data-reveal="right">
                <div class="field"><label>Type</label><select name="type">@foreach ($types as $t)<option @selected(old('type', $place->type) === $t)>{{ $t }}</option>@endforeach</select></div>
                <div class="field"><label>Display order</label><input type="number" name="sort" value="{{ old('sort', $place->sort) }}"></div>
                <label class="switch"><span>Top pick<small>The big banner on Explore (only one)</small></span><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $place->is_featured))></label>
            </div>
            <div class="a-card" style="margin-top:22px" data-reveal="right">
                <h2 style="margin-bottom:14px">Photo</h2>
                <img class="img-preview" src="{{ $place->image ? $place->imageUrl() : '' }}" alt="" data-preview="image" @unless ($place->image) style="display:none" @endunless>
                <div class="upload" style="padding:16px"><input type="file" name="image" accept="image/*" data-preview-target="image">{{ $place->image ? 'Replace photo' : 'Choose a photo' }}</div>
                @error('image')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="sticky-save"><a href="{{ route('admin.places.index') }}" class="btn btn-outline">Cancel</a><button class="btn btn-primary">{{ $place->exists ? 'Save changes' : 'Add place' }}</button></div>
</form>
@endsection

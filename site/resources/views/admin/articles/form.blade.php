@extends('layouts.admin')
@section('title', $article->exists ? 'Edit article' : 'New article')
@section('actions')<a href="{{ route('admin.articles.index') }}" class="btn btn-outline btn-sm">← All articles</a>@endsection

@section('content')
<form method="POST" action="{{ $article->exists ? route('admin.articles.update', $article->id) : route('admin.articles.store') }}" enctype="multipart/form-data">
    @csrf @if ($article->exists) @method('PUT') @endif
    <div class="form-grid">
        <div class="a-card" data-reveal>
            <div class="field"><label>Title</label><input name="title" value="{{ old('title', $article->title) }}" class="@error('title') invalid @enderror" style="font-family:var(--serif);font-size:1.25rem">@error('title')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field"><label>Short summary <span class="muted" style="font-weight:400">(shown on cards)</span></label><input name="excerpt" value="{{ old('excerpt', $article->excerpt) }}" class="@error('excerpt') invalid @enderror">@error('excerpt')<span class="error">{{ $message }}</span>@enderror</div>
            <div class="field">
                <label>Article</label>
                <textarea name="body" style="min-height:420px;font-family:ui-monospace,Menlo,monospace;font-size:.92rem;line-height:1.6" class="@error('body') invalid @enderror">{{ old('body', $article->body) }}</textarea>
                <span class="hint">Formatting: <code>## Heading</code> · <code>**bold**</code> · <code>- bullet</code> · <code>&gt; quote</code> on its own line.</span>
                @error('body')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div>
            <div class="a-card" data-reveal="right">
                <h2 style="margin-bottom:14px">Publishing</h2>
                <label class="switch"><span>Published<small>Visible on the Community page</small></span><input type="checkbox" name="published" value="1" @checked(old('published', $article->exists ? (bool) $article->published_at : true))></label>
                <label class="switch"><span>Featured<small>Big banner at the top of Community</small></span><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $article->is_featured))></label>
                <div class="field" style="margin-top:14px"><label>Topic</label><select name="topic">@foreach ($topics as $t)<option @selected(old('topic', $article->topic) === $t)>{{ $t }}</option>@endforeach</select></div>
                <div class="row2">
                    <div class="field"><label>Author</label><input name="author" value="{{ old('author', $article->author) }}"></div>
                    <div class="field"><label>Minutes to read</label><input type="number" name="read_minutes" min="1" max="60" value="{{ old('read_minutes', $article->read_minutes) }}"></div>
                </div>
            </div>
            <div class="a-card" style="margin-top:22px" data-reveal="right">
                <h2 style="margin-bottom:14px">Cover photo</h2>
                <img class="img-preview" src="{{ $article->image ? $article->imageUrl() : '' }}" alt="" data-preview="image" @unless ($article->image) style="display:none" @endunless>
                <div class="upload" style="padding:16px"><input type="file" name="image" accept="image/*" data-preview-target="image">{{ $article->image ? 'Replace photo' : 'Choose a photo' }}</div>
                @error('image')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>
    <div class="sticky-save"><a href="{{ route('admin.articles.index') }}" class="btn btn-outline">Cancel</a><button class="btn btn-primary">{{ $article->exists ? 'Save changes' : 'Create article' }}</button></div>
</form>
@endsection

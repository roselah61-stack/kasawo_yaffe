<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommunityController;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArticleController extends Controller
{
    use Concerns;

    public function index(): View
    {
        return view('admin.articles.index', ['articles' => Article::latest('published_at')->latest()->paginate(20)]);
    }

    public function create(): View
    {
        return view('admin.articles.form', ['article' => new Article(['author' => 'Kasawo Yaffe team', 'read_minutes' => 4]), 'topics' => array_keys(CommunityController::TOPICS)]);
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.form', ['article' => $article, 'topics' => array_keys(CommunityController::TOPICS)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $article = Article::create($this->data($request, new Article));

        return redirect()->route('admin.articles.index')->with('status', "<b>{$article->title}</b> created.");
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $article->update($this->data($request, $article));

        return redirect()->route('admin.articles.index')->with('status', "<b>{$article->title}</b> saved.");
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return back()->with('status', "<b>{$article->title}</b> deleted.");
    }

    private function data(Request $request, Article $article): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'topic' => ['required', Rule::in(array_keys(CommunityController::TOPICS))],
            'excerpt' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'author' => ['required', 'string', 'max:120'],
            'read_minutes' => ['required', 'integer', 'min:1', 'max:60'],
            'image' => [$article->exists ? 'nullable' : 'required', 'image', 'max:5120'],
        ]);

        return [
            ...collect($data)->except('image')->all(),
            'slug' => $article->slug ?: Str::slug($data['title']).'-'.Str::lower(Str::random(4)),
            'image' => $this->storeImage($request, 'image', 'articles', $article->image),
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $request->boolean('published') ? ($article->published_at ?? now()) : null,
        ];
    }
}

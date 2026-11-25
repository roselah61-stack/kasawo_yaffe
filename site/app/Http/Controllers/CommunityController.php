<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public const TOPICS = ['Farming' => '🌱', 'Business' => '💼', 'Health' => '🏥', 'Education' => '🎓', 'Safety' => '🛡️', 'Digital' => '📱'];

    public function index(Request $request): View
    {
        $topic = array_key_exists((string) $request->query('topic'), self::TOPICS) ? $request->query('topic') : null;
        $articles = Article::published()->when($topic, fn ($q) => $q->where('topic', $topic))->latest('published_at')->get();

        return view('community.index', [
            'featured' => $topic ? null : $articles->firstWhere('is_featured', true),
            'articles' => $topic ? $articles : $articles->where('is_featured', false),
            'notices' => Notice::latest()->take(5)->get(),
            'topics' => self::TOPICS,
            'topic' => $topic,
        ]);
    }

    public function show(Article $article): View
    {
        abort_unless($article->published_at && $article->published_at->isPast(), 404);

        return view('community.show', [
            'article' => $article,
            'more' => Article::published()->whereKeyNot($article->id)->latest('published_at')->take(3)->get(),
        ]);
    }
}

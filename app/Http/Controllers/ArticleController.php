<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Support\HtmlSanitizer;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(private readonly HtmlSanitizer $htmlSanitizer)
    {
    }

    /**
     * Display articles listing
     */
    public function index()
    {
        $articles = Article::where('is_published', true)
            ->latest('published_at')
            ->paginate(9);

        return view('articles.index', compact('articles'));
    }

    /**
     * Display article detail
     */
    public function show(Article $article)
    {
        // Check if published
        if (!$article->is_published) {
            abort(404);
        }

        // Increment views
        $article->increment('views');

        // Related articles
        $relatedArticles = Article::where('is_published', true)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $safeContent = $this->htmlSanitizer->sanitizeArticleContent($article->content);

        return view('articles.show', compact('article', 'relatedArticles', 'safeContent'));
    }
}

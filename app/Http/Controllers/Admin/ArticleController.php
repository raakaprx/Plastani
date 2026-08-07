<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Support\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function __construct(private readonly HtmlSanitizer $htmlSanitizer)
    {
    }

    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:500',
            'excerpt' => 'nullable|string|min:10|max:500',
            'content' => 'required|string|min:50|max:50000',
            'author' => 'required|string|min:3|max:255',
            'is_published' => 'boolean',
        ], [
            'title.required' => 'Judul artikel wajib diisi.',
            'title.min' => 'Judul artikel minimal 5 karakter.',
            'title.max' => 'Judul artikel maksimal 500 karakter.',
            'excerpt.min' => 'Excerpt minimal 10 karakter.',
            'excerpt.max' => 'Excerpt maksimal 500 karakter.',
            'content.required' => 'Konten artikel wajib diisi.',
            'content.min' => 'Konten artikel minimal 50 karakter.',
            'content.max' => 'Konten artikel maksimal 50000 karakter.',
            'author.required' => 'Nama penulis wajib diisi.',
            'author.min' => 'Nama penulis minimal 3 karakter.',
            'author.max' => 'Nama penulis maksimal 255 karakter.',
        ]);

        $validated['slug'] = Str::slug($request->title);
        $validated['is_published'] = $request->has('is_published');
        $validated['content'] = $this->htmlSanitizer->sanitizeArticleContent($validated['content']);

        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dibuat.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:500',
            'excerpt' => 'nullable|string|min:10|max:500',
            'content' => 'required|string|min:50|max:50000',
            'author' => 'required|string|min:3|max:255',
            'is_published' => 'boolean',
        ], [
            'title.required' => 'Judul artikel wajib diisi.',
            'title.min' => 'Judul artikel minimal 5 karakter.',
            'title.max' => 'Judul artikel maksimal 500 karakter.',
            'excerpt.min' => 'Excerpt minimal 10 karakter.',
            'excerpt.max' => 'Excerpt maksimal 500 karakter.',
            'content.required' => 'Konten artikel wajib diisi.',
            'content.min' => 'Konten artikel minimal 50 karakter.',
            'content.max' => 'Konten artikel maksimal 50000 karakter.',
            'author.required' => 'Nama penulis wajib diisi.',
            'author.min' => 'Nama penulis minimal 3 karakter.',
            'author.max' => 'Nama penulis maksimal 255 karakter.',
        ]);

        $validated['slug'] = Str::slug($request->title);
        $validated['is_published'] = $request->has('is_published');
        $validated['content'] = $this->htmlSanitizer->sanitizeArticleContent($validated['content']);

        // Set published_at jika baru dipublish
        if ($validated['is_published'] && !$article->is_published) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully');
    }
}

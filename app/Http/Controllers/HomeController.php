<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Journal;
use App\Models\Article;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('category')->latest()->take(8)->get();
            $journals = Journal::latest()->take(9)->get();
            $articles = Article::latest()->take(3)->get();
        } catch (\Throwable $e) {
            $products = collect();
            $journals = collect();
            $articles = collect();
        }

        return view('home', compact('products', 'journals', 'articles'));
    }

    public function about()
    {
        return view('about');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Journal;
use App\Models\Article;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_journals' => Journal::count(),
            'total_articles' => Article::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_stock' => Product::sum('stock'),
        ];

        $recent_products = Product::with('category')->latest()->take(5)->get();
        $popular_journals = Journal::orderBy('views', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_products', 'popular_journals'));
    }
}

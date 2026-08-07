<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    /**
     * Display journals listing
     */
    public function index(Request $request)
    {
        $query = Journal::query();

        // Filter by type
        if ($request->has('type') && in_array($request->type, ['international', 'national'])) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('authors', 'like', '%' . $search . '%')
                  ->orWhere('journal_name', 'like', '%' . $search . '%');
            });
        }

        $journals = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total' => Journal::count(),
            'international' => Journal::where('type', 'international')->count(),
            'national' => Journal::where('type', 'national')->count(),
        ];

        return view('journals.index', compact('journals', 'stats'));
    }

    /**
     * Redirect to journal URL and increment views
     */
    public function redirect(Journal $journal)
    {
        $journal->increment('views');
        return redirect()->away($journal->url);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    /**
     * Display a listing of journals
     */
    public function index()
    {
        $journals = Journal::latest()->paginate(20);
        return view('admin.journals.index', compact('journals'));
    }

    /**
     * Show the form for creating a new journal
     */
    public function create()
    {
        return view('admin.journals.create');
    }

    /**
     * Store a newly created journal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:500',
            'description' => 'nullable|string|max:1000',
            'journal_name' => 'required|string|min:3|max:255',
            'authors' => 'required|string|min:3|max:500',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'url' => 'required|url|max:500',
            'type' => 'required|in:international,national',
        ], [
            'title.required' => 'Judul jurnal wajib diisi.',
            'title.min' => 'Judul jurnal minimal 5 karakter.',
            'title.max' => 'Judul jurnal maksimal 500 karakter.',
            'description.max' => 'Deskripsi jurnal maksimal 1000 karakter.',
            'journal_name.required' => 'Nama jurnal wajib diisi.',
            'journal_name.min' => 'Nama jurnal minimal 3 karakter.',
            'journal_name.max' => 'Nama jurnal maksimal 255 karakter.',
            'authors.required' => 'Nama penulis wajib diisi.',
            'authors.min' => 'Nama penulis minimal 3 karakter.',
            'authors.max' => 'Nama penulis maksimal 500 karakter.',
            'year.required' => 'Tahun wajib diisi.',
            'year.integer' => 'Tahun harus berupa angka.',
            'year.min' => 'Tahun minimal 1900.',
            'year.max' => 'Tahun tidak boleh melebihi tahun sekarang.',
            'url.required' => 'URL jurnal wajib diisi.',
            'url.url' => 'Format URL tidak valid.',
            'url.max' => 'URL maksimal 500 karakter.',
            'type.required' => 'Tipe jurnal wajib diisi.',
            'type.in' => 'Tipe jurnal harus berupa international atau national.',
        ]);

        Journal::create($validated);

        return redirect()->route('admin.journals.index')
            ->with('success', 'Jurnal berhasil ditambahkan.');
    }

    /**
     * Display the specified journal
     */
    public function show(Journal $journal)
    {
        return view('admin.journals.show', compact('journal'));
    }

    /**
     * Show the form for editing the journal
     */
    public function edit(Journal $journal)
    {
        return view('admin.journals.edit', compact('journal'));
    }

    /**
     * Update the specified journal
     */
    public function update(Request $request, Journal $journal)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:500',
            'description' => 'nullable|string|max:1000',
            'journal_name' => 'required|string|min:3|max:255',
            'authors' => 'required|string|min:3|max:500',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'url' => 'required|url|max:500',
            'type' => 'required|in:international,national',
        ], [
            'title.required' => 'Judul jurnal wajib diisi.',
            'title.min' => 'Judul jurnal minimal 5 karakter.',
            'title.max' => 'Judul jurnal maksimal 500 karakter.',
            'description.max' => 'Deskripsi jurnal maksimal 1000 karakter.',
            'journal_name.required' => 'Nama jurnal wajib diisi.',
            'journal_name.min' => 'Nama jurnal minimal 3 karakter.',
            'journal_name.max' => 'Nama jurnal maksimal 255 karakter.',
            'authors.required' => 'Nama penulis wajib diisi.',
            'authors.min' => 'Nama penulis minimal 3 karakter.',
            'authors.max' => 'Nama penulis maksimal 500 karakter.',
            'year.required' => 'Tahun wajib diisi.',
            'year.integer' => 'Tahun harus berupa angka.',
            'year.min' => 'Tahun minimal 1900.',
            'year.max' => 'Tahun tidak boleh melebihi tahun sekarang.',
            'url.required' => 'URL jurnal wajib diisi.',
            'url.url' => 'Format URL tidak valid.',
            'url.max' => 'URL maksimal 500 karakter.',
            'type.required' => 'Tipe jurnal wajib diisi.',
            'type.in' => 'Tipe jurnal harus berupa international atau national.',
        ]);

        $journal->update($validated);

        return redirect()->route('admin.journals.index')
            ->with('success', 'Jurnal berhasil diperbarui.');
    }

    /**
     * Remove the specified journal
     */
    public function destroy(Journal $journal)
    {
        $journal->delete();

        return redirect()->route('admin.journals.index')
            ->with('success', 'Journal deleted successfully');
    }
}

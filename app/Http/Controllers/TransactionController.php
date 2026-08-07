<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $transactions = Transaction::with('product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:99999'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'qty.required' => 'Jumlah produk wajib diisi.',
            'qty.integer' => 'Jumlah produk harus berupa angka bulat.',
            'qty.min' => 'Jumlah produk minimal 1.',
            'qty.max' => 'Jumlah produk maksimal 99999.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        // Validate product status and stock
        $errors = [];

        if (! $product->is_active) {
            $errors['qty'] = 'Produk tidak aktif dan tidak dapat ditransaksikan.';
        }

        if ($product->stock < $validated['qty']) {
            $errors['qty'] = 'Stok tidak mencukupi untuk jumlah yang diminta. Stok tersedia: ' . $product->stock;
        }

        if ($product->price <= 0) {
            $errors['qty'] = 'Harga produk tidak valid.';
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        Transaction::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'qty' => $validated['qty'],
            'total_price' => round(((float) $product->price) * $validated['qty'], 2),
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dibuat dengan status pending.');
    }
}


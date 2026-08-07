<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::with(['user', 'product'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $transactions = $query->paginate(15)->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }

    public function updateStatus(Request $request, Transaction $transaction): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,success,failed'],
        ], [
            'status.required' => 'Status transaksi wajib diisi.',
            'status.in' => 'Status transaksi harus berupa pending, success, atau failed.',
            'status.string' => 'Status transaksi harus berupa text.',
        ]);

        // Validasi transaksi masih ada
        if (! $transaction->exists) {
            return back()->with('error', 'Transaksi tidak ditemukan.');
        }

        // Validasi product masih ada
        if (! $transaction->product) {
            return back()->with('error', 'Produk terkait transaksi tidak ditemukan.');
        }

        // Validasi user masih ada
        if (! $transaction->user) {
            return back()->with('error', 'User terkait transaksi tidak ditemukan.');
        }

        DB::transaction(function () use ($transaction, $validated): void {
            $transaction->refresh();
            $product = Product::query()
                ->lockForUpdate()
                ->findOrFail($transaction->product_id);

            $oldStatus = $transaction->status;
            $newStatus = $validated['status'];

            // Prevent invalid status transitions
            if ($oldStatus === 'failed' && $newStatus === 'success') {
                throw ValidationException::withMessages([
                    'status' => 'Transaksi yang sudah gagal tidak dapat diubah ke success.',
                ]);
            }

            if ($oldStatus !== 'success' && $newStatus === 'success') {
                if ($product->stock < $transaction->qty) {
                    throw ValidationException::withMessages([
                        'status' => 'Stok tidak cukup untuk mengubah transaksi ke status success. Stok tersedia: ' . $product->stock . ', dibutuhkan: ' . $transaction->qty,
                    ]);
                }

                if ($product->price <= 0) {
                    throw ValidationException::withMessages([
                        'status' => 'Harga produk tidak valid, tidak dapat mengubah status.',
                    ]);
                }

                $product->decrement('stock', $transaction->qty);
            }

            if ($oldStatus === 'success' && $newStatus !== 'success') {
                $product->increment('stock', $transaction->qty);
            }

            $transaction->update([
                'status' => $newStatus,
            ]);
        });

        return back()->with('success', 'Status transaksi berhasil diperbarui.');
    }
}


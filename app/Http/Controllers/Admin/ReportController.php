<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PdfExportService;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display dashboard dengan overview reports
     */
    public function dashboard(): View
    {
        // Transaction Summary - last 7 days
        $transactionSummary = DB::select("
            SELECT
                DATE(created_at) as date,
                COUNT(*) as total_transactions,
                SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as success_count,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_count,
                SUM(CASE WHEN status = 'success' THEN total_price ELSE 0 END) as daily_revenue
            FROM transactions
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ");

        // Top 5 Products by Revenue
        $topProducts = DB::select("
            SELECT
                p.id,
                p.name,
                p.price,
                p.stock,
                COUNT(CASE WHEN t.status = 'success' THEN t.id END) as success_count,
                SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END) as total_sold,
                SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END) as revenue
            FROM products p
            LEFT JOIN transactions t ON p.id = t.product_id
            WHERE p.is_active = true
            GROUP BY p.id, p.name, p.price, p.stock
            ORDER BY revenue DESC
            LIMIT 5
        ");

        // Top 5 Users by Spending
        $topUsers = DB::select("
            SELECT
                u.id,
                u.name,
                u.email,
                COUNT(DISTINCT t.id) as transaction_count,
                SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END) as total_items,
                SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END) as total_spent
            FROM users u
            LEFT JOIN transactions t ON u.id = t.user_id
            WHERE u.admin_plastani = false
            GROUP BY u.id, u.name, u.email
            ORDER BY total_spent DESC
            LIMIT 5
        ");

        // Overall Statistics
        $stats = DB::selectOne("
            SELECT
                COUNT(DISTINCT user_id) as total_users,
                COUNT(id) as total_transactions,
                SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as success_transactions,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_transactions,
                SUM(CASE WHEN status = 'success' THEN total_price ELSE 0 END) as total_revenue
            FROM transactions
        ");

        return view('admin.reports.dashboard', compact('transactionSummary', 'topProducts', 'topUsers', 'stats'));
    }

    /**
     * Display detailed transaction reports with advanced filtering
     */
    public function transactions(View $view): View
    {
        $dateFrom = request()->query('date_from');
        $dateTo = request()->query('date_to');
        $status = request()->query('status');
        $userId = request()->query('user_id');
        $productId = request()->query('product_id');

        $query = "
            SELECT
                t.id,
                t.status,
                t.qty,
                t.total_price,
                t.notes,
                t.created_at,
                u.name as user_name,
                u.email as user_email,
                p.name as product_name,
                p.price,
                c.name as category_name
            FROM transactions t
            JOIN users u ON t.user_id = u.id
            JOIN products p ON t.product_id = p.id
            JOIN categories c ON p.category_id = c.id
            WHERE 1=1
        ";

        $bindings = [];

        if ($dateFrom) {
            $query .= " AND DATE(t.created_at) >= ?";
            $bindings[] = $dateFrom;
        }

        if ($dateTo) {
            $query .= " AND DATE(t.created_at) <= ?";
            $bindings[] = $dateTo;
        }

        if ($status) {
            $query .= " AND t.status = ?";
            $bindings[] = $status;
        }

        if ($userId) {
            $query .= " AND t.user_id = ?";
            $bindings[] = $userId;
        }

        if ($productId) {
            $query .= " AND t.product_id = ?";
            $bindings[] = $productId;
        }

        $query .= " ORDER BY t.created_at DESC LIMIT 100";

        $transactions = DB::select($query, $bindings);

        // Get available users for filter dropdown
        $users = DB::select("
            SELECT u.id, u.name, COUNT(t.id) as transaction_count
            FROM users u
            LEFT JOIN transactions t ON u.id = t.user_id
            WHERE u.admin_plastani = false
            GROUP BY u.id, u.name
            ORDER BY u.name
        ");

        // Get available products for filter dropdown
        $products = DB::select("
            SELECT p.id, p.name, COUNT(t.id) as transaction_count
            FROM products p
            LEFT JOIN transactions t ON p.id = t.product_id
            GROUP BY p.id, p.name
            ORDER BY p.name
        ");

        return view('admin.reports.transactions', compact('transactions', 'users', 'products'));
    }

    /**
     * Display product sales report dengan detailed analytics
     */
    public function productSales(): View
    {
        $categoryFilter = request()->query('category');

        $query = "
            SELECT
                p.id,
                p.name,
                p.price,
                p.stock,
                p.eco_rating,
                p.is_active,
                c.name as category_name,
                COUNT(CASE WHEN t.status = 'success' THEN t.id END) as success_count,
                SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END) as total_sold,
                SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END) as total_revenue,
                ROUND(SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END) / NULLIF(COUNT(CASE WHEN t.status = 'success' THEN t.id END), 0), 2) as avg_qty_per_transaction
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN transactions t ON p.id = t.product_id
        ";

        $bindings = [];

        if ($categoryFilter) {
            $query .= " WHERE c.id = ?";
            $bindings[] = $categoryFilter;
        }

        $query .= " GROUP BY p.id, p.name, p.price, p.stock, p.eco_rating, p.is_active, c.name";
        $query .= " ORDER BY total_revenue DESC";

        $products = DB::select($query, $bindings);

        // Get categories for filter
        $categories = DB::select("
            SELECT c.id, c.name, COUNT(p.id) as product_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            GROUP BY c.id, c.name
            ORDER BY c.name
        ");

        return view('admin.reports.product-sales', compact('products', 'categories'));
    }

    /**
     * Display user activity report
     */
    public function userActivity(): View
    {
        $users = DB::select("
            SELECT
                u.id,
                u.name,
                u.email,
                u.created_at as registered_at,
                COUNT(DISTINCT t.id) as total_transactions,
                COUNT(DISTINCT CASE WHEN t.status = 'success' THEN t.id END) as success_count,
                COUNT(DISTINCT CASE WHEN t.status = 'pending' THEN t.id END) as pending_count,
                COUNT(DISTINCT CASE WHEN t.status = 'failed' THEN t.id END) as failed_count,
                SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END) as total_items_bought,
                SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END) as total_spending,
                MAX(t.created_at) as last_purchase_date
            FROM users u
            LEFT JOIN transactions t ON u.id = t.user_id
            WHERE u.admin_plastani = false
            GROUP BY u.id, u.name, u.email, u.created_at
            ORDER BY total_spending DESC
        ");

        return view('admin.reports.user-activity', compact('users'));
    }

    /**
     * Display monthly revenue trend
     */
    public function monthlyTrend(): View
    {
        $monthlyData = DB::select("
            SELECT
                DATE_FORMAT(created_at, '%Y-%m') as month,
                MONTH(created_at) as month_num,
                COUNT(id) as total_transactions,
                COUNT(CASE WHEN status = 'success' THEN id END) as success_count,
                COUNT(CASE WHEN status = 'pending' THEN id END) as pending_count,
                COUNT(CASE WHEN status = 'failed' THEN id END) as failed_count,
                SUM(CASE WHEN status = 'success' THEN total_price ELSE 0 END) as revenue,
                SUM(CASE WHEN status = 'success' THEN qty ELSE 0 END) as items_sold,
                COUNT(DISTINCT CASE WHEN status = 'success' THEN user_id END) as unique_buyers
            FROM transactions
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m'), MONTH(created_at)
            ORDER BY month DESC
        ");

        return view('admin.reports.monthly-trend', compact('monthlyData'));
    }

    /**
     * Export transaction report as CSV
     */
    public function exportTransactions()
    {
        $dateFrom = request()->query('date_from');
        $dateTo = request()->query('date_to');
        $status = request()->query('status');

        $query = "
            SELECT
                t.id,
                t.status,
                t.qty,
                t.total_price,
                t.created_at,
                u.name as user_name,
                u.email as user_email,
                p.name as product_name,
                c.name as category_name
            FROM transactions t
            JOIN users u ON t.user_id = u.id
            JOIN products p ON t.product_id = p.id
            JOIN categories c ON p.category_id = c.id
            WHERE 1=1
        ";

        $bindings = [];

        if ($dateFrom) {
            $query .= " AND DATE(t.created_at) >= ?";
            $bindings[] = $dateFrom;
        }

        if ($dateTo) {
            $query .= " AND DATE(t.created_at) <= ?";
            $bindings[] = $dateTo;
        }

        if ($status) {
            $query .= " AND t.status = ?";
            $bindings[] = $status;
        }

        $query .= " ORDER BY t.created_at DESC";

        $transactions = DB::select($query, $bindings);

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="transactions_' . date('Y-m-d_His') . '.csv"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for UTF-8

            // Header
            fputcsv($file, ['ID', 'Status', 'Qty', 'Total Price', 'Date', 'User Name', 'User Email', 'Product Name', 'Category'], ';');

            // Data
            foreach ($transactions as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->status,
                    $row->qty,
                    $row->total_price,
                    $row->created_at,
                    $row->user_name,
                    $row->user_email,
                    $row->product_name,
                    $row->category_name,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export transaction report as HTML (printable PDF)
     */
    public function exportTransactionsHtml(PdfExportService $pdfService)
    {
        $dateFrom = request()->query('date_from');
        $dateTo = request()->query('date_to');
        $status = request()->query('status');

        $query = "
            SELECT
                t.id,
                t.status,
                t.qty,
                t.total_price,
                t.created_at,
                u.name as user_name,
                u.email as user_email,
                p.name as product_name,
                c.name as category_name
            FROM transactions t
            JOIN users u ON t.user_id = u.id
            JOIN products p ON t.product_id = p.id
            JOIN categories c ON p.category_id = c.id
            WHERE 1=1
        ";

        $bindings = [];

        if ($dateFrom) {
            $query .= " AND DATE(t.created_at) >= ?";
            $bindings[] = $dateFrom;
        }

        if ($dateTo) {
            $query .= " AND DATE(t.created_at) <= ?";
            $bindings[] = $dateTo;
        }

        if ($status) {
            $query .= " AND t.status = ?";
            $bindings[] = $status;
        }

        $query .= " ORDER BY t.created_at DESC";

        $transactions = DB::select($query, $bindings);

        $columns = [
            'id' => 'ID',
            'status' => 'Status',
            'qty' => 'Qty',
            'total_price' => 'Total Price',
            'created_at' => 'Date',
            'user_name' => 'User',
            'product_name' => 'Product',
            'category_name' => 'Category',
        ];

        $data = array_map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'status' => ucfirst($transaction->status),
                'qty' => $transaction->qty,
                'total_price' => 'Rp ' . number_format($transaction->total_price, 0, ',', '.'),
                'created_at' => $transaction->created_at,
                'user_name' => $transaction->user_name,
                'product_name' => $transaction->product_name,
                'category_name' => $transaction->category_name,
            ];
        }, $transactions);

        $filePath = $pdfService->exportToHtml(
            'laporan-transaksi-' . date('Y-m-d-His'),
            'Laporan Transaksi Penjualan',
            $columns,
            $data,
            'Tabel Transaksi'
        );

        $url = $pdfService->getDownloadUrl($filePath);

        return response()->redirectTo($url)->with('success', 'Laporan berhasil diekspor. Silakan cetak ke PDF dari browser Anda.');
    }

    /**
     * Export product sales as HTML (printable PDF)
     */
    public function exportProductSalesHtml(PdfExportService $pdfService)
    {
        $categoryFilter = request()->query('category');

        $query = "
            SELECT
                p.id,
                p.name,
                p.price,
                p.stock,
                c.name as category_name,
                COUNT(CASE WHEN t.status = 'success' THEN t.id END) as success_count,
                SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END) as total_sold,
                SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END) as total_revenue
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN transactions t ON p.id = t.product_id
        ";

        $bindings = [];

        if ($categoryFilter) {
            $query .= " WHERE c.id = ?";
            $bindings[] = $categoryFilter;
        }

        $query .= " GROUP BY p.id, p.name, p.price, p.stock, c.name";
        $query .= " ORDER BY total_revenue DESC";

        $products = DB::select($query, $bindings);

        $columns = [
            'name' => 'Nama Produk',
            'price' => 'Harga',
            'stock' => 'Stok',
            'category_name' => 'Kategori',
            'total_sold' => 'Terjual',
            'total_revenue' => 'Pendapatan',
        ];

        $data = array_map(function ($product) {
            return [
                'name' => $product->name,
                'price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                'stock' => $product->stock,
                'category_name' => $product->category_name,
                'total_sold' => $product->total_sold ?? 0,
                'total_revenue' => 'Rp ' . number_format($product->total_revenue ?? 0, 0, ',', '.'),
            ];
        }, $products);

        $filePath = $pdfService->exportToHtml(
            'laporan-penjualan-produk-' . date('Y-m-d-His'),
            'Laporan Penjualan Produk',
            $columns,
            $data,
            'Analisis Penjualan'
        );

        $url = $pdfService->getDownloadUrl($filePath);

        return response()->redirectTo($url)->with('success', 'Laporan berhasil diekspor. Silakan cetak ke PDF dari browser Anda.');
    }

    /**
     * Export user activity as HTML (printable PDF)
     */
    public function exportUserActivityHtml(PdfExportService $pdfService)
    {
        $users = DB::select("
            SELECT
                u.id,
                u.name,
                u.email,
                u.created_at as registered_at,
                COUNT(DISTINCT t.id) as total_transactions,
                COUNT(DISTINCT CASE WHEN t.status = 'success' THEN t.id END) as success_count,
                SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END) as total_items_bought,
                SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END) as total_spending
            FROM users u
            LEFT JOIN transactions t ON u.id = t.user_id
            WHERE u.admin_plastani = false
            GROUP BY u.id, u.name, u.email, u.created_at
            ORDER BY total_spending DESC
        ");

        $columns = [
            'name' => 'Nama',
            'email' => 'Email',
            'registered_at' => 'Terdaftar',
            'total_transactions' => 'Total Transaksi',
            'success_count' => 'Berhasil',
            'total_items_bought' => 'Item Dibeli',
            'total_spending' => 'Total Belanja',
        ];

        $data = array_map(function ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'registered_at' => $user->registered_at,
                'total_transactions' => $user->total_transactions,
                'success_count' => $user->success_count,
                'total_items_bought' => $user->total_items_bought ?? 0,
                'total_spending' => 'Rp ' . number_format($user->total_spending ?? 0, 0, ',', '.'),
            ];
        }, $users);

        $filePath = $pdfService->exportToHtml(
            'laporan-aktivitas-user-' . date('Y-m-d-His'),
            'Laporan Aktivitas Pengguna',
            $columns,
            $data,
            'Analisis User'
        );

        $url = $pdfService->getDownloadUrl($filePath);

        return response()->redirectTo($url)->with('success', 'Laporan berhasil diekspor. Silakan cetak ke PDF dari browser Anda.');
    }
}

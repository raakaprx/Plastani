<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates VIEWs untuk advanced reporting dan analytics
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            // MySQL specific queries with detailed JOINs and aggregations
            DB::statement("
                CREATE OR REPLACE VIEW transaction_summaries AS
                SELECT
                    u.id as user_id,
                    u.name as user_name,
                    u.email as user_email,
                    t.status,
                    p.id as product_id,
                    p.name as product_name,
                    c.name as category_name,
                    COUNT(t.id) as transaction_count,
                    SUM(t.qty) as total_qty,
                    SUM(t.total_price) as total_revenue,
                    AVG(t.total_price) as avg_price,
                    MIN(t.created_at) as first_transaction,
                    MAX(t.created_at) as last_transaction
                FROM transactions t
                JOIN users u ON t.user_id = u.id
                JOIN products p ON t.product_id = p.id
                JOIN categories c ON p.category_id = c.id
                GROUP BY u.id, u.name, u.email, t.status, p.id, p.name, c.name
                ORDER BY u.id, t.status
            ");

            DB::statement("
                CREATE OR REPLACE VIEW product_sales_reports AS
                SELECT
                    p.id,
                    p.name as product_name,
                    p.slug,
                    c.name as category_name,
                    p.price,
                    p.stock,
                    COALESCE(SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END), 0) as total_sold,
                    COALESCE(SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END), 0) as total_revenue,
                    COALESCE(SUM(CASE WHEN t.status = 'pending' THEN t.qty ELSE 0 END), 0) as pending_qty,
                    COALESCE(SUM(CASE WHEN t.status = 'failed' THEN t.qty ELSE 0 END), 0) as failed_qty,
                    COALESCE(COUNT(CASE WHEN t.status = 'success' THEN 1 END), 0) as success_transaction_count,
                    COALESCE(COUNT(CASE WHEN t.status = 'pending' THEN 1 END), 0) as pending_transaction_count,
                    p.eco_rating,
                    p.is_active,
                    p.created_at,
                    p.updated_at
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN transactions t ON p.id = t.product_id
                GROUP BY p.id, p.name, p.slug, c.name, p.price, p.stock, p.eco_rating, p.is_active, p.created_at, p.updated_at
                ORDER BY total_revenue DESC
            ");

            DB::statement("
                CREATE OR REPLACE VIEW user_activity_reports AS
                SELECT
                    u.id,
                    u.name,
                    u.email,
                    u.admin_plastani,
                    u.created_at,
                    COALESCE(COUNT(DISTINCT t.id), 0) as total_transactions,
                    COALESCE(COUNT(DISTINCT CASE WHEN t.status = 'success' THEN t.id END), 0) as success_count,
                    COALESCE(COUNT(DISTINCT CASE WHEN t.status = 'pending' THEN t.id END), 0) as pending_count,
                    COALESCE(COUNT(DISTINCT CASE WHEN t.status = 'failed' THEN t.id END), 0) as failed_count,
                    COALESCE(SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END), 0) as total_spending,
                    COALESCE(SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END), 0) as total_items_bought,
                    COALESCE(MAX(t.created_at), NULL) as last_transaction_date
                FROM users u
                LEFT JOIN transactions t ON u.id = t.user_id
                GROUP BY u.id, u.name, u.email, u.admin_plastani, u.created_at
                ORDER BY total_spending DESC
            ");

            DB::statement("
                CREATE OR REPLACE VIEW transaction_audit_trail AS
                SELECT
                    t.id as transaction_id,
                    u.name as user_name,
                    u.email as user_email,
                    p.name as product_name,
                    t.qty,
                    t.total_price,
                    t.status,
                    t.notes,
                    t.created_at as transaction_created,
                    t.updated_at as transaction_updated,
                    DATE_FORMAT(t.created_at, '%Y-%m-%d') as transaction_date,
                    DATE_FORMAT(t.created_at, '%Y-%m') as transaction_month,
                    YEAR(t.created_at) as transaction_year
                FROM transactions t
                JOIN users u ON t.user_id = u.id
                JOIN products p ON t.product_id = p.id
                ORDER BY t.created_at DESC
            ");

            DB::statement("
                CREATE OR REPLACE VIEW category_performance_reports AS
                SELECT
                    c.id,
                    c.name as category_name,
                    c.slug,
                    COUNT(DISTINCT p.id) as product_count,
                    COALESCE(SUM(p.stock), 0) as total_stock,
                    COALESCE(SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END), 0) as total_sold,
                    COALESCE(SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END), 0) as total_revenue,
                    COALESCE(AVG(CASE WHEN t.status = 'success' THEN t.total_price END), 0) as avg_transaction_value,
                    COALESCE(COUNT(DISTINCT CASE WHEN t.status = 'success' THEN t.id END), 0) as success_transaction_count,
                    c.created_at,
                    c.updated_at
                FROM categories c
                LEFT JOIN products p ON c.id = p.category_id
                LEFT JOIN transactions t ON p.id = t.product_id
                GROUP BY c.id, c.name, c.slug, c.created_at, c.updated_at
                ORDER BY total_revenue DESC
            ");

            DB::statement("
                CREATE OR REPLACE VIEW monthly_revenue_trends AS
                SELECT
                    DATE_FORMAT(t.created_at, '%Y-%m') as month,
                    YEAR(t.created_at) as year,
                    MONTH(t.created_at) as month_num,
                    COUNT(DISTINCT t.id) as transaction_count,
                    SUM(CASE WHEN t.status = 'success' THEN 1 ELSE 0 END) as success_count,
                    SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END) as revenue,
                    SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END) as items_sold,
                    COUNT(DISTINCT CASE WHEN t.status = 'success' THEN t.user_id END) as unique_buyers
                FROM transactions t
                GROUP BY DATE_FORMAT(t.created_at, '%Y-%m'), YEAR(t.created_at), MONTH(t.created_at)
                ORDER BY year DESC, month_num DESC
            ");
        } elseif ($driver === 'sqlite') {
            // SQLite specific queries - simpler aggregations
            DB::statement("
                CREATE VIEW IF NOT EXISTS transaction_summaries AS
                SELECT
                    u.id as user_id,
                    u.name as user_name,
                    u.email as user_email,
                    t.status,
                    p.id as product_id,
                    p.name as product_name,
                    c.name as category_name,
                    COUNT(t.id) as transaction_count,
                    SUM(t.qty) as total_qty,
                    SUM(t.total_price) as total_revenue,
                    AVG(t.total_price) as avg_price,
                    MIN(t.created_at) as first_transaction,
                    MAX(t.created_at) as last_transaction
                FROM transactions t
                JOIN users u ON t.user_id = u.id
                JOIN products p ON t.product_id = p.id
                JOIN categories c ON p.category_id = c.id
                GROUP BY u.id, u.name, u.email, t.status, p.id, p.name, c.name
                ORDER BY u.id, t.status
            ");

            DB::statement("
                CREATE VIEW IF NOT EXISTS product_sales_reports AS
                SELECT
                    p.id,
                    p.name as product_name,
                    p.slug,
                    c.name as category_name,
                    p.price,
                    p.stock,
                    COALESCE(SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END), 0) as total_sold,
                    COALESCE(SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END), 0) as total_revenue,
                    COALESCE(SUM(CASE WHEN t.status = 'pending' THEN t.qty ELSE 0 END), 0) as pending_qty,
                    COALESCE(SUM(CASE WHEN t.status = 'failed' THEN t.qty ELSE 0 END), 0) as failed_qty,
                    COALESCE(COUNT(CASE WHEN t.status = 'success' THEN 1 END), 0) as success_transaction_count,
                    COALESCE(COUNT(CASE WHEN t.status = 'pending' THEN 1 END), 0) as pending_transaction_count,
                    p.eco_rating,
                    p.is_active,
                    p.created_at,
                    p.updated_at
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN transactions t ON p.id = t.product_id
                GROUP BY p.id, p.name, p.slug, c.name, p.price, p.stock, p.eco_rating, p.is_active, p.created_at, p.updated_at
                ORDER BY total_revenue DESC
            ");

            DB::statement("
                CREATE VIEW IF NOT EXISTS user_activity_reports AS
                SELECT
                    u.id,
                    u.name,
                    u.email,
                    u.admin_plastani,
                    u.created_at,
                    COALESCE(COUNT(DISTINCT t.id), 0) as total_transactions,
                    COALESCE(COUNT(DISTINCT CASE WHEN t.status = 'success' THEN t.id END), 0) as success_count,
                    COALESCE(COUNT(DISTINCT CASE WHEN t.status = 'pending' THEN t.id END), 0) as pending_count,
                    COALESCE(COUNT(DISTINCT CASE WHEN t.status = 'failed' THEN t.id END), 0) as failed_count,
                    COALESCE(SUM(CASE WHEN t.status = 'success' THEN t.total_price ELSE 0 END), 0) as total_spending,
                    COALESCE(SUM(CASE WHEN t.status = 'success' THEN t.qty ELSE 0 END), 0) as total_items_bought,
                    COALESCE(MAX(t.created_at), NULL) as last_transaction_date
                FROM users u
                LEFT JOIN transactions t ON u.id = t.user_id
                GROUP BY u.id, u.name, u.email, u.admin_plastani, u.created_at
                ORDER BY total_spending DESC
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS monthly_revenue_trends');
        DB::statement('DROP VIEW IF EXISTS category_performance_reports');
        DB::statement('DROP VIEW IF EXISTS transaction_audit_trail');
        DB::statement('DROP VIEW IF EXISTS user_activity_reports');
        DB::statement('DROP VIEW IF EXISTS product_sales_reports');
        DB::statement('DROP VIEW IF EXISTS transaction_summaries');
    }
};

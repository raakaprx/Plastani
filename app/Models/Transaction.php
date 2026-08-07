<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'qty',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'qty' => 'integer',
        'total_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Filter by status
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Filter successful transactions only
     */
    public function scopeSuccessful(Builder $query): Builder
    {
        return $query->where('status', 'success');
    }

    /**
     * Filter pending transactions only
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Filter failed transactions only
     */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'failed');
    }

    /**
     * Filter by user
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Filter by product
     */
    public function scopeByProduct(Builder $query, int $productId): Builder
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Filter by date range
     */
    public function scopeBetweenDates(Builder $query, string $dateFrom, string $dateTo): Builder
    {
        return $query->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
    }

    /**
     * Eager load with user and product details
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with(['user', 'product.category']);
    }

    /**
     * Get total revenue for successful transactions
     */
    public static function totalRevenue(): float
    {
        return (float) self::successful()->sum('total_price');
    }

    /**
     * Get revenue by status
     */
    public static function revenueByStatus(): array
    {
        $data = self::selectRaw('status, SUM(total_price) as revenue, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->toArray();

        return array_reduce($data, function ($carry, $item) {
            $carry[$item['status']] = [
                'revenue' => (float) $item['revenue'],
                'count' => (int) $item['count'],
            ];
            return $carry;
        }, []);
    }

    /**
     * Get monthly revenue trend (last 12 months)
     */
    public static function monthlyRevenueTrend(): array
    {
        return self::selectRaw("
            DATE_FORMAT(created_at, '%Y-%m') as month,
            SUM(CASE WHEN status = 'success' THEN total_price ELSE 0 END) as revenue,
            COUNT(CASE WHEN status = 'success' THEN id END) as success_count
        ")
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->toArray();
    }
}


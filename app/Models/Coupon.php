<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Coupon extends Model
{
    use HasFactory;

    public const TYPE_PERCENTAGE = 'percentage';
    public const TYPE_FIXED = 'fixed';
    public const TYPE_FREE_SHIPPING = 'free_shipping';


    protected $fillable = [
        'coupon_code',
        'name',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount',
        'expires_at',
        'usage_limit',
        'times_used',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    /**
     * Check if coupon is valid.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->usage_limit && $this->times_used >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if the specific user has already used this coupon.
     */
    public function isUsedByUser($userId): bool
    {
        return \App\Models\Order::where('user_id', $userId)
            ->where('coupon_code', $this->coupon_code)
            ->where('status', '!=', 'cancelled')
            ->exists();
    }
}

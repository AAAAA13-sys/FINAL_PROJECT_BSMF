<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RestockRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_NOTIFIED = 'notified';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'product_id',
        'user_id',
        'status',
        'requested_at',
        'notified_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    /**
     * Get the product for the restock request.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who requested the restock.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

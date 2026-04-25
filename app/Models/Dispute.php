<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Dispute extends Model
{
    use HasFactory;

    public const TYPE_WRONG_ITEM = 'wrong_item';
    public const TYPE_NEVER_RECEIVED = 'never_received';
    public const TYPE_DAMAGED_CARD = 'damaged_card';
    public const TYPE_NOT_AS_DESCRIBED = 'not_as_described';

    public const STATUS_PENDING = 'pending';
    public const STATUS_INVESTIGATING = 'investigating';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'order_id',
        'user_id',
        'order_item_id',
        'dispute_type',
        'description',
        'evidence_photos',
        'status',
        'resolution_notes',
        'refund_amount',
        'refund_issued',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'evidence_photos' => 'array',
        'refund_issued' => 'boolean',
        'resolved_at' => 'datetime',
        'refund_amount' => 'decimal:2',
    ];

    /**
     * Get the order associated with the dispute.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who filed the dispute.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order item associated with the dispute.
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}

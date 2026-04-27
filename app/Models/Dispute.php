<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispute_number',
        'order_id',
        'user_id',
        'subject',
        'description',
        'status',
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
     * Get the messages for the dispute.
     */
    public function messages()
    {
        return $this->hasMany(DisputeMessage::class);
    }
}

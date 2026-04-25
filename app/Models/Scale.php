<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Scale extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sort_order',
    ];

    /**
     * Get the products for the scale.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

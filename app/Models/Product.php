<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'scale_id',
        'series_id',
        'name',
        'casting_name',
        'slug',
        'year',
        'color',
        'is_treasure_hunt',
        'is_super_treasure_hunt',
        'is_chase',
        'is_rlc_exclusive',
        'card_condition',
        'is_carded',
        'is_loose',
        'price',
        'original_price',
        'stock_quantity',
        'low_stock_threshold',
        'main_image',
        'card_image',
        'loose_image',
        'additional_images',
        'short_description',
        'description',
        'is_active',
        'is_pre_order',
        'expected_release_date',
        'views',
    ];

    protected $casts = [
        'is_treasure_hunt' => 'boolean',
        'is_super_treasure_hunt' => 'boolean',
        'is_chase' => 'boolean',
        'is_rlc_exclusive' => 'boolean',
        'is_carded' => 'boolean',
        'is_loose' => 'boolean',
        'is_active' => 'boolean',
        'is_pre_order' => 'boolean',
        'additional_images' => 'array',
        'expected_release_date' => 'date',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
    ];

    /**
     * Get the brand for the product.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the scale for the product.
     */
    public function scale(): BelongsTo
    {
        return $this->belongsTo(Scale::class);
    }

    /**
     * Get the series for the product.
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }


    /**
     * Get the gallery images for the product.
     */
    public function gallery(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope for active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if product is in stock.
     */
    public function inStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Get stock status label.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock_quantity <= 0) {
            return 'Sold Out';
        }
        if ($this->stock_quantity <= $this->low_stock_threshold) {
            return 'Low Stock';
        }
        return 'In Stock';
    }
}

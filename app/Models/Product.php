<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasSlugHistory;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasUniversalSlug, HasSlugHistory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'video_url',
        'short_description',
        'long_description',
        'regular_price',
        'sale_price',
        'cost_price',
        'sale_start_at',
        'sale_end_at',
        'track_stock',
        'stock_quantity',
        'low_stock_threshold',
        'allow_backorder',
        'status',
        'is_featured',
        'seo_title',
        'seo_description',
        'seo_og_image',
        'canonical_url',
        'noindex',
        'custom_fields',
        'settings',
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'sale_start_at' => 'datetime',
        'sale_end_at' => 'datetime',
        'track_stock' => 'boolean',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'allow_backorder' => 'boolean',
        'is_featured' => 'boolean',
        'noindex' => 'boolean',
        'custom_fields' => 'array',
        'settings' => 'array',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(ProductOffer::class);
    }

    public function landingPage(): HasOne
    {
        return $this->hasOne(LandingPage::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the active selling price (considering sale schedule).
     */
    public function getEffectivePriceAttribute(): float
    {
        if ($this->sale_price !== null && (float) $this->sale_price > 0) {
            $now = now();
            $started = $this->sale_start_at === null || $now->gte($this->sale_start_at);
            $notEnded = $this->sale_end_at === null || $now->lte($this->sale_end_at);

            if ($started && $notEnded) {
                return (float) $this->sale_price;
            }
        }

        return (float) $this->regular_price;
    }

    /**
     * Calculate discount percentage if sale price is active.
     */
    public function getDiscountPercentAttribute(): int
    {
        $regular = (float) $this->regular_price;
        $effective = $this->effective_price;

        if ($regular > 0 && $effective < $regular) {
            return (int) round((($regular - $effective) / $regular) * 100);
        }

        return 0;
    }

    /**
     * Check if product is in stock.
     */
    public function isInStock(): bool
    {
        if (!$this->track_stock || $this->allow_backorder) {
            return true;
        }

        return $this->stock_quantity > 0;
    }

    /**
     * Primary image URL or placeholder.
     */
    public function getPrimaryImageUrlAttribute(): string
    {
        $image = $this->images->firstWhere('is_primary', true) ?? $this->images->first();

        if ($image && !empty($image->image_path)) {
            return str_starts_with($image->image_path, 'http')
                ? $image->image_path
                : asset('storage/' . $image->image_path);
        }

        return 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600"><rect width="100%" height="100%" fill="%23f1f5f9"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-size="24" fill="%2364748b">No Image Available</text></svg>';
    }
}

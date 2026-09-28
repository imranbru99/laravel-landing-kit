<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'name',
        'title',
        'discount_type',
        'discount_amount',
        'min_quantity',
        'buy_x_get_y',
        'is_active',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'min_quantity' => 'integer',
        'buy_x_get_y' => 'array',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

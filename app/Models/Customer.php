<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'phone',
        'name',
        'email',
        'default_address',
        'district_id',
        'thana_id',
        'total_orders',
        'total_spent',
        'delivered_orders_count',
        'returned_orders_count',
        'cancelled_orders_count',
        'success_rate',
        'is_blocked',
        'notes',
    ];

    protected $casts = [
        'total_orders' => 'integer',
        'total_spent' => 'decimal:2',
        'delivered_orders_count' => 'integer',
        'returned_orders_count' => 'integer',
        'cancelled_orders_count' => 'integer',
        'success_rate' => 'decimal:2',
        'is_blocked' => 'boolean',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function thana(): BelongsTo
    {
        return $this->belongsTo(Thana::class, 'thana_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->latest();
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /**
     * Recalculate customer statistics based on orders.
     */
    public function recalculateMetrics(): void
    {
        $this->total_orders = $this->orders()->count();
        $this->delivered_orders_count = $this->orders()->whereIn('status', [\App\Enums\OrderStatus::Delivered->value, \App\Enums\OrderStatus::Paid->value])->count();
        $this->returned_orders_count = $this->orders()->whereIn('status', [\App\Enums\OrderStatus::Returned->value, \App\Enums\OrderStatus::ReturnReceived->value])->count();
        $this->cancelled_orders_count = $this->orders()->where('status', \App\Enums\OrderStatus::Cancelled->value)->count();

        $this->total_spent = (float) $this->orders()->whereIn('status', [\App\Enums\OrderStatus::Delivered->value, \App\Enums\OrderStatus::Paid->value])->sum('total_amount');

        if ($this->total_orders > 0) {
            $this->success_rate = round(($this->delivered_orders_count / $this->total_orders) * 100, 2);
        } else {
            $this->success_rate = 100.00;
        }

        $this->save();
    }

    /**
     * Get masked display name for guest phone lookup privacy.
     */
    public function getMaskedNameAttribute(): string
    {
        $parts = explode(' ', trim($this->name));
        $first = $parts[0] ?? '';

        if (count($parts) > 1) {
            return $first . ' ' . substr($parts[1], 0, 1) . '...';
        }

        return $first;
    }
}

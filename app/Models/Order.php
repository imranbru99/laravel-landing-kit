<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'order_token',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'district_id',
        'thana_id',
        'delivery_zone_id',
        'status',
        'subtotal',
        'delivery_charge',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_trx_id',
        'payment_sender_number',
        'customer_notes',
        'admin_notes',
        'courier_driver',
        'courier_tracking_id',
        'courier_consignment_id',
        'courier_status',
        'courier_sent_at',
        'ip_address',
        'user_agent',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'fbclid',
        'gclid',
        'ttclid',
        'fraud_score',
        'fraud_flags',
        'purchase_tracked_at',
        'stock_decremented',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'subtotal' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'fraud_score' => 'integer',
        'fraud_flags' => 'array',
        'stock_decremented' => 'boolean',
        'courier_sent_at' => 'datetime',
        'purchase_tracked_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_token)) {
                $order->order_token = Str::random(40);
            }

            if (empty($order->order_number)) {
                $prefix = (string) setting('order_number_prefix', 'ORD');
                $date = now()->format('Ym');
                $random = strtoupper(Str::random(5));
                $order->order_number = "{$prefix}-{$date}-{$random}";
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function notes(): HasMany
    {
        return $this->hasMany(OrderNote::class)->latest();
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function thana(): BelongsTo
    {
        return $this->belongsTo(Thana::class);
    }

    public function deliveryZone(): BelongsTo
    {
        return $this->belongsTo(DeliveryZone::class);
    }
}

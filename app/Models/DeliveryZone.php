<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'charge',
        'estimated_days',
        'is_active',
    ];

    protected $casts = [
        'charge' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}

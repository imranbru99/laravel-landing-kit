<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $table = 'bd_districts';

    protected $fillable = [
        'name_en',
        'name_bn',
        'division',
        'is_inside_dhaka',
        'is_sub_dhaka',
    ];

    protected $casts = [
        'is_inside_dhaka' => 'boolean',
        'is_sub_dhaka' => 'boolean',
    ];

    public function thanas(): HasMany
    {
        return $this->hasMany(Thana::class, 'district_id');
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'landing_page_id',
        'section_type',
        'position',
        'is_visible',
        'content',
        'style',
        'responsive',
    ];

    protected $casts = [
        'position' => 'integer',
        'is_visible' => 'boolean',
        'content' => 'array',
        'style' => 'array',
        'responsive' => 'array',
    ];

    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }
}

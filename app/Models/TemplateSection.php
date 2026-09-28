<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'section_type',
        'position',
        'content',
        'style',
        'responsive',
    ];

    protected $casts = [
        'position' => 'integer',
        'content' => 'array',
        'style' => 'array',
        'responsive' => 'array',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}

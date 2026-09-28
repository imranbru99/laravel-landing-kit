<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Template extends Model
{
    use HasFactory, HasUniversalSlug, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'thumbnail',
        'tags',
        'palette',
        'default_language',
        'sample_product_data',
        'is_active',
    ];

    protected $casts = [
        'tags' => 'array',
        'palette' => 'array',
        'sample_product_data' => 'array',
        'is_active' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(TemplateSection::class)->orderBy('position');
    }
}

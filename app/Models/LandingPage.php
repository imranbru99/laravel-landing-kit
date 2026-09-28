<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class LandingPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'template_id',
        'title',
        'status',
        'theme_tokens',
        'custom_css',
        'custom_js',
        'settings',
        'published_at',
    ];

    protected $casts = [
        'theme_tokens' => 'array',
        'settings' => 'array',
        'published_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('position');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PageRevision::class)->latest();
    }

    /**
     * Create an immutable revision snapshot of the current landing page.
     */
    public function createRevision(?User $user = null, ?string $title = null): PageRevision
    {
        $sections = $this->sections()->get()->map(fn ($s) => [
            'section_type' => $s->section_type,
            'position' => $s->position,
            'is_visible' => $s->is_visible,
            'content' => $s->content,
            'style' => $s->style,
            'responsive' => $s->responsive,
        ])->toArray();

        return PageRevision::create([
            'landing_page_id' => $this->id,
            'user_id' => $user?->id,
            'title' => $title ?: 'Autosave ' . now()->format('d M Y, h:i A'),
            'snapshot' => [
                'page' => [
                    'theme_tokens' => $this->theme_tokens,
                    'custom_css' => $this->custom_css,
                    'custom_js' => $this->custom_js,
                    'settings' => $this->settings,
                ],
                'sections' => $sections,
            ],
        ]);
    }

    /**
     * Restore landing page state from a revision.
     */
    public function restoreRevision(PageRevision $revision): void
    {
        DB::transaction(function () use ($revision) {
            $snapshot = $revision->snapshot;
            $pageData = $snapshot['page'] ?? [];
            $sectionsData = $snapshot['sections'] ?? [];

            $this->update([
                'theme_tokens' => $pageData['theme_tokens'] ?? null,
                'custom_css' => $pageData['custom_css'] ?? null,
                'custom_js' => $pageData['custom_js'] ?? null,
                'settings' => $pageData['settings'] ?? null,
            ]);

            // Re-create sections
            $this->sections()->delete();
            foreach ($sectionsData as $index => $section) {
                PageSection::create([
                    'landing_page_id' => $this->id,
                    'section_type' => $section['section_type'],
                    'position' => $section['position'] ?? $index,
                    'is_visible' => $section['is_visible'] ?? true,
                    'content' => $section['content'] ?? [],
                    'style' => $section['style'] ?? [],
                    'responsive' => $section['responsive'] ?? [],
                ]);
            }
        });
    }

    /**
     * Apply a pre-built template to this landing page.
     */
    public function applyTemplate(Template $template): void
    {
        DB::transaction(function () use ($template) {
            // Take backup revision first
            $this->createRevision(title: 'Before applying template: ' . $template->name);

            $this->update([
                'template_id' => $template->id,
                'theme_tokens' => $template->palette,
            ]);

            $this->sections()->delete();

            foreach ($template->sections as $sec) {
                PageSection::create([
                    'landing_page_id' => $this->id,
                    'section_type' => $sec->section_type,
                    'position' => $sec->position,
                    'is_visible' => true,
                    'content' => $sec->content,
                    'style' => $sec->style,
                    'responsive' => $sec->responsive,
                ]);
            }
        });
    }
}

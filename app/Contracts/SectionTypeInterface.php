<?php

declare(strict_types=1);

namespace App\Contracts;

interface SectionTypeInterface
{
    /**
     * Unique identifier for the section (e.g. 'hero_image_right').
     */
    public function key(): string;

    /**
     * Human-readable label for UI display.
     */
    public function label(): string;

    /**
     * Category grouping (hero, showcase, features, social_proof, offer, order_form, faq, guarantee, content, media, cta, footer, header, category_specific).
     */
    public function category(): string;

    /**
     * Heroicon or icon identifier.
     */
    public function icon(): string;

    /**
     * Form fields definition for Filament section editor.
     */
    public function schema(): array;

    /**
     * Default content structure and initial values.
     */
    public function defaults(): array;

    /**
     * Blade template path (e.g. 'sections.hero.image-right').
     */
    public function view(): string;

    /**
     * SVG thumbnail or HTML preview snippet.
     */
    public function preview(): string;
}

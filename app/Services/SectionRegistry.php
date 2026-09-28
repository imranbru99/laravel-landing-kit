<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SectionTypeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class SectionRegistry
{
    /**
     * Registered section types keyed by section identifier.
     *
     * @var array<string, SectionTypeInterface>
     */
    protected array $sections = [];

    /**
     * Register a section type instance.
     */
    public function register(SectionTypeInterface $section): self
    {
        $this->sections[$section->key()] = $section;
        return $this;
    }

    /**
     * Get all registered sections.
     *
     * @return Collection<string, SectionTypeInterface>
     */
    public function all(): Collection
    {
        return collect($this->sections);
    }

    /**
     * Get a registered section by key.
     */
    public function get(string $key): ?SectionTypeInterface
    {
        return $this->sections[$key] ?? null;
    }

    /**
     * Check if a section key is registered.
     */
    public function has(string $key): bool
    {
        return isset($this->sections[$key]);
    }

    /**
     * Get sections filtered by category.
     *
     * @return Collection<string, SectionTypeInterface>
     */
    public function byCategory(?string $category = null): Collection
    {
        if (!$category || $category === 'all') {
            return $this->all();
        }

        return $this->all()->filter(fn (SectionTypeInterface $s) => $s->category() === $category);
    }

    /**
     * Get list of unique categories and count of sections in each.
     */
    public function categories(): array
    {
        return [
            'hero' => 'Hero & Banner',
            'showcase' => 'Product Showcase',
            'features' => 'Features & Benefits',
            'social_proof' => 'Social Proof & Reviews',
            'offer' => 'Offers & Urgency',
            'order_form' => 'Order Form & Checkout',
            'faq' => 'Frequently Asked Questions',
            'guarantee' => 'Guarantee & Trust',
            'content' => 'Content & Story',
            'media' => 'Media & Video',
            'cta' => 'Call to Action (CTA)',
            'footer' => 'Contact & Footer',
            'header' => 'Header & Announcement',
            'category_specific' => 'Niche / Category-Specific',
        ];
    }

    /**
     * Count total registered sections.
     */
    public function count(): int
    {
        return count($this->sections);
    }

    /**
     * Render a section to HTML with its content, style, and context data.
     */
    public function render(string $key, array $content = [], array $style = [], array $context = []): string
    {
        $section = $this->get($key);
        if (!$section) {
            return "<!-- Section [{$key}] not found in registry -->";
        }

        $mergedContent = array_merge($section->defaults(), $content);
        $viewPath = $section->view();

        if (!View::exists($viewPath)) {
            // Render generic fallback card if specific Blade view doesn't exist yet
            return view('sections.generic-fallback', [
                'section' => $section,
                'content' => $mergedContent,
                'style' => $style,
                'context' => $context,
            ])->render();
        }

        return view($viewPath, array_merge($context, [
            'content' => $mergedContent,
            'style' => $style,
            'section' => $section,
        ]))->render();
    }
}

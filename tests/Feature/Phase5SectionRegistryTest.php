<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\SectionRegistry;
use Tests\TestCase;

class Phase5SectionRegistryTest extends TestCase
{
    public function test_section_registry_contains_at_least_100_sections(): void
    {
        /** @var SectionRegistry $registry */
        $registry = app(SectionRegistry::class);

        $sections = $registry->all();

        $this->assertGreaterThanOrEqual(100, count($sections), 'Expected at least 100 registered sections');
    }

    public function test_section_registry_categories_coverage(): void
    {
        /** @var SectionRegistry $registry */
        $registry = app(SectionRegistry::class);

        $categories = array_keys($registry->categories());

        $this->assertContains('hero', $categories);
        $this->assertContains('showcase', $categories);
        $this->assertContains('features', $categories);
        $this->assertContains('social_proof', $categories);
        $this->assertContains('offer', $categories);
        $this->assertContains('order_form', $categories);
        $this->assertContains('faq', $categories);
        $this->assertContains('guarantee', $categories);
        $this->assertContains('content', $categories);
        $this->assertContains('media', $categories);
        $this->assertContains('cta', $categories);
        $this->assertContains('footer', $categories);
        $this->assertContains('header', $categories);
        $this->assertContains('category_specific', $categories);

        $heroSections = $registry->byCategory('hero');
        $this->assertGreaterThanOrEqual(10, count($heroSections));

        $orderFormSections = $registry->byCategory('order_form');
        $this->assertGreaterThanOrEqual(5, count($orderFormSections));
    }

    public function test_each_section_has_required_attributes_and_renders(): void
    {
        /** @var SectionRegistry $registry */
        $registry = app(SectionRegistry::class);

        $heroRight = $registry->get('hero_image_right');
        $this->assertNotNull($heroRight);
        $this->assertEquals('Hero With Image Right', $heroRight->label());
        $this->assertEquals('hero', $heroRight->category());
        $this->assertNotEmpty($heroRight->defaults());

        // Test render generic fallback or configured view
        $html = $registry->render('hero_image_right', ['title' => 'Test Hero Title']);
        $this->assertStringContainsString('Test Hero Title', $html);
    }
}

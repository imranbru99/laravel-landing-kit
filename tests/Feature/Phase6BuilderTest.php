<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\LandingPage;
use App\Models\PageRevision;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\SavedSection;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class Phase6BuilderTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected Product $product;
    protected LandingPage $landingPage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin_builder_' . uniqid() . '@test.com',
        ]);

        $this->product = Product::create([
            'name' => 'Premium Panjabi 2026',
            'regular_price' => 2500,
            'sale_price' => 1950,
            'status' => 'active',
        ]);

        $this->landingPage = LandingPage::create([
            'product_id' => $this->product->id,
            'title' => 'Panjabi Landing Page',
            'status' => 'draft',
            'theme_tokens' => [
                'primary_color' => '#10b981',
                'accent_color' => '#f59e0b',
            ],
        ]);
    }

    public function test_builder_for_product_creates_landing_page_if_missing_and_redirects(): void
    {
        $newProduct = Product::create([
            'name' => 'Leather Wallet 2026',
            'regular_price' => 1200,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.builder', $newProduct));

        $response->assertRedirect();
        $this->assertDatabaseHas('landing_pages', [
            'product_id' => $newProduct->id,
        ]);

        $page = $newProduct->fresh()->landingPage;
        $this->assertNotEmpty($page->sections);
    }

    public function test_builder_view_renders_successfully_for_authenticated_admin(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.builder.index', $this->landingPage));

        $response->assertOk();
        $response->assertSee('VISUAL BUILDER');
        $response->assertSee('সেকশন লাইব্রেরি');
        $response->assertSee('Premium Panjabi 2026');
    }

    public function test_canvas_renders_all_visible_sections_in_order(): void
    {
        PageSection::create([
            'landing_page_id' => $this->landingPage->id,
            'section_type' => 'hero_image_right',
            'position' => 0,
            'is_visible' => true,
            'content' => ['title' => 'ঈদ স্পেশাল পাঞ্জাবি কালেকশন'],
        ]);

        PageSection::create([
            'landing_page_id' => $this->landingPage->id,
            'section_type' => 'features_grid_3col',
            'position' => 1,
            'is_visible' => true,
            'content' => ['title' => 'আমাদের বিশেষত্ব'],
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.builder.canvas', $this->landingPage));

        $response->assertOk();
        $response->assertSee('ঈদ স্পেশাল পাঞ্জাবি কালেকশন');
        $response->assertSee('আমাদের বিশেষত্ব');
    }

    public function test_admin_can_add_duplicate_and_delete_sections(): void
    {
        // 1. Add section
        $addResponse = $this->actingAs($this->admin)
            ->postJson(route('admin.builder.add-section', $this->landingPage), [
                'section_type' => 'faq_accordion',
            ]);

        $addResponse->assertOk();
        $addResponse->assertJson(['success' => true]);

        $sectionId = $addResponse->json('section.id');
        $this->assertDatabaseHas('page_sections', [
            'id' => $sectionId,
            'section_type' => 'faq_accordion',
        ]);

        // 2. Duplicate section
        $dupResponse = $this->actingAs($this->admin)
            ->postJson("/admin/landing-pages/{$this->landingPage->id}/builder/sections/{$sectionId}/duplicate");

        $dupResponse->assertOk();
        $dupResponse->assertJson(['success' => true]);
        $dupSectionId = $dupResponse->json('section.id');
        $this->assertNotEquals($sectionId, $dupSectionId);

        // 3. Delete section
        $delResponse = $this->actingAs($this->admin)
            ->deleteJson("/admin/landing-pages/{$this->landingPage->id}/builder/sections/{$sectionId}");

        $delResponse->assertOk();
        $this->assertDatabaseMissing('page_sections', ['id' => $sectionId]);
    }

    public function test_admin_can_reorder_and_update_section_content(): void
    {
        $sec1 = PageSection::create([
            'landing_page_id' => $this->landingPage->id,
            'section_type' => 'hero_centered',
            'position' => 0,
            'content' => ['title' => 'Old Title'],
        ]);

        $sec2 = PageSection::create([
            'landing_page_id' => $this->landingPage->id,
            'section_type' => 'social_testimonial_slider',
            'position' => 1,
            'content' => ['title' => 'Reviews'],
        ]);

        // Reorder (swap sec2 to 0, sec1 to 1)
        $reorderResponse = $this->actingAs($this->admin)
            ->postJson(route('admin.builder.reorder-sections', $this->landingPage), [
                'order' => [$sec2->id, $sec1->id],
            ]);

        $reorderResponse->assertOk();
        $this->assertEquals(0, $sec2->fresh()->position);
        $this->assertEquals(1, $sec1->fresh()->position);

        // Update sec1 content
        $updateResponse = $this->actingAs($this->admin)
            ->postJson("/admin/landing-pages/{$this->landingPage->id}/builder/sections/{$sec1->id}/update", [
                'content' => ['title' => 'Updated Glorious Title'],
                'style' => ['bg_color' => '#f0fdf4'],
            ]);

        $updateResponse->assertOk();
        $this->assertEquals('Updated Glorious Title', $sec1->fresh()->content['title']);
        $this->assertEquals('#f0fdf4', $sec1->fresh()->style['bg_color']);
    }

    public function test_save_reusable_and_insert_saved_section(): void
    {
        $sec = PageSection::create([
            'landing_page_id' => $this->landingPage->id,
            'section_type' => 'guarantee_money_back',
            'position' => 0,
            'content' => ['title' => '৭ দিনের শতভাগ মানিব্যাক গ্যারান্টি'],
            'style' => ['bg_color' => '#ffffff'],
        ]);

        // Save as reusable
        $saveResponse = $this->actingAs($this->admin)
            ->postJson("/admin/landing-pages/{$this->landingPage->id}/builder/sections/{$sec->id}/save-reusable", [
                'name' => 'Standard Money-Back Badge',
            ]);

        $saveResponse->assertOk();
        $savedSectionId = $saveResponse->json('saved_section.id');
        $this->assertNotNull($savedSectionId);

        // Insert saved section
        $insertResponse = $this->actingAs($this->admin)
            ->postJson(route('admin.builder.insert-saved-section', $this->landingPage), [
                'saved_section_id' => $savedSectionId,
            ]);

        $insertResponse->assertOk();
        $this->assertDatabaseHas('page_sections', [
            'landing_page_id' => $this->landingPage->id,
            'section_type' => 'guarantee_money_back',
        ]);
    }

    public function test_revisions_create_and_restore(): void
    {
        $sec = PageSection::create([
            'landing_page_id' => $this->landingPage->id,
            'section_type' => 'hero_image_right',
            'position' => 0,
            'content' => ['title' => 'Original Before Revision'],
        ]);

        // Create revision
        $revResponse = $this->actingAs($this->admin)
            ->postJson(route('admin.builder.create-revision', $this->landingPage), [
                'title' => 'Snapshot V1',
            ]);

        $revResponse->assertOk();
        $revisionId = $revResponse->json('revision.id');
        $this->assertNotNull($revisionId);

        // Modify section
        $sec->update(['content' => ['title' => 'Changed After Revision']]);
        $this->assertEquals('Changed After Revision', $sec->fresh()->content['title']);

        // Restore revision
        $restoreResponse = $this->actingAs($this->admin)
            ->postJson("/admin/landing-pages/{$this->landingPage->id}/builder/revisions/{$revisionId}/restore");

        $restoreResponse->assertOk();
        $restoredSec = $this->landingPage->sections()->first();
        $this->assertEquals('Original Before Revision', $restoredSec->content['title']);
    }

    public function test_publish_toggle_updates_landing_page_status(): void
    {
        $this->assertEquals('draft', $this->landingPage->status);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.builder.publish', $this->landingPage), [
                'publish' => true,
            ]);

        $response->assertOk();
        $this->assertEquals('published', $this->landingPage->fresh()->status);
        $this->assertNotNull($this->landingPage->fresh()->published_at);
    }
}

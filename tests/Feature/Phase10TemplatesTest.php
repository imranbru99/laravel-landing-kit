<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\LandingPage;
use App\Models\Product;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class Phase10TemplatesTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected Product $product;
    protected LandingPage $landingPage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'templates_test_' . uniqid() . '@example.com',
        ]);

        $this->product = Product::create([
            'name' => 'Demo Template Product',
            'regular_price' => 1000,
            'status' => 'active',
        ]);

        $this->landingPage = LandingPage::create([
            'product_id' => $this->product->id,
            'title' => 'Landing Page Before Template',
            'status' => 'draft',
        ]);
    }

    public function test_database_contains_at_least_100_templates_across_all_categories(): void
    {
        $count = Template::where('is_active', true)->count();
        $this->assertGreaterThanOrEqual(100, $count, 'Expected at least 100 templates in database');

        $categories = Template::select('category')->distinct()->pluck('category')->toArray();

        $expectedCategories = [
            'fashion',
            'footwear',
            'beauty',
            'health',
            'grocery',
            'electronics',
            'home',
            'kids',
            'books',
            'automotive',
            'gifts',
            'craft',
            'universal',
        ];

        foreach ($expectedCategories as $cat) {
            $this->assertContains($cat, $categories, "Category {$cat} must exist in seeded templates");
        }
    }

    public function test_apply_template_to_landing_page_copies_sections_and_takes_revision_backup(): void
    {
        $template = Template::where('slug', 'fashion-panjabi-eid')->firstOrFail();

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.builder.apply-template', [
                'landingPage' => $this->landingPage,
                'template' => $template,
            ]));

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->landingPage->refresh();
        $this->assertEquals($template->id, $this->landingPage->template_id);
        $this->assertNotEmpty($this->landingPage->sections);
        $this->assertGreaterThanOrEqual(4, $this->landingPage->sections->count());

        // Verify a revision backup was taken automatically
        $this->assertDatabaseHas('page_revisions', [
            'landing_page_id' => $this->landingPage->id,
        ]);
    }
}

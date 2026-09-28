<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\LandingPage;
use App\Models\Product;
use App\Models\User;
use App\Services\AI\AiLandingGenerator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use ImranDevBd\AiHub\Facades\AIHub;
use Tests\TestCase;

class Phase9AiBuilderTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected Product $product;
    protected LandingPage $landingPage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'ai_test_' . uniqid() . '@example.com',
        ]);

        $this->product = Product::create([
            'name' => 'Organic Black Seed Oil',
            'regular_price' => 850,
            'sale_price' => 750,
            'status' => 'active',
        ]);

        $this->landingPage = LandingPage::create([
            'product_id' => $this->product->id,
            'title' => 'Black Seed Oil Landing',
            'status' => 'draft',
        ]);
    }

    public function test_ai_generator_returns_validated_and_sanitized_sections(): void
    {
        $mockJson = json_encode([
            [
                'section_type' => 'hero_image_right',
                'content' => [
                    'title' => '১০০% খাঁটি কালোজিরা তেল <script>alert("xss")</script>',
                    'subtitle' => 'রোগ প্রতিরোধ ক্ষমতা বৃদ্ধিতে অনন্য প্রাকৃতিক মহৌষধ।',
                    'badge' => 'প্রাকৃতিক বিশুদ্ধতা',
                ],
            ],
            [
                'section_type' => 'features_grid_3col',
                'content' => [
                    'title' => 'কালোজিরা তেলের স্বাস্থ্য উপকারিতা',
                    'items' => [
                        ['title' => 'ইমিউনিটি বুস্টার', 'desc' => 'শরীরের রোগ প্রতিরোধ ক্ষমতা দ্বিগুণ করে।'],
                    ],
                ],
            ],
            [
                'section_type' => 'malicious_unknown_type', // Must be discarded!
                'content' => ['title' => 'Should be discarded'],
            ],
        ]);

        AIHub::fake([
            $mockJson,
        ]);

        /** @var AiLandingGenerator $generator */
        $generator = app(AiLandingGenerator::class);
        $sections = $generator->generateLandingPage([
            'product_name' => 'Organic Black Seed Oil',
            'category' => 'Health & Wellness',
        ]);

        $this->assertNotEmpty($sections);
        $this->assertCount(2, $sections); // The unknown section was stripped

        // Verify XSS script tags were stripped
        $this->assertStringNotContainsString('<script>', $sections[0]['content']['title']);
        $this->assertStringContainsString('১০০% খাঁটি কালোজিরা তেল', $sections[0]['content']['title']);
    }

    public function test_ai_generator_falls_back_gracefully_on_invalid_output(): void
    {
        AIHub::fake([
            'I cannot help with that or this is not valid JSON at all.',
        ]);

        /** @var AiLandingGenerator $generator */
        $generator = app(AiLandingGenerator::class);
        $sections = $generator->generateLandingPage([
            'product_name' => 'Premium Mustard Oil',
        ]);

        $this->assertNotEmpty($sections);
        $this->assertGreaterThanOrEqual(4, count($sections));
        $this->assertEquals('hero_image_right', $sections[0]['section_type']);
    }

    public function test_ai_rewrite_and_seo_generation(): void
    {
        AIHub::fake([
            'এই সুযোগ হাতছাড়া করবেন না! এখনই অর্ডার করুন সেরা দামে।',
            json_encode([
                'seo_title' => 'খাঁটি সরিষার তেল - ১০০% ঘানি ভাঙা | ক্যাশ অন ডেলিভারি',
                'seo_description' => 'সরাসরি গ্রাম থেকে সংগৃহীত খাঁটি সরিষার তেল। সারা বাংলাদেশে ফ্রি ডেলিভারি।',
            ]),
        ]);

        /** @var AiLandingGenerator $generator */
        $generator = app(AiLandingGenerator::class);

        $rewritten = $generator->rewriteCopy('অর্ডার করুন', 'more_urgent');
        $this->assertStringContainsString('এই সুযোগ হাতছাড়া করবেন না', $rewritten);

        $seo = $generator->generateSeoMeta('খাঁটি সরিষার তেল');
        $this->assertArrayHasKey('seo_title', $seo);
        $this->assertArrayHasKey('seo_description', $seo);
    }

    public function test_controller_generates_full_page_and_creates_draft_sections(): void
    {
        AIHub::fake([
            json_encode([
                [
                    'section_type' => 'hero_image_right',
                    'content' => ['title' => 'AI Generated Hero'],
                ],
                [
                    'section_type' => 'order_form_classic',
                    'content' => ['title' => 'AI Generated Order Form'],
                ],
            ]),
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.builder.ai.generate-full-page', $this->landingPage), [
                'product_name' => 'Smart Watch Pro',
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertEquals(2, $this->landingPage->fresh()->sections()->count());
        $this->assertDatabaseHas('page_sections', [
            'landing_page_id' => $this->landingPage->id,
            'section_type' => 'hero_image_right',
        ]);
    }
}

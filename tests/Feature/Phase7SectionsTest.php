<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\LandingPage;
use App\Models\PageSection;
use App\Models\Product;
use App\Services\SectionRegistry;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class Phase7SectionsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_custom_blade_views_render_with_product_data(): void
    {
        /** @var SectionRegistry $registry */
        $registry = app(SectionRegistry::class);

        $product = Product::create([
            'name' => 'Premium Men Panjabi',
            'regular_price' => 2800,
            'sale_price' => 2200,
            'status' => 'active',
        ]);

        // Render Hero Image Right
        $heroHtml = $registry->render('hero_image_right', [
            'title' => 'এক্সক্লুসিভ লাক্সারি পাঞ্জাবি',
            'badge' => 'ঈদ ধামাকা অফার',
        ], [], ['product' => $product]);

        $this->assertStringContainsString('এক্সক্লুসিভ লাক্সারি পাঞ্জাবি', $heroHtml);
        $this->assertStringContainsString('ঈদ ধামাকা অফার', $heroHtml);
        $this->assertStringContainsString('২,২০০', $heroHtml);

        // Render Classic Order Form
        $formHtml = $registry->render('order_form_classic', [
            'title' => 'সহজ অর্ডার ফর্ম',
        ], [], ['product' => $product]);

        $this->assertStringContainsString('সহজ অর্ডার ফর্ম', $formHtml);
        $this->assertStringContainsString('customer_phone', $formHtml);
        $this->assertStringContainsString('website_url', $formHtml); // Honeypot
    }

    public function test_product_page_renders_dynamic_sections_when_published(): void
    {
        $product = Product::create([
            'name' => 'Organic Pure Honey BD',
            'slug' => 'organic-pure-honey-bd-'.uniqid(),
            'regular_price' => 1500,
            'sale_price' => 1250,
            'status' => 'active',
        ]);

        $landingPage = LandingPage::create([
            'product_id' => $product->id,
            'title' => 'Honey Special Landing Page',
            'status' => 'published',
            'theme_tokens' => ['primary_color' => '#10b981'],
        ]);

        PageSection::create([
            'landing_page_id' => $landingPage->id,
            'section_type' => 'hero_image_right',
            'position' => 0,
            'is_visible' => true,
            'content' => ['title' => 'খাঁটি সুন্দরবনের প্রাকৃতিক মধু'],
        ]);

        PageSection::create([
            'landing_page_id' => $landingPage->id,
            'section_type' => 'order_form_classic',
            'position' => 1,
            'is_visible' => true,
            'content' => ['title' => 'ক্যাশ অন ডেলিভারিতে অর্ডার করুন'],
        ]);

        $response = $this->get('/'.$product->slug);

        $response->assertOk();
        $response->assertSee('খাঁটি সুন্দরবনের প্রাকৃতিক মধু');
        $response->assertSee('ক্যাশ অন ডেলিভারিতে অর্ডার করুন');
    }
}

# Section Library & Developer Guide

**Laravel Landing Kit** includes an extensible Section Architecture and over 107 prebuilt, responsive sections specifically designed for Bangladeshi e-commerce.

---

## 1. Section Catalog Breakdown (107+ Sections)

All sections are auto-registered in `App\Services\SectionRegistry` through `App\Services\SectionCatalog`.

| Category | Count | Key Sections Included |
| :--- | :--- | :--- |
| **Hero & Banner** | 12 | `hero_image_right`, `hero_image_left`, `hero_centered`, `hero_video_bg`, `hero_countdown_offer`, `hero_split_form`, `hero_minimal`, `hero_floating_badge`, `hero_slider`, `hero_testimonial_strip`, etc. |
| **Product Showcase** | 10 | `showcase_gallery_grid`, `showcase_slider_thumbs`, `showcase_360_preview`, `showcase_zoom`, `showcase_feature_callouts`, `showcase_before_after`, `showcase_video_review`, `showcase_unboxing`, `showcase_size_chart`, `showcase_variants` |
| **Features & Benefits** | 12 | `features_grid_3col`, `features_grid_4col`, `features_alternating_rows`, `features_checklist`, `features_numbered_steps`, `features_cards`, `features_big_image`, `features_us_vs_them`, `features_specs_table`, `features_why_choose_us`, `features_problem_solution` |
| **Social Proof & Reviews** | 12 | `proof_testimonial_cards`, `proof_slider`, `proof_screenshot_reviews` (WhatsApp/FB chat style), `proof_video_testimonials`, `proof_rating_summary`, `proof_review_wall`, `proof_customer_photos`, `proof_logos_strip`, `proof_counter_sold`, `proof_live_popup`, `proof_trust_badges` |
| **Offers & Urgency** | 10 | `offer_countdown_banner`, `offer_limited_stock_bar`, `offer_discount_badge`, `offer_bundle_cards`, `offer_buy_more_save_more`, `offer_free_delivery_strip`, `offer_flash_sale_ticker`, `offer_coupon_box`, `offer_price_comparison`, `offer_sticky_offer_bar` |
| **Order Form & Checkout** | 10 | `order_form_classic`, `order_form_summary_split`, `order_form_two_column`, `order_form_sticky_mobile`, `order_form_popup_modal`, `order_form_minimal_1step`, `order_form_variant_picker`, `order_form_quantity_radios`, `order_form_bump_offer`, `order_form_zone_selector` |
| **Frequently Asked Questions** | 5 | `faq_accordion_modern`, `faq_two_column`, `faq_categorized_tabs`, `faq_with_hotline_cta`, `faq_minimal_clean` |
| **Guarantee & Trust** | 6 | `trust_money_back_guarantee`, `trust_cod_assurance`, `trust_replacement_warranty`, `trust_return_policy`, `trust_secure_order_steps`, `trust_delivery_promise` |
| **Content & Brand Story** | 8 | `content_rich_text`, `content_image_with_story`, `content_video_narrative`, `content_timeline_journey`, `content_about_brand`, `content_founder_note`, `content_editorial_article`, `content_stats_counters` |
| **Media & Videos** | 5 | `media_video_embed`, `media_video_grid`, `media_masonry_gallery`, `media_curated_lookbook`, `media_instagram_feed` |
| **Call to Action (CTA)** | 6 | `cta_full_width_banner`, `cta_image_with_headline`, `cta_countdown_urgent`, `cta_whatsapp_hotline`, `cta_floating_button`, `cta_sticky_bottom_bar` |
| **Header & Nav** | 5 | `header_logo_call_button`, `header_sticky_with_cta`, `header_announcement_ticker`, `header_minimal_centered`, `header_with_language_switch` |
| **Contact & Footer** | 6 | `footer_contact_strip`, `footer_map_address`, `footer_minimal`, `footer_with_policy_links`, `footer_social_row`, `footer_brand_summary` |
| **Niche / Category-Specific** | 10 | `niche_fashion_size_guide`, `niche_fabric_care_instructions`, `niche_food_nutrition_facts`, `niche_cosmetics_how_to_use`, `niche_cosmetics_ingredients`, `niche_gadgets_specs_table`, `niche_gadgets_warranty_info`, `niche_home_room_preview`, `niche_organic_halal_badges`, `niche_recipe_serving_suggestion` |

---

## 2. Creating a Custom Section

Adding a new section takes less than 3 minutes.

### Step 1: Create the Blade View
Create `resources/views/sections/my-custom-feature.blade.php`:
```blade
@props([
    'content' => [],
    'style' => [],
    'product' => null,
])

<section class="py-12 px-4" style="background-color: {{ $style['bg_color'] ?? '#ffffff' }};">
    <div class="max-w-5xl mx-auto text-center">
        <h2 class="text-3xl font-extrabold text-slate-900">
            {{ $content['title'] ?? 'কাস্টম সেকশন টাইটেল' }}
        </h2>
        <p class="text-slate-600 mt-2">
            {{ $content['subtitle'] ?? 'আপনার প্রয়োজনীয় বিবরণ এখানে লিখুন।' }}
        </p>
    </div>
</section>
```

### Step 2: Register in `App\Providers\LandingKitServiceProvider`
```php
use App\Sections\ConfigurableSection;
use App\Services\SectionRegistry;

public function boot(SectionRegistry $registry): void
{
    $registry->register(new ConfigurableSection(
        key: 'my_custom_feature',
        label: 'My Custom Feature',
        category: 'features',
        icon: 'heroicon-o-sparkles',
        schema: [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'subtitle', 'type' => 'textarea', 'label' => 'Subtitle'],
        ],
        defaults: [
            'title' => 'কাস্টম সেকশন টাইটেল',
            'subtitle' => 'আপনার প্রয়োজনীয় বিবরণ এখানে লিখুন।',
        ],
        view: 'sections.my-custom-feature'
    ));
}
```
The section will instantly appear in the Admin Visual Builder library, ready to be dragged onto any landing page.

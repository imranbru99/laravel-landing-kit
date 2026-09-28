<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\LandingPage;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoStoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Category: Men's Fashion
            $fashionCat = Category::firstOrCreate(['name' => 'Fashion & Apparel'], ['slug' => 'fashion-apparel', 'is_active' => true]);
            $foodCat = Category::firstOrCreate(['name' => 'Grocery & Organic Food'], ['slug' => 'grocery-food', 'is_active' => true]);
            $gadgetsCat = Category::firstOrCreate(['name' => 'Gadgets & Electronics'], ['slug' => 'gadgets-electronics', 'is_active' => true]);

            // Product 1: Premium Panjabi
            $panjabi = Product::updateOrCreate(
                ['slug' => 'premium-semi-fitted-eid-panjabi'],
                [
                    'name' => 'প্রিমিয়াম সেমি-ফিটেড ঈদ কালেকশন পাঞ্জাবি',
                    'regular_price' => 2450.00,
                    'sale_price' => 1850.00,
                    'status' => 'active',
                    'track_stock' => true,
                    'stock_quantity' => 50,
                    'short_description' => '১০০% পিওর কটন ফেব্রিক, এক্সক্লুসিভ অ্যামব্রয়ডারি কলার ও বাটন প্লেট।',
                    'seo_title' => 'প্রিমিয়াম ঈদ কালেকশন পাঞ্জাবি | Amar Shop BD',
                    'seo_description' => 'ক্যাশ অন ডেলিভারিতে অর্ডার করুন প্রিমিয়াম ঈদ পাঞ্জাবি। সারা বাংলাদেশে হোম ডেলিভারি।',
                ]
            );
            $panjabi->categories()->sync([$fashionCat->id]);

            // Variants: Sizes
            ProductVariant::firstOrCreate(['product_id' => $panjabi->id, 'name' => 'Size 40 (M)'], ['price' => 1850.00, 'stock_quantity' => 20]);
            ProductVariant::firstOrCreate(['product_id' => $panjabi->id, 'name' => 'Size 42 (L)'], ['price' => 1850.00, 'stock_quantity' => 20]);
            ProductVariant::firstOrCreate(['product_id' => $panjabi->id, 'name' => 'Size 44 (XL)'], ['price' => 1850.00, 'stock_quantity' => 10]);

            // Volume Offers
            ProductOffer::firstOrCreate(
                ['product_id' => $panjabi->id, 'min_quantity' => 2],
                [
                    'name' => 'Buy 2 Save 150 BDT',
                    'title' => '২টি কিনলে ১৫০ টাকা অতিরিক্ত ছাড়!',
                    'type' => 'bundle',
                    'discount_type' => 'fixed',
                    'discount_amount' => 150.00,
                    'is_active' => true,
                ]
            );

            // Landing Page for Panjabi
            $panjabiLp = LandingPage::updateOrCreate(
                ['product_id' => $panjabi->id],
                [
                    'title' => 'প্রিমিয়াম সেমি-ফিটেড ঈদ কালেকশন পাঞ্জাবি',
                    'status' => 'published',
                    'published_at' => now(),
                    'theme_tokens' => ['primary_color' => '#059669', 'accent_color' => '#f59e0b', 'font_family' => 'Hind Siliguri'],
                ]
            );

            $panjabiLp->sections()->delete();
            PageSection::create([
                'landing_page_id' => $panjabiLp->id,
                'section_type' => 'hero_image_right',
                'position' => 0,
                'is_visible' => true,
                'content' => [
                    'badge' => 'ঈদ স্পেশাল অফার',
                    'title' => '১০০% পিওর কটন প্রিমিয়াম ডিজাইনার পাঞ্জাবি',
                    'subtitle' => 'নরম ও আরামদায়ক সুতি কাপড়, দৃষ্টিনন্দন হ্যান্ডওয়ার্ক ও পারফেক্ট ফিনিশিং। সারা বাংলাদেশে ক্যাশ অন ডেলিভারি।',
                    'cta_text' => 'এখনই অর্ডার করুন',
                ],
            ]);
            PageSection::create([
                'landing_page_id' => $panjabiLp->id,
                'section_type' => 'features_grid_3col',
                'position' => 1,
                'is_visible' => true,
                'content' => [
                    'title' => 'আমাদের পাঞ্জাবির বিশেষত্ব',
                ],
            ]);
            PageSection::create([
                'landing_page_id' => $panjabiLp->id,
                'section_type' => 'proof_testimonial_cards',
                'position' => 2,
                'is_visible' => true,
                'content' => ['title' => 'গ্রাহকরা যা বলছেন'],
            ]);
            PageSection::create([
                'landing_page_id' => $panjabiLp->id,
                'section_type' => 'order_form_classic',
                'position' => 3,
                'is_visible' => true,
                'content' => ['title' => 'ক্যাশ অন ডেলিভারিতে অর্ডার করতে তথ্য দিন'],
            ]);

            // Product 2: Organic Mustard Oil
            $mustardOil = Product::updateOrCreate(
                ['slug' => 'pure-wood-pressed-mustard-oil'],
                [
                    'name' => 'কাঠের ঘানি ভাঙা ১০০% খাঁটি সরিষার তেল (৫ লিটার)',
                    'regular_price' => 1800.00,
                    'sale_price' => 1450.00,
                    'status' => 'active',
                    'track_stock' => true,
                    'stock_quantity' => 100,
                    'short_description' => 'কোনো প্রকার কেমিক্যাল বা প্রিজারভেটিভ ছাড়া সরাসরি মাঘী সরিষা থেকে প্রস্তুত।',
                ]
            );
            $mustardOil->categories()->sync([$foodCat->id]);

            // Product 3: ANC Earbuds
            $earbuds = Product::updateOrCreate(
                ['slug' => 'pro-anc-wireless-bluetooth-earbuds'],
                [
                    'name' => 'প্রো নয়েজ ক্যান্সেলেশন ওয়্যারলেস এয়ারবাডস',
                    'regular_price' => 2200.00,
                    'sale_price' => 1650.00,
                    'status' => 'active',
                    'track_stock' => true,
                    'stock_quantity' => 45,
                    'short_description' => 'ডিপ বাস, অ্যাক্টিভ নয়েজ ক্যান্সেলেশন, ৪০ ঘণ্টা ব্যাটারি ব্যাকআপ ও টাইপ-সি ফাস্ট চার্জিং।',
                ]
            );
            $earbuds->categories()->sync([$gadgetsCat->id]);
        });
    }
}

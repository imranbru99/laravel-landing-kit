<?php

declare(strict_types=1);

namespace App\Services;

use App\Sections\ConfigurableSection;

class SectionCatalog
{
    /**
     * Get all 107+ section definitions.
     *
     * @return array<ConfigurableSection>
     */
    public static function getDefinitions(): array
    {
        return [
            // =========================================================================
            // 1. HERO SECTIONS (12)
            // =========================================================================
            new ConfigurableSection(
                key: 'hero_image_right',
                label: 'Hero With Image Right',
                category: 'hero',
                icon: 'heroicon-o-sparkles',
                defaults: [
                    'badge' => 'ধামাকা অফার',
                    'title' => '১০০% অরিজিনাল ও প্রিমিয়াম কোয়ালিটি পণ্য',
                    'subtitle' => 'সারা বাংলাদেশে দ্রুততম হোম ডেলিভারি এবং পণ্য হাতে পেয়ে ক্যাশ অন ডেলিভারিতে মূল্য পরিশোধের সুবিধা।',
                    'cta_text' => 'এখনই অর্ডার করুন',
                    'cta_link' => '#checkout-form',
                    'image_url' => '',
                ]
            ),
            new ConfigurableSection(
                key: 'hero_image_left',
                label: 'Hero With Image Left',
                category: 'hero',
                icon: 'heroicon-o-photo',
                defaults: [
                    'badge' => 'প্রিমিয়াম কালেকশন',
                    'title' => 'খাঁটি মানের নিশ্চয়তায় আপনার পছন্দের পণ্য',
                    'subtitle' => 'সেরা দামে সেরা জিনিস কিনুন সরাসরি প্রস্তুতকারকের কাছ থেকে।',
                    'cta_text' => 'অর্ডার করতে ক্লিক করুন',
                    'cta_link' => '#checkout-form',
                ]
            ),
            new ConfigurableSection(
                key: 'hero_centered',
                label: 'Centered Hero Banner',
                category: 'hero',
                icon: 'heroicon-o-bars-3-center-left',
                defaults: [
                    'badge' => 'সীমিত সময়ের অফার',
                    'title' => 'দেশজুড়ে সর্বাধিক বিক্রিত হট ডিল!',
                    'subtitle' => 'হাজারো সন্তুষ্ট গ্রাহকের প্রথম পছন্দ। স্টক শেষ হওয়ার আগেই অর্ডার করুন।',
                    'cta_text' => 'অর্ডার করুন ৳',
                    'cta_link' => '#checkout-form',
                ]
            ),
            new ConfigurableSection(
                key: 'hero_video_bg',
                label: 'Hero With Video Background',
                category: 'hero',
                icon: 'heroicon-o-video-camera',
                defaults: [
                    'title' => 'লাইভ ভিডিওতে দেখুন পণ্যের আসল রূপ',
                    'subtitle' => 'কোনো রকম ফিল্টার ছাড়া আসল কোয়ালিটি যাচাই করে নিশ্চিন্তে কিনুন।',
                    'video_url' => '',
                    'cta_text' => 'অর্ডার কনফার্ম করুন',
                ]
            ),
            new ConfigurableSection(
                key: 'hero_countdown',
                label: 'Hero With Offer Countdown',
                category: 'hero',
                icon: 'heroicon-o-clock',
                defaults: [
                    'badge' => 'ফ্ল্যাশ সেল চলছে!',
                    'title' => 'আজকের স্পেশাল ডিসকাউন্টে কেনাকাটা করুন',
                    'subtitle' => 'সময় বাকি আছে মাত্র কয়েক ঘণ্টা, অফার হাতছাড়া করবেন না!',
                    'countdown_hours' => 12,
                    'cta_text' => 'ছাড়ে অর্ডার করুন',
                ]
            ),
            new ConfigurableSection(
                key: 'hero_price_cta',
                label: 'Hero With Price & CTA Highlight',
                category: 'hero',
                icon: 'heroicon-o-currency-bangladeshi',
                defaults: [
                    'title' => 'অবিশ্বাস্য স্পেশাল মূল্যে সেরা অফার',
                    'regular_price' => '৳ 1,500',
                    'sale_price' => '৳ 990',
                    'discount_text' => '৫১০ টাকা ছাড়',
                    'cta_text' => 'অর্ডার করতে এখানে চাপুন',
                ]
            ),
            new ConfigurableSection(
                key: 'hero_slider',
                label: 'Hero With Gallery Slider',
                category: 'hero',
                icon: 'heroicon-o-view-columns',
                defaults: [
                    'title' => 'এক নজরে সকল আকর্ষণীয় কালার ও ডিজাইন',
                    'subtitle' => 'আপনার পছন্দের ভ্যারিয়েন্ট বেছে নিন এক ক্লিকেই।',
                ]
            ),
            new ConfigurableSection(
                key: 'hero_split_form',
                label: 'Split Hero With Integrated Form',
                category: 'hero',
                icon: 'heroicon-o-clipboard-document-list',
                defaults: [
                    'title' => 'এক পেজেই দেখুন ও অর্ডার সম্পন্ন করুন',
                    'subtitle' => 'মোবাইল নম্বর দিন এবং মুহূর্তেই ডেলিভারি কনফার্ম করুন।',
                ]
            ),
            new ConfigurableSection(
                key: 'hero_minimal',
                label: 'Minimalist Clean Hero',
                category: 'hero',
                icon: 'heroicon-o-minus-small',
                defaults: [
                    'title' => 'সহজ, সুন্দর ও নির্ভরযোগ্য কেনাকাটা',
                    'subtitle' => 'অতিরিক্ত ঝামেলা ছাড়াই প্রিমিয়াম প্রোডাক্ট হোম ডেলিভারি।',
                ]
            ),
            new ConfigurableSection(
                key: 'hero_badges',
                label: 'Hero With Trust Badges Strip',
                category: 'hero',
                icon: 'heroicon-o-shield-check',
                defaults: [
                    'title' => 'গ্রাহক আস্থায় সবার শীর্ষে আমাদের পণ্য',
                    'badges' => ['ক্যাশ অন ডেলিভারি', 'দ্রুত শিপিং', 'সহজ রিটার্ন', '২৪/৭ সাপোর্ট'],
                ]
            ),
            new ConfigurableSection(
                key: 'hero_floating_product',
                label: 'Hero With Floating 3D Product',
                category: 'hero',
                icon: 'heroicon-o-cube-transparent',
                defaults: [
                    'title' => 'আধুনিক ও প্রিমিয়াম অভিজ্ঞতায় কেনাকাটা',
                    'subtitle' => 'আপনার দৈনন্দিন জীবনের সেরা সঙ্গী।',
                ]
            ),
            new ConfigurableSection(
                key: 'hero_testimonial_strip',
                label: 'Hero With Top Customer Rating Strip',
                category: 'hero',
                icon: 'heroicon-o-star',
                defaults: [
                    'rating' => '৪.৯ / ৫.০',
                    'review_count' => '১২,০০০+ সন্তুষ্ট কাস্টমার',
                    'title' => 'সবার প্রিয় এবং প্রশংসিত কালেকশন',
                ]
            ),

            // =========================================================================
            // 2. PRODUCT SHOWCASE (10)
            // =========================================================================
            new ConfigurableSection(
                key: 'showcase_gallery_grid',
                label: 'Product Gallery Grid',
                category: 'showcase',
                icon: 'heroicon-o-squares-2x2',
                defaults: [
                    'title' => 'পণ্যটির বাস্তব ছবি সমূহ',
                    'subtitle' => 'সব দিক থেকে পণ্যের নিখুঁত ফিনিশিং দেখে নিন।',
                ]
            ),
            new ConfigurableSection(
                key: 'showcase_gallery_slider',
                label: 'Gallery Slider With Thumbnails',
                category: 'showcase',
                icon: 'heroicon-o-film',
                defaults: [
                    'title' => 'ইন্টারেক্টিভ ফটো গ্যালারি',
                ]
            ),
            new ConfigurableSection(
                key: 'showcase_360_view',
                label: '360-Style Showcase Rotation',
                category: 'showcase',
                icon: 'heroicon-o-arrow-path',
                defaults: [
                    'title' => '৩৬০ ডিগ্রি সম্পূর্ণ ভিউ',
                    'subtitle' => 'প্রতিটি কোণ থেকে বিস্তারিত পর্যবেক্ষণ করুন।',
                ]
            ),
            new ConfigurableSection(
                key: 'showcase_zoom_gallery',
                label: 'High-Res Zoom Gallery',
                category: 'showcase',
                icon: 'heroicon-o-magnifying-glass-plus',
                defaults: [
                    'title' => 'হাই রেজ্যুলেশন ডিটেইল ভিউ',
                    'subtitle' => 'কাপড় বা উপাদানের টেক্সচার জুম করে দেখুন।',
                ]
            ),
            new ConfigurableSection(
                key: 'showcase_feature_callouts',
                label: 'Product Image With Hotspot Callouts',
                category: 'showcase',
                icon: 'heroicon-o-cursor-arrow-rays',
                defaults: [
                    'title' => 'পণ্যটির মূল অংশগুলোর বিশেষত্ব',
                ]
            ),
            new ConfigurableSection(
                key: 'showcase_before_after',
                label: 'Before & After Comparison Slider',
                category: 'showcase',
                icon: 'heroicon-o-adjustments-horizontal',
                defaults: [
                    'title' => 'ব্যবহারের আগের ও পরের ফলাফল',
                    'before_label' => 'পূর্বে (Before)',
                    'after_label' => 'পরে (After)',
                ]
            ),
            new ConfigurableSection(
                key: 'showcase_product_video',
                label: 'Product Demonstration Video',
                category: 'showcase',
                icon: 'heroicon-o-play-circle',
                defaults: [
                    'title' => 'ভিডিওতে দেখে নিন এর কার্যকারিতা',
                ]
            ),
            new ConfigurableSection(
                key: 'showcase_unboxing',
                label: 'Unboxing & Package Contents',
                category: 'showcase',
                icon: 'heroicon-o-archive-box',
                defaults: [
                    'title' => 'বক্সের ভেতরে যা যা পাবেন (Unboxing)',
                    'items' => ['মূল পণ্য ১ পিস', 'ইউজার ম্যানুয়াল', 'ওয়ারেন্টি কার্ড', 'সুরক্ষিত গিফট বক্স'],
                ]
            ),
            new ConfigurableSection(
                key: 'showcase_size_chart',
                label: 'Product Size & Measurement Chart',
                category: 'showcase',
                icon: 'heroicon-o-table-cells',
                defaults: [
                    'title' => 'সাইজ ও মাপের সঠিক চার্ট',
                    'subtitle' => 'আপনার সঠিক সাইজটি বেছে নিতে নিচের মাপগুলো মিলিয়ে নিন।',
                ]
            ),
            new ConfigurableSection(
                key: 'showcase_variant_swatches',
                label: 'Color & Variant Visual Swatches',
                category: 'showcase',
                icon: 'heroicon-o-swatch',
                defaults: [
                    'title' => 'উপলব্ধ সকল কালার ও মডেল',
                ]
            ),

            // =========================================================================
            // 3. FEATURES & BENEFITS (12)
            // =========================================================================
            new ConfigurableSection(
                key: 'features_grid_3col',
                label: 'Features Grid (3 Columns)',
                category: 'features',
                icon: 'heroicon-o-squares-plus',
                defaults: [
                    'title' => 'কেন এটি আপনার জন্য সেরা পছন্দ?',
                    'items' => [
                        ['title' => '১০০% পিওর উপাদান', 'desc' => 'কোনো রকম ক্ষতিকারক কেমিক্যাল বা ভেজাল নেই।'],
                        ['title' => 'দীর্ঘস্থায়ী স্থায়িত্ব', 'desc' => 'মজবুত ও টেকসই কোয়ালিটি বহুদিনের ব্যবহারের নিশ্চয়তা।'],
                        ['title' => 'সহজ ব্যবহার', 'desc' => 'যেকোনো বয়সের মানুষের জন্য অত্যন্ত আরামদায়ক ও সহজ।'],
                    ],
                ]
            ),
            new ConfigurableSection(
                key: 'features_grid_4col',
                label: 'Features Grid (4 Columns)',
                category: 'features',
                icon: 'heroicon-o-rectangle-group',
                defaults: [
                    'title' => 'পণ্যটির ৪টি অসাধারণ সুবিধা',
                ]
            ),
            new ConfigurableSection(
                key: 'features_alternating_rows',
                label: 'Alternating Image & Text Rows',
                category: 'features',
                icon: 'heroicon-o-queue-list',
                defaults: [
                    'title' => 'প্রতিটি ফিচারের বিশদ বিবরণ',
                ]
            ),
            new ConfigurableSection(
                key: 'features_checklist',
                label: 'Key Benefits Checklist',
                category: 'features',
                icon: 'heroicon-o-check-badge',
                defaults: [
                    'title' => 'যা যা উপকারিতা আপনি উপভোগ করবেন:',
                    'points' => [
                        'প্রাকৃতিক উপাদানে তৈরি শতভাগ নিরাপদ',
                        'ক্লিনিক্যালভাবে পরীক্ষিত ও অনুমোদিত',
                        'খুব দ্রুত দৃশ্যমান ফলাফল পাওয়া যায়',
                        'বাজেট-বান্ধব অথচ এক্সক্লুসিভ কোয়ালিটি',
                    ],
                ]
            ),
            new ConfigurableSection(
                key: 'features_numbered_steps',
                label: 'Numbered Step-by-Step Benefits',
                category: 'features',
                icon: 'heroicon-o-list-bullet',
                defaults: [
                    'title' => 'এটি যেভাবে কাজ করে (৩টি সহজ ধাপ)',
                ]
            ),
            new ConfigurableSection(
                key: 'features_cards',
                label: 'Modern Feature Cards With Badges',
                category: 'features',
                icon: 'heroicon-o-credit-card',
                defaults: [
                    'title' => 'অনন্য বৈশিষ্ট্যসমূহ',
                ]
            ),
            new ConfigurableSection(
                key: 'features_big_image',
                label: 'Feature Spotlight With Large Graphic',
                category: 'features',
                icon: 'heroicon-o-photo',
                defaults: [
                    'title' => 'উদ্ভাবনী প্রযুক্তি ও প্রিমিয়াম ডিজাইন',
                ]
            ),
            new ConfigurableSection(
                key: 'features_us_vs_them',
                label: 'Comparison Table ("Us vs Them")',
                category: 'features',
                icon: 'heroicon-o-arrows-right-left',
                defaults: [
                    'title' => 'আমাদের পণ্য বনাম সাধারণ বাজারের পণ্য',
                    'us_label' => 'আমাদের আসল পণ্য',
                    'them_label' => 'বাজারের সাধারণ পণ্য',
                ]
            ),
            new ConfigurableSection(
                key: 'features_specs_table',
                label: 'Technical Specifications Table',
                category: 'features',
                icon: 'heroicon-o-clipboard-document',
                defaults: [
                    'title' => 'প্রয়োজনীয় টেকনিক্যাল স্পেসিফিকেশন',
                ]
            ),
            new ConfigurableSection(
                key: 'features_materials_list',
                label: 'Ingredients & Material Breakdown',
                category: 'features',
                icon: 'heroicon-o-beaker',
                defaults: [
                    'title' => 'উপাদান তালিকা ও বিশুদ্ধতার নিশ্চয়তা',
                ]
            ),
            new ConfigurableSection(
                key: 'features_why_choose_us',
                label: '"Why Choose Us" Value Props',
                category: 'features',
                icon: 'heroicon-o-check-circle',
                defaults: [
                    'title' => 'আমাদের থেকে কেনার ৫টি অন্যতম কারণ',
                ]
            ),
            new ConfigurableSection(
                key: 'features_problem_solution',
                label: 'Problem → Solution Comparison',
                category: 'features',
                icon: 'heroicon-o-light-bulb',
                defaults: [
                    'title' => 'আপনার সমস্যার স্থায়ী সমাধান',
                    'problem_text' => 'আপনি কি এই সমস্যায় ভুগছেন?',
                    'solution_text' => 'আমাদের পণ্য যেভাবে দিচ্ছে স্থায়ী সমাধান',
                ]
            ),

            // =========================================================================
            // 4. SOCIAL PROOF & REVIEWS (12)
            // =========================================================================
            new ConfigurableSection(
                key: 'proof_testimonial_cards',
                label: 'Customer Testimonial Cards',
                category: 'social_proof',
                icon: 'heroicon-o-chat-bubble-left-right',
                defaults: [
                    'title' => 'আমাদের গ্রাহকরা যা বলছেন',
                    'testimonials' => [
                        ['name' => 'তানভীর আহমেদ', 'city' => 'ধানমন্ডি, ঢাকা', 'comment' => 'পণ্যটি যেমন ছবিতে দেখেছি তেমনই পেয়েছি। কোয়ালিটি নিয়ে কোনো সন্দেহ নেই। ধন্যবাদ!'],
                        ['name' => 'ফারহানা ইয়াসমিন', 'city' => 'চট্টগ্রাম', 'comment' => 'ডেলিভারি অনেক দ্রুত পেয়েছি। প্যাকেজিং খুবই ভালো ছিল। আবার অর্ডার করব ইনশাআল্লাহ।'],
                        ['name' => 'মো: জাহিদুল ইসলাম', 'city' => 'সিলেট', 'comment' => 'ক্যাশ অন ডেলিভারিতে চেক করে নিতে পেরেছি, এটাই সবচেয়ে বড় সুবিধা। ১০/১০ রেটিং!'],
                    ],
                ]
            ),
            new ConfigurableSection(
                key: 'proof_testimonial_slider',
                label: 'Testimonial Carousel Slider',
                category: 'social_proof',
                icon: 'heroicon-o-arrows-up-down',
                defaults: ['title' => 'গ্রাহকদের অভিমত']
            ),
            new ConfigurableSection(
                key: 'proof_whatsapp_reviews',
                label: 'WhatsApp / Facebook Chat Screenshots',
                category: 'social_proof',
                icon: 'heroicon-o-device-phone-mobile',
                defaults: [
                    'title' => 'হোয়াটসঅ্যাপে গ্রাহকদের সরাসরি রিভিউ',
                    'subtitle' => 'শতভাগ আসল ও আনকাট কাস্টমার ফিডব্যাক স্ক্রিনশট।',
                ]
            ),
            new ConfigurableSection(
                key: 'proof_video_testimonials',
                label: 'Customer Video Testimonials',
                category: 'social_proof',
                icon: 'heroicon-o-video-camera',
                defaults: ['title' => 'ভিডিও রিভিউ দেখুন']
            ),
            new ConfigurableSection(
                key: 'proof_rating_summary',
                label: 'Star Rating Summary Box',
                category: 'social_proof',
                icon: 'heroicon-o-star',
                defaults: [
                    'average' => '৪.৯',
                    'total_reviews' => '৩,৪৫০+ রিভিউ',
                ]
            ),
            new ConfigurableSection(
                key: 'proof_review_wall',
                label: 'Review Wall Masonry',
                category: 'social_proof',
                icon: 'heroicon-o-building-office-2',
                defaults: ['title' => 'গ্রাহকদের ভালোবাসার গল্প']
            ),
            new ConfigurableSection(
                key: 'proof_customer_photos',
                label: 'Real Customer Unboxing Photos',
                category: 'social_proof',
                icon: 'heroicon-o-camera',
                defaults: ['title' => 'গ্রাহকদের পাঠানো পণ্যের বাস্তব ছবি']
            ),
            new ConfigurableSection(
                key: 'proof_logos_strip',
                label: 'Partner & Media Logos Strip',
                category: 'social_proof',
                icon: 'heroicon-o-building-storefront',
                defaults: ['title' => 'আমাদের ডেলিভারি ও পেমেন্ট পার্টনার্স']
            ),
            new ConfigurableSection(
                key: 'proof_sold_counter',
                label: 'Live "Units Sold" Stats Counter',
                category: 'social_proof',
                icon: 'heroicon-o-arrow-trending-up',
                defaults: [
                    'count' => '১৫,০০০+',
                    'label' => 'পিস ইতিমধ্যে সারাদেশে ডেলিভারি সম্পন্ন হয়েছে!',
                ]
            ),
            new ConfigurableSection(
                key: 'proof_live_popup',
                label: 'Recent Buyer Activity Toast',
                category: 'social_proof',
                icon: 'heroicon-o-bell-alert',
                defaults: ['text' => 'কয়েক মিনিট আগে ঢাকা থেকে ১টি অর্ডার সম্পন্ন হয়েছে!']
            ),
            new ConfigurableSection(
                key: 'proof_trust_badges',
                label: 'Official Trust & Security Badges',
                category: 'social_proof',
                icon: 'heroicon-o-lock-closed',
                defaults: ['title' => 'নিরাপদ অনলাইন কেনাকাটার নিশ্চয়তা']
            ),
            new ConfigurableSection(
                key: 'proof_case_study',
                label: 'Customer Case Study & Success Story',
                category: 'social_proof',
                icon: 'heroicon-o-newspaper',
                defaults: ['title' => 'আমাদের গ্রাহকের বাস্তব অভিজ্ঞতা']
            ),

            // =========================================================================
            // 5. OFFER & URGENCY (10)
            // =========================================================================
            new ConfigurableSection(
                key: 'offer_countdown_banner',
                label: 'Urgent Offer Countdown Banner',
                category: 'offer',
                icon: 'heroicon-o-fire',
                defaults: [
                    'title' => 'সীমিত সময়ের বিশেষ ডিসকাউন্ট!',
                    'end_time' => 'আজ রাত ১২টা পর্যন্ত',
                ]
            ),
            new ConfigurableSection(
                key: 'offer_stock_bar',
                label: 'Limited Stock Progress Bar',
                category: 'offer',
                icon: 'heroicon-o-chart-bar',
                defaults: [
                    'remaining' => 'মাত্র ১২টি অবশিষ্ট আছে!',
                    'percentage' => 85,
                ]
            ),
            new ConfigurableSection(
                key: 'offer_discount_badge',
                label: 'Highlighted Discount Ribbon',
                category: 'offer',
                icon: 'heroicon-o-tag',
                defaults: ['discount' => '৪০% পর্যন্ত বিশাল ছাড়!']
            ),
            new ConfigurableSection(
                key: 'offer_bundle_cards',
                label: 'Bundle Deal & Multi-Pack Cards',
                category: 'offer',
                icon: 'heroicon-o-gift',
                defaults: ['title' => 'প্যাকেজ অফার সমূহ']
            ),
            new ConfigurableSection(
                key: 'offer_tier_table',
                label: 'Buy More Save More Pricing Tier',
                category: 'offer',
                icon: 'heroicon-o-circle-stack',
                defaults: [
                    'title' => 'বেশি কিনলে বেশি সাশ্রয়!',
                    'tiers' => [
                        ['qty' => '১টি কিনলে', 'price' => '৳ ৯৫০', 'save' => 'সাধারণ মূল্য'],
                        ['qty' => '২টি কিনলে', 'price' => '৳ ১৭৫০', 'save' => '১৫০ টাকা সাশ্রয়'],
                        ['qty' => '৩টি কিনলে', 'price' => '৳ ২৪৫০ + ফ্রি ডেলিভারি', 'save' => '৪০০ টাকা সাশ্রয়!'],
                    ],
                ]
            ),
            new ConfigurableSection(
                key: 'offer_free_delivery',
                label: 'Free Delivery Special Banner',
                category: 'offer',
                icon: 'heroicon-o-truck',
                defaults: ['title' => 'আজ অর্ডার করলেই সারা বাংলাদেশে ফ্রি ডেলিভারি!']
            ),
            new ConfigurableSection(
                key: 'offer_flash_sale',
                label: 'Flash Sale Urgent Ribbon',
                category: 'offer',
                icon: 'heroicon-o-bolt',
                defaults: ['title' => '⚡ ফ্ল্যাশ সেল চলছে! দ্রুত অর্ডার নিশ্চিত করুন।']
            ),
            new ConfigurableSection(
                key: 'offer_coupon_box',
                label: 'Coupon Code & Promo Card',
                category: 'offer',
                icon: 'heroicon-o-ticket',
                defaults: ['coupon' => 'EID2026', 'discount' => 'অতিরিক্ত ১০% ছাড়']
            ),
            new ConfigurableSection(
                key: 'offer_price_comparison',
                label: 'Price Comparison vs Market',
                category: 'offer',
                icon: 'heroicon-o-scale',
                defaults: ['market_price' => '৳ ২,০০০', 'our_price' => '৳ ১,২০০']
            ),
            new ConfigurableSection(
                key: 'offer_sticky_bar',
                label: 'Sticky Floating Offer Bar',
                category: 'offer',
                icon: 'heroicon-o-bookmark',
                defaults: ['title' => 'স্পেশাল ছাড় হাতছাড়া করবেন না!']
            ),

            // =========================================================================
            // 6. ORDER FORM & CHECKOUT (10)
            // =========================================================================
            new ConfigurableSection(
                key: 'order_form_classic',
                label: 'Classic Phone-First Order Form',
                category: 'order_form',
                icon: 'heroicon-o-shopping-cart',
                defaults: [
                    'title' => 'অর্ডার করতে আপনার তথ্য দিন',
                    'subtitle' => 'ক্যাশ অন ডেলিভারিতে পণ্য পৌঁছাবে আপনার ঠিকানায়।',
                ]
            ),
            new ConfigurableSection(
                key: 'order_form_summary',
                label: 'Order Form With Sticky Summary',
                category: 'order_form',
                icon: 'heroicon-o-calculator',
                defaults: ['title' => 'অর্ডার ফর্ম ও পেমেন্ট বিবরণ']
            ),
            new ConfigurableSection(
                key: 'order_form_twocol',
                label: 'Two-Column Split Checkout Form',
                category: 'order_form',
                icon: 'heroicon-o-columns',
                defaults: ['title' => 'সহজ ও দ্রুত ২ কলাম চেকআউট']
            ),
            new ConfigurableSection(
                key: 'order_form_mobile_sticky',
                label: 'Sticky Mobile Quick Order Bar',
                category: 'order_form',
                icon: 'heroicon-o-device-phone-mobile',
                defaults: ['button_text' => '🛒 এখনই অর্ডার করুন (ক্যাশ অন ডেলিভারি)']
            ),
            new ConfigurableSection(
                key: 'order_form_popup',
                label: '1-Click Modal Popup Order Form',
                category: 'order_form',
                icon: 'heroicon-o-window',
                defaults: ['title' => 'দ্রুত অর্ডার করতে তথ্য দিন']
            ),
            new ConfigurableSection(
                key: 'order_form_minimal',
                label: 'Minimalist 30-Second Checkout Form',
                category: 'order_form',
                icon: 'heroicon-o-clock',
                defaults: ['title' => 'মাত্র ৩০ সেকেন্ডে অর্ডার সম্পন্ন করুন']
            ),
            new ConfigurableSection(
                key: 'order_form_variant_picker',
                label: 'Form With Visual Variant Selectors',
                category: 'order_form',
                icon: 'heroicon-o-swatch',
                defaults: ['title' => 'সাইজ ও কালার পছন্দ করে অর্ডার করুন']
            ),
            new ConfigurableSection(
                key: 'order_form_quantity_radios',
                label: 'Form With Quantity Radio Deals',
                category: 'order_form',
                icon: 'heroicon-o-circle-stack',
                defaults: ['title' => 'প্যাকেজ নির্বাচন করে অর্ডার করুন']
            ),
            new ConfigurableSection(
                key: 'order_form_order_bump',
                label: 'Form With 1-Click Order Bump Addon',
                category: 'order_form',
                icon: 'heroicon-o-plus-circle',
                defaults: ['bump_title' => 'অর্ডারের সাথে যোগ করুন স্পেশাল গিফট বক্স (+৳৯৯)']
            ),
            new ConfigurableSection(
                key: 'order_form_delivery_zones',
                label: 'Form With Bangladesh Delivery Zones',
                category: 'order_form',
                icon: 'heroicon-o-map-pin',
                defaults: ['title' => 'ডেলিভারি এলাকা ও সঠিক ঠিকানা দিন']
            ),

            // =========================================================================
            // 7. FAQ (5)
            // =========================================================================
            new ConfigurableSection(
                key: 'faq_accordion',
                label: 'Interactive Accordion FAQ',
                category: 'faq',
                icon: 'heroicon-o-question-mark-circle',
                defaults: [
                    'title' => 'সাধারণ জিজ্ঞাসিত প্রশ্নাবলী (FAQ)',
                    'faqs' => [
                        ['q' => 'আমি কীভাবে পণ্যটি অর্ডার করব?', 'a' => 'ফর্মটিতে আপনার নাম, মোবাইল নম্বর ও ঠিকানা দিয়ে "অর্ডার কনফার্ম করুন" বাটনে ক্লিক করলেই অর্ডার সম্পন্ন হবে।'],
                        ['q' => 'ডেলিভারি পেতে কতদিন সময় লাগবে?', 'a' => 'ঢাকা সিটির ভেতরে ২৪ থেকে ৪৮ ঘণ্টার মধ্যে এবং ঢাকার বাইরে ২ থেকে ৩ দিনের মধ্যে হোম ডেলিভারি পাবেন।'],
                        ['q' => 'পণ্য হাতে পেয়ে কি দেখে নেওয়া যাবে?', 'a' => 'অবশ্যই! ডেলিভারিম্যানের সামনে প্যাকেট খুলে চেক করে তারপর মূল্য পরিশোধ করতে পারবেন।'],
                        ['q' => 'পণ্য অপছন্দ বা ডিফেক্ট হলে কী করব?', 'a' => 'আমাদের ৩ দিনের সহজ রিপ্লেসমেন্ট গ্যারান্টি রয়েছে। কোনো সমস্যা হলে আমাদের হটলাইনে কল করলেই সাথে সাথে সমাধান করা হবে।'],
                    ],
                ]
            ),
            new ConfigurableSection(
                key: 'faq_two_column',
                label: 'Two-Column Grid FAQ',
                category: 'faq',
                icon: 'heroicon-o-bars-2',
                defaults: ['title' => 'প্রয়োজনীয় প্রশ্নোত্তর']
            ),
            new ConfigurableSection(
                key: 'faq_categorized',
                label: 'Categorized Tabbed FAQ',
                category: 'faq',
                icon: 'heroicon-o-folder',
                defaults: ['title' => 'অর্ডার, ডেলিভারি ও রিটার্ন সম্পর্কিত তথ্য']
            ),
            new ConfigurableSection(
                key: 'faq_with_cta',
                label: 'FAQ With Direct Support Call Button',
                category: 'faq',
                icon: 'heroicon-o-phone-arrow-up-right',
                defaults: ['title' => 'অন্য কোনো প্রশ্ন আছে? সরাসরি কল করুন!']
            ),
            new ConfigurableSection(
                key: 'faq_minimal',
                label: 'Minimal Clean FAQ List',
                category: 'faq',
                icon: 'heroicon-o-bars-3-bottom-left',
                defaults: ['title' => 'এক নজরে সকল প্রশ্নের উত্তর']
            ),

            // =========================================================================
            // 8. GUARANTEE & TRUST (6)
            // =========================================================================
            new ConfigurableSection(
                key: 'guarantee_money_back',
                label: '100% Money-Back Guarantee Seal',
                category: 'guarantee',
                icon: 'heroicon-o-check-badge',
                defaults: [
                    'title' => 'শতভাগ টাকা ফেরত গ্যারান্টি',
                    'desc' => 'পণ্য বর্ণনার সাথে না মিললে বা সন্তুষ্ট না হলে শতভাগ মূল্য ফেরত দেওয়া হবে।',
                ]
            ),
            new ConfigurableSection(
                key: 'guarantee_cod_assurance',
                label: 'Cash-on-Delivery Safety Pledge',
                category: 'guarantee',
                icon: 'heroicon-o-banknotes',
                defaults: [
                    'title' => 'জিরো রিস্ক: ক্যাশ অন ডেলিভারি সুবিধা',
                    'desc' => 'আগে কোনো টাকা দেওয়ার প্রয়োজন নেই। পণ্য হাতে পেয়ে সম্পূর্ণ সন্তুষ্ট হয়ে টাকা পরিশোধ করুন।',
                ]
            ),
            new ConfigurableSection(
                key: 'guarantee_warranty',
                label: 'Official Brand Warranty Card',
                category: 'guarantee',
                icon: 'heroicon-o-document-check',
                defaults: [
                    'title' => 'অফিসিয়াল সার্ভিস ওয়ারেন্টি',
                    'duration' => '১ বছরের রিপ্লেসমেন্ট ওয়ারেন্টি',
                ]
            ),
            new ConfigurableSection(
                key: 'guarantee_return_policy',
                label: '3-Day Hassle-Free Return Policy',
                category: 'guarantee',
                icon: 'heroicon-o-arrow-path-rounded-square',
                defaults: [
                    'title' => 'সহজ ও ঝামেলাহীন রিটার্ন সুবিধা',
                ]
            ),
            new ConfigurableSection(
                key: 'guarantee_secure_steps',
                label: 'Secure Order & Dispatch Steps',
                category: 'guarantee',
                icon: 'heroicon-o-shield-check',
                defaults: ['title' => 'আমাদের সুরক্ষিত ডেলিভারি প্রক্রিয়া']
            ),
            new ConfigurableSection(
                key: 'guarantee_delivery_promise',
                label: 'Express 48-Hour Delivery Promise',
                category: 'guarantee',
                icon: 'heroicon-o-clock',
                defaults: ['title' => '২৪-৪৮ ঘণ্টার মধ্যে দ্রুততম হোম ডেলিভারি']
            ),

            // =========================================================================
            // 9. CONTENT & STORY (8)
            // =========================================================================
            new ConfigurableSection(
                key: 'content_rich_text',
                label: 'Rich Text Article Block',
                category: 'content',
                icon: 'heroicon-o-document-text',
                defaults: ['title' => 'পণ্যের প্রেক্ষাপট ও বিশেষত্ব']
            ),
            new ConfigurableSection(
                key: 'content_image_text',
                label: 'Side-by-Side Image & Narrative',
                category: 'content',
                icon: 'heroicon-o-newspaper',
                defaults: ['title' => 'আমাদের নিজস্ব কারিগরদের নিখুঁত কাজ']
            ),
            new ConfigurableSection(
                key: 'content_text_video',
                label: 'Story Article With Video Preview',
                category: 'content',
                icon: 'heroicon-o-film',
                defaults: ['title' => 'তৈরির গল্প ও প্রক্রিয়া']
            ),
            new ConfigurableSection(
                key: 'content_timeline_story',
                label: 'Heritage & Brand Timeline Story',
                category: 'content',
                icon: 'heroicon-o-clock',
                defaults: ['title' => 'আমাদের দীর্ঘদিনের বিশ্বস্ততার ইতিহাস']
            ),
            new ConfigurableSection(
                key: 'content_about_brand',
                label: 'About Our Shop & Mission',
                category: 'content',
                icon: 'heroicon-o-home',
                defaults: ['title' => 'আমাদের সম্পর্কে (About Us)']
            ),
            new ConfigurableSection(
                key: 'content_founder_note',
                label: 'Founder Note & Signature Message',
                category: 'content',
                icon: 'heroicon-o-pencil-square',
                defaults: [
                    'title' => 'প্রতিষ্ঠাতার বিশেষ বার্তা',
                    'message' => 'আমাদের লক্ষ্য দেশের প্রতিটি ঘরে খাঁটি ও মানসম্মত পণ্য পৌঁছে দেওয়া।',
                ]
            ),
            new ConfigurableSection(
                key: 'content_article',
                label: 'Editorial Magazine-Style Feature',
                category: 'content',
                icon: 'heroicon-o-book-open',
                defaults: ['title' => 'কেন আধুনিক জীবনে এটি অপরিহার্য?']
            ),
            new ConfigurableSection(
                key: 'content_stats_counter',
                label: 'Key Milestones & Stats Counter',
                category: 'content',
                icon: 'heroicon-o-presentation-chart-line',
                defaults: ['title' => 'আমাদের অর্জন ও সাফল্য']
            ),

            // =========================================================================
            // 10. MEDIA & VIDEO (5)
            // =========================================================================
            new ConfigurableSection(
                key: 'media_video_embed',
                label: 'Responsive Video Embed (YouTube/Vimeo)',
                category: 'media',
                icon: 'heroicon-o-video-camera',
                defaults: ['title' => 'ইউটিউব ভিডিও রিভিউ']
            ),
            new ConfigurableSection(
                key: 'media_video_grid',
                label: 'Multi-Video Showcase Grid',
                category: 'media',
                icon: 'heroicon-o-squares-2x2',
                defaults: ['title' => 'ভিডিও সিরিজ']
            ),
            new ConfigurableSection(
                key: 'media_masonry_gallery',
                label: 'Masonry Photo Gallery',
                category: 'media',
                icon: 'heroicon-o-squares-plus',
                defaults: ['title' => 'ফটো মোজাইক কালেকশন']
            ),
            new ConfigurableSection(
                key: 'media_lookbook',
                label: 'Fashion & Style Lookbook Grid',
                category: 'media',
                icon: 'heroicon-o-sparkles',
                defaults: ['title' => 'সিজনাল লুকবুক']
            ),
            new ConfigurableSection(
                key: 'media_instagram_grid',
                label: 'Instagram-Style Social Photo Feed',
                category: 'media',
                icon: 'heroicon-o-camera',
                defaults: ['title' => 'আমাদের ইন্সটাগ্রাম কমিউনিটি']
            ),

            // =========================================================================
            // 11. CALL TO ACTION (CTA) (6)
            // =========================================================================
            new ConfigurableSection(
                key: 'cta_fullwidth',
                label: 'Full-Width High-Impact CTA Banner',
                category: 'cta',
                icon: 'heroicon-o-megaphone',
                defaults: [
                    'title' => 'দেরি না করে আজই আপনার কপিটি সংগ্রহ করুন!',
                    'cta_text' => 'এখনই অর্ডার করতে ক্লিক করুন',
                ]
            ),
            new ConfigurableSection(
                key: 'cta_with_image',
                label: 'Visual CTA With Product Backdrop',
                category: 'cta',
                icon: 'heroicon-o-photo',
                defaults: ['title' => 'আপনার ঠিকানায় পৌঁছে দিতে প্রস্তুত']
            ),
            new ConfigurableSection(
                key: 'cta_with_countdown',
                label: 'Last-Chance Countdown CTA',
                category: 'cta',
                icon: 'heroicon-o-clock',
                defaults: ['title' => 'অফারের সময় দ্রুত শেষ হচ্ছে!']
            ),
            new ConfigurableSection(
                key: 'cta_whatsapp_call',
                label: 'Direct WhatsApp & Call Buttons Strip',
                category: 'cta',
                icon: 'heroicon-o-phone',
                defaults: [
                    'title' => 'ফোনে কথা বলে অর্ডার করতে চান?',
                    'whatsapp_text' => 'হোয়াটসঅ্যাপ মেসেজ দিন',
                    'call_text' => 'সরাসরি কল করুন',
                ]
            ),
            new ConfigurableSection(
                key: 'cta_floating',
                label: 'Floating WhatsApp / Support Orb',
                category: 'cta',
                icon: 'heroicon-o-chat-bubble-oval-left',
                defaults: ['text' => 'সহায়তা প্রয়োজন?']
            ),
            new ConfigurableSection(
                key: 'cta_sticky_bottom',
                label: 'Mobile Sticky Bottom Checkout CTA',
                category: 'cta',
                icon: 'heroicon-o-arrow-up-circle',
                defaults: ['text' => '🛒 এখনই অর্ডার করুন ৳']
            ),

            // =========================================================================
            // 12. CONTACT & FOOTER (6)
            // =========================================================================
            new ConfigurableSection(
                key: 'footer_contact_strip',
                label: 'Hotline & Support Strip',
                category: 'footer',
                icon: 'heroicon-o-phone-arrow-down-left',
                defaults: ['title' => 'যেকোনো জিজ্ঞাসায় আমাদের কল করুন: ০১৭০০০০০০০০']
            ),
            new ConfigurableSection(
                key: 'footer_map_address',
                label: 'Physical Shop Address & Google Map',
                category: 'footer',
                icon: 'heroicon-o-map-pin',
                defaults: ['address' => 'ঢাকা, বাংলাদেশ']
            ),
            new ConfigurableSection(
                key: 'footer_minimal',
                label: 'Minimal Clean Copyright Footer',
                category: 'footer',
                icon: 'heroicon-o-stop',
                defaults: ['copyright' => 'সর্বস্বত্ব সংরক্ষিত।']
            ),
            new ConfigurableSection(
                key: 'footer_with_links',
                label: 'Multi-Column Footer With Navigation',
                category: 'footer',
                icon: 'heroicon-o-bars-3',
                defaults: ['title' => 'গুরুত্বপূর্ণ লিংক সমূহ']
            ),
            new ConfigurableSection(
                key: 'footer_policy_links',
                label: 'Terms, Privacy & Return Policy Bar',
                category: 'footer',
                icon: 'heroicon-o-document-text',
                defaults: ['privacy_text' => 'গোপনীয়তা নীতি', 'terms_text' => 'শর্তাবলী']
            ),
            new ConfigurableSection(
                key: 'footer_social_row',
                label: 'Social Media Profiles Row',
                category: 'footer',
                icon: 'heroicon-o-share',
                defaults: ['title' => 'আমাদের সোশ্যাল মিডিয়ায় যুক্ত থাকুন']
            ),

            // =========================================================================
            // 13. NAV & HEADER (5)
            // =========================================================================
            new ConfigurableSection(
                key: 'header_logo_call',
                label: 'Logo With Direct Hotline Call Button',
                category: 'header',
                icon: 'heroicon-o-phone',
                defaults: ['hotline' => '০১৭০০০০০০০০']
            ),
            new ConfigurableSection(
                key: 'header_sticky_cta',
                label: 'Sticky Floating Top Header With CTA',
                category: 'header',
                icon: 'heroicon-o-arrow-down-circle',
                defaults: ['title' => 'স্পেশাল ডিসকাউন্ট অফার']
            ),
            new ConfigurableSection(
                key: 'header_announcement',
                label: 'Top Announcement Banner Strip',
                category: 'header',
                icon: 'heroicon-o-megaphone',
                defaults: ['text' => '🎉 আজকের অর্ডারে সারা বাংলাদেশে ক্যাশ অন ডেলিভারি ও ফ্রি শিপিং!']
            ),
            new ConfigurableSection(
                key: 'header_minimal',
                label: 'Simple Clean Logo Header',
                category: 'header',
                icon: 'heroicon-o-sparkles',
                defaults: ['title' => 'অফিসিয়াল স্টোর']
            ),
            new ConfigurableSection(
                key: 'header_language_switch',
                label: 'Header With English / Bangla Switcher',
                category: 'header',
                icon: 'heroicon-o-language',
                defaults: ['en_label' => 'English', 'bn_label' => 'বাংলা']
            ),

            // =========================================================================
            // 14. NICHE & CATEGORY-SPECIFIC (10)
            // =========================================================================
            new ConfigurableSection(
                key: 'category_fashion_size_guide',
                label: 'Fashion Size Guide & Fit Recommendation',
                category: 'category_specific',
                icon: 'heroicon-o-scissors',
                defaults: [
                    'title' => 'পাঞ্জাবি ও পোশাকের সাইজ গাইড',
                    'sizes' => [
                        ['size' => '৪০ (M)', 'chest' => '৪০ ইঞ্চি', 'length' => '৪০ ইঞ্চি'],
                        ['size' => '৪২ (L)', 'chest' => '৪২ ইঞ্চি', 'length' => '৪২ ইঞ্চি'],
                        ['size' => '৪৪ (XL)', 'chest' => '৪৪ ইঞ্চি', 'length' => '৪৪ ইঞ্চি'],
                    ],
                ]
            ),
            new ConfigurableSection(
                key: 'category_fashion_fabric_care',
                label: 'Fabric Care & Washing Instructions (Fashion)',
                category: 'category_specific',
                icon: 'heroicon-o-hand-raised',
                defaults: [
                    'title' => 'কাপড়ের যত্ন ও ধোয়ার নিয়মাবলী',
                    'instructions' => ['প্রথমবার ড্রাই ওয়াশ করুন', 'হালকা রোদে শুকাতে দিন', 'কুসুম গরম পানিতে ওয়াশ করুন'],
                ]
            ),
            new ConfigurableSection(
                key: 'category_food_nutrition',
                label: 'Nutrition Facts & Food Quality Standard',
                category: 'category_specific',
                icon: 'heroicon-o-heart',
                defaults: [
                    'title' => 'পুষ্টিগুণ ও স্বাস্থ্য উপকারিতা (Food Facts)',
                    'highlights' => ['১০০% অপরিশোধিত ও খাঁটি', 'প্রাকৃতিক ভিটামিন ই সমৃদ্ধ', 'হৃদযন্ত্রের জন্য উপকারী'],
                ]
            ),
            new ConfigurableSection(
                key: 'category_cosmetics_how_to_use',
                label: 'How To Use & Application Routine (Cosmetics)',
                category: 'category_specific',
                icon: 'heroicon-o-sparkles',
                defaults: [
                    'title' => 'ব্যবহারের সঠিক নিয়ম ও রুটিন',
                    'steps' => ['প্রথমে মুখ ভালো করে ধুয়ে নিন', 'পরিমাণমতো সিরাম/তেল মালিশ করুন', 'নিয়মিত রাতে ঘুমানোর পূর্বে ব্যবহার করুন'],
                ]
            ),
            new ConfigurableSection(
                key: 'category_cosmetics_ingredients',
                label: 'Herbal & Active Ingredients (Cosmetics)',
                category: 'category_specific',
                icon: 'heroicon-o-beaker',
                defaults: [
                    'title' => 'প্রাকৃতিক হার্বাল উপাদানের কার্যকারিতা',
                ]
            ),
            new ConfigurableSection(
                key: 'category_electronics_specs',
                label: 'Hardware & Battery Specs (Electronics)',
                category: 'category_specific',
                icon: 'heroicon-o-cpu-chip',
                defaults: [
                    'title' => 'প্রযুক্তিগত তথ্য ও ব্যাটারি ব্যাকআপ',
                    'battery' => '৫০০০ মিলিঅ্যাম্পিয়ার ব্যাটারি',
                    'display' => 'অ্যামোলেড ডিসপ্লে',
                ]
            ),
            new ConfigurableSection(
                key: 'category_electronics_warranty',
                label: 'Official Brand Warranty & Repair Details',
                category: 'category_specific',
                icon: 'heroicon-o-wrench-screwdriver',
                defaults: [
                    'title' => 'সার্ভিস ও পার্টস ওয়ারেন্টি কভারেজ',
                ]
            ),
            new ConfigurableSection(
                key: 'category_furniture_room_preview',
                label: 'Living Room Space Preview (Furniture)',
                category: 'category_specific',
                icon: 'heroicon-o-home-modern',
                defaults: [
                    'title' => 'আপনার ঘরে যেভাবে মানিয়ে যাবে',
                ]
            ),
            new ConfigurableSection(
                key: 'category_food_recipe',
                label: 'Recipe & Serving Suggestions (Food/Spice)',
                category: 'category_specific',
                icon: 'heroicon-o-cake',
                defaults: [
                    'title' => 'সুস্বাদু রান্নার রেসিপি টিপস',
                ]
            ),
            new ConfigurableSection(
                key: 'category_organic_badges',
                label: 'BSTI, Halal & 100% Organic Certifications',
                category: 'category_specific',
                icon: 'heroicon-o-check-badge',
                defaults: [
                    'title' => 'বিএসটিআই অনুমোদিত ও হালাল সার্টিফিকেটপ্রাপ্ত',
                    'badges' => ['১০০% ন্যাচারাল', 'বিএসটিআই টেস্টেড', 'হালাল সার্টিফাইড', 'প্রিজারভেটিভ মুক্ত'],
                ]
            ),
        ];
    }
}

# Laravel Landing Kit (LLK) 🇧🇩

> **A high-performance, admin-managed Laravel 13 landing page and one-product-checkout system engineered specifically for Bangladesh e-commerce shops.**

[![Laravel 13](https://img.shields.io/badge/Laravel-13.x-red.svg)](https://laravel.com)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://php.net)
[![Filament v4](https://img.shields.io/badge/Filament-v4.x-orange.svg)](https://filamentphp.com)
[![Pest Tests](https://img.shields.io/badge/Tests-54%20passed-brightgreen.svg)](https://pestphp.com)
[![Code Style: Pint](https://img.shields.io/badge/Code%20Style-Laravel%20Pint-black.svg)](https://github.com/laravel/pint)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

**Laravel Landing Kit (LLK)** is a modern, ultra-fast alternative to WordPress, Elementor, and WooCommerce for direct-to-consumer merchants operating shops with 1 to 10 products in Bangladesh. It delivers **90+ Google Lighthouse mobile scores**, eliminates cart abandonment with a phone-number-first single-page checkout, provides a visual 3-pane landing page builder with **107+ prebuilt sections** and **107 ready-made Bangladeshi shop templates**, supports dual client/server tracking (Meta CAPI & Stape), and integrates seamlessly with local couriers (Steadfast, Pathao, RedX).

---

## 📑 Table of Contents
1. [Core Features & Architecture](#-core-features--architecture)
2. [Phone-Number-First Checkout System](#-phone-number-first-checkout-system)
3. [Visual 3-Pane Landing Page Builder](#-visual-3-pane-landing-page-builder)
4. [Prebuilt Section Library (107+ Sections)](#-prebuilt-section-library-107-sections)
5. [Ready-Made Shop Templates (107 Templates)](#-ready-made-shop-templates-107-templates)
6. [Tracking, Analytics & Meta CAPI Engine](#-tracking-analytics--meta-capi-engine)
7. [Order Management & 22-State Lifecycle Machine](#-order-management--22-state-lifecycle-machine)
8. [Courier Integrations (Steadfast, Pathao, RedX, Manual)](#-courier-integrations)
9. [AI Page & Copywriting Builder](#-ai-page--copywriting-builder)
10. [Unicode Bengali PDF Documents](#-unicode-bengali-pdf-documents)
11. [Admin Panel, Widgets & Roles (Filament v4)](#-admin-panel-widgets--roles-filament-v4)
12. [Tech Stack & Native Packages](#-tech-stack--native-packages)
13. [Installation & Quickstart Guide](#-installation--quickstart-guide)
14. [Testing & Quality Assurance](#-testing--quality-assurance)

---

## ⚡ Core Features & Architecture

- **Dedicated Root-Level Product Slugs**: Every product has its own landing page at `/{product-slug}` using [`imrandevbd/laravel-universal-slug`](https://github.com/imrandevbd/laravel-universal-slug).
- **SEO & Permanent 301 Redirects**: Historical slugs are automatically tracked in `slug_histories` for automatic 301 redirects, ensuring that updating a product's name or slug never breaks incoming ad traffic.
- **Reserved System Slugs**: Protected routes (`admin`, `checkout`, `order-success`, `track-order`, `api`, `llk`, `login`, `sitemap.xml`, `robots.txt`) cannot collide with product URLs.
- **Single-Page Checkout on Landing Page**: Eliminates multi-step cart flows; visitors review the product, select variants, and confirm their order on the same page.
- **Mobile-First High Performance**: Built with Blade, Tailwind CSS standalone CDN, and Alpine.js. Zero heavy SPA overhead; critical CSS and `font-display: swap` ensure ultra-fast mobile loading for Facebook/TikTok traffic.
- **Automated Dynamic SEO**: Auto-generates dynamic `/sitemap.xml`, configurable `/robots.txt`, and embeds Schema.org `Product` JSON-LD structured data on every product page with BDT currency.

---

## 🛒 Phone-Number-First Checkout System

Engineered specifically for consumer purchasing behavior in Bangladesh:

- **Bangladeshi Phone Normalization**:
  - Accepts all standard prefixes: `013-019`, `+880`, `880`, and spaces/dashes.
  - Automatically translates Bengali numerals (`০, ১, ২, ৩, ৪, ৫, ৬, ৭, ৮, ৯`) to standard English digits (`0-9`).
  - Normalizes every number to standard 11-digit `01XXXXXXXXX` format.
- **Debounced Smart Customer Lookup**:
  - When 11 digits are entered, a debounced, rate-limited AJAX request checks the `customers` table.
  - **Returning Customer**: Displays a friendly *"Welcome back, {Name}"* badge, auto-fills the name, address, and district, and provides an *"Edit Details"* toggle.
  - **New Customer**: Automatically expands name, full delivery address, and district fields.
  - **Privacy Masking**: Returns only safe masked previews to prevent unauthorized harvesting of customer data.
- **Cascading Bangladesh Location Selectors**:
  - Pre-seeded with all **64 Districts** and **495 Thanas / Upazilas**.
  - Selecting a district dynamically filters thanas and recalculates delivery fees.
- **Flexible Delivery Zones**:
  - Inside Dhaka (Default: ৳60)
  - Outside Dhaka (Default: ৳120)
  - Configurable custom sub-zones with free-delivery thresholds in Admin Settings.
- **Real-Time Reactive Order Summary**:
  - Live calculations via Alpine.js: Unit Price × Quantity − Volume Discounts + Delivery Charge = Total Payable in BDT (`৳`).
- **Flexible Payment Methods**:
  - **Cash on Delivery (COD)**: Default 1-click confirmation.
  - **Manual Mobile Banking**: Toggleable support for bKash, Nagad, and Rocket with configurable merchant account numbers, transaction instructions, and customer TrxID / sender number fields.
- **Abandoned Checkout & Lead Capture**:
  - As soon as a visitor types a valid phone number, an incomplete lead is captured in the `incomplete_orders` table.
  - Leads automatically convert to `converted` status once the order is finalized.
- **Spam & Duplicate Order Protection**:
  - Invisible honeypot inputs reject automated bots.
  - IP and phone blocklist check via `Blocklist::isBlocked($phone, $ip)`.
  - Configurable duplicate order prevention window (default: 5 minutes) prevents double submissions.
- **Single-Fire Success Page**:
  - Redirects to `/order-success/{signed-token}` with an unguessable token (not sequential IDs).
  - Contains order summary and direct tracking links.
  - Tracks `purchase_tracked_at` on the order record, guaranteeing that browser refreshes **never** double-fire the Meta/GA4 purchase tracking events.

---

## 🎨 Visual 3-Pane Landing Page Builder

An intuitive visual builder designed inside Filament at `/admin/products/{product}/builder`:

- **Left Pane — Section Library**:
  - Instant search and category tabs across 107+ prebuilt sections.
  - 1-click **"+ Add Section"** to append new sections to the page.
  - Access to reusable **Saved Sections** and AI generation prompts.
- **Center Canvas — Interactive Live Preview**:
  - Live iframe preview rendered with actual product data and theme tokens.
  - Responsive Viewport Switcher: **Desktop (100%)**, **Tablet (768px)**, and **Mobile (375px)**.
  - Drag-and-drop reordering powered by **SortableJS**.
  - Quick-Action Hover Toolbar on every section:
    - 👁️ **Hide / Show**: Toggle visibility without deleting the section.
    - 📋 **Duplicate**: Clone a section with its exact configuration.
    - 🗑️ **Delete**: Remove a section from the page.
    - 💾 **Save as Reusable**: Save custom section configurations to library.
    - 🪄 **Rewrite with AI**: Contextual copy improvement.
- **Right Pane — Reactive Section Inspector**:
  - Edit all content fields (headlines, subtitles, badges, button texts, bullet items).
  - Style Tokens: Background colors, spacing, typography.
  - Responsive Controls: Toggle visibility per device (desktop vs. mobile).
- **Revisions & Version History**:
  - Automatic snapshot taken before applying any template.
  - 1-click **Restore Revision** to roll back any changes instantly.
- **Global Design Tokens**:
  - Configure primary, secondary, and accent brand colors, font family (Hind Siliguri / Inter), and button radius per landing page.

---

## 📚 Prebuilt Section Library (107+ Sections)

All sections are auto-registered in `SectionRegistry` and available in the builder:

| Category | Count | Sections Included |
| :--- | :---: | :--- |
| **Hero & Banner** | 12 | `hero_image_right`, `hero_image_left`, `hero_centered`, `hero_video_bg`, `hero_countdown_offer`, `hero_price_cta`, `hero_slider`, `hero_split_form`, `hero_minimal`, `hero_badges`, `hero_floating_product`, `hero_testimonial_strip` |
| **Product Showcase** | 10 | `showcase_gallery_grid`, `showcase_slider_thumbs`, `showcase_360_preview`, `showcase_zoom`, `showcase_feature_callouts`, `showcase_before_after`, `showcase_video_review`, `showcase_unboxing`, `showcase_size_chart`, `showcase_variants` |
| **Features & Benefits** | 12 | `features_grid_3col`, `features_grid_4col`, `features_alternating_rows`, `features_checklist`, `features_numbered_steps`, `features_cards`, `features_big_image`, `features_us_vs_them`, `features_specs_table`, `features_ingredient_list`, `features_why_choose_us`, `features_problem_solution` |
| **Social Proof & Reviews** | 12 | `proof_testimonial_cards`, `proof_slider`, `proof_screenshot_reviews` (WhatsApp/FB chat style), `proof_video_testimonials`, `proof_rating_summary`, `proof_review_wall`, `proof_customer_photos`, `proof_logos_strip`, `proof_counter_sold`, `proof_live_popup`, `proof_trust_badges`, `proof_case_study` |
| **Offers & Urgency** | 10 | `offer_countdown_banner`, `offer_limited_stock_bar`, `offer_discount_badge`, `offer_bundle_cards`, `offer_buy_more_save_more`, `offer_free_delivery_strip`, `offer_flash_sale_ticker`, `offer_coupon_box`, `offer_price_comparison`, `offer_sticky_offer_bar` |
| **Order Form & Checkout** | 10 | `order_form_classic`, `order_form_summary_split`, `order_form_two_column`, `order_form_sticky_mobile`, `order_form_popup_modal`, `order_form_minimal_1step`, `order_form_variant_picker`, `order_form_quantity_radios`, `order_form_bump_offer`, `order_form_zone_selector` |
| **FAQ** | 5 | `faq_accordion_modern`, `faq_two_column`, `faq_categorized_tabs`, `faq_with_hotline_cta`, `faq_minimal_clean` |
| **Guarantee & Trust** | 6 | `trust_money_back_guarantee`, `trust_cod_assurance`, `trust_replacement_warranty`, `trust_return_policy`, `trust_secure_order_steps`, `trust_delivery_promise` |
| **Content & Brand Story** | 8 | `content_rich_text`, `content_image_with_story`, `content_video_narrative`, `content_timeline_journey`, `content_about_brand`, `content_founder_note`, `content_editorial_article`, `content_stats_counters` |
| **Media & Videos** | 5 | `media_video_embed`, `media_video_grid`, `media_masonry_gallery`, `media_curated_lookbook`, `media_instagram_feed` |
| **Call to Action (CTA)** | 6 | `cta_full_width_banner`, `cta_image_with_headline`, `cta_countdown_urgent`, `cta_whatsapp_hotline`, `cta_floating_button`, `cta_sticky_bottom_bar` |
| **Header & Nav** | 5 | `header_logo_call_button`, `header_sticky_with_cta`, `header_announcement_ticker`, `header_minimal_centered`, `header_with_language_switch` |
| **Contact & Footer** | 6 | `footer_contact_strip`, `footer_map_address`, `footer_minimal`, `footer_with_policy_links`, `footer_social_row`, `footer_brand_summary` |
| **Niche / Category-Specific** | 10 | `niche_fashion_size_guide`, `niche_fabric_care_instructions`, `niche_food_nutrition_facts`, `niche_cosmetics_how_to_use`, `niche_cosmetics_ingredients`, `niche_gadgets_specs_table`, `niche_gadgets_warranty_info`, `niche_home_room_preview`, `niche_recipe_serving_suggestion`, `niche_organic_halal_badges` |

---

## 📦 Ready-Made Shop Templates (107 Templates)

Seeded with realistic Bengali and English copy reflecting Bangladeshi purchasing psychology:

- **Fashion & Apparel (10)**: Panjabi/Men's ethnic, Saree, Three-piece/Salwar, Designer Kurti, Formal Shirts & Pants, Everyday T-Shirts & Polos, Denim Jeans, Kids' Wear, Hijab/Borka/Abaya, Winter Wear/Jackets.
- **Footwear & Bags (8)**: Genuine Leather Formal Shoes, Running Sneakers, Casual Leather Sandals, Women's Luxury Handbag, Travel Duffel & Backpack, Slim Wallet & Belt Combo, School & College Bag, Ladies Flat Sandals.
- **Beauty & Personal Care (8)**: Kumkumadi Face Serum, Herbal Hair Fall Solution Oil, Natural Glow Day & Night Cream, Halal Attar & Perfume, Beard Grooming Kit, Organic Neem & Turmeric Soap, Matte Liquid Lipstick Set, Sunblock SPF 50+ Aqua Gel.
- **Health & Wellness (8)**: 100% Pure Sundarban Raw Honey, Cold-Pressed Black Seed (Kalonji) Oil, Saudi Medjool Dates, Shilajit & Ginseng Energy Booster, Pure Village Deshi Ghee, Deep Tissue Electric Massage Gun, Smart Digital Blood Pressure Monitor, Magnetic Posture Corrector Belt.
- **Grocery & Organic Food (9)**: Pure Wood-Pressed Mustard Oil, Kalijeera Aromatic Rice, Handmade Traditional Mango Pickle, Rajshahi Fresh Himsagar Mango, Royal Assam & Sylhet Black Tea, Crispy Chanachur & Dry Snacks Combo, Fresh Hilsha (Ilish) Fish Box, Organic Mixed Dry Fruits & Nuts, Organic Chia Seeds & Flaxseeds.
- **Electronics & Gadgets (8)**: ANC Wireless Bluetooth Earbuds, AMOLED Bluetooth Calling Smartwatch, 20,000mAh PD Fast-Charging Power Bank, Professional Cordless Beard & Hair Trimmer, 360° WiFi Smart Security Camera, Portable USB Rechargeable Mini Desk Fan, High-Speed Multi-Port USB-C Hub, Ultra-Bright Emergency LED Rechargeable Light.
- **Home & Living (8)**: Luxury 100% Cotton Double Bedsheet Set, Multifunctional Vegetable Chopper & Slicer, Non-Stick Granite Cookware Set, Ultrasonic Aromatherapy Oil Diffuser, Foldable Clothes Wardrobe & Storage Box, Microfiber Spin Mop & Bucket Set, Modern Geometric Wall Clock, Ergonomic Memory Foam Neck Pillow.
- **Kids & Baby (8)**: Rechargeable RC Stunt Monster Car, Interactive English & Bangla Sound Book, Non-Toxic Silicone Baby Feeding Set, 120-Piece Wooden Building Blocks, Ergonomic Multi-Position Baby Carrier, Waterproof Diaper Backpack, Educational Spelling & Counting Puzzle, Ultra-Soft Cotton Newborn Clothing Set.
- **Books & Education (8)**: Bestselling Self-Development Books Combo, Complete Freelancing & Outsourcing Video Course, IELTS Masterclass Digital Preparation Package, Islamic History & Stories of the Prophets, English Spoken & Fluency Crash Course, Luxury Hardcover Ruled Journal & Metal Pen, Digital Marketing & Facebook Ads Practical Course, Kids Bengali Quran & Ampara Learning Kit.
- **Automotive (8)**: DOT Certified Full-Face Motorcycle Helmet, Waterproof Heavy-Duty Bike & Motorbike Cover, Dual-Lens High-Resolution Dash Camera, High-Pressure Portable Car Foam Sprayer, Anti-Theft GPS Motorcycle & Car Tracker, 12V High-Power Digital Car Tyre Inflator, Universal Magnetic Car Dashboard Phone Mount, Microfiber Car Detailing Wash & Wax Kit.
- **Gifts & Occasions (8)**: Luxury Eid Gift Hamper with Chocolate & Attar, Romantic Anniversary / Birthday Surprise Box, Customized Couple Matching Watches & Mug Box, Ramadan Special Iftar & Prayer Kit, Traditional Pohela Boishakh Festive Gift Box, Executive Corporate Leather Notebook Gift Set, Mother's Day Special Skincare Gift Box, Wedding Invitation & Sweets Presentation Box.
- **Handicraft & Local Heritage (8)**: Handwoven Pure Silk Tangail Jamdani Saree, Traditional Handcrafted Nakshi Kantha Blanket, Clay Terracotta Decorative Pottery Set, Eco-Friendly Golden Fiber Jute Shopper Bag, Genuine Full-Grain Hazaribagh Leather Duffle Bag, Handcrafted Bamboo & Cane Home Decor Lamp, Local Handloom Khadi Cotton Kurta, Brass & Bell-Metal Traditional Tabletop Artifact.
- **Universal Templates (7)**: Clean Modern Minimalist, Bold Flash Sale Urgency, Split-Screen Hero with Instant Checkout, Storytelling Editorial Founder Page, Dark Mode High-Tech Product Showcase, Ultra-Simple 1-Step Mobile Speed Checkout, Multi-Offer Grid.

### Template Import / Export
- **Export**: Export any template definition as a clean, portable `.json` file.
- **Import**: Upload `.json` template blueprints directly in Filament under **Templates** to register new templates without writing code.

---

## 📡 Tracking, Analytics & Meta CAPI Engine

Plug-and-play marketing tracking configured entirely through Admin Settings:

- **Unified Client & Server Tracking Architecture**:
  - Client-side `<x-tracking.head/>` injects standard GA4 ecommerce `dataLayer`, supports Google Consent Mode v2, and exposes `window.LLK.track(event, payload)`.
  - Server-side events are dispatched via queued `SendServerTrackingEvent` jobs with automatic retry and exponential backoff.
- **Meta Pixel + Conversions API (CAPI)**:
  - Dual browser and server-side tracking.
  - Automatically normalizes and hashes user identifiers with **SHA-256** (`phone` in E.164 format `8801XXXXXXXX`, `email`, `first_name`, `city`, `country=bd`).
  - Passes `_fbp` and `_fbc` click cookies, IP address, and user agent for maximum **Event Match Quality (EMQ 8.0+)**.
  - **Deduplication**: Browser `fbq('track', ...)` and server CAPI events share the identical `event_id`.
- **Stape Server-Side Gateway**:
  - Native integration with Stape custom domain GTM loaders and Stape Meta CAPI proxy endpoints.
- **Supported Tracking Platforms**:
  - Google Tag Manager (GTM container ID + custom loader domain)
  - Meta Pixel & Meta Conversions API (CAPI)
  - Google Analytics 4 (GA4 direct Measurement Protocol + gtag)
  - Google Ads (Conversion ID, labels, enhanced conversions)
  - TikTok Pixel & TikTok Events API
  - Microsoft Clarity & Hotjar session recordings
  - Custom script injection slots for Header, Body, and Footer
- **Live Event Debugger**:
  - `tracking_logs` table logs every dispatched server event, payload, response status, and duration.
  - Filament **Tracking Logs** resource with test event buttons for immediate verification.
- **Attribution & UTM Capture**:
  - Automatically captures and saves `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`, `fbclid`, `gclid`, and `ttclid` on orders and leads.

---

## 🔄 Order Management & 22-State Lifecycle Machine

Complete order lifecycle system governed by `OrderStatusStateMachine`:

### 22 Status Definitions
`incomplete` (Lead), `pending`, `confirmed`, `on_hold`, `processing`, `ready_to_ship`, `shipped` (Sent to Courier), `in_transit`, `out_for_delivery`, `delivered`, `partially_delivered`, `payment_pending`, `paid`, `failed_delivery`, `rescheduled`, `cancelled`, `returned`, `return_received`, `refunded`, `partially_refunded`, `exchange`, `fake_spam`.

- **Atomic Stock Management**:
  - Inventory is automatically and atomically decremented upon order **Confirmation**.
  - Stock is restored if an order is **Cancelled** or **Returned**.
- **Immutable Status Timeline**:
  - Every status change creates an entry in `order_status_histories` recording previous status, new status, the user who made the change, timestamp, and notes.
- **Customer Lifetime Metrics**:
  - Automatically recalculates customer statistics: `total_orders`, `total_spent`, `delivered_orders_count`, `returned_orders_count`, `cancelled_orders_count`, and `success_rate` percentage.
- **Manual Order Entry**:
  - Create manual phone orders directly from the Filament admin panel with instant customer lookup.
- **Volume & Bundle Pricing Tiers**:
  - Configure quantity discounts (e.g., *"Buy 2 Save ৳150"*, *"Buy 3 Get Free Delivery"*).

---

## 🚚 Courier Integrations

Pluggable courier driver contract (`CourierDriverInterface`) with native implementations:

- **Steadfast Courier (`SteadfastCourierDriver`)**:
  - Send orders to Steadfast via API.
  - Stores consignment ID, tracking code, and provides tracking URLs.
- **Pathao Courier (`PathaoCourierDriver`)**:
  - OAuth2 bearer token authentication.
  - City, zone, and area mapping with live parcel dispatch.
- **RedX Courier (`RedXCourierDriver`)**:
  - Access token authentication and parcel creation.
- **In-House / Manual Courier (`ManualCourierDriver`)**:
  - Record custom delivery rider details and manual tracking codes.
- **1-Click Dispatch**:
  - Dispatch individual or bulk orders to couriers directly from the Filament Orders table.

---

## 🤖 AI Page & Copywriting Builder

Powered by [`imrandevbd/laravel-ai-hub`](https://github.com/imrandevbd/laravel-ai-hub):

- **Full Landing Page Generation**:
  - Input product name, target audience, price, tone, and language (Bangla / English / Mixed).
  - Generates structured JSON adhering to registered section schemas.
  - Validated and sanitized against XSS/script injection before saving as a draft in the builder.
- **Contextual Copywriting & Micro-Copy**:
  - Rewrite sections to be shorter, more persuasive, or create higher urgency.
  - Translate between English and Bangla.
- **Automated SEO Generator**:
  - Generate high-CTR SEO meta titles and descriptions tailored to Bangladeshi shoppers.
- **Image Prompt Suggestions**:
  - Generates detailed midjourney/flux prompts tailored to the product for ad creatives.
- **Token & Cost Usage Tracking**:
  - Logs all AI API requests, tokens used, and response latency.

---

## 📄 Unicode Bengali PDF Documents

Powered by [`imrandevbd/laravel-unicode-pdf`](https://github.com/imrandevbd/laravel-unicode-pdf):

- **Correct Bengali Typography**: Uses embedded *Hind Siliguri* fonts to ensure complex Bengali script conjuncts (যুক্তাক্ষর) and vowel signs render without broken glyphs.
- **Generated Documents**:
  1. **Customer Invoices**: Formatted with company logo, customer details, itemized breakdown, BDT pricing, and payment terms.
  2. **Warehouse Packing Slips**: Optimized for packing and order fulfillment.
  3. **Courier Shipping Stickers**: Compact printable labels with customer address, COD amount, and QR codes.
- **Instant Preview & Download**: Print or download PDFs with 1 click directly from the Filament Orders resource.

---

## 🖥️ Admin Panel, Widgets & Roles (Filament v4)

Built with modern Filament v4 components and Spatie Role-Based Access Control:

- **Roles & Permissions**:
  - `Super Admin`: Complete system control, settings, and script management.
  - `Manager`: Order management, product creation, customer edits, reports.
  - `Order Handler`: Order status progression, courier booking, and invoice printing.
  - `Content Editor`: Visual builder, landing pages, templates, and blog content.
- **Interactive Dashboard Widgets**:
  - **Stats Overview**: Today/Week/Month Revenue, Orders count, Conversion Rate (%), and Incomplete Leads.
  - **Order Status Lifecycle Chart**: Real-time visual chart showing orders across pending, confirmed, shipped, delivered, and cancelled states.
  - **Latest Orders Table**: Fast-access view of recent orders with status badges and quick actions.
- **Settings System**:
  - Tabbed settings panel: General, Checkout, Delivery Zones, Courier API Keys, Tracking & CAPI, Payment Methods (bKash/Nagad/Rocket), and Security Blocklists.
  - Sensitive API keys encrypted at rest in the database.

---

## 🛠️ Tech Stack & Native Packages

| Layer | Technology | Details |
| :--- | :--- | :--- |
| **Framework** | Laravel 13 (Latest Stable) | PHP 8.3+ |
| **Admin Panel** | Filament v4 | Schemas architecture, dark/light mode |
| **Database** | MySQL 8.0 | InnoDB, UTF8mb4, indexed relations |
| **Queue / Cache** | Redis 6.0+ | Asynchronous CAPI, AI jobs, and PDF rendering |
| **Frontend** | Blade + Tailwind CSS + Alpine.js | Zero SPA overhead, 90+ Lighthouse mobile |
| **Testing** | Pest PHP | 54 tests, 261 assertions passing cleanly |
| **Code Style** | Laravel Pint | Strict PSR-12 standard |

### Native Packages
1. [`imrandevbd/laravel-ai-hub`](https://github.com/imrandevbd/laravel-ai-hub): AI engine and provider management.
2. [`imrandevbd/laravel-unicode-pdf`](https://github.com/imrandevbd/laravel-unicode-pdf): UTF-8 Bengali PDF generator.
3. [`imrandevbd/laravel-universal-slug`](https://github.com/imrandevbd/laravel-universal-slug): Multi-script slugs with 301 history redirects.
4. [`imrandevbd/laravel-filament-master`](https://github.com/imrandevbd/laravel-filament-master): Filament resource helpers and base conventions.

---

## 🚀 Installation & Quickstart Guide

### Prerequisites
- PHP 8.3+ (Extensions: `bcmath`, `curl`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo_mysql`, `sodium`, `zip`)
- Composer 2.5+
- MySQL 8.0+
- Redis 6.0+

### Step-by-Step Setup
```bash
# 1. Clone repository
git clone https://github.com/imrandevbd/laravel-landing-kit.git
cd laravel-landing-kit

# 2. Install dependencies & configure .env
cp .env.example .env
composer install
php artisan key:generate

# 3. Configure database in .env (MySQL 8 & Redis)
# DB_DATABASE=laravel_landing_kit
# DB_USERNAME=root
# DB_PASSWORD=secret

# 4. Run the Master Installer
php artisan llk:install --force
```

### The `llk:install` command automatically:
1. Executes all database migrations.
2. Seeds default shop settings and BDT currency formatting.
3. Seeds all 64 Bangladesh districts, 495 thanas, and delivery zones.
4. Seeds Spatie RBAC roles (`Super Admin`, `Manager`, `Order Handler`, `Content Editor`).
5. Seeds all 107 ready-made shop templates.
6. Seeds demo products with published landing pages.
7. Creates the default Super Admin user (`admin@amaronline.com` / `password`).
8. Links public storage for media uploads.

### Start the Development Server
```bash
# Start local server on standard port 80:
php artisan serve --port=80

# Or standard port 8000:
php artisan serve
```

### Default URLs & Credentials
- **Admin Panel**: [`http://localhost/admin`](http://localhost/admin)
- **Email**: `admin@amaronline.com`
- **Password**: `password`
- **Demo Storefront**: [`http://localhost/premium-semi-fitted-eid-panjabi`](http://localhost/premium-semi-fitted-eid-panjabi)
- **Order Tracking**: [`http://localhost/track-order`](http://localhost/track-order)
- **Sitemap XML**: [`http://localhost/sitemap.xml`](http://localhost/sitemap.xml)
- **Robots TXT**: [`http://localhost/robots.txt`](http://localhost/robots.txt)

---

## 🧪 Testing & Quality Assurance

Run the automated Pest test suite covering all 11 phases:
```bash
vendor/bin/pest
```

Verify strict code style with Laravel Pint:
```bash
vendor/bin/pint --test
```

---

## 📄 License
The MIT License (MIT). Please see [License File](LICENSE) for more information.

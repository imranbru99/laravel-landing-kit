# Laravel Landing Kit (LLK) 🇧🇩

> **A high-performance, admin-managed Laravel 13 landing page and one-product-checkout system engineered specifically for Bangladesh e-commerce shops.**

[![Laravel 13](https://img.shields.io/badge/Laravel-13.x-red.svg)](https://laravel.com)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://php.net)
[![Filament v4](https://img.shields.io/badge/Filament-v4.x-orange.svg)](https://filamentphp.com)
[![Pest Tests](https://img.shields.io/badge/Tests-54%20passed-brightgreen.svg)](https://pestphp.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

A modern, lightning-fast alternative to WordPress/Elementor/WooCommerce for direct-to-consumer merchants running Facebook/TikTok ad funnels in Bangladesh. Achieve **90+ Google Lighthouse mobile scores**, eliminate cart abandonment, and streamline Cash-on-Delivery fulfillment.

---

## ⚡ Key Highlights

- **Phone-Number-First Checkout**: Validates Bangladeshi mobile formats (`01[3-9]...`), normalizes prefixes (`+880`, `880`), translates Bengali digits (`০-৯`), and auto-detects returning customers with privacy-preserving masked autofill.
- **Root-Level Product Slugs**: Every product has its own dedicated landing page at `/{product-slug}` using Unicode-safe slugs with historical 301 redirects.
- **3-Pane Visual Builder**: Custom drag-and-drop builder with a library of **107+ prebuilt sections**, live iframe canvas with responsive desktop/tablet/mobile toggles, live property inspector, revisions restore, and reusable saved sections.
- **107 Ready-Made Bangladeshi Shop Templates**: Spread across 13 core categories (Panjabi, Saree, Honey, Organic Oil, ANC Earbuds, Smartwatch, Bedsheets, Books, Helmets, etc.) with realistic Bengali copy, BDT pricing (`৳`), and festival offers (Eid, Pohela Boishakh, Winter Sale).
- **AI-Powered Builder**: Integrates `imrandevbd/laravel-ai-hub` to generate complete structured landing pages, rewrite micro-copy, craft persuasive benefits, and suggest image prompts—with strict schema validation and XSS protection.
- **Plug-and-Play Tracking & CAPI**: Dual browser & server-side tracking (Google Tag Manager, Meta Pixel + Conversions API via Stape or direct, GA4 Measurement Protocol, TikTok Events API) with SHA-256 hashed user data, shared `event_id` deduplication, and single-fire purchase guards.
- **22-State Order Lifecycle**: Complete order state machine (Incomplete Lead, Pending, Confirmed, Shipped, Delivered, Returned, etc.) with atomic stock decrement/restoration and customer metrics calculation.
- **Pluggable Bangladeshi Couriers**: Native driver implementations for **Steadfast**, **Pathao**, **RedX**, and In-House/Manual delivery.
- **Unicode Bengali PDFs**: Invoices, packing slips, and courier stickers rendered with correct Bengali typography via `imrandevbd/laravel-unicode-pdf`.
- **Zero-Config Master Installer**: Run `php artisan llk:install` to set up migrations, seed locations, templates, demo products, and super admin.

---

## 🛠️ Tech Stack & Required Packages

- **Framework**: Laravel 13 (Latest Stable)
- **Admin Panel**: Filament v4
- **Database**: MySQL 8.0 & Redis 6.0+ (Sessions, Queues, Cache)
- **Frontend**: Blade + Tailwind CSS (standalone CDN / critical CSS) + Alpine.js
- **Testing**: Pest PHP (54 tests, 261 assertions)
- **Packages**:
  1. [`imrandevbd/laravel-ai-hub`](https://github.com/imrandevbd/laravel-ai-hub): AI engine & provider management.
  2. [`imrandevbd/laravel-unicode-pdf`](https://github.com/imrandevbd/laravel-unicode-pdf): Bangla-safe PDF document generator.
  3. [`imrandevbd/laravel-universal-slug`](https://github.com/imrandevbd/laravel-universal-slug): Multi-script slug engine with 301 history redirects.
  4. [`imrandevbd/laravel-filament-master`](https://github.com/imrandevbd/laravel-filament-master): Filament resource conventions & base helpers.

---

## 🚀 Quickstart & Installation

```bash
# 1. Clone the repository
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

### Default Credentials
- **Admin Panel**: [`http://localhost/admin`](http://localhost/admin)
- **Email**: `admin@amaronline.com`
- **Password**: `password`
- **Demo Storefront**: [`http://localhost/premium-semi-fitted-eid-panjabi`](http://localhost/premium-semi-fitted-eid-panjabi)

---

## 📂 Documentation

- 📖 [Setup & Deployment Guide](docs/SETUP.md)
- 📐 [Architectural Decisions & Record](docs/DECISIONS.md)
- 🎨 [Section Library & Adding Custom Sections](docs/SECTIONS.md)
- 📦 [107 Templates Catalog & JSON Import/Export](docs/TEMPLATES.md)
- 📡 [Tracking Setup: Stape, GTM & Meta CAPI](docs/TRACKING.md)
- 🚚 [Courier Integration: Steadfast, Pathao & RedX](docs/COURIERS.md)

---

## 🧪 Testing & Code Quality

Run tests using Pest:
```bash
php artisan test
```

Run Laravel Pint for strict PSR-12 code style:
```bash
vendor/bin/pint
```

---

## 📄 License
The MIT License (MIT). Please see [License File](LICENSE) for more information.

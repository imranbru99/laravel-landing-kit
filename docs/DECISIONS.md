# Architectural & Design Decisions

This document records architectural, technical, and domain decisions made for **Laravel Landing Kit (LLK)**.

---

## 1. Tech Stack & Version Compatibility
- **PHP**: PHP 8.3.35 CLI running with extensions (`bcmath`, `curl`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo_mysql`, `sodium`, `zip`).
- **Laravel Framework**: Laravel 13 (`^13.0` / v13.33.0).
- **Filament**: Filament v4.14.0. Both Filament v4.14+ and `imrandevbd/laravel-filament-master` cleanly target Laravel 13 contracts and support each other.
- **Filament v4 Compatibility Alias**: In Filament v4, `CanManipulateFiles` moved from `Filament\Commands\Concerns\CanManipulateFiles` to `Filament\Support\Commands\Concerns\CanManipulateFiles`. An autoload alias in `bootstrap/compat.php` ensures `imrandevbd/laravel-filament-master` CLI commands operate smoothly.
- **Database**: MySQL 8.0 on port 3306 running in Docker container (`llk-mysql`).
- **Cache & Queue & Session**: Redis container (`llk-redis`) on port 6379 via `predis/predis`, with database fallback for local resilience.
- **Testing**: Pest PHP v4 (`pestphp/pest` and `pestphp/pest-plugin-laravel`).

---

## 2. Package Integrations
1. **`imrandevbd/laravel-ai-hub` (v1.6.0)**:
   - Powers all AI-assisted copywriting, structured JSON landing page generation, section drafting, and image prompts.
   - Provider keys (Gemini, OpenAI, Anthropic, etc.) can be configured through Admin Settings UI stored in encrypted form, falling back to `.env`.
   - Uses `AIHub::fake()` in automated tests for predictable, deterministic assertions without external API costs.
2. **`imrandevbd/laravel-unicode-pdf` (dev-main)**:
   - Configured with the `'bengali'` preset for generating invoices, order slips, packing slips, and courier stickers.
   - Preserves complex Bengali script shaping and UTF-8 glyphs properly.
3. **`imrandevbd/laravel-universal-slug` (dev-main)**:
   - Multi-script, Unicode-safe slug generator with historical 301 redirects, atomic locking, and reserved system routes (`admin`, `checkout`, `order-success`, `track-order`, `api`, `llk`).
4. **`imrandevbd/laravel-filament-master` (dev-main)**:
   - Used for standardized resource patterns, base classes, and automated schema introspection.

---

## 3. Order Status State Machine
- **Statuses**:
  - `incomplete` (Lead)
  - `pending` (Default for customer submitted orders)
  - `confirmed` (Triggers stock decrement)
  - `on_hold`
  - `processing`
  - `ready_to_ship`
  - `shipped` (Sent to courier)
  - `in_transit`
  - `out_for_delivery`
  - `delivered` (Revenue recognized)
  - `partially_delivered`
  - `payment_pending`
  - `paid`
  - `failed_delivery`
  - `rescheduled`
  - `cancelled` (Stock restored if previously confirmed)
  - `returned` (Stock restored if returned)
  - `return_received`
  - `refunded`
  - `partially_refunded`
  - `exchange`
  - `fake_spam`
- **Transition Guard**:
  - Validated via `OrderStatusStateMachine` service.
  - Every status change creates an immutable `order_status_histories` entry recording `from_status`, `to_status`, `changed_by_user_id`, and `notes`.
  - Dispatches `OrderStatusChanged` event for stock adjustment, tracking, courier notification, and customer SMS/Email.

---

## 4. Frontend & Checkout Architecture
- **Root-level routing**: `/{slug}` catches product landing pages while checking against universal-slug reserved paths.
- **Phone-Number-First Checkout**:
  - Normalizes `+880`, `880`, Bangladeshi phone formats with 013-019 prefixes, including Bengali numeral translation (`০-৯` -> `0-9`).
  - Debounced AJAX lookup `/api/checkout/customer-lookup`:
    - Checks `customers` table.
    - If found: returns masked preview and prefill data.
    - If not found: triggers expansion of address fields for new customer.
    - Rate limited to prevent phone scanning.
  - Automatically captures incomplete orders (abandoned checkout leads) when a phone number is entered.
- **Tracking**:
  - Unified `TrackingManager` dispatching both client-side `dataLayer.push()` and server-side Conversions API (Meta CAPI, Stape, GA4 Measurement Protocol, TikTok Events API).
  - Deduplicated with shared `event_id`.
  - `purchase_tracked_at` timestamp on `orders` prevents duplicate purchase firing on refresh.

---

## 5. Courier & Document Generation
- **Courier Drivers**:
  - Implements `CourierDriverInterface` with `sendOrder(Order $order)` and `trackOrder(Order $order)`.
  - Pluggable drivers: `SteadfastCourierDriver`, `PathaoCourierDriver`, `RedXCourierDriver`, and `ManualCourierDriver`.
  - Credentials managed via cached and encrypted settings in admin panel.
  - Consignment ID and tracking code recorded on order for 1-click status checking.
- **Bangla PDF Generation**:
  - `PdfGeneratorService` backed by `imrandevbd/laravel-unicode-pdf`.
  - Templates rendered in UTF-8 with Hind Siliguri font support for Bengali invoices, packing slips, and courier stickers.
  - Generates downloadable PDFs or print-ready preview streams directly from the admin panel orders table.
- **Customer Lifetime Analytics**:
  - Atomic recalculation of customer statistics (`total_orders`, `total_spent`, `delivered_orders_count`, `returned_orders_count`, `cancelled_orders_count`, `success_rate`) upon order status updates.

---

## 6. Public Checkout, Lead Capture & Public Pages
- **Root Slug Handling**:
  - `/{product-slug}` routes to `ProductLandingController@show`.
  - Guarded against system routes (`admin`, `checkout`, `track-order`, `api`, `login`, etc.) via `slug_is_reserved()`.
  - Unmatched slugs fall back to `SlugHistory` for permanent 301 redirects, preserving SEO value when a product title/slug changes.
- **Phone-First Returning Customer Recognition**:
  - Accept phone numbers with any BD prefix (`01[3-9]...`, `+880`, `880`) and Bengali numerals.
  - Returns masked customer name and address snapshot to avoid exposing full customer data publicly.
  - Allows returning customers 1-click order confirmation or explicit detail editing.
- **Incomplete Checkout / Lead Capture**:
  - Captures customer phone and partial entries into `incomplete_orders` on blur or debounce.
  - Automatically converts lead status to `converted` when the order is successfully finalized.
- **Deduplicated Purchase Tracking**:
  - Order success page tracks `purchase_tracked_at` timestamp on `Order`.
  - Purchase events are dispatched only on the first visit with unified transaction ID and items payload; subsequent page refreshes never fire duplicate events.
- **Spam & Abuse Protection**:
  - Invisible honeypot inputs (`_hp_name`, `_hp_time`) reject bot submissions submitted in under 1 second.
  - IP and phone blocklist check via `Blocklist::isBlocked($phone, $ip)`.
  - Configurable duplicate order prevention window (default 5 minutes).
---

## 7. Section Registry & 3-Pane Visual Builder Architecture
- **Section System**:
  - `SectionTypeInterface` contract with `key()`, `label()`, `category()`, `icon()`, `schema()`, `defaults()`, `view()`, `preview()`.
  - `BaseSection` and `ConfigurableSection` provide rapid extensibility for registering new sections without creating dedicated classes for each variant.
  - Over 107 pre-registered section blueprints across 14 distinct categories in `SectionCatalog.php`.
  - Blade views in `resources/views/sections/` support dynamic style tokens (background color, typography, spacing) and product contextual binding.
- **3-Pane Builder UX**:
  - Left pane: Section Library categorized by Hero, Showcase, Benefits, Social Proof, Urgency, Checkout, etc., with search and 1-click addition.
  - Center pane: Live interactive canvas with desktop/tablet/mobile preview frame switcher, drag-and-drop reordering powered by SortableJS, and section toolbar (duplicate, hide, delete, save as reusable).
  - Right pane: Reactive Section Settings Inspector for updating content fields, colors, and responsive visibility.
  - Revisions system snapshots landing page states with instant 1-click restore.
  - Reusable "Saved Sections" allow marketing teams to reuse high-converting custom blocks across multiple products.

---

## 8. Tracking Module, Meta CAPI & Stape Server-Side Gateway
- **Architecture**:
  - `TrackingManager` acts as the single entry point for browser and server events.
  - Client-side `<x-tracking.head/>` initializes standard GA4 `dataLayer`, handles Google Consent Mode v2, and exposes `window.LLK.track(event, payload)`.
  - Server-side events dispatched via `SendServerTrackingEvent` queued job with automatic retry and backoff.
- **Meta Conversions API (CAPI)**:
  - Supports direct Meta Graph API endpoint and Stape CAPI gateway proxy.
  - User identifiers (`phone` normalized to E.164 without leading plus `8801XXXXXXXX`, `email`, `first_name`, `city`) are hashed with SHA-256 before transmission.
  - Preserves `_fbp` and `_fbc` click cookies, client IP address, and user agent for maximum event match quality (EMQ 8.0+).
  - Deduplication: browser `fbq('track', event, payload, { eventID })` and server CAPI event share the identical `event_id`.
- **Live Event Debugger**:
  - `tracking_logs` table records platform, event name, payload, API response, status, and execution duration.
  - Dedicated Filament `TrackingLogResource` gives admins a live event inspector with test event buttons for immediate verification.

---

## 9. AI Builder Architecture
- **Package**: `imrandevbd/laravel-ai-hub` (`AIHub` facade).
- **Security & Schema Validation**:
  - Raw AI completions are never trusted or injected directly into views.
  - Full-page generation prompts require structured JSON conforming to `AiLandingGenerator` contract.
  - Output is strictly validated against allowed section types in `SectionRegistry` and sanitized against script tags and XSS payloads.
  - Fallback logic creates robust default hero, benefits, social proof, and checkout sections if the AI output fails schema validation.
- **Micro-Copy & SEO Tools**:
  - Contextual section rewrites (more persuasive, shorter, more urgent, Bengali translation).
  - Automated SEO title & meta description generation based on product attributes and target Bangladeshi audience.

---

## 10. Template System (107 Prebuilt BD E-Commerce Templates)
- **Catalog**:
  - 107 complete landing page templates seeded across 13 core categories: Fashion, Footwear & Bags, Beauty & Personal Care, Health & Wellness, Grocery & Food, Electronics & Gadgets, Home & Living, Kids & Baby, Books & Education, Automotive, Gifts & Occasions, Handicraft & Local, and Universal.
  - Realistic Bengali and English copy reflecting Bangladeshi consumer psychology: Cash on Delivery emphasis, delivery coverage across 64 districts, festival offers (Eid, Pohela Boishakh, Winter Sale, Ramadan).
- **Import / Export**:
  - Full template definitions exportable as clean, portable JSON files.
  - 1-click JSON import in Filament `TemplateResource` allows merchants to import templates from agency kits or export their best-performing landing pages.
  - Applying a template takes an automatic revision backup of the product's landing page before copying template sections.

---

## 11. Command-Line Installation & Dashboard
- **`php artisan llk:install`**:
  - Single-command zero-configuration setup for new servers.
  - Runs database migrations, default settings seeder, 64 Bangladesh districts & delivery zones, Spatie roles/permissions, 107 templates, demo products, default super admin user (`admin@amaronline.com`), and links public storage.
- **Filament Dashboard**:
  - Live analytics widgets: `StatsOverviewWidget` (Revenue, Orders, Conversion Rate, Incomplete Leads), `OrderStatusChartWidget` (visual lifecycle breakdown), and `LatestOrdersWidget` (recent orders table).
- **Schema.org & SEO**:
  - Dynamic `/sitemap.xml` generates clean XML with all active products and landing pages.
  - Dynamic `/robots.txt` respects site configuration and links to the sitemap.
  - Automatic `schema.org/Product` JSON-LD embedded on all product landing pages with price in BDT and stock availability.

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


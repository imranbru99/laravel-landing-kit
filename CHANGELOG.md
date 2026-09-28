# Changelog

All notable changes to **Laravel Landing Kit** will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.0] - 2026-09-28

### Added
- **Phase 1: Base Architecture & Settings**
  - Laravel 13 framework setup with Filament v4 admin panel.
  - Integration of `imrandevbd/laravel-ai-hub`, `imrandevbd/laravel-unicode-pdf`, `imrandevbd/laravel-universal-slug`, and `imrandevbd/laravel-filament-master`.
  - Encrypted settings management service (`SettingService`) with typed accessors and cache invalidation.
  - Spatie RBAC with Super Admin, Manager, Order Handler, and Content Editor roles.
  - Bangladeshi phone normalization and BDT currency formatting helpers with Bengali numeral conversion.
- **Phase 2: Location & Catalog Engine**
  - Seeders for 64 Bangladesh districts, 495 thanas/upazilas, and delivery zones (Inside Dhaka, Outside Dhaka).
  - Category and Product models with universal slug generation, gallery images, variants/attributes, and volume offers.
- **Phase 3: Orders, Customers & Couriers**
  - 22-state `OrderStatus` state machine with atomic stock decrement on confirmation and restoration on cancellation.
  - Customer lifetime metrics auto-calculation (`total_spent`, `success_rate`).
  - Pluggable courier driver contract with Steadfast, Pathao, RedX, and Manual delivery drivers.
  - Unicode Bengali PDF invoices, packing slips, and courier labels via `imrandevbd/laravel-unicode-pdf`.
- **Phase 4: Root Routing & Phone-First Checkout**
  - Root `/{slug}` routing with reserved system route protection and 301 history redirects.
  - Debounced phone lookup with privacy-preserving masked autofill.
  - Abandoned cart / lead capture into `incomplete_orders`.
  - Duplicate order prevention window and anti-bot honeypot.
  - Secure `/order-success/{order-token}` page with single-fire purchase tracking.
- **Phase 5: Section Registry & Catalog**
  - Modular `SectionRegistry` with `SectionTypeInterface` contract.
  - Over 107 prebuilt sections across 14 categories.
- **Phase 6: Visual 3-Pane Builder**
  - Interactive visual builder with section library, canvas iframe (desktop/tablet/mobile toggles), and property inspector.
  - SortableJS drag-and-drop reordering, section duplication, hiding, and deleting.
  - Revisions system with instant restore and reusable saved sections.
- **Phase 7: Dynamic Blade Section Views**
  - Polished responsive section views with design tokens, Alpine.js interactivity, and product context binding.
- **Phase 8: Tracking Module & Server-Side Gateway**
  - Dual browser and server-side tracking (Meta CAPI, Stape gateway, GA4 Measurement Protocol, TikTok Events API).
  - SHA-256 hashed user identifiers and shared `event_id` deduplication.
  - Live tracking logs resource with test event buttons in admin panel.
- **Phase 9: AI Landing Page Builder**
  - Full-page generation, section drafting, copy rewrite, and SEO tools via `imrandevbd/laravel-ai-hub`.
  - Strict JSON schema validation and XSS sanitization.
- **Phase 10: 107 Bangladesh Shop Templates**
  - 107 production-ready templates across 13 core Bangladeshi e-commerce categories.
  - Portable JSON template import and export in Filament.
- **Phase 11: Final Polish, CLI Installer & Analytics**
  - Single-command master installer: `php artisan llk:install`.
  - Filament Dashboard analytics widgets (`StatsOverviewWidget`, `OrderStatusChartWidget`, `LatestOrdersWidget`).
  - Dynamic `/sitemap.xml`, `/robots.txt`, and Schema.org Product JSON-LD.
  - Full test suite with 54 tests and 261 assertions passing cleanly.

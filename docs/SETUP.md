# Quickstart & Setup Guide

**Laravel Landing Kit (LLK)** is an enterprise-grade one-product checkout and visual landing page builder designed specifically for high-conversion Bangladeshi e-commerce.

---

## 1. Prerequisites
- **PHP**: 8.3 or higher (with `bcmath`, `curl`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo_mysql`, `sodium`, `zip`)
- **Composer**: 2.5+
- **Database**: MySQL 8.0+
- **Queue/Cache**: Redis 6.0+ (or database driver for local testing)
- **Node.js**: (Optional - Frontend uses modern zero-build Tailwind CSS & Alpine.js CDN)

---

## 2. Fast Installation

### Step 1: Clone & Configure Environment
```bash
git clone https://github.com/imranbru99/laravel-landing-kit.git
cd laravel-landing-kit
cp .env.example .env
composer install
php artisan key:generate
```

### Step 2: Database Configuration
Edit `.env` to connect to your MySQL and Redis servers:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_landing_kit
DB_USERNAME=root
DB_PASSWORD=secret

QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
CACHE_STORE=redis
```

### Step 3: Run Master Installer
Execute the single-command installer:
```bash
php artisan llk:install --force
```

This single command automatically:
1. Runs all database migrations.
2. Seeds default shop settings and currency formatting.
3. Seeds 64 Bangladesh districts, thanas/upazilas, and delivery zones.
4. Seeds Spatie RBAC roles (Super Admin, Manager, Order Handler, Content Editor).
5. Seeds 107+ ready-made e-commerce templates with Bengali copy.
6. Seeds demo products with published landing pages.
7. Creates the default Super Admin user.
8. Links public storage for uploads.

---

## 3. Default Credentials & URLs

| Service | URL / Info | Credentials |
| :--- | :--- | :--- |
| **Admin Panel** | `http://localhost/admin` | `admin@amaronline.com` / `password` |
| **Storefront** | `http://localhost` | Demo products at `/{slug}` |
| **Demo Panjabi** | `http://localhost/premium-semi-fitted-eid-panjabi` | Live Checkout |
| **Order Tracking** | `http://localhost/track-order` | Phone & Order Number |
| **Sitemap XML** | `http://localhost/sitemap.xml` | Auto-generated XML |
| **Robots TXT** | `http://localhost/robots.txt` | Direct SEO feed |

---

## 4. Background Queue Worker
For asynchronous Meta CAPI server-side event dispatch, AI generation, and courier tracking updates, run the Laravel queue worker:
```bash
php artisan queue:work --tries=3 --timeout=90
```

---

## 5. Running Automated Tests
The application is covered by Pest PHP:
```bash
php artisan test
# Or directly via Pest:
vendor/bin/pest
```

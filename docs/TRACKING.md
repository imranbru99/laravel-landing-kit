# Tracking & Marketing Setup Guide

**Laravel Landing Kit** includes enterprise-grade, server-side and client-side tracking designed to bypass iOS ad-blockers and privacy restrictions while maintaining high Event Match Quality (EMQ 8.0+).

---

## 1. Supported Tracking Integrations

1. **Google Tag Manager (GTM)**: Custom Web Container with support for custom loader domains (e.g., Stape).
2. **Meta Pixel & Conversions API (CAPI)**: Dual browser + server-side event tracking with SHA-256 hashed user data and deduplication.
3. **Stape Server-Side Gateway**: Stape custom domain proxy and CAPI gateway.
4. **Google Analytics 4 (GA4)**: Direct Measurement Protocol server-side integration and browser `gtag`.
5. **TikTok Pixel & Events API**: Browser and server tracking.
6. **Microsoft Clarity & Hotjar**: Session recording and heatmap analytics.
7. **Google Consent Mode v2**: Pre-configured defaults for EU/Global privacy compliance.

---

## 2. Configuration via Admin Settings UI

Navigate to **Admin Panel** (`/admin`) → **Settings** → **Tracking & Marketing**.

### Meta Pixel + Conversions API (CAPI)
1. Toggle **Meta Pixel & CAPI Enabled** to `ON`.
2. Enter your **Meta Pixel ID** (e.g., `123456789012345`).
3. Enter your **Meta CAPI Access Token** (generated from Meta Events Manager → Settings → Conversions API → Generate Access Token).
4. Enter your optional **Test Event Code** (e.g., `TEST12345`) to verify events in Meta Events Manager in real time.
5. Click **Save Changes**.

### Stape Server-Side Gateway Setup
1. Create a container on [Stape.io](https://stape.io).
2. Note your custom domain (e.g., `https://sst.yourbrand.com`).
3. In **Settings** → **Tracking & Marketing**:
   - Toggle **Stape Gateway Enabled** to `ON`.
   - Enter your **Stape Container URL**.
   - If using Stape's Meta CAPI Gateway, enter your dedicated endpoint.
4. Save Settings. All server-side jobs will now route traffic through your first-party Stape proxy.

### Google Tag Manager (GTM)
1. Toggle **GTM Enabled** to `ON`.
2. Enter your **Container ID** (`GTM-XXXXXXX`).
3. If using a custom GTM loader domain (e.g. via Stape), enter the URL in **GTM Custom Domain**.

---

## 3. Event Deduplication Architecture

Every tracked interaction generates an atomic `event_id`:
```
Browser Event (fbq)    ───► [event_id: llk_174000_abc123] ───┐
                                                               ├─► Meta Graph API
Server Job (Meta CAPI) ───► [event_id: llk_174000_abc123] ───┘   (Deduplicated!)
```

### Standard E-Commerce Events Dispatched

| Trigger | Browser Event | Server CAPI Event | DataLayer Payload |
| :--- | :--- | :--- | :--- |
| Page Visit | `PageView` / `ViewContent` | Queued `PageView` | `view_item` with product SKU, price, name |
| Phone Number Entered | `Lead` | Queued `Lead` | `generate_lead` with masked phone |
| Checkout Focused | `InitiateCheckout` | Queued `InitiateCheckout` | `begin_checkout` |
| Order Confirmed | `Purchase` | Queued `Purchase` | `purchase` with order number, total BDT, items array |

### Duplicate Purchase Prevention
When an order is created, the success page is loaded at `/order-success/{signed-token}`.
- LLK checks `orders.purchase_tracked_at`.
- If `NULL`, the `Purchase` event is dispatched and `purchase_tracked_at` is stamped with the current timestamp.
- On browser refresh or page reload, LLK detects `purchase_tracked_at != null` and skips re-firing the purchase event.

---

## 4. Live Debugger & Inspection

In the Admin Panel, navigate to **Tracking Logs** (`/admin/tracking-logs`):
- View chronological list of every server-side event dispatched.
- Inspect JSON payload sent, platform response, HTTP status, and response latency.
- Filter by platform (`meta_capi`, `stape`, `ga4_mp`) or status (`success`, `failed`).

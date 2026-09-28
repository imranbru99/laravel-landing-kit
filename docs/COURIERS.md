# Courier Integration & Logistics Guide

**Laravel Landing Kit** provides a pluggable courier driver architecture for seamless shipping fulfillment across Bangladesh.

---

## 1. Supported Courier Drivers

| Courier | Driver Class | Features Supported |
| :--- | :--- | :--- |
| **Steadfast Courier** | `App\Services\Courier\SteadfastCourierDriver` | Order placement, Consignment ID assignment, Real-time status tracking via API |
| **Pathao Courier** | `App\Services\Courier\PathaoCourierDriver` | OAuth2 Bearer token auth, City/Zone/Area mapping, Order dispatch, Tracking URL |
| **RedX Courier** | `App\Services\Courier\RedXCourierDriver` | API token authentication, Parcel creation, Tracking webhook integration |
| **Manual / In-House** | `App\Services\Courier\ManualCourierDriver` | Custom delivery boy notes, manual tracking code input, local shop delivery |

---

## 2. Courier Setup via Admin Settings

Navigate to **Admin Panel** (`/admin`) → **Settings** → **Courier Integrations**.

### Steadfast Courier Setup
1. Set **Default Courier** to `Steadfast`.
2. Enter your **Steadfast API Key**.
3. Enter your **Steadfast Secret Key**.
4. Save Settings.

### Pathao Courier Setup
1. Set your **Pathao Client ID** & **Client Secret**.
2. Enter your **Pathao Username** & **Password**.
3. Enter your registered **Pathao Store ID**.
4. Save Settings.

### RedX Courier Setup
1. Enter your **RedX Access Token**.
2. Save Settings.

---

## 3. Order Dispatch Workflow

1. In the **Orders** resource (`/admin/orders`), select an order in `confirmed` or `processing` status.
2. In the Order View, click the **Send to Courier** action.
3. The selected courier driver executes the API request:
   - Posts customer name, phone, full address, district, COD amount (BDT), and items summary.
   - Saves the returned `consignment_id` and `tracking_code` onto the `Order`.
   - Transitions order status to `shipped`.
   - Dispatches customer notification (SMS/WhatsApp with tracking link).
4. Print **Unicode Bangla Invoices**, **Packing Slips**, or **Courier Shipping Labels** with 1-click via `imrandevbd/laravel-unicode-pdf`.

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Events\OrderStatusChanged;
use App\Http\Requests\SubmitOrderRequest;
use App\Models\Blocklist;
use App\Models\Customer;
use App\Models\DeliveryZone;
use App\Models\District;
use App\Models\IncompleteOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Thana;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * AJAX phone lookup for returning customers (rate-limited, privacy-masked).
     */
    public function customerLookup(Request $request): JsonResponse
    {
        $phoneInput = (string) $request->input('phone', '');
        
        if (!llk_is_valid_bd_phone($phoneInput)) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid phone number format',
            ], 422);
        }

        $phone = llk_normalize_phone($phoneInput);

        // Check if customer is blocked
        if (Blocklist::isBlocked($phone, $request->ip())) {
            return response()->json([
                'valid' => true,
                'blocked' => true,
                'message' => 'This account is restricted from placing orders.',
            ]);
        }

        $customer = Customer::where('phone', $phone)->first();

        if (!$customer) {
            return response()->json([
                'valid' => true,
                'found' => false,
            ]);
        }

        // Return customer info for auto-fill with privacy masking
        $thanas = $customer->district_id 
            ? Thana::where('district_id', $customer->district_id)->orderBy('name_en')->get(['id', 'name_en', 'name_bn'])
            : [];

        return response()->json([
            'valid' => true,
            'found' => true,
            'name' => $customer->name,
            'masked_name' => $customer->masked_name,
            'default_address' => $customer->default_address,
            'district_id' => $customer->district_id,
            'thana_id' => $customer->thana_id,
            'thanas' => $thanas,
        ]);
    }

    /**
     * Capture incomplete order / lead on phone entry or blur.
     */
    public function captureLead(Request $request): JsonResponse
    {
        $phoneInput = (string) $request->input('phone', '');
        $productId = $request->input('product_id');

        if (!llk_is_valid_bd_phone($phoneInput) || !$productId) {
            return response()->json(['success' => false], 422);
        }

        $phone = llk_normalize_phone($phoneInput);

        // Upsert incomplete order lead
        $lead = IncompleteOrder::updateOrCreate(
            [
                'phone' => $phone,
                'product_id' => (int) $productId,
                'status' => 'lead',
            ],
            [
                'name' => $request->input('name'),
                'customer_address' => $request->input('address'),
                'district_id' => $request->input('district_id'),
                'thana_id' => $request->input('thana_id'),
                'product_variant_id' => $request->input('product_variant_id'),
                'quantity' => (int) ($request->input('quantity', 1)),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'utm_source' => $request->input('utm_source'),
                'utm_medium' => $request->input('utm_medium'),
                'utm_campaign' => $request->input('utm_campaign'),
                'utm_content' => $request->input('utm_content'),
                'utm_term' => $request->input('utm_term'),
                'fbclid' => $request->input('fbclid'),
                'gclid' => $request->input('gclid'),
                'ttclid' => $request->input('ttclid'),
            ]
        );

        // Dispatch Lead Server Tracking Event
        try {
            app(\App\Services\Tracking\TrackingManager::class)->trackLead($lead);
        } catch (\Throwable $e) {
            // Fail silently so customer experience is never interrupted
        }

        return response()->json([
            'success' => true,
            'lead_id' => $lead->id,
        ]);
    }

    /**
     * Submit Order from public landing page checkout form.
     */
    public function submitOrder(SubmitOrderRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $phone = llk_normalize_phone($validated['phone']);
        $product = Product::findOrFail($validated['product_id']);

        // Check for duplicate order in configured time window (e.g. 5 minutes)
        $duplicateWindow = (int) setting('duplicate_order_window', 5);
        if ($duplicateWindow > 0) {
            $recentOrder = Order::where('customer_phone', $phone)
                ->where('created_at', '>=', now()->subMinutes($duplicateWindow))
                ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
                ->first();

            if ($recentOrder) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'You already submitted this order recently.',
                        'redirect_url' => route('order.success', ['token' => $recentOrder->order_token]),
                    ]);
                }

                return redirect()->route('order.success', ['token' => $recentOrder->order_token])
                    ->with('info', 'আপনার এই অর্ডারটি ইতিপূর্বে সফলভাবে গ্রহণ করা হয়েছে!');
            }
        }

        // Calculate Pricing
        $quantity = (int) $validated['quantity'];
        $unitPrice = $product->effective_price;
        $variant = null;

        if (!empty($validated['product_variant_id'])) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->find($validated['product_variant_id']);

            if ($variant && $variant->price > 0) {
                $unitPrice = (float) $variant->price;
            }
        }

        $subtotal = $unitPrice * $quantity;
        $discountAmount = 0.00;

        // Check volume/bundle offers
        $matchingOffer = $product->offers()
            ->where('is_active', true)
            ->where('min_quantity', '<=', $quantity)
            ->orderByDesc('min_quantity')
            ->first();

        if ($matchingOffer) {
            if ($matchingOffer->discount_amount > 0) {
                $discountAmount = (float) $matchingOffer->discount_amount;
            } elseif ($matchingOffer->discount_percentage > 0) {
                $discountAmount = round(($subtotal * (float) $matchingOffer->discount_percentage) / 100, 2);
            }
        }

        // Calculate delivery charge
        $deliveryCharge = 60.00; // default inside dhaka
        $deliveryZoneId = $validated['delivery_zone_id'] ?? null;

        if ($deliveryZoneId) {
            $zone = DeliveryZone::find($deliveryZoneId);
            if ($zone) {
                $deliveryCharge = (float) $zone->charge;
            }
        } elseif (!empty($validated['district_id'])) {
            $district = District::find($validated['district_id']);
            if ($district && $district->is_inside_dhaka) {
                $deliveryCharge = 60.00;
            } elseif ($district && $district->is_sub_dhaka) {
                $deliveryCharge = 100.00;
            } else {
                $deliveryCharge = 120.00;
            }
        }

        // Free delivery threshold check
        $freeDeliveryThreshold = (float) setting('free_delivery_threshold', 0);
        if ($freeDeliveryThreshold > 0 && $subtotal >= $freeDeliveryThreshold) {
            $deliveryCharge = 0.00;
        }

        $totalAmount = max(0.00, $subtotal - $discountAmount + $deliveryCharge);

        // Atomic Database Transaction
        $order = DB::transaction(function () use (
            $validated, $phone, $product, $variant, $quantity, $unitPrice,
            $subtotal, $discountAmount, $deliveryCharge, $totalAmount, $deliveryZoneId, $request
        ) {
            // 1. Upsert Customer
            $customer = Customer::firstOrNew(['phone' => $phone]);
            $customer->name = $validated['name'];
            $customer->default_address = $validated['address'];
            $customer->district_id = $validated['district_id'];
            $customer->thana_id = $validated['thana_id'] ?? null;
            $customer->save();

            // 2. Create Order
            $order = Order::create([
                'customer_id' => $customer->id,
                'customer_name' => $validated['name'],
                'customer_phone' => $phone,
                'customer_address' => $validated['address'],
                'district_id' => $validated['district_id'],
                'thana_id' => $validated['thana_id'] ?? null,
                'delivery_zone_id' => $deliveryZoneId,
                'status' => OrderStatus::Pending,
                'subtotal' => $subtotal,
                'delivery_charge' => $deliveryCharge,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'] ?? 'cod',
                'payment_status' => ($validated['payment_method'] ?? 'cod') === 'cod' ? 'unpaid' : 'pending_verification',
                'payment_trx_id' => $validated['payment_trx_id'] ?? null,
                'payment_sender_number' => $validated['payment_sender_number'] ?? null,
                'customer_notes' => $validated['customer_notes'] ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'utm_source' => $request->input('utm_source'),
                'utm_medium' => $request->input('utm_medium'),
                'utm_campaign' => $request->input('utm_campaign'),
                'utm_content' => $request->input('utm_content'),
                'utm_term' => $request->input('utm_term'),
                'fbclid' => $request->input('fbclid'),
                'gclid' => $request->input('gclid'),
                'ttclid' => $request->input('ttclid'),
            ]);

            // 3. Create Order Item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'product_name' => $product->name,
                'variant_name' => $variant ? "{$variant->attribute_name}: {$variant->attribute_value}" : null,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $subtotal,
            ]);

            // 4. Convert Incomplete Order Lead if present
            IncompleteOrder::where('phone', $phone)
                ->where('product_id', $product->id)
                ->where('status', 'lead')
                ->update([
                    'status' => 'converted',
                    'converted_order_id' => $order->id,
                ]);

            // 5. Fire OrderStatusChanged Event for initial state
            OrderStatusChanged::dispatch($order, null, OrderStatus::Pending, null, 'Customer placed order through landing page.');

            return $order;
        });

        // Dispatch Server-side Purchase tracking event with exact matching event_id
        try {
            app(\App\Services\Tracking\TrackingManager::class)->trackPurchase($order, 'purchase_' . $order->id);
        } catch (\Throwable $e) {
            // Fail safely
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'order_token' => $order->order_token,
                'redirect_url' => route('order.success', ['token' => $order->order_token]),
            ]);
        }

        return redirect()->route('order.success', ['token' => $order->order_token]);
    }

    /**
     * Get cascading thanas for selected district.
     */
    public function thanas(Request $request): JsonResponse
    {
        $districtId = $request->input('district_id');
        if (!$districtId) {
            return response()->json([]);
        }

        $thanas = Thana::where('district_id', (int) $districtId)
            ->orderBy('name_en')
            ->get(['id', 'name_en', 'name_bn']);

        return response()->json($thanas);
    }
}

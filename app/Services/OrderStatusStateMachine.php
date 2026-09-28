<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Events\OrderStatusChanged;
use App\Exceptions\InvalidStatusTransitionException;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderStatusStateMachine
{
    /**
     * Check if transition is allowed.
     */
    public function canTransition(Order $order, OrderStatus $targetStatus): bool
    {
        $current = $order->status;
        if ($current === $targetStatus) {
            return false;
        }

        return in_array($targetStatus, $current->allowedTransitions(), true);
    }

    /**
     * Execute status transition with stock adjustments, history logging, and events.
     */
    public function transition(Order $order, OrderStatus $targetStatus, ?User $user = null, ?string $notes = null): Order
    {
        if (! $this->canTransition($order, $targetStatus)) {
            throw InvalidStatusTransitionException::make($order->status, $targetStatus);
        }

        return DB::transaction(function () use ($order, $targetStatus, $user, $notes) {
            $fromStatus = $order->status;

            // 1. Stock Decrement
            if ($targetStatus->decrementsStock() && ! $order->stock_decremented) {
                $this->adjustStock($order, decrement: true);
                $order->stock_decremented = true;
            }

            // 2. Stock Restoration
            if ($targetStatus->restoresStock() && $order->stock_decremented) {
                $this->adjustStock($order, decrement: false);
                $order->stock_decremented = false;
            }

            // 3. Update Order status
            $order->status = $targetStatus;
            $order->save();

            // 4. Log History
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => $fromStatus,
                'to_status' => $targetStatus,
                'changed_by_user_id' => $user?->id,
                'notes' => $notes,
            ]);

            // 5. Update Customer Metrics
            if ($order->customer) {
                $order->customer->recalculateMetrics();
            }

            // 6. Fire event
            OrderStatusChanged::dispatch($order, $fromStatus, $targetStatus, $user, $notes);

            return $order;
        });
    }

    /**
     * Adjust stock quantities for all items in order.
     */
    protected function adjustStock(Order $order, bool $decrement): void
    {
        foreach ($order->items as $item) {
            $qty = $item->quantity;

            // Adjust variant stock if applicable
            if ($item->product_variant_id) {
                $variant = ProductVariant::find($item->product_variant_id);
                if ($variant) {
                    if ($decrement) {
                        $variant->decrement('stock_quantity', $qty);
                    } else {
                        $variant->increment('stock_quantity', $qty);
                    }
                }
            }

            // Adjust product stock if tracking
            $product = Product::find($item->product_id);
            if ($product && $product->track_stock) {
                if ($decrement) {
                    $product->decrement('stock_quantity', $qty);
                } else {
                    $product->increment('stock_quantity', $qty);
                }
            }
        }
    }
}

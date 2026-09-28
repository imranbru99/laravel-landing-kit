<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderSuccessController extends Controller
{
    public function show(Request $request, string $token): View
    {
        $order = Order::where('order_token', $token)
            ->with(['items.product', 'district', 'thana', 'customer'])
            ->firstOrFail();

        // Check if purchase event has already been tracked to prevent duplicate event firing on page refresh
        $firePurchaseEvent = false;

        if (is_null($order->purchase_tracked_at)) {
            $order->update(['purchase_tracked_at' => now()]);
            $firePurchaseEvent = true;
        }

        return view('landing.order-success', compact('order', 'firePurchaseEvent'));
    }
}

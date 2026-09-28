<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index(Request $request): View
    {
        $order = null;
        $searched = false;

        $orderNumber = trim((string) $request->input('order_number', ''));
        $phoneInput = trim((string) $request->input('phone', ''));

        if ($orderNumber !== '' && $phoneInput !== '') {
            $searched = true;
            $phone = llk_normalize_phone($phoneInput);

            $order = Order::where('order_number', $orderNumber)
                ->where('customer_phone', $phone)
                ->with(['items.product', 'statusHistories', 'district', 'thana'])
                ->first();
        }

        return view('landing.track-order', compact('order', 'searched', 'orderNumber', 'phoneInput'));
    }
}
